<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('enrollments.create');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'course_offering_id' => ['required', 'exists:course_offerings,id'],
            'enrollment_date' => ['required', 'date'],
            'price_paid' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:inscrito,en_curso,completado,retirado,suspendido'],
            'notes' => ['nullable', 'string'],
            'requires_approval' => ['nullable', 'boolean'],
            
            // Payment plan fields
            'payment_type' => ['required', 'in:contado,cuotas'],
            'periodicity' => ['required_if:payment_type,cuotas', 'nullable', 'in:semanal,quincenal,mensual'],
            'number_of_installments' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Debe seleccionar un estudiante',
            'student_id.exists' => 'El estudiante seleccionado no existe',
            'course_offering_id.required' => 'Debe seleccionar un curso',
            'course_offering_id.exists' => 'El curso seleccionado no existe',
            'enrollment_date.required' => 'La fecha de inscripción es obligatoria',
            'price_paid.required' => 'El precio pagado es obligatorio',
            'price_paid.min' => 'El precio debe ser mayor o igual a 0',
            'payment_type.required' => 'Debe seleccionar el tipo de pago',
            'periodicity.required_if' => 'La periodicidad es obligatoria para pagos en cuotas',
        ];
    }
}