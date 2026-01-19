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
                $this->paymentPlanService->createPaymentPlan(
                    enrollmentId: $enrollment->id,
                    paymentType: $paymentPlanData['payment_type'],
                    totalAmount: $paymentPlanData['total_amount'],
                    periodicity: $paymentPlanData['periodicity'] ?? null,
                    numberOfInstallments: $paymentPlanData['number_of_installments'] ?? null
                );
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
            return; // No hay cursos activos, no puede haber conflicto
        }

        // Cargar las fechas del curso nuevo
        $newOffering->load('dates', 'course');
        $newDates = $newOffering->dates()->where('is_cancelled', false)->get();

        if ($newDates->isEmpty()) {
            return; // Si no tiene fechas programadas, no hay conflicto posible
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
     *
     * @param string|null $start1 Hora inicio 1 (HH:MM:SS)
     * @param string|null $end1 Hora fin 1 (HH:MM:SS)
     * @param string|null $start2 Hora inicio 2 (HH:MM:SS)
     * @param string|null $end2 Hora fin 2 (HH:MM:SS)
     * @return bool
     */
    private function hasTimeOverlap(?string $start1, ?string $end1, ?string $start2, ?string $end2): bool
    {
        // Si alguno no tiene horarios definidos, no podemos verificar overlap
        if (!$start1 || !$end1 || !$start2 || !$end2) {
            return false; // No hay suficiente información para determinar conflicto
        }

        // Convertir a Carbon para comparación
        $start1Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $start1);
        $end1Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $end1);
        $start2Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $start2);
        $end2Carbon = \Carbon\Carbon::createFromFormat('H:i:s', $end2);

        // Verificar overlap:
        // Hay overlap si el inicio de uno está antes del fin del otro Y viceversa
        return $start1Carbon->lt($end2Carbon) && $start2Carbon->lt($end1Carbon);
    }
}