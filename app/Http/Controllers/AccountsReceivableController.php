<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\CourseOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsReceivableController extends Controller
{
    /**
     * Muestra el reporte de cuentas por cobrar
     */
    public function index(Request $request)
    {
        $query = PaymentPlan::with([
            'enrollment.student',
            'enrollment.courseOffering.course',
            'schedules' => function($q) {
                $q->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                  ->orderBy('due_date');
            }
        ])
        ->where('balance', '>', 0);

        // Filtro por curso
        if ($request->filled('course_offering_id')) {
            $query->whereHas('enrollment', function($q) use ($request) {
                $q->where('course_offering_id', $request->course_offering_id);
            });
        }

        // Filtro por estado de pago
        if ($request->filled('payment_status')) {
            $query->where('status', $request->payment_status);
        }

        // Filtro por vencimiento
        if ($request->filled('overdue_only') && $request->overdue_only) {
            $query->whereHas('schedules', function($q) {
                $q->where('status', 'vencido');
            });
        }

        $paymentPlans = $query->orderBy('updated_at', 'desc')->get();

        // Calcular totales
        $totalReceivable = $paymentPlans->sum('balance');
        $totalOverdue = $paymentPlans->sum(function($plan) {
            return $plan->schedules->where('status', 'vencido')->sum('balance');
        });
        $totalCurrent = $totalReceivable - $totalOverdue;

        // Agrupar por curso para el resumen
        $byCourse = $paymentPlans->groupBy(function($plan) {
            return $plan->enrollment->courseOffering->id;
        })->map(function($plans, $offeringId) {
            $firstPlan = $plans->first();
            return [
                'course_name' => $firstPlan->enrollment->courseOffering->course->name,
                'offering_name' => $firstPlan->enrollment->courseOffering->full_name,
                'count' => $plans->count(),
                'total_balance' => $plans->sum('balance'),
            ];
        })->sortByDesc('total_balance');

        // Obtener cursos para filtro
        $courseOfferings = CourseOffering::with('course')
            ->where('is_active', true)
            ->whereHas('enrollments.paymentPlan', function($q) {
                $q->where('balance', '>', 0);
            })
            ->get();

        return view('reports.accounts-receivable', compact(
            'paymentPlans',
            'totalReceivable',
            'totalOverdue',
            'totalCurrent',
            'byCourse',
            'courseOfferings'
        ));
    }

    /**
     * Exporta el reporte a Excel
     */
    public function exportExcel(Request $request)
    {
        // TODO: Implementar exportación a Excel
        return back()->with('info', 'Funcionalidad de exportación en desarrollo');
    }

    /**
     * Exporta el reporte a PDF
     */
    public function exportPdf(Request $request)
    {
        $query = PaymentPlan::with([
            'enrollment.student',
            'enrollment.courseOffering.course',
            'schedules' => function($q) {
                $q->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                  ->orderBy('due_date');
            }
        ])
        ->where('balance', '>', 0);

        // Aplicar mismos filtros que en index
        if ($request->filled('course_offering_id')) {
            $query->whereHas('enrollment', function($q) use ($request) {
                $q->where('course_offering_id', $request->course_offering_id);
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('status', $request->payment_status);
        }

        if ($request->filled('overdue_only') && $request->overdue_only) {
            $query->whereHas('schedules', function($q) {
                $q->where('status', 'vencido');
            });
        }

        $paymentPlans = $query->orderBy('updated_at', 'desc')->get();

        $totalReceivable = $paymentPlans->sum('balance');
        $totalOverdue = $paymentPlans->sum(function($plan) {
            return $plan->schedules->where('status', 'vencido')->sum('balance');
        });
        $totalCurrent = $totalReceivable - $totalOverdue;

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setChroot(base_path());

        $dompdf = new \Dompdf\Dompdf($options);
        $html = view('reports.accounts-receivable-pdf', compact(
            'paymentPlans',
            'totalReceivable',
            'totalOverdue',
            'totalCurrent'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'landscape');
        $dompdf->render();

        return $dompdf->stream('cuentas-por-cobrar-' . now()->format('Y-m-d') . '.pdf', ['Attachment' => true]);
    }
}
