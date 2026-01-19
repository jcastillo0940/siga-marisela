<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email}';
    protected $description = 'Resetear la contraseña de un administrador';

    public function handle(): int
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ No se encontró usuario con email: {$email}");
            return self::FAILURE;
        }

        $newPassword = $this->secret('Nueva contraseña (mínimo 8 caracteres)');
        $confirmPassword = $this->secret('Confirma la contraseña');

        if ($newPassword !== $confirmPassword) {
            $this->error('❌ Las contraseñas no coinciden');
            return self::FAILURE;
        }

        if (strlen($newPassword) < 8) {
            $this->error('❌ La contraseña debe tener al menos 8 caracteres');
            return self::FAILURE;
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        $this->newLine();
        $this->info('✅ Contraseña actualizada exitosamente');
        $this->newLine();
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Usuario', $user->name],
                ['Email', $user->email],
                ['Nueva Contraseña', $newPassword],
            ]
        );

        return self::SUCCESS;
    }
}