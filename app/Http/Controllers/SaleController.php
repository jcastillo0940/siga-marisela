<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct(
        private CashRegisterService $cashRegisterService
    ) {}

    /**
     * Listar ventas
     */
    public function index(Request $request)
    {
        $query = Sale::with(['items', 'soldBy', 'paymentMethods']);

        // Filtros
        if ($request->filled('date_from')) {
            $query->where('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('sale_date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->orderBy('sale_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('sales.index', compact('sales'));
    }

    /**
     * Vista POS para venta genérica
     */
    public function pos()
    {
        // Verificar caja abierta
        $activeCashRegister = $this->cashRegisterService->getActiveCashRegister();

        if (!$activeCashRegister) {
            return redirect()
                ->route('cash-registers.index')
                ->with('warning', 'Debes abrir caja antes de realizar ventas');
        }

        $products = Product::active()->available()->orderBy('name')->get();

        return view('sales.pos', compact('products', 'activeCashRegister'));
    }

    /**
     * Registrar venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_document' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:efectivo,transferencia,tarjeta_credito,tarjeta_debito,yappy,otro',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'payment_methods' => 'nullable|array|min:1',
            'payment_methods.*.method' => 'required|in:efectivo,transferencia,tarjeta_credito,tarjeta_debito,yappy,otro',
            'payment_methods.*.amount' => 'required|numeric|min:0.01',
            'payment_methods.*.reference_number' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Verificar caja abierta
            $activeCashRegister = $this->cashRegisterService->getActiveCashRegister();

            if (!$activeCashRegister) {
                throw new \Exception('No hay una caja abierta.');
            }

            // Calcular totales
            $subtotal = 0;
            foreach ($request->items as $itemData) {
                $itemSubtotal = ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount'] ?? 0);
                $subtotal += $itemSubtotal;
            }

            $discount = $request->discount ?? 0;
            $total = $subtotal - $discount;

            // Determinar método de pago
            $useMultipleMethods = $request->has('payment_methods') && !empty($request->payment_methods);

            if ($useMultipleMethods) {
                $totalMethodsAmount = collect($request->payment_methods)->sum('amount');
                if (abs($totalMethodsAmount - $total) > 0.01) {
                    throw new \Exception("La suma de los métodos de pago debe ser igual al total");
                }
            }

            // Crear venta
            $sale = Sale::create([
                'sale_date' => now(),
                'customer_name' => $request->customer_name,
                'customer_document' => $request->customer_document,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => 0, // TODO: Implementar cálculo de impuestos si es necesario
                'total' => $total,
                'payment_method' => $useMultipleMethods ? 'multiple' : $request->payment_method,
                'reference_number' => $useMultipleMethods ? null : $request->reference_number,
                'cash_register_id' => $activeCashRegister->id,
                'sold_by' => auth()->id(),
                'status' => 'completado',
                'notes' => $request->notes,
            ]);

            // Crear items de venta
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $itemSubtotal = ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount'] ?? 0);

                \App\Models\SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'item_name' => $product->name,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount' => $itemData['discount'] ?? 0,
                    'subtotal' => $itemSubtotal,
                ]);

                // Disminuir inventario
                $product->decreaseStock($itemData['quantity']);
            }

            // Crear métodos de pago si aplica
            if ($useMultipleMethods) {
                foreach ($request->payment_methods as $methodData) {
                    \App\Models\SalePaymentMethod::create([
                        'sale_id' => $sale->id,
                        'method' => $methodData['method'],
                        'amount' => $methodData['amount'],
                        'reference_number' => $methodData['reference_number'] ?? null,
                        'notes' => $methodData['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('sales.show', $sale->id)
                ->with('success', 'Venta registrada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de venta
     */
    public function show(int $id)
    {
        $sale = Sale::with(['items.product', 'soldBy', 'paymentMethods', 'cashRegister'])
            ->findOrFail($id);

        return view('sales.show', compact('sale'));
    }

    /**
     * Cancelar venta
     */
    public function cancel(int $id)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::with('items.product')->findOrFail($id);

            if ($sale->status === 'cancelado') {
                throw new \Exception('Esta venta ya está cancelada');
            }

            // Restaurar inventario
            foreach ($sale->items as $item) {
                if ($item->product) {
                    $item->product->increaseStock($item->quantity);
                }
            }

            $sale->status = 'cancelado';
            $sale->save();

            DB::commit();

            return redirect()
                ->route('sales.show', $sale->id)
                ->with('success', 'Venta cancelada exitosamente. El inventario ha sido restaurado.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }
}
