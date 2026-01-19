<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    // ❌ ELIMINAR el constructor completamente
    
    public function index(): View
    {
        $user = auth()->user()->load('roles');
        
        // Métricas básicas (expandir en fases posteriores)
        $stats = [
            'total_users' => \App\Models\User::where('is_active', true)->count(),
            'total_roles' => \App\Models\Role::where('is_active', true)->count(),
            // Placeholder para métricas futuras
            'total_students' => 0,
            'total_enrollments' => 0,
        ];

        return view('dashboard', compact('user', 'stats'));
    }
}