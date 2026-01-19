<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function getAllPermissions(): Collection
    {
        return Permission::orderBy('module')->orderBy('action')->get();
    }

    public function getPermissionsByModule(string $module): Collection
    {
        return Permission::where('module', $module)
            ->orderBy('action')
            ->get();
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create([
            'module' => $data['module'],
            'action' => $data['action'],
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
    }

    public function seedDefaultPermissions(): void
    {
        $modules = [
            'users', 'roles', 'leads', 'students', 'courses', 
            'enrollments', 'payments', 'invoices', 'lunches', 
            'attendance', 'reports'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(
                    ['module' => $module, 'action' => $action],
                    ['description' => ucfirst($action) . ' ' . $module]
                );
            }
        }
    }
}