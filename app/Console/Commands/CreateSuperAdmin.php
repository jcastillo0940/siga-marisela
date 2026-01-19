<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:super-admin 
                            {--email= : Email del super administrador}
                            {--password= : Contraseña del super administrador}
                            {--name= : Nombre del super administrador}';

    protected $description = 'Crear un usuario super administrador con acceso total al sistema';

    public function handle(): int
    {
        $this->info('🚀 Creando Super Administrador para Academia Auténtica');
        $this->newLine();

        // Obtener datos
        $email = $this->option('email') ?? $this->ask('Email del super administrador', 'admin@academiaautentica.com');
        $name = $this->option('name') ?? $this->ask('Nombre completo', 'Super Administrador');
        $password = $this->option('password') ?? $this->secret('Contraseña (mínimo 8 caracteres)');

        // Validar datos
        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ], [
            'email' => 'required|email',
            'name' => 'required|string|min:3',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('❌ Errores de validación:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("  - {$error}");
            }
            return self::FAILURE;
        }

        // Verificar si el usuario ya existe
        if (User::where('email', $email)->exists()) {
            $this->error("❌ Ya existe un usuario con el email: {$email}");
            
            if ($this->confirm('¿Deseas actualizar este usuario a Super Administrador?', true)) {
                return $this->updateExistingUser($email, $name, $password);
            }
            
            return self::FAILURE;
        }

        try {
            // Crear o obtener todos los permisos
            $this->info('📋 Verificando permisos...');
            $this->createPermissionsIfNotExist();

            // Crear o obtener rol de Administrador
            $this->info('🔐 Configurando rol de Administrador...');
            $adminRole = $this->createOrUpdateAdminRole();

            // Crear usuario
            $this->info('👤 Creando usuario...');
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'position' => 'Super Administrador',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Asignar rol
            $user->roles()->attach($adminRole);

            $this->newLine();
            $this->info('✅ ¡Super Administrador creado exitosamente!');
            $this->newLine();
            
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Nombre', $user->name],
                    ['Email', $user->email],
                    ['Rol', $adminRole->name],
                    ['Permisos', 'Acceso Total'],
                    ['Estado', 'Activo'],
                ]
            );

            $this->newLine();
            $this->warn('🔑 Credenciales de acceso:');
            $this->line("   Email: {$email}");
            $this->line("   Contraseña: {$password}");
            $this->newLine();
            $this->comment('💡 Guarda estas credenciales en un lugar seguro.');

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error al crear el super administrador:');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }

    private function createPermissionsIfNotExist(): void
    {
        $modules = [
            'users', 'roles', 'leads', 'students', 'courses', 
            'enrollments', 'payments', 'invoices', 'lunches', 
            'attendance', 'reports', 'dashboard'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        $permissionsCreated = 0;

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate(
                    ['module' => $module, 'action' => $action],
                    ['description' => ucfirst($action) . ' ' . $module]
                );

                if ($permission->wasRecentlyCreated) {
                    $permissionsCreated++;
                }
            }
        }

        if ($permissionsCreated > 0) {
            $this->line("   ✓ {$permissionsCreated} permisos creados");
        } else {
            $this->line("   ✓ Permisos ya existentes");
        }
    }

    private function createOrUpdateAdminRole(): Role
    {
        $role = Role::firstOrCreate(
            ['slug' => 'administrador'],
            [
                'name' => 'Administrador',
                'description' => 'Acceso total al sistema - Super Administrador',
                'is_active' => true,
            ]
        );

        // Asignar TODOS los permisos al rol
        $allPermissions = Permission::all();
        $role->permissions()->sync($allPermissions->pluck('id'));

        $this->line("   ✓ Rol configurado con {$allPermissions->count()} permisos");

        return $role;
    }

    private function updateExistingUser(string $email, string $name, string $password): int
    {
        try {
            $user = User::where('email', $email)->first();

            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
                'position' => 'Super Administrador',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->createPermissionsIfNotExist();
            $adminRole = $this->createOrUpdateAdminRole();
            
            // Sincronizar rol (reemplaza cualquier rol anterior)
            $user->roles()->sync([$adminRole->id]);

            $this->newLine();
            $this->info('✅ ¡Usuario actualizado a Super Administrador!');
            $this->newLine();
            
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Nombre', $user->name],
                    ['Email', $user->email],
                    ['Rol', $adminRole->name],
                    ['Permisos', 'Acceso Total'],
                ]
            );

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error al actualizar el usuario:');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}