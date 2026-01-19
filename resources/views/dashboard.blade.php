@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="fade-in">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Usuarios -->
        <div class="card-premium">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Usuarios</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-display font-semibold text-primary-dark">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Activos</p>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Roles -->
        <div class="card-premium">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Roles</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-display font-semibold text-primary-dark">{{ $stats['total_roles'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Configurados</p>
                </div>
            </div>
        </div>
        
        <!-- Card 3: Estudiantes (Placeholder) -->
        <div class="card-premium opacity-50">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                </div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Estudiantes</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-display font-semibold text-primary-dark">{{ $stats['total_students'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Próximamente</p>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Inscripciones (Placeholder) -->
        <div class="card-premium opacity-50">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Inscripciones</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-4xl font-display font-semibold text-primary-dark">{{ $stats['total_enrollments'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Próximamente</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Welcome Section -->
    <!-- Welcome Section -->
<div class="card-premium mb-8">
    <div class="flex items-center space-x-4">
        <div class="w-16 h-16 bg-accent-red rounded-full flex items-center justify-center text-white text-2xl font-display font-semibold">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div>
            <h2 class="text-2xl font-display font-semibold text-primary-dark mb-1">
                {{ $user->greeting }}, {{ $user->name }}! {{-- ✅ Usar el getter dinámico --}}
            </h2>
            <p class="text-gray-600">
                Rol: <span class="text-accent-red font-medium">{{ $user->roles->pluck('name')->join(', ') ?: 'Usuario' }}</span>
            </p>
        </div>
    </div>
</div>
    
    <!-- Quick Actions -->
    <div class="card-premium">
        <h3 class="text-xl font-display font-semibold text-primary-dark mb-6">Accesos Rápidos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @can('users.view')
            <a href="{{ route('users.index') }}" class="flex items-center p-4 bg-neutral-bg rounded hover:bg-gray-100 transition-colors duration-200">
                <div class="w-10 h-10 bg-accent-red rounded flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-primary-dark">Gestionar Usuarios</p>
                    <p class="text-sm text-gray-500">Ver y administrar usuarios</p>
                </div>
            </a>
            @endcan
            
            @can('roles.view')
            <a href="{{ route('roles.index') }}" class="flex items-center p-4 bg-neutral-bg rounded hover:bg-gray-100 transition-colors duration-200">
                <div class="w-10 h-10 bg-blue-600 rounded flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-primary-dark">Gestionar Roles</p>
                    <p class="text-sm text-gray-500">Configurar permisos</p>
                </div>
            </a>
            @endcan
            
            <div class="flex items-center p-4 bg-neutral-bg rounded opacity-50">
                <div class="w-10 h-10 bg-gray-400 rounded flex items-center justify-center mr-4">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-primary-dark">Estudiantes</p>
                    <p class="text-sm text-gray-500">Próximamente</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection