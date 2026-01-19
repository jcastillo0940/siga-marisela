<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('students.edit');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('students')->ignore($this->route('student'))
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'gender' => ['sometimes', 'required', 'in:male,female,other'],
            'birth_date' => ['nullable', 'date'],
            'identification' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('students')->ignore($this->route('student'))
            ],
            'identification_type' => ['nullable', 'in:cedula,passport,otro'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:100'],
            'medical_notes' => ['nullable', 'string'],
            'emotional_notes' => ['nullable', 'string'],
            'goals' => ['nullable', 'string'],
            'status' => ['nullable', 'in:prospecto,activo,inactivo,graduado,retirado'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es obligatorio',
            'last_name.required' => 'El apellido es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'gender.required' => 'El género es obligatorio',
            'identification.unique' => 'Esta identificación ya está registrada',
        ];
    }
}