<?php

namespace App\Http\Requests\Enrollment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('enrollments.edit');
    }

    public function rules(): array
    {
        return [
            'student_id' => ['sometimes', 'required', 'exists:students,id'],
            'course_offering_id' => ['sometimes', 'required', 'exists:course_offerings,id'],
            'enrollment_date' => ['sometimes', 'required', 'date'],
            'price_paid' => ['sometimes', 'required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'required', 'in:inscrito,en_curso,completado,retirado,suspendido'],
            'notes' => ['nullable', 'string'],
            'certificate_issued' => ['nullable', 'boolean'],
            'certificate_issued_at' => ['nullable', 'date'],
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
        ];
    }
}