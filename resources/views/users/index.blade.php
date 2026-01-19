@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <p class="text-gray-600 mt-2">
                Administra los usuarios del sistema y sus roles
            </p>
        </div>
        
        <div class="flex space-x-4">
            <!-- Toggle Inactive Users -->
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center">
                <label class="flex items-center space-x-2 text-sm text-gray-600">
                    <input type="checkbox" 
                           name="include_inactive" 
                           value="1"
                           {{ $includeInactive ? 'checked' : '' }}
                           onchange="this.form.submit()"
                           class="w-4 h-4 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                    <span class="uppercase tracking-wide">Mostrar inactivos</span>
                </label>
            </form>
            
            <!-- Create Button -->
            @if(auth()->user()->hasPermission('users.create'))
            <a href="{{ route('users.create') }}" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Usuario
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Total Usuarios</p>
                    <p class="text-3xl font-display font-semibold text-primary-dark">{{ $users->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Activos</p>
                    <p class="text-3xl font-display font-semibold text-green-600">{{ $users->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wider mb-1">Inactivos</p>
                    <p class="text-3xl font-display font-semibold text-red-600">{{ $users->where('is_active', false)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card-premium">
        @if($users->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg">No hay usuarios registrados</p>
                @if(auth()->user()->hasPermission('users.create'))
                <a href="{{ route('users.create') }}" class="btn-primary mt-4 inline-block">
                    Crear Primer Usuario
                </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Género</th>
                            <th>Posición</th>
                            <th>Roles</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <!-- User Info -->
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-accent-red rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-primary-dark">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-accent-red hover:underline">
                                    {{ $user->email }}
                                </a>
                            </td>

                            <!-- Gender -->
                            <td>
                                @php
                                    $genderLabels = [
                                        'male' => 'Masculino',
                                        'female' => 'Femenino',
                                        'other' => 'Otro'
                                    ];
                                @endphp
                                <span class="text-sm text-gray-600">
                                    {{ $genderLabels[$user->gender] ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Position -->
                            <td>
                                <span class="text-sm text-gray-600">
                                    {{ $user->position ?? 'Sin asignar' }}
                                </span>
                            </td>

                            <!-- Roles -->
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-info">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">Sin roles</span>
                                    @endforelse
                                </div>
                            </td>

                            <!-- Status -->
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    @if(auth()->user()->hasPermission('users.view'))
                                    <a href="{{ route('users.show', $user->id) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Edit -->
                                    @if(auth()->user()->hasPermission('users.edit'))
                                    <a href="{{ route('users.edit', $user->id) }}" 
                                       class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Toggle Status -->
                                    @if(auth()->user()->hasPermission('users.edit') && $user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.toggle-status', $user->id) }}" class="inline" id="toggle-form-{{ $user->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                class="p-2 {{ $user->is_active ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} rounded transition-colors"
                                                title="{{ $user->is_active ? 'Desactivar' : 'Activar' }}"
                                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de {{ $user->name }}?', function() { document.getElementById('toggle-form-{{ $user->id }}').submit(); })">
                                            @if($user->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Delete -->
                                    @if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline" id="delete-form-{{ $user->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar a {{ $user->name }}? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $user->id }}').submit(); })">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection