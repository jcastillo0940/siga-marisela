@extends('layouts.app')

@section('title', 'Crear Usuario')
@section('page-title', 'Crear Nuevo Usuario')

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
                        <span class="text-gray-700 font-medium">Nuevo Usuario</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información del Usuario
                </h2>

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4 sm:mb-6">
                        <label for="name" class="label-elegant">
                            Nombre Completo *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="input-elegant @error('name') border-red-500 @enderror"
                               placeholder="Ej: María García López"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4 sm:mb-6">
                        <label for="email" class="label-elegant">
                            Correo Electrónico *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="input-elegant @error('email') border-red-500 @enderror"
                               placeholder="correo@academiaautentica.com"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="mb-4 sm:mb-6">
                        <label for="gender" class="label-elegant">
                            Género *
                        </label>
                        <select id="gender" 
                                name="gender" 
                                class="input-elegant @error('gender') border-red-500 @enderror"
                                required>
                            <option value="">Seleccionar género</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('gender')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4 sm:mb-6">
                        <label for="password" class="label-elegant">
                            Contraseña *
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="input-elegant @error('password') border-red-500 @enderror"
                               placeholder="Mínimo 8 caracteres"
                               required>
                        <p class="mt-2 text-xs text-gray-500">
                            Debe contener al menos 8 caracteres, incluyendo mayúsculas, minúsculas y números
                        </p>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-4 sm:mb-6">
                        <label for="phone" class="label-elegant">
                            Teléfono
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="input-elegant @error('phone') border-red-500 @enderror"
                               placeholder="+507 6000-0000">
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div class="mb-4 sm:mb-6">
                        <label for="position" class="label-elegant">
                            Posición / Cargo
                        </label>
                        <input type="text" 
                               id="position" 
                               name="position" 
                               value="{{ old('position') }}"
                               class="input-elegant @error('position') border-red-500 @enderror"
                               placeholder="Ej: Coordinadora Académica">
                        @error('position')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles -->
                    <div class="mb-4 sm:mb-6">
                        <label class="label-elegant mb-3">
                            Roles
                        </label>
                        <div class="space-y-2 sm:space-y-3">
                            @forelse($roles as $role)
                                <label class="flex items-start p-3 sm:p-4 bg-neutral-bg rounded hover:bg-gray-100 transition-colors cursor-pointer">
                                    <input type="checkbox" 
                                           name="roles[]" 
                                           value="{{ $role->id }}"
                                           {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red mt-0.5">
                                    <div class="ml-3 flex-1">
                                        <p class="font-medium text-primary-dark text-sm sm:text-base">{{ $role->name }}</p>
                                        @if($role->description)
                                            <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $role->description }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1 sm:mt-2">
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
                    <div class="mb-6 sm:mb-8">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                            <span class="label-elegant mb-0">Usuario Activo</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2 ml-7 sm:ml-8">
                            Los usuarios inactivos no podrán iniciar sesión en el sistema
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('users.index') }}" class="btn-secondary text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1">
            <!-- Help Card -->
            <div class="card-premium mb-4 sm:mb-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-primary-dark text-sm sm:text-base">
                        Información Importante
                    </h3>
                </div>
                
                <div class="space-y-3 text-xs sm:text-sm text-gray-600">
                    <p>
                        <strong class="text-primary-dark">Contraseña Segura:</strong> 
                        Asegúrate de crear una contraseña fuerte con al menos 8 caracteres.
                    </p>
                    <p>
                        <strong class="text-primary-dark">Roles:</strong> 
                        Los roles determinan qué acciones puede realizar el usuario en el sistema.
                    </p>
                    <p>
                        <strong class="text-primary-dark">Email Único:</strong> 
                        Cada usuario debe tener un correo electrónico único.
                    </p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Roles Disponibles
                </h3>
                <div class="space-y-2">
                    @forelse($roles as $role)
                        <div class="flex items-center justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">{{ $role->name }}</span>
                            <span class="badge badge-info">{{ $role->permissions->count() }}</span>
                        </div>
                    @empty
                        <p class="text-xs sm:text-sm text-gray-500 italic">Sin roles configurados</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection