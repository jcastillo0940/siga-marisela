<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('roles.edit');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('roles')->ignore($this->route('role'))
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio',
            'name.unique' => 'Ya existe un rol con este nombre',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no existen',
        ];
    }
}