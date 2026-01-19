<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('courses.edit');
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('courses')->ignore($this->route('course'))
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['sometimes', 'required', 'in:cocina,reposteria,panaderia,barista,otro'],
            'level' => ['sometimes', 'required', 'in:basico,intermedio,avanzado,especializado'],
            'duration_hours' => ['sometimes', 'required', 'integer', 'min:1'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'max_students' => ['sometimes', 'required', 'integer', 'min:1'],
            'min_students' => ['sometimes', 'required', 'integer', 'min:1'],
            'requirements' => ['nullable', 'string'],
            'objectives' => ['nullable', 'string'],
            'content_outline' => ['nullable', 'string'],
            'materials_included' => ['nullable', 'string'],
            'certificate_included' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El código es obligatorio',
            'code.unique' => 'Este código ya está registrado',
            'name.required' => 'El nombre del curso es obligatorio',
            'category.required' => 'La categoría es obligatoria',
            'level.required' => 'El nivel es obligatorio',
            'duration_hours.required' => 'La duración en horas es obligatoria',
            'price.required' => 'El precio es obligatorio',
            'max_students.required' => 'El máximo de estudiantes es obligatorio',
            'min_students.required' => 'El mínimo de estudiantes es obligatorio',
        ];
    }
}