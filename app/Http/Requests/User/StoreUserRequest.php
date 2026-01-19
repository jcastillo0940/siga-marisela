<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('users.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()],
            'phone' => ['nullable', 'string', 'max:20'],
            'position' => ['nullable', 'string', 'max:100'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'roles.*.exists' => 'Uno o más roles seleccionados no existen',
        ];
    }
}