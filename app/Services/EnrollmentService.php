<?php

namespace App\Services;

use App\DTOs\Enrollment\CreateEnrollmentDTO;
use App\DTOs\Enrollment\UpdateEnrollmentDTO;
use App\Models\Enrollment;
use App\Models\CourseOffering;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function __construct(
        private PaymentPlanService $paymentPlanService
    ) {}

    public function getAllEnrollments(): Collection
    {
        return Enrollment::with(['student', 'courseOffering.course', 'paymentPlan'])
                        ->orderBy('created_at', 'desc')
                        ->get();
    }

    public function getEnrollmentById(int $id): ?Enrollment
    {
        return Enrollment::with([
            'student', 
            'courseOffering.course', 
            'courseOffering.dates',
            'paymentPlan.schedules',
            'payments.receivedBy'
        ])->find($id);
    }

    /**
     * Crea una inscripción y sincroniza automáticamente el Plan de Pagos
     */
    public function createEnrollment(
        CreateEnrollmentDTO $dto,
        array $paymentPlanData = []
    ): Enrollment {
        return DB::transaction(function () use ($dto, $paymentPlanData) {
            // 1. Verificar disponibilidad de cupos
            $offering = CourseOffering::findOrFail($dto->course_offering_id);

            if ($offering->available_spots <= 0) {
                throw new \Exception('No hay cupos disponibles para este curso.');
            }

            // 2. Verificar si el estudiante ya está inscrito
            $exists = Enrollment::where('student_id', $dto->student_id)
                               ->where('course_offering_id', $dto->course_offering_id)
                               ->exists();

            if ($exists) {
                throw new \Exception('El estudiante ya está inscrito en este curso.');
            }

            // 3. Verificar choque de horarios con otros cursos activos
            $this->checkScheduleConflicts($dto->student_id, $offering);

            // 4. Crear inscripción
            $enrollment = Enrollment::create($dto->toArray());

            // 5. Crear plan de pagos sincronizado
            if (!empty($paymentPlanData)) {
                $paymentType = $paymentPlanData['payment_type'] ?? 'contado';
                $amountPaid = $paymentPlanData['amount_paid'] ?? 0;
                
                /**
                 * REGLA DE CONSISTENCIA: 
                 * El monto total del plan de pagos DEBE ser el final_price de la inscripción.
                 * Esto evita que el total diga $350 y el saldo se calcule sobre $300.
                 */
                $totalAmount = $enrollment->final_price; 
                
                // Determinar si es pago completo o tiene plan de cuotas
                if ($paymentType === 'contado' || $amountPaid >= $totalAmount) {
                    $this->paymentPlanService->createPaymentPlan(
                        enrollmentId: $enrollment->id,
                        paymentType: 'contado',
                        totalAmount: $totalAmount,
                        amountPaid: $amountPaid > 0 ? $amountPaid : $totalAmount,
                        periodicity: null,
                        numberOfInstallments: null
                    );
                } else {
                    $this->paymentPlanService->createPaymentPlan(
                        enrollmentId: $enrollment->id,
                        paymentType: 'cuotas',
                        totalAmount: $totalAmount,
                        amountPaid: $amountPaid,
                        periodicity: $paymentPlanData['periodicity'] ?? 'mensual',
                        numberOfInstallments: $paymentPlanData['number_of_installments'] ?? 1
                    );
                }
            }

            return $enrollment->fresh(['paymentPlan.schedules']);
        });
    }

    public function updateEnrollment(int $id, UpdateEnrollmentDTO $dto): Enrollment
    {
        return DB::transaction(function () use ($id, $dto) {
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->update($dto->toArray());

            /**
             * OPCIONAL: Si cambias el precio o descuento en el Update, 
             * deberías llamar a $enrollment->paymentPlan->updateBalance() 
             * para resincronizar los montos.
             */
            if ($enrollment->paymentPlan) {
                $enrollment->paymentPlan->updateBalance();
            }

            return $enrollment->fresh(['student', 'courseOffering.course']);
        });
    }

    public function deleteEnrollment(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $enrollment = Enrollment::findOrFail($id);
            return $enrollment->delete();
        });
    }

    public function issueCertificate(int $id): Enrollment
    {
        return DB::transaction(function () use ($id) {
            $enrollment = Enrollment::findOrFail($id);

            if (!$enrollment->courseOffering->certificate_included) {
                throw new \Exception('Este curso no incluye certificado.');
            }

            if ($enrollment->status !== 'completado') {
                throw new \Exception('El estudiante debe completar el curso para recibir el certificado.');
            }

            $enrollment->certificate_issued = true;
            $enrollment->certificate_issued_at = now();
            $enrollment->save();

            return $enrollment->fresh();
        });
    }

    private function checkScheduleConflicts(int $studentId, CourseOffering $newOffering): void
    {
        $activeEnrollments = Enrollment::where('student_id', $studentId)
            ->whereIn('status', ['inscrito', 'en_curso'])
            ->with(['courseOffering.dates', 'courseOffering.course'])
            ->get();

        if ($activeEnrollments->isEmpty()) {
            return;
        }

        $newOffering->load('dates', 'course');
        $newDates = $newOffering->dates()->where('is_cancelled', false)->get();

        if ($newDates->isEmpty()) {
            return;
        }

        foreach ($activeEnrollments as $enrollment) {
            $existingDates = $enrollment->courseOffering->dates()
                ->where('is_cancelled', false)
                ->get();

            foreach ($newDates as $newDate) {
                foreach ($existingDates as $existingDate) {
                    if ($newDate->class_date->isSameDay($existingDate->class_date)) {
                        if ($this->hasTimeOverlap(
                            $newDate->start_time,
                            $newDate->end_time,
                            $existingDate->start_time,
                            $existingDate->end_time
                        )) {
                            throw new \Exception(
                                "Conflicto de horario: El estudiante ya tiene '{$enrollment->courseOffering->full_name}' " .
                                "el {$existingDate->class_date->format('d/m/Y')} a las {$existingDate->start_time}."
                            );
                        }
                    }
                }
            }
        }
    }

    private function hasTimeOverlap(?string $start1, ?string $end1, ?string $start2, ?string $end2): bool
    {
        if (!$start1 || !$end1 || !$start2 || !$end2) return false;

        $start1Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $start1);
        $end1Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $end1);
        $start2Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $start2);
        $end2Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $end2);

        return $start1Carbon->lt($end2Carbon) && $start2Carbon->lt($end1Carbon);
    }
}