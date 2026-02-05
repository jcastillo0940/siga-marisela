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

    public function createEnrollment(
        CreateEnrollmentDTO $dto,
        array $paymentPlanData = []
    ): Enrollment {
        return DB::transaction(function () use ($dto, $paymentPlanData) {
            // Verificar disponibilidad de cupos
            $offering = CourseOffering::findOrFail($dto->course_offering_id);

            if ($offering->available_spots <= 0) {
                throw new \Exception('No hay cupos disponibles para este curso.');
            }

            // Verificar si el estudiante ya está inscrito
            $exists = Enrollment::where('student_id', $dto->student_id)
                               ->where('course_offering_id', $dto->course_offering_id)
                               ->exists();

            if ($exists) {
                throw new \Exception('El estudiante ya está inscrito en este curso.');
            }

            // Verificar choque de horarios con otros cursos activos
            $this->checkScheduleConflicts($dto->student_id, $offering);

            // Crear inscripción
            $enrollment = Enrollment::create($dto->toArray());

            // Crear plan de pagos si se proporcionan datos
            if (!empty($paymentPlanData)) {
                $paymentType = $paymentPlanData['payment_type'] ?? 'contado';
                $totalAmount = $paymentPlanData['total_amount'] ?? $dto->price_paid;
                $amountPaid = $paymentPlanData['amount_paid'] ?? 0;
                
                // Determinar si es pago completo o tiene plan de cuotas
                if ($paymentType === 'contado' || $amountPaid >= $totalAmount) {
                    // Pago completo - crear plan sin cuotas pendientes
                    $this->paymentPlanService->createPaymentPlan(
                        enrollmentId: $enrollment->id,
                        paymentType: 'contado',
                        totalAmount: $totalAmount,
                        amountPaid: $amountPaid > 0 ? $amountPaid : $totalAmount,
                        periodicity: null,
                        numberOfInstallments: null
                    );
                } else {
                    // Pago parcial - crear plan con cuotas
                    $this->paymentPlanService->createPaymentPlan(
                        enrollmentId: $enrollment->id,
                        paymentType: 'cuotas',
                        totalAmount: $totalAmount,
                        amountPaid: $amountPaid,
                        periodicity: $paymentPlanData['periodicity'] ?? null,
                        numberOfInstallments: $paymentPlanData['number_of_installments'] ?? null
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

    /**
     * Verifica si hay choque de horarios entre el curso nuevo y los cursos activos del estudiante
     *
     * @throws \Exception si hay conflicto de horarios
     */
    private function checkScheduleConflicts(int $studentId, CourseOffering $newOffering): void
    {
        // Obtener inscripciones activas del estudiante
        $activeEnrollments = Enrollment::where('student_id', $studentId)
            ->whereIn('status', ['inscrito', 'en_curso'])
            ->with(['courseOffering.dates', 'courseOffering.course'])
            ->get();

        if ($activeEnrollments->isEmpty()) {
            return;
        }

        // Cargar las fechas del curso nuevo
        $newOffering->load('dates', 'course');
        $newDates = $newOffering->dates()->where('is_cancelled', false)->get();

        if ($newDates->isEmpty()) {
            return;
        }

        // Verificar conflictos con cada curso activo
        foreach ($activeEnrollments as $enrollment) {
            $existingDates = $enrollment->courseOffering->dates()
                ->where('is_cancelled', false)
                ->get();

            if ($existingDates->isEmpty()) {
                continue;
            }

            // Comparar cada fecha del curso nuevo con las fechas existentes
            foreach ($newDates as $newDate) {
                foreach ($existingDates as $existingDate) {
                    // Verificar si es el mismo día
                    if ($newDate->class_date->isSameDay($existingDate->class_date)) {
                        // Verificar si hay overlap de horarios
                        if ($this->hasTimeOverlap(
                            $newDate->start_time,
                            $newDate->end_time,
                            $existingDate->start_time,
                            $existingDate->end_time
                        )) {
                            $conflictCourse = $enrollment->courseOffering->full_name;
                            $conflictDate = $existingDate->class_date->format('d/m/Y');
                            $conflictTime = $existingDate->start_time . ' - ' . $existingDate->end_time;

                            throw new \Exception(
                                "Conflicto de horario detectado: El estudiante ya tiene inscripción en '{$conflictCourse}' " .
                                "el día {$conflictDate} de {$conflictTime}. " .
                                "No puede inscribirse en dos cursos con horarios que se cruzan."
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     * Verifica si dos rangos de tiempo se superponen
     */
    private function hasTimeOverlap(?string $start1, ?string $end1, ?string $start2, ?string $end2): bool
    {
        if (!$start1 || !$end1 || !$start2 || !$end2) {
            return false;
        }

        $start1Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $start1);
        $end1Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $end1);
        $start2Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $start2);
        $end2Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $end2);

        return $start1Carbon->lt($end2Carbon) && $start2Carbon->lt($end1Carbon);
    }
}