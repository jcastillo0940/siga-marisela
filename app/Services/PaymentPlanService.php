<?php

namespace App\Services;

use App\Models\PaymentPlan;
use App\Models\PaymentSchedule;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentPlanService
{
    public function createPaymentPlan(
        int $enrollmentId,
        string $paymentType,
        float $totalAmount,
        ?string $periodicity = null,
        ?int $numberOfInstallments = null
    ): PaymentPlan {
        return DB::transaction(function () use ($enrollmentId, $paymentType, $totalAmount, $periodicity, $numberOfInstallments) {
            $enrollment = Enrollment::with('courseOffering')->findOrFail($enrollmentId);
            
            $firstPaymentDate = $enrollment->enrollment_date;
            $lastPaymentDate = $enrollment->courseOffering->start_date;

            // Calcular número de cuotas automáticamente si no se proporciona
            if ($paymentType === 'cuotas' && !$numberOfInstallments) {
                $numberOfInstallments = $this->calculateInstallments(
                    $firstPaymentDate,
                    $lastPaymentDate,
                    $periodicity
                );
            }

            // Crear el plan de pagos
            $paymentPlan = PaymentPlan::create([
                'enrollment_id' => $enrollmentId,
                'payment_type' => $paymentType,
                'total_amount' => $totalAmount,
                'balance' => $totalAmount,
                'number_of_installments' => $paymentType === 'contado' ? 1 : $numberOfInstallments,
                'periodicity' => $paymentType === 'cuotas' ? $periodicity : null,
                'first_payment_date' => $firstPaymentDate,
                'last_payment_date' => $lastPaymentDate,
                'status' => 'pendiente',
            ]);

            // Generar cronograma de pagos
            $this->generatePaymentSchedule($paymentPlan);

            return $paymentPlan->fresh(['schedules']);
        });
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

    private function generatePaymentSchedule(PaymentPlan $paymentPlan): void
    {
        $installmentAmount = $paymentPlan->total_amount / $paymentPlan->number_of_installments;
        $currentDate = $paymentPlan->first_payment_date->copy();

        for ($i = 1; $i <= $paymentPlan->number_of_installments; $i++) {
            // Última cuota debe caer exactamente en la fecha de inicio del curso
            if ($i === $paymentPlan->number_of_installments) {
                $dueDate = $paymentPlan->last_payment_date;
            } else {
                $dueDate = $currentDate->copy();
            }

            // Ajustar monto de última cuota para cubrir diferencias de redondeo
            $amount = ($i === $paymentPlan->number_of_installments) 
                ? $paymentPlan->total_amount - ($installmentAmount * ($paymentPlan->number_of_installments - 1))
                : $installmentAmount;

            PaymentSchedule::create([
                'payment_plan_id' => $paymentPlan->id,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'amount' => round($amount, 2),
                'status' => 'pendiente',
            ]);

            // Calcular siguiente fecha según periodicidad
            if ($i < $paymentPlan->number_of_installments) {
                $currentDate = $this->calculateNextDate($currentDate, $paymentPlan->periodicity);
            }
        }
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
            $payment = \App\Models\Payment::create([
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