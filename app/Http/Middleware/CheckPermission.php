<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión');
        }

        $user = auth()->user();

        // Administrador tiene acceso total
        if ($user->hasRole('administrador')) {
            return $next($request);
        }

        // Verificar permiso específico
        if (!$user->hasPermission($permission)) {
            abort(403, 'No tiene permisos para realizar esta acción');
        }

        return $next($request);
    }
}