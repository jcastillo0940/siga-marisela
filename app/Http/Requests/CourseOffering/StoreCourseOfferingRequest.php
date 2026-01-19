<?php

namespace App\Http\Requests\CourseOffering;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseOfferingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('courses.create');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_hours' => ['required', 'integer', 'min:1'],
            'is_generation' => ['nullable', 'boolean'],
            'generation_name' => ['nullable', 'string', 'max:255'],
            'min_students' => ['required', 'integer', 'min:1'],
            'max_students' => ['required', 'integer', 'min:1', 'gte:min_students'],
            'certificate_included' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:programado,en_curso,completado,cancelado'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'class_dates' => ['nullable', 'array'],
            'class_dates.*.date' => ['required', 'date'],
            'class_dates.*.start_time' => ['nullable', 'date_format:H:i'],
            'class_dates.*.end_time' => ['nullable', 'date_format:H:i', 'after:class_dates.*.start_time'],
            'class_dates.*.notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Debe seleccionar un curso',
            'location.required' => 'La ubicación es obligatoria',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'end_date.required' => 'La fecha de fin es obligatoria',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio',
            'price.required' => 'El precio es obligatorio',
            'duration_hours.required' => 'La duración es obligatoria',
            'min_students.required' => 'El mínimo de estudiantes es obligatorio',
            'max_students.required' => 'El máximo de estudiantes es obligatorio',
            'max_students.gte' => 'El máximo debe ser mayor o igual al mínimo',
            'class_dates.*.date.required' => 'La fecha de clase es obligatoria',
            'class_dates.*.end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio',
        ];
    }
}