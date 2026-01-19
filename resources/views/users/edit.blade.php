{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

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
                        <a href="{{ route('users.show', $user->id) }}" class="text-gray-500 hover:text-accent-red transition-colors">
                            {{ $user->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Editar</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card-premium">
                <!-- Header with Avatar -->
                <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-100">
                    <div class="w-20 h-20 bg-accent-red rounded-full flex items-center justify-center text-white text-3xl font-display font-semibold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-display font-semibold text-primary-dark">
                            {{ $user->name }}
                        </h2>
                        <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="label-elegant">
                            Nombre Completo *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="input-elegant @error('name') border-red-500 @enderror"
                               placeholder="Ej: María García López"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="label-elegant">
                            Correo Electrónico *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="input-elegant @error('email') border-red-500 @enderror"
                               placeholder="correo@academiaautentica.com"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="mb-6">
                        <label for="gender" class="label-elegant">
                            Género *
                        </label>
                        <select id="gender" 
                                name="gender" 
                                class="input-elegant @error('gender') border-red-500 @enderror"
                                required>
                            <option value="">Seleccionar género</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="label-elegant">
                            Nueva Contraseña
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="input-elegant @error('password') border-red-500 @enderror"
                               placeholder="Dejar en blanco para mantener la actual">
                        <p class="mt-2 text-xs text-gray-500">
                            Solo completar si deseas cambiar la contraseña actual
                        </p>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="phone" class="label-elegant">
                            Teléfono
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}"
                               class="input-elegant @error('phone') border-red-500 @enderror"
                               placeholder="+507 6000-0000">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div class="mb-6">
                        <label for="position" class="label-elegant">
                            Posición / Cargo
                        </label>
                        <input type="text" 
                               id="position" 
                               name="position" 
                               value="{{ old('position', $user->position) }}"
                               class="input-elegant @error('position') border-red-500 @enderror"
                               placeholder="Ej: Coordinadora Académica">
                        @error('position')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles -->
                    <div class="mb-6">
                        <label class="label-elegant mb-3">
                            Roles
                        </label>
                        <div class="space-y-3">
                            @php
                                $userRoleIds = old('roles', $user->roles->pluck('id')->toArray());
                            @endphp
                            @forelse($roles as $role)
                                <label class="flex items-start p-4 bg-neutral-bg rounded hover:bg-gray-100 transition-colors cursor-pointer">
                                    <input type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->id }}"
                                           {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}
                                           class="w-5 h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red mt-0.5">
                                    <div class="ml-3 flex-1">
                                        <p class="font-medium text-primary-dark">{{ $role->name }}</p>
                                        @if($role->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-2">
                                            {{ $role->permissions->count() }} permisos asignados
                                        </p>
                                    </div>
                                </label>
                            @empty
                                <p class="text-sm text-gray-500 italic">No hay roles disponibles</p>
                            @endforelse
                        </div>
                        @error('roles')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-8">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                   class="w-5 h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                            <span class="label-elegant mb-0">Usuario Activo</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2 ml-8">
                            Los usuarios inactivos no podrán iniciar sesión en el sistema
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <div>
                            @if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id())
                            <button type="button" 
                                    onclick="if(confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) { document.getElementById('delete-form').submit(); }"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Usuario
                            </button>
                            @endif
                        </div>

                        <div class="flex items-center space-x-4">
                            <a href="{{ route('users.show', $user->id) }}" class="btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Delete Form (Hidden) -->
                @if(auth()->user()->hasPermission('users.delete') && $user->id !== auth()->id())
                <form id="delete-form" 
                      method="POST" 
                      action="{{ route('users.destroy', $user->id) }}"
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1">
            <!-- User Info Card -->
            <div class="card-premium mb-6">
                <h3 class="font-display font-semibold text-primary-dark mb-4">
                    Información del Usuario
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado:</span>
                        @if($user->is_active)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="text-primary-dark font-medium">#{{ $user->id }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Creado:</span>
                        <span class="text-primary-dark font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Última actualización:</span>
                        <span class="text-primary-dark font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>

                    @if($user->email_verified_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email verificado:</span>
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Current Roles Card -->
            <div class="card-premium mb-6">
                <h3 class="font-display font-semibold text-primary-dark mb-4">
                    Roles Actuales
                </h3>
                
                <div class="space-y-2">
                    @forelse($user->roles as $role)
                        <div class="flex items-center justify-between p-3 bg-neutral-bg rounded">
                            <span class="text-sm font-medium text-primary-dark">{{ $role->name }}</span>
                            <span class="text-xs text-gray-500">{{ $role->permissions->count() }} permisos</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">Sin roles asignados</p>
                    @endforelse
                </div>
            </div>

            <!-- Help Card -->
            <div class="card-premium">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-primary-dark">
                        Información
                    </h3>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600">
                    <p>
                        <strong class="text-primary-dark">Contraseña:</strong> 
                        Deja el campo vacío si no deseas cambiar la contraseña actual.
                    </p>
                    <p>
                        <strong class="text-primary-dark">Roles:</strong> 
                        Los cambios en roles afectarán los permisos del usuario inmediatamente.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection