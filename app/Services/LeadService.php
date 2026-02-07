<?php

namespace App\Services;

use App\DTOs\Lead\CreateLeadDTO;
use App\DTOs\Lead\UpdateLeadDTO;
use App\Models\Lead;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Role;
use App\DTOs\Enrollment\CreateEnrollmentDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
     * Ahora incluye soporte para plan de pagos y cuotas.
     */
    public function verifyPayment(int $leadId, string $status, array $data): Lead
    {
        return DB::transaction(function () use ($leadId, $status, $data) {
            $lead = Lead::findOrFail($leadId);
            
            // Actualizar notas del lead
            $notes = $data['notes'] ?? '';
            $lead->update([
                'payment_status' => $status,
                'notes' => $lead->notes . "\n[Verificación de Pago " . now()->format('d/m/Y H:i') . "]: " . $notes
            ]);

            // Si el pago es verificado y tiene un curso asignado, convertir automáticamente
            if ($status === 'verified' && $lead->course_offering_id && !$lead->isConverted()) {
                $this->convertToStudent($lead->id, $data);
            }

            return $lead->fresh();
        });
    }

    /**
     * Convierte un Lead en Estudiante e Inscripción. 
     * Ahora incluye soporte completo para plan de pagos y creación de usuario.
     */
    public function convertToStudent(int $leadId, array $additionalData = []): Student
    {
        return DB::transaction(function () use ($leadId, $additionalData) {
            $lead = Lead::findOrFail($leadId);

            // Evitar doble conversión
            if ($lead->isConverted()) {
                throw new \Exception('Este lead ya fue convertido a estudiante anteriormente.');
            }

            // 1. Buscar o crear estudiante
            $student = Student::where('email', $lead->email)->first();

            if (!$student) {
                // Crear nuevo estudiante si no existe
                $student = Student::create([
                    'first_name' => $lead->first_name,
                    'last_name' => $lead->last_name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'phone_secondary' => $lead->phone_secondary,
                    'gender' => $additionalData['gender'] ?? 'other',
                    'birth_date' => $lead->birth_date_text,
                    'address' => $lead->address_full,
                    'emergency_contact_phone' => $lead->parent_phone,
                    'medical_notes' => $lead->medical_notes_lead,
                    'status' => 'activo',
                    'is_active' => true,
                ]);
            } else {
                // Actualizar datos del estudiante existente
                $student->update([
                    'phone' => $lead->phone,
                    'address' => $lead->address_full,
                    'medical_notes' => $lead->medical_notes_lead,
                    'emergency_contact_phone' => $lead->parent_phone,
                ]);
            }

            // 1.5 Crear Usuario Automáticamente si no tiene
            if (!$student->user_id) {
                // Verificar si ya existe un usuario con este email
                $existingUser = User::where('email', $student->email)->first();

                if ($existingUser) {
                    // Si existe el usuario por email, vinculamos
                    $student->update(['user_id' => $existingUser->id]);
                    
                    // Asegurar rol de estudiante
                    $studentRole = Role::where('slug', 'student')->first();
                    if ($studentRole && !$existingUser->hasRole('student')) {
                        $existingUser->roles()->attach($studentRole->id);
                    }
                } else {
                    // Crear nuevo usuario
                    // Contraseña: Fecha nacimiento (dmY) o por defecto 12345678
                    $passwordRaw = '12345678';
                    if (!empty($lead->birth_date_text)) {
                        try {
                            $passwordRaw = Carbon::parse($lead->birth_date_text)->format('dmY');
                        } catch (\Exception $e) {
                            // Fallback si la fecha no es parseable
                        }
                    }

                    $newUser = User::create([
                        'name' => $lead->first_name . ' ' . $lead->last_name,
                        'email' => $lead->email,
                        'password' => bcrypt($passwordRaw),
                        'is_active' => true,
                    ]);

                    // Asignar rol
                    $studentRole = Role::where('slug', 'student')->first();
                    if ($studentRole) {
                        $newUser->roles()->attach($studentRole->id);
                    }

                    // Vincular al estudiante
                    $student->update(['user_id' => $newUser->id]);
                }
            }

            // 2. Crear inscripción si el lead tiene una oferta de curso
            if ($lead->course_offering_id) {
                
                // Verificar si ya existe una inscripción
                $isAlreadyEnrolled = Enrollment::where('student_id', $student->id)
                    ->where('course_offering_id', $lead->course_offering_id)
                    ->exists();

                if (!$isAlreadyEnrolled) {
                    // Obtener datos del curso
                    $courseOffering = $lead->courseOffering;
                    $coursePrice = $courseOffering->price ?? 0;
                    
                    // Obtener monto pagado del additional data
                    $amountPaid = $additionalData['amount_paid'] ?? $coursePrice;
                    
                    // Determinar si es pago completo o parcial
                    $isFullPayment = $amountPaid >= $coursePrice;
                    
                    // Preparar datos de pago
                    if ($isFullPayment) {
                        // Pago completo
                        $paymentType = 'contado';
                        $periodicity = null;
                        $numberOfInstallments = null;
                    } else {
                        // Pago parcial - requiere plan de pagos
                        $paymentType = $additionalData['payment_type'] ?? 'cuotas';
                        $periodicity = $additionalData['periodicity'] ?? null;
                        $numberOfInstallments = $additionalData['number_of_installments'] ?? null;
                        
                        if (!$periodicity || !$numberOfInstallments) {
                             // Fallback valores por defecto si faltan datos en conversión automática
                             $paymentType = 'cuotas';
                             $periodicity = 'monthly'; 
                             $numberOfInstallments = 1; 
                        }
                    }
                    
                    // Crear inscripción
                    $enrollment = $this->enrollmentService->createEnrollment(
                        new CreateEnrollmentDTO(
                            student_id: $student->id,
                            course_offering_id: $lead->course_offering_id,
                            enrollment_date: now()->format('Y-m-d'),
                            price_paid: $coursePrice,
                            discount: 0,
                            status: 'inscrito',
                            notes: "Convertido desde lead #{$lead->id}. Monto inicial pagado: $" . number_format($amountPaid, 2)
                        ),
                        [
                            'payment_type' => $paymentType,
                            'total_amount' => $coursePrice,
                            'amount_paid' => $amountPaid,
                            'periodicity' => $periodicity,
                            'number_of_installments' => $numberOfInstallments,
                        ]
                    );
                }
            }

            // 3. Actualizar el estado del Lead
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