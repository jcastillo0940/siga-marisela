<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $modules = [
            'users', 'roles', 'leads', 'students', 'courses', 
            'enrollments', 'payments', 'invoices', 'lunches', 
            'attendance', 'reports', 'dashboard'
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

        // Crear rol Administrador
        $adminRole = Role::firstOrCreate(
            ['slug' => 'administrador'],
            [
                'name' => 'Administrador',
                'description' => 'Acceso total al sistema - Super Administrador',
                'is_active' => true,
            ]
        );

        // Asignar todos los permisos al administrador
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@academiaautentica.com'],
            [
                'name' => 'Super Administrador',
                'gender' => 'male',
                'password' => Hash::make('Admin123!'),
                'position' => 'Super Administrador',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id => ['assigned_at' => now()]]);

        // Rol Coordinación
        $coordinacionRole = Role::firstOrCreate(
            ['slug' => 'coordinacion'],
            [
                'name' => 'Coordinación',
                'description' => 'Gestión académica y administrativa',
                'is_active' => true,
            ]
        );

        $coordinacionPerms = Permission::whereIn('module', ['students', 'courses', 'enrollments', 'attendance', 'reports'])
            ->get();
        $coordinacionRole->permissions()->sync($coordinacionPerms->pluck('id'));

        // Rol Caja
        $cajaRole = Role::firstOrCreate(
            ['slug' => 'caja'],
            [
                'name' => 'Caja',
                'description' => 'Gestión de pagos y facturación',
                'is_active' => true,
            ]
        );

        $cajaPerms = Permission::whereIn('module', ['payments', 'invoices', 'reports'])
            ->whereIn('action', ['view', 'create'])
            ->get();
        $cajaRole->permissions()->sync($cajaPerms->pluck('id'));
    }
}