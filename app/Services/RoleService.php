<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function getAllRoles(): Collection
    {
        return Role::with('permissions')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getRoleById(int $id): Role
    {
        return Role::with('permissions', 'users')->findOrFail($id);
    }

    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? null,
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Asignar permisos
            if (!empty($data['permissions'])) {
                $permissions = Permission::whereIn('id', $data['permissions'])->get();
                $role->permissions()->attach($permissions);
            }

            AuditLog::logAction('created', 'Role', $role->id, null, $role->toArray());

            return $role->load('permissions');
        });
    }

    public function updateRole(int $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data) {
            $role = Role::findOrFail($id);
            $oldValues = $role->toArray();

            $role->update([
                'name' => $data['name'] ?? $role->name,
                'description' => $data['description'] ?? $role->description,
                'is_active' => $data['is_active'] ?? $role->is_active,
            ]);

            // Sincronizar permisos
            if (isset($data['permissions'])) {
                $role->permissions()->sync($data['permissions']);
            }

            AuditLog::logAction('updated', 'Role', $role->id, $oldValues, $role->fresh()->toArray());

            return $role->load('permissions');
        });
    }

    public function deleteRole(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $role = Role::findOrFail($id);

            // Verificar que no haya usuarios con este rol
            if ($role->users()->count() > 0) {
                throw new \Exception('No se puede eliminar un rol con usuarios asignados');
            }

            $oldValues = $role->toArray();
            $deleted = $role->delete();

            AuditLog::logAction('deleted', 'Role', $id, $oldValues);

            return $deleted;
        });
    }
}