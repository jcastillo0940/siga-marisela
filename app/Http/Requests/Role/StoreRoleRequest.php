<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('roles.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:roles,slug'],
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
            'slug.unique' => 'Ya existe un rol con este slug',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no existen',
        ];
    }
}