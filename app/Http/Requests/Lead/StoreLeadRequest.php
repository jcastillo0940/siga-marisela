<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('leads.create');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:leads,email'],
            'phone' => ['required', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'source' => ['required', 'in:web,referido,redes_sociales,llamada,evento,otro'],
            'source_detail' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:nuevo,contactado,interesado,negociacion,inscrito,perdido'],
            'notes' => ['nullable', 'string'],
            'interests' => ['nullable', 'string'],
            'follow_up_date' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es obligatorio',
            'last_name.required' => 'El apellido es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'phone.required' => 'El teléfono es obligatorio',
            'source.required' => 'La fuente es obligatoria',
            'assigned_to.exists' => 'El usuario asignado no existe',
        ];
    }
}