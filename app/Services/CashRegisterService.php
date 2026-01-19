<?php

namespace App\Services;

use App\Models\CashRegister;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Exception;

class CashRegisterService
{
    /**
     * Abrir una nueva caja registradora
     */
    public function openCashRegister(float $openingAmount, ?string $notes = null): CashRegister
    {
        return DB::transaction(function () use ($openingAmount, $notes) {
            // Verificar si ya hay una caja abierta por este usuario
            $existingOpen = $this->getActiveCashRegister();

            if ($existingOpen) {
                throw new Exception('Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.');
            }

            return CashRegister::create([
                'opened_by'      => auth()->id(),
                'opening_amount' => $openingAmount,
                'status'         => 'abierta',
                'opened_at'      => now(),
                'opening_notes'  => $notes,
            ]);
        });
    }

    /**
     * Cerrar una caja registradora existente
     */
    public function closeCashRegister(int $cashRegisterId, float $closingAmount, ?string $notes = null): CashRegister
    {
        return DB::transaction(function () use ($cashRegisterId, $closingAmount, $notes) {
            $cashRegister = CashRegister::findOrFail($cashRegisterId);

            if ($cashRegister->status === 'cerrada') {
                throw new Exception('Esta caja ya está cerrada.');
            }

            // Calcular el total esperado: Fondo inicial + Suma de pagos recibidos
            $totalPayments = $cashRegister->payments()->sum('amount');
            $expectedAmount = $cashRegister->opening_amount + $totalPayments;
            $difference = $closingAmount - $expectedAmount;

            $cashRegister->update([
                'closed_by'       => auth()->id(),
                'closing_amount'  => $closingAmount,
                'expected_amount' => $expectedAmount,
                'difference'      => $difference,
                'status'          => 'cerrada',
                'closed_at'       => now(),
                'closing_notes'   => $notes,
            ]);

            return $cashRegister->fresh();
        });
    }

    /**
     * Obtener la caja abierta actualmente del usuario autenticado
     */
    public function getActiveCashRegister(): ?CashRegister
    {
        return CashRegister::where('opened_by', auth()->id())
            ->where('status', 'abierta')
            ->first();
    }

    /**
     * Obtener reporte detallado de una caja (Corte Z o histórico)
     */
    public function getDetailedReport(int $id): array
    {
        $cashRegister = CashRegister::with(['openedBy', 'closedBy'])
            ->findOrFail($id);

        // Pagos agrupados por método
        $paymentsByMethod = Payment::where('cash_register_id', $id)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->payment_method => [
                    'count' => $item->count,
                    'total' => (float) $item->total
                ]];
            });

        // Obtener todos los pagos
        $payments = Payment::where('cash_register_id', $id)
            ->with(['enrollment.student', 'enrollment.courseOffering.course'])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Estadísticas generales
        $stats = [
            'total_transactions'  => $payments->count(),
            'total_collected'     => (float) $payments->sum('amount'),
            'average_transaction' => $payments->count() > 0 ? (float) $payments->avg('amount') : 0,
            'first_payment'       => $payments->last()?->payment_date,
            'last_payment'        => $payments->first()?->payment_date,
        ];

        return [
            'cash_register'      => $cashRegister,
            'payments_by_method' => $paymentsByMethod,
            'payments'           => $payments,
            'stats'              => $stats,
        ];
    }

    /**
     * Corte X - Reporte sin cerrar la caja
     */
    public function getCorteX(int $id): array
    {
        // Validar que la caja esté abierta
        $cashRegister = CashRegister::where('id', $id)
            ->where('status', 'abierta')
            ->firstOrFail();

        return $this->getDetailedReport($id);
    }

    /**
     * Obtener listado de cajas cerradas con filtros
     */
    public function getClosedRegisters(array $filters = [])
    {
        $query = CashRegister::where('status', 'cerrada')
            ->with(['openedBy', 'closedBy']);

        if (!empty($filters['date_from'])) {
            $query->whereDate('opened_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('opened_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('opened_by', $filters['user_id']);
        }

        return $query->orderBy('closed_at', 'desc')->paginate(20);
    }
}