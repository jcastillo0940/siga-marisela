<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CashRegister;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class CashRegisterController extends Controller
{
    public function __construct(
        private CashRegisterService $cashRegisterService
    ) {}

    public function index()
    {
        $activeCashRegister = $this->cashRegisterService->getActiveCashRegister();
        return view('cash-registers.index', compact('activeCashRegister'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'opening_notes' => 'nullable|string',
        ]);

        try {
            $cashRegister = $this->cashRegisterService->openCashRegister(
                $request->opening_amount,
                $request->opening_notes
            );

            return redirect()
                ->route('pos.index')
                ->with('success', 'Caja abierta exitosamente: ' . $cashRegister->code);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al abrir caja: ' . $e->getMessage());
        }
    }

    public function close(Request $request, int $id)
    {
        $request->validate([
            'closing_amount' => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string',
        ]);

        try {
            $this->cashRegisterService->closeCashRegister(
                $id,
                $request->closing_amount,
                $request->closing_notes
            );

            return redirect()
                ->route('cash-registers.report', $id)
                ->with('success', 'Caja cerrada exitosamente');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al cerrar caja: ' . $e->getMessage());
        }
    }

    // --- NUEVOS MÉTODOS ACTUALIZADOS ---

    public function report($id) 
    {
        // Usamos el servicio para traer datos detallados (Corte Z)
        $reportData = $this->cashRegisterService->getDetailedReport($id);
        return view('cash-registers.report', $reportData);
    }

    public function corteX($id)
    {
        try {
            // El Corte X es un estado actual de la caja sin cerrarla
            $reportData = $this->cashRegisterService->getCorteX($id);
            return view('cash-registers.corte-x', $reportData);
        } catch (\Exception $e) {
            return redirect()
                ->route('cash-registers.index')
                ->with('error', 'Error al generar Corte X: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'user_id']);
        $registers = $this->cashRegisterService->getClosedRegisters($filters);
        $users = User::orderBy('name')->get();
        
        return view('cash-registers.history', compact('registers', 'users', 'filters'));
    }

    public function downloadReportPdf($id)
    {
        $reportData = $this->cashRegisterService->getDetailedReport($id);
                
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setChroot(base_path());
                
        $dompdf = new Dompdf($options);
        $html = view('cash-registers.report-pdf', $reportData)->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
                
        return $dompdf->stream('corte-z-' . $reportData['cash_register']->code . '.pdf', [
            'Attachment' => true
        ]);
    }
}