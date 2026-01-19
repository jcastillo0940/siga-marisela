@extends('layouts.app')

@section('title', 'Detalles del Usuario')
@section('page-title', 'Detalles del Usuario')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Usuarios
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $user->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header Actions -->
    <div class="flex justify-between items-start mb-8">
        <div class="flex items-center space-x-6">
            <div class="w-24 h-24 bg-accent-red rounded-full flex items-center justify-center text-white text-4xl font-display font-semibold shadow-elegant">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $user->name }}
                </h1>
                <div class="flex items-center space-x-4 text-gray-600">
                    <a href="mailto:{{ $user->email }}" class="flex items-center hover:text-accent-red transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $user->email }}
                    </a>
                    @if($user->phone)
                    <a href="tel:{{ $user->phone }}" class="flex items-center hover:text-accent-red transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $user->phone }}
                    </a>
                    @endif
                </div>
                <div class="flex items-center space-x-3 mt-3">
                    @if($user->is_active)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                    @if($user->position)
                        <span class="badge badge-info">{{ $user->position }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3">
            @if(auth()->user()->hasPermission('users.edit'))
            <a href="{{ route('users.edit', $user->id) }}" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif

            <a href="{{ route('users.index') }}" class="btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="card-premium">
                <h2 class="text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información Personal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Nombre Completo</label>
                        <p class="text-primary-dark font-medium">{{ $user->name }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Correo Electrónico</label>
                        <p class="text-primary-dark font-medium">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Género</label>
                        <p class="text-primary-dark font-medium">
                            @php
                                $genderLabels = [
                                    'male' => 'Masculino',
                                    'female' => 'Femenino',
                                    'other' => 'Otro'
                                ];
                            @endphp
                            {{ $genderLabels[$user->gender] ?? 'No especificado' }}
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono</label>
                        <p class="text-primary-dark font-medium">{{ $user->phone ?? 'No registrado' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Posición / Cargo</label>
                        <p class="text-primary-dark font-medium">{{ $user->position ?? 'No asignado' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Estado</label>
                        <div>
                            @if($user->is_active)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="card-premium">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-display font-semibold text-primary-dark">
                        Roles y Permisos
                    </h2>
                    <span class="text-sm text-gray-500">
                        {{ $user->roles->count() }} {{ $user->roles->count() === 1 ? 'rol' : 'roles' }} asignados
                    </span>
                </div>

                @forelse($user->roles as $role)
                    <div class="mb-6 last:mb-0">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="text-lg font-display font-semibold text-primary-dark">
                                    {{ $role->name }}
                                </h3>
                                @if($role->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                                @endif
                            </div>
                            <span class="badge badge-info">
                                {{ $role->permissions->count() }} permisos
                            </span>
                        </div>

                        <!-- Permissions grouped by module -->
                        @php
                            $groupedPermissions = $role->permissions->groupBy('module');
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($groupedPermissions as $module => $permissions)
                                <div class="bg-neutral-bg p-4 rounded">
                                    <h4 class="text-xs uppercase tracking-wider font-medium text-gray-700 mb-3">
                                        {{ ucfirst($module) }}
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($permissions as $permission)
                                            <div class="flex items-center text-sm">
                                                <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-700">{{ ucfirst($permission->action) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <p class="text-gray-500">Este usuario no tiene roles asignados</p>
                    </div>
                @endforelse
            </div>

            <!-- Activity Log -->
            <div class="card-premium">
                <h2 class="text-2xl font-display font-semibold text-primary-dark mb-6">
                    Registro de Actividad
                </h2>

                @php
                    $logs = $user->auditLogs()->latest()->take(10)->get();
                @endphp

                @if($logs->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($logs as $log)
                            <div class="flex items-start space-x-4 p-4 bg-neutral-bg rounded hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                                    @if($log->action === 'login')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                        </svg>
                                    @elseif($log->action === 'logout')
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-primary-dark">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        @if($log->model && $log->model !== 'User')
                                            en {{ $log->model }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $log->created_at->diffForHumans() }}
                                        <span class="mx-2">•</span>
                                        IP: {{ $log->ip_address }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">No hay actividad registrada</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Stats -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4">
                    Estadísticas
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Usuario ID</span>
                        <span class="font-display font-semibold text-primary-dark">#{{ $user->id }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Roles asignados</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $user->roles->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Permisos totales</span>
                        <span class="font-display font-semibold text-primary-dark">
                            {{ $user->roles->flatMap->permissions->unique('id')->count() }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Registros de actividad</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $user->auditLogs->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4">
                    Información de Cuenta
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de registro</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $user->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Última actualización</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $user->updated_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $user->updated_at->diffForHumans() }}
                        </p>
                    </div>

                    @if($user->email_verified_at)
                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Email verificado</label>
                        <div class="flex items-center mt-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-green-700 font-medium">Verificado</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $user->email_verified_at->format('d/m/Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            @if(auth()->user()->hasPermission('users.edit'))
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4">
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-2">
                    <a href="{{ route('users.edit', $user->id) }}" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Editar información</span>
                    </a>

                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('users.toggle-status', $user->id) }}" id="toggle-status-form-{{ $user->id }}">
                        @csrf
                        @method('PATCH')
                        <button type="button" 
                                class="w-full flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm"
                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de {{ $user->name }}?', function() { document.getElementById('toggle-status-form-{{ $user->id }}').submit(); })">
                            @if($user->is_active)
                                <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Desactivar usuario</span>
                            @else
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Activar usuario</span>
                            @endif
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection