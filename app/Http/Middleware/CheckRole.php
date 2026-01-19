<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión');
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403, 'No tiene el rol necesario para acceder');
        }

        return $next($request);
    }
}