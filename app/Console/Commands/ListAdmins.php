<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListAdmins extends Command
{
    protected $signature = 'admin:list';
    protected $description = 'Listar todos los usuarios con rol de Administrador';

    public function handle(): int
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('slug', 'administrador');
        })->with('roles')->get();

        if ($admins->isEmpty()) {
            $this->warn('⚠️  No hay administradores en el sistema.');
            $this->info('💡 Usa: php artisan make:super-admin');
            return self::SUCCESS;
        }

        $this->info('👥 Administradores del Sistema:');
        $this->newLine();

        $data = $admins->map(function ($admin) {
            return [
                $admin->id,
                $admin->name,
                $admin->email,
                $admin->is_active ? '✅ Activo' : '❌ Inactivo',
                $admin->created_at->diffForHumans(),
            ];
        });

        $this->table(
            ['ID', 'Nombre', 'Email', 'Estado', 'Creado'],
            $data
        );

        return self::SUCCESS;
    }
}