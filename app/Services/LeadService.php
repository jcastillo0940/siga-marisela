<?php

namespace App\Services;

use App\DTOs\Lead\CreateLeadDTO;
use App\DTOs\Lead\UpdateLeadDTO;
use App\Models\Lead;
use App\Models\Student;
use App\Models\Enrollment;
use App\DTOs\Enrollment\CreateEnrollmentDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function __construct(
        private readonly EnrollmentService $enrollmentService
    ) {}

    public function getAllLeads(): Collection
    {
        return Lead::with(['assignedUser', 'courseOffering.course'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getLeadById(int $id): ?Lead
    {
        return Lead::with(['assignedUser', 'student', 'courseOffering.course'])->find($id);
    }

    public function createLead(CreateLeadDTO $dto): Lead
    {
        return DB::transaction(function () use ($dto) {
            return Lead::create($dto->toArray());
        });
    }

    public function updateLead(int $id, UpdateLeadDTO $dto): Lead
    {
        return DB::transaction(function () use ($id, $dto) {
            $lead = Lead::findOrFail($id);
            $lead->update($dto->toArray());
            return $lead->fresh();
        });
    }

    /**
     * Valida el pago de un Lead y dispara la conversión si es verificado.
     */
    public function verifyPayment(int $leadId, string $status, ?string $notes = null): Lead
    {
        return DB::transaction(function () use ($leadId, $status, $notes) {
            $lead = Lead::findOrFail($leadId);
            
            $lead->update([
                'payment_status' => $status,
                'notes' => $lead->notes . "\n[Verificación de Pago " . now() . "]: " . $notes
            ]);

            // Si el pago es verificado y tiene un curso asignado, convertir automáticamente
            if ($status === 'verified' && $lead->course_offering_id && !$lead->isConverted()) {
                $this->convertToStudent($lead->id);
            }

            return $lead->fresh();
        });
    }

    /**
     * Convierte un Lead en Estudiante. 
     * Si el email ya existe en Students, reutiliza el registro para evitar errores de duplicidad.
     */
    public function convertToStudent(int $leadId, array $additionalData = []): Student
    {
        return DB::transaction(function () use ($leadId, $additionalData) {
            $lead = Lead::findOrFail($leadId);

            // Evitar doble conversión
            if ($lead->isConverted()) {
                throw new \Exception('Este lead ya fue convertido a estudiante anteriormente.');
            }

            // 1. Lógica anti-duplicados: Buscar estudiante existente por email
            $student = Student::where('email', $lead->email)->first();

            if (!$student) {
                // Crear nuevo estudiante si no existe
                $student = Student::create(array_merge([
                    'first_name' => $lead->first_name,
                    'last_name' => $lead->last_name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'phone_secondary' => $lead->phone_secondary,
                    'gender' => $additionalData['gender'] ?? $lead->gender ?? 'other',
                    'birth_date' => $lead->birth_date_text,
                    'address' => $lead->address_full,
                    'emergency_contact_phone' => $lead->parent_phone,
                    'medical_notes' => $lead->medical_notes_lead,
                    'status' => 'activo',
                    'is_active' => true,
                ], $additionalData));
            } else {
                // Actualizar datos del estudiante existente con la información más reciente
                $student->update([
                    'phone' => $lead->phone,
                    'address' => $lead->address_full,
                    'medical_notes' => $lead->medical_notes_lead,
                    'emergency_contact_phone' => $lead->parent_phone,
                ]);
            }

            // 2. Procesar la inscripción si el lead tiene una oferta de curso asignada
            if ($lead->course_offering_id) {
                
                // Verificar si ya existe una inscripción para evitar registros duplicados
                $isAlreadyEnrolled = Enrollment::where('student_id', $student->id)
                    ->where('course_offering_id', $lead->course_offering_id)
                    ->exists();

                if (!$isAlreadyEnrolled) {
                    $this->enrollmentService->createEnrollment(
                        new CreateEnrollmentDTO(
                            student_id: $student->id,
                            course_offering_id: $lead->course_offering_id,
                            enrollment_date: now()->format('Y-m-d'),
                            price_paid: $lead->courseOffering->price ?? 0,
                            status: 'inscrito'
                        ),
                        [
                            'payment_type' => 'contado',
                            'total_amount' => $lead->courseOffering->price ?? 0
                        ]
                    );
                }
            }

            // 3. Actualizar el estado del Lead y vincularlo al estudiante
            $lead->update([
                'converted_to_student_id' => $student->id,
                'converted_at' => now(),
                'status' => 'inscrito',
                'payment_status' => 'verified'
            ]);

            return $student;
        });
    }

    public function deleteLead(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $lead = Lead::findOrFail($id);
            return $lead->delete();
        });
    }
}