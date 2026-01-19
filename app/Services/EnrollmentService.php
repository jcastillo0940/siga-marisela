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
}