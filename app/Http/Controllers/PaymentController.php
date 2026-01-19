<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Services\PaymentPlanService;
use App\Services\CashRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentPlanService $paymentPlanService
    ) {}

    public function index(Request $request)
    {
        $query = Payment::with(['enrollment.student', 'enrollment.courseOffering.course', 'receivedBy']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('payment_date', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->whereHas('enrollment', function($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        $payments = $query->orderBy('payment_date', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        $students = Student::where('is_active', true)->orderBy('first_name')->get();

        return view('payments.index', compact('payments', 'students'));
    }

    public function create(Request $request)
    {
        $studentId = $request->get('student_id');
        $student = null;
        $enrollments = collect();

        if ($studentId) {
            $student = Student::with([
                'enrollments.courseOffering.course',
                'enrollments.paymentPlan.schedules' => function($query) {
                    $query->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                          ->orderBy('due_date');
                }
            ])->find($studentId);

            if ($student) {
                $enrollments = $student->enrollments()
                    ->whereHas('paymentPlan', function($q) {
                        $q->where('status', '!=', 'completado');
                    })
                    ->with(['courseOffering.course', 'paymentPlan.schedules'])
                    ->get();
            }
        }

        return view('payments.create', compact('student', 'enrollments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'selected_schedules' => 'required|string', // JSON string with schedule IDs
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|in:efectivo,transferencia,tarjeta_credito,tarjeta_debito,yappy,otro', // Opcional si se usan múltiples métodos
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            // Nuevos campos para múltiples métodos de pago
            'payment_methods' => 'nullable|array|min:1',
            'payment_methods.*.method' => 'required|in:efectivo,transferencia,tarjeta_credito,tarjeta_debito,yappy,otro',
            'payment_methods.*.amount' => 'required|numeric|min:0.01',
            'payment_methods.*.reference_number' => 'nullable|string|max:255',
            'payment_methods.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Verificar que hay una caja abierta
            $activeCashRegister = app(CashRegisterService::class)->getActiveCashRegister();
            
            if (!$activeCashRegister) {
                throw new \Exception('No hay una caja abierta. Debes abrir caja antes de registrar pagos.');
            }

            $enrollment = Enrollment::with('paymentPlan.schedules')->findOrFail($request->enrollment_id);

            if (!$enrollment->paymentPlan) {
                throw new \Exception('Esta inscripción no tiene un plan de pagos asociado.');
            }

            // Decodificar schedule IDs seleccionados
            $selectedScheduleIds = json_decode($request->selected_schedules, true);
            
            if (empty($selectedScheduleIds)) {
                throw new \Exception('Debes seleccionar al menos una cuota.');
            }

            // Determinar si se usan múltiples métodos de pago
            $useMultipleMethods = $request->has('payment_methods') && !empty($request->payment_methods);

            // Validar que la suma de los métodos de pago sea igual al monto total
            if ($useMultipleMethods) {
                $totalMethodsAmount = collect($request->payment_methods)->sum('amount');

                if (abs($totalMethodsAmount - $request->amount) > 0.01) {
                    throw new \Exception(
                        "La suma de los métodos de pago ($" . number_format($totalMethodsAmount, 2) . ") " .
                        "debe ser igual al monto total ($" . number_format($request->amount, 2) . ")"
                    );
                }
            }

            // Crear el pago principal
            $payment = Payment::create([
                'enrollment_id' => $enrollment->id,
                'payment_plan_id' => $enrollment->paymentPlan->id,
                'payment_schedule_id' => null, // Múltiples cuotas
                'payment_date' => now(),
                'amount' => $request->amount,
                'payment_method' => $useMultipleMethods ? 'multiple' : $request->payment_method,
                'reference_number' => $useMultipleMethods ? null : $request->reference_number,
                'received_by' => auth()->id(),
                'cash_register_id' => $activeCashRegister->id,
                'status' => 'completado',
                'notes' => $request->notes,
            ]);

            // Crear registros individuales de métodos de pago si se usan múltiples métodos
            if ($useMultipleMethods) {
                foreach ($request->payment_methods as $methodData) {
                    \App\Models\PaymentMethod::create([
                        'payment_id' => $payment->id,
                        'method' => $methodData['method'],
                        'amount' => $methodData['amount'],
                        'reference_number' => $methodData['reference_number'] ?? null,
                        'notes' => $methodData['notes'] ?? null,
                    ]);
                }
            }

            // Aplicar el pago a las cuotas seleccionadas
            $remainingAmount = $request->amount;
            
            // Obtener las cuotas seleccionadas ordenadas
            $schedules = \App\Models\PaymentSchedule::whereIn('id', $selectedScheduleIds)
                ->orderBy('installment_number')
                ->get();

            foreach ($schedules as $schedule) {
                if ($remainingAmount <= 0) break;
                
                $scheduleBalance = $schedule->balance;
                $amountToApply = min($remainingAmount, $scheduleBalance);
                
                $schedule->addPayment($amountToApply);
                $remainingAmount -= $amountToApply;
            }

            // Si queda sobrante, aplicar a la siguiente cuota pendiente
            if ($remainingAmount > 0) {
                $nextSchedule = $enrollment->paymentPlan->schedules()
                    ->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                    ->whereNotIn('id', $selectedScheduleIds)
                    ->orderBy('installment_number')
                    ->first();

                if ($nextSchedule) {
                    $nextSchedule->addPayment($remainingAmount);
                }
            }

            // Actualizar balance del plan
            $enrollment->paymentPlan->updateBalance();

            DB::commit();

            // Generar PDF y enviar email automáticamente
            try {
                $payment->load([
                    'enrollment.student',
                    'enrollment.courseOffering.course',
                    'paymentPlan',
                    'paymentSchedule',
                    'receivedBy'
                ]);

                $options = new \Dompdf\Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true);
                $options->setChroot(base_path());
                
                $dompdf = new \Dompdf\Dompdf($options);
                $html = view('payments.receipt-pdf', compact('payment'))->render();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('letter', 'portrait');
                $dompdf->render();
                $pdfContent = $dompdf->output();

                Mail::to($payment->enrollment->student->email)
                    ->send(new PaymentReceiptMail($payment, $pdfContent));

            } catch (\Exception $e) {
                // Log error but don't fail the payment
                \Log::error('Error sending payment receipt email: ' . $e->getMessage());
            }

            return redirect()
                ->route('payments.show', $payment->id)
                ->with('success', 'Pago registrado exitosamente. Se ha enviado el recibo por correo.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $payment = Payment::with([
            'enrollment.student',
            'enrollment.courseOffering.course',
            'paymentPlan',
            'paymentSchedule',
            'receivedBy'
        ])->findOrFail($id);

        return view('payments.show', compact('payment'));
    }

    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($id);
            
            // Revert payment from schedule
            if ($payment->paymentSchedule) {
                $schedule = $payment->paymentSchedule;
                $schedule->amount_paid -= $payment->amount;
                $schedule->updateStatus();
            }

            // Update payment plan balance
            $payment->paymentPlan->updateBalance();

            // Delete payment
            $payment->delete();

            DB::commit();

            return redirect()
                ->route('payments.index')
                ->with('success', 'Pago eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }

    public function searchStudentPayments(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $students = Student::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('identification', 'like', "%{$query}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$query}%"]);
            })
            ->whereHas('enrollments', function($q) {
                $q->whereHas('paymentPlan', function($plan) {
                    $plan->where('balance', '>', 0);
                });
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'identification']);
        
        return response()->json($students);
    }
    
    public function pos(Request $request)
    {
        // Verificar si hay caja abierta
        $activeCashRegister = app(CashRegisterService::class)->getActiveCashRegister();
        
        if (!$activeCashRegister) {
            return redirect()
                ->route('cash-registers.index')
                ->with('warning', 'Debes abrir caja antes de usar el POS');
        }

        $studentId = $request->get('student_id');
        $student = null;
        $enrollments = collect();

        if ($studentId) {
            $student = Student::find($studentId);

            if ($student) {
                $enrollments = Enrollment::where('student_id', $studentId)
                    ->whereHas('paymentPlan', function($q) {
                        $q->where('balance', '>', 0);
                    })
                    ->with([
                        'courseOffering.course',
                        'paymentPlan',
                        'paymentPlan.schedules' => function($query) {
                            $query->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                                  ->orderBy('installment_number');
                        }
                    ])
                    ->get();
            }
        }

        return view('pos.index', compact('student', 'enrollments', 'activeCashRegister'));
    }

    public function downloadPdf(int $id)
    {
        $payment = Payment::with([
            'enrollment.student',
            'enrollment.courseOffering.course',
            'paymentPlan',
            'paymentSchedule',
            'receivedBy'
        ])->findOrFail($id);

        // Configurar DomPDF sin public_path
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setChroot(base_path());
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Cargar vista
        $html = view('payments.receipt-pdf', compact('payment'))->render();
        $dompdf->loadHtml($html);
        
        // Configurar papel
        $dompdf->setPaper('letter', 'portrait');
        
        // Renderizar
        $dompdf->render();
        
        // Descargar
        return $dompdf->stream('recibo-' . $payment->payment_code . '.pdf', ['Attachment' => true]);
    }

    public function sendEmail(int $id)
    {
        try {
            $payment = Payment::with([
                'enrollment.student',
                'enrollment.courseOffering.course',
                'paymentPlan',
                'paymentSchedule',
                'receivedBy'
            ])->findOrFail($id);

            // Generar PDF con configuración personalizada
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->setChroot(base_path());
            
            $dompdf = new \Dompdf\Dompdf($options);
            $html = view('payments.receipt-pdf', compact('payment'))->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('letter', 'portrait');
            $dompdf->render();
            $pdfContent = $dompdf->output();

            // Enviar email
            Mail::to($payment->enrollment->student->email)
                ->send(new PaymentReceiptMail($payment, $pdfContent));

            return back()->with('success', 'Recibo enviado por correo exitosamente a ' . $payment->enrollment->student->email);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }
}