<?php

namespace App\Http\Requests\CourseOffering;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseOfferingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('courses.edit');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['sometimes', 'required', 'exists:courses,id'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'duration_hours' => ['sometimes', 'required', 'integer', 'min:1'],
            'min_students' => ['sometimes', 'required', 'integer', 'min:1'],
            'max_students' => ['sometimes', 'required', 'integer', 'min:1', 'gte:min_students'],
            'is_generation' => ['sometimes', 'boolean'],
            'generation_name' => ['nullable', 'string', 'max:255'],
            'certificate_included' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'required', 'in:programado,en_curso,completado,cancelado'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            
            // Class dates
            'class_dates' => ['sometimes', 'array'],
            'class_dates.*.date' => ['required_with:class_dates', 'date'],
            'class_dates.*.start_time' => ['nullable', 'date_format:H:i:s,H:i'], // Acepta ambos formatos
            'class_dates.*.end_time' => ['nullable', 'date_format:H:i:s,H:i', 'after:class_dates.*.start_time'],
            'class_dates.*.notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.exists' => 'El curso seleccionado no existe',
            'location.required' => 'La ubicación es obligatoria',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'price.required' => 'El precio es obligatorio',
            'max_students.gte' => 'El máximo de estudiantes debe ser mayor o igual al mínimo',
            'class_dates.*.date.required_with' => 'La fecha de clase es obligatoria',
            'class_dates.*.start_time.date_format' => 'La hora de inicio debe tener formato HH:MM',
            'class_dates.*.end_time.date_format' => 'La hora de fin debe tener formato HH:MM',
            'class_dates.*.end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio',
        ];
    }
}