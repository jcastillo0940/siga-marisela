<?php

namespace App\Services;

use App\DTOs\Auth\LoginDTO;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    public function login(LoginDTO $dto): array
    {
        $user = User::where('email', $dto->email)
            ->where('is_active', true)
            ->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            AuditLog::create([
                'user_id' => $user?->id,
                'action' => 'login_failed',
                'model' => 'User',
                'model_id' => $user?->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            throw new UnauthorizedException('Credenciales inválidas');
        }

        Auth::login($user, $dto->remember);

        // Log exitoso
        AuditLog::logAction('login', 'User', $user->id);

        // Generar token para API (si usas Sanctum)
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user->load('roles.permissions'),
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        $user = Auth::user();
        
        if ($user) {
            AuditLog::logAction('logout', 'User', $user->id);
            
            // Revocar todos los tokens
            $user->tokens()->delete();
        }

        Auth::logout();
    }

    public function validateSession(): bool
    {
        return Auth::check() && Auth::user()->is_active;
    }
}