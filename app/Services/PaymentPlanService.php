<?php

namespace App\Services;

use App\Models\PaymentPlan;
use App\Models\PaymentSchedule;
use App\Models\Enrollment;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentPlanService
{
    /**
     * Crea un plan de pagos con soporte para pago inicial
     */
    public function createPaymentPlan(
        int $enrollmentId,
        string $paymentType,
        float $totalAmount,
        ?string $periodicity = null,
        ?int $numberOfInstallments = null,
        float $amountPaid = 0
    ): PaymentPlan {
        return DB::transaction(function () use ($enrollmentId, $paymentType, $totalAmount, $periodicity, $numberOfInstallments, $amountPaid) {
            $enrollment = Enrollment::with('courseOffering')->findOrFail($enrollmentId);
            
            $firstPaymentDate = $enrollment->enrollment_date;
            $lastPaymentDate = $enrollment->courseOffering->start_date;

            // Determinar si es pago completo
            $isFullPayment = $amountPaid >= $totalAmount || $paymentType === 'contado';
            
            // Calcular balance inicial
            $initialBalance = $totalAmount - $amountPaid;

            // Si es pago completo, forzar a contado
            if ($isFullPayment) {
                $paymentType = 'contado';
                $numberOfInstallments = 1;
                $periodicity = null;
            } else {
                // Calcular número de cuotas automáticamente si no se proporciona
                if ($paymentType === 'cuotas' && !$numberOfInstallments) {
                    $numberOfInstallments = $this->calculateInstallments(
                        $firstPaymentDate,
                        $lastPaymentDate,
                        $periodicity
                    );
                }
            }

            // Crear el plan de pagos
            $paymentPlan = PaymentPlan::create([
                'enrollment_id' => $enrollmentId,
                'payment_type' => $paymentType,
                'total_amount' => $totalAmount,
                'balance' => $initialBalance,
                'number_of_installments' => $paymentType === 'contado' ? 1 : $numberOfInstallments,
                'periodicity' => $paymentType === 'cuotas' ? $periodicity : null,
                'first_payment_date' => $firstPaymentDate,
                'last_payment_date' => $lastPaymentDate,
                'status' => $initialBalance <= 0 ? 'completado' : 'pendiente',
            ]);

            // Generar cronograma de pagos
            if ($isFullPayment) {
                // Pago completo - una sola cuota marcada como pagada
                $this->generateFullPaymentSchedule($paymentPlan, $amountPaid);
            } else {
                // Pago parcial - generar cuotas y registrar pago inicial
                $this->generatePaymentSchedule($paymentPlan, $amountPaid, $initialBalance);
            }

            return $paymentPlan->fresh(['schedules']);
        });
    }

    /**
     * Genera cronograma para pago completo
     */
    private function generateFullPaymentSchedule(PaymentPlan $paymentPlan, float $amountPaid): void
    {
        // Crear única cuota como pagada
        $schedule = PaymentSchedule::create([
            'payment_plan_id' => $paymentPlan->id,
            'installment_number' => 1,
            'due_date' => $paymentPlan->first_payment_date,
            'amount' => $paymentPlan->total_amount,
            'amount_paid' => $amountPaid,
            'balance' => 0,
            'status' => 'pagado',
            'paid_at' => now(),
        ]);

        // Registrar el pago en la tabla de pagos
        Payment::create([
            'enrollment_id' => $paymentPlan->enrollment_id,
            'payment_plan_id' => $paymentPlan->id,
            'payment_schedule_id' => $schedule->id,
            'payment_date' => now(),
            'amount' => $amountPaid,
            'payment_method' => 'transferencia',
            'reference_number' => 'PAGO_INICIAL_LEAD',
            'received_by' => auth()->id() ?? 1,
            'status' => 'completado',
            'notes' => 'Pago completo al momento de la inscripción desde lead',
        ]);
    }

    /**
     * Genera cronograma para plan de cuotas con pago inicial
     */
    private function generatePaymentSchedule(
        PaymentPlan $paymentPlan, 
        float $amountPaid, 
        float $remainingBalance
    ): void {
        $numberOfInstallments = $paymentPlan->number_of_installments;
        $installmentAmount = $remainingBalance / $numberOfInstallments;
        $currentDate = $paymentPlan->first_payment_date->copy();

        // Si hay pago inicial, crear primera cuota como pagada
        if ($amountPaid > 0) {
            $firstSchedule = PaymentSchedule::create([
                'payment_plan_id' => $paymentPlan->id,
                'installment_number' => 0, // Cuota 0 = pago inicial
                'due_date' => $paymentPlan->first_payment_date,
                'amount' => $amountPaid,
                'amount_paid' => $amountPaid,
                'balance' => 0,
                'status' => 'pagado',
                'paid_at' => now(),
            ]);

            // Registrar el pago inicial
            Payment::create([
                'enrollment_id' => $paymentPlan->enrollment_id,
                'payment_plan_id' => $paymentPlan->id,
                'payment_schedule_id' => $firstSchedule->id,
                'payment_date' => now(),
                'amount' => $amountPaid,
                'payment_method' => 'transferencia',
                'reference_number' => 'PAGO_INICIAL_LEAD',
                'received_by' => auth()->id() ?? 1,
                'status' => 'completado',
                'notes' => 'Pago inicial al momento de la inscripción desde lead',
            ]);
        }

        // Generar cuotas restantes
        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            // Última cuota debe caer exactamente en la fecha de inicio del curso
            if ($i === $numberOfInstallments) {
                $dueDate = $paymentPlan->last_payment_date;
            } else {
                $dueDate = $currentDate->copy();
            }

            // Ajustar monto de última cuota para cubrir diferencias de redondeo
            $amount = ($i === $numberOfInstallments) 
                ? $remainingBalance - ($installmentAmount * ($numberOfInstallments - 1))
                : $installmentAmount;

            PaymentSchedule::create([
                'payment_plan_id' => $paymentPlan->id,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'amount' => round($amount, 2),
                'amount_paid' => 0,
                'balance' => round($amount, 2),
                'status' => 'pendiente',
            ]);

            // Calcular siguiente fecha según periodicidad
            if ($i < $numberOfInstallments) {
                $currentDate = $this->calculateNextDate($currentDate, $paymentPlan->periodicity);
            }
        }
    }

    private function calculateInstallments(Carbon $startDate, Carbon $endDate, string $periodicity): int
    {
        $days = $startDate->diffInDays($endDate);
        
        return match($periodicity) {
            'semanal' => max(1, (int) ceil($days / 7)),
            'quincenal' => max(1, (int) ceil($days / 15)),
            'mensual' => max(1, (int) ceil($days / 30)),
            default => 1
        };
    }

    private function calculateNextDate(Carbon $currentDate, ?string $periodicity): Carbon
    {
        return match($periodicity) {
            'semanal' => $currentDate->addWeek(),
            'quincenal' => $currentDate->addWeeks(2),
            'mensual' => $currentDate->addMonth(),
            default => $currentDate->addDay()
        };
    }

    public function registerPayment(
        int $paymentPlanId,
        float $amount,
        string $paymentMethod,
        ?int $paymentScheduleId = null,
        ?string $referenceNumber = null,
        ?string $notes = null
    ): void {
        DB::transaction(function () use ($paymentPlanId, $amount, $paymentMethod, $paymentScheduleId, $referenceNumber, $notes) {
            $paymentPlan = PaymentPlan::with('schedules')->findOrFail($paymentPlanId);

            // Crear el pago
            $payment = Payment::create([
                'enrollment_id' => $paymentPlan->enrollment_id,
                'payment_plan_id' => $paymentPlanId,
                'payment_schedule_id' => $paymentScheduleId,
                'payment_date' => now(),
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'reference_number' => $referenceNumber,
                'received_by' => auth()->id(),
                'status' => 'completado',
                'notes' => $notes,
            ]);

            // Si hay una cuota específica, aplicar el pago
            if ($paymentScheduleId) {
                $schedule = PaymentSchedule::findOrFail($paymentScheduleId);
                $schedule->addPayment($amount);
            } else {
                // Aplicar pago a la primera cuota pendiente
                $pendingSchedule = $paymentPlan->schedules()
                    ->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                    ->orderBy('installment_number')
                    ->first();

                if ($pendingSchedule) {
                    $remainingAmount = $amount;
                    
                    while ($remainingAmount > 0 && $pendingSchedule) {
                        $scheduleBalance = $pendingSchedule->balance;
                        $amountToApply = min($remainingAmount, $scheduleBalance);
                        
                        $pendingSchedule->addPayment($amountToApply);
                        $remainingAmount -= $amountToApply;

                        if ($remainingAmount > 0) {
                            $pendingSchedule = $paymentPlan->schedules()
                                ->whereIn('status', ['pendiente', 'parcial', 'vencido'])
                                ->orderBy('installment_number')
                                ->first();
                        }
                    }
                }
            }

            // Actualizar balance del plan
            $paymentPlan->updateBalance();
        });
    }

    public function getPaymentPlanById(int $id): ?PaymentPlan
    {
        return PaymentPlan::with([
            'enrollment.student',
            'enrollment.courseOffering.course',
            'schedules',
            'payments.receivedBy'
        ])->find($id);
    }
}