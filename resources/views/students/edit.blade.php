@extends('layouts.app')

@section('title', 'Editar Estudiante')
@section('page-title', 'Editar Estudiante')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Estudiantes
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('students.show', $student->id) }}" class="text-gray-500 hover:text-accent-red transition-colors">
                            {{ $student->full_name }}
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

    <form method="POST" action="{{ route('students.update', $student->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header with Avatar -->
                <div class="card-premium">
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-accent-red rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold">
                            {{ substr($student->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                                {{ $student->full_name }}
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $student->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información Personal -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información Personal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="label-elegant">Nombre *</label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name', $student->first_name) }}"
                                   class="input-elegant @error('first_name') border-red-500 @enderror"
                                   required>
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="label-elegant">Apellido *</label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name', $student->last_name) }}"
                                   class="input-elegant @error('last_name') border-red-500 @enderror"
                                   required>
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="label-elegant">Correo Electrónico *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $student->email) }}"
                                   class="input-elegant @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="label-elegant">Género *</label>
                            <select id="gender" 
                                    name="gender" 
                                    class="input-elegant @error('gender') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccionar género</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Femenino</option>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('gender')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="label-elegant">Teléfono Principal</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $student->phone) }}"
                                   class="input-elegant @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Secondary -->
                        <div>
                            <label for="phone_secondary" class="label-elegant">Teléfono Secundario</label>
                            <input type="tel" 
                                   id="phone_secondary" 
                                   name="phone_secondary" 
                                   value="{{ old('phone_secondary', $student->phone_secondary) }}"
                                   class="input-elegant @error('phone_secondary') border-red-500 @enderror">
                            @error('phone_secondary')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="label-elegant">Fecha de Nacimiento</label>
                            <input type="date" 
                                   id="birth_date" 
                                   name="birth_date" 
                                   value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"
                                   class="input-elegant @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identification Type -->
                        <div>
                            <label for="identification_type" class="label-elegant">Tipo de Identificación</label>
                            <select id="identification_type" 
                                    name="identification_type" 
                                    class="input-elegant @error('identification_type') border-red-500 @enderror">
                                <option value="">Seleccionar tipo</option>
                                <option value="cedula" {{ old('identification_type', $student->identification_type) == 'cedula' ? 'selected' : '' }}>Cédula</option>
                                <option value="passport" {{ old('identification_type', $student->identification_type) == 'passport' ? 'selected' : '' }}>Pasaporte</option>
                                <option value="otro" {{ old('identification_type', $student->identification_type) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('identification_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identification -->
                        <div>
                            <label for="identification" class="label-elegant">Número de Identificación</label>
                            <input type="text" 
                                   id="identification" 
                                   name="identification" 
                                   value="{{ old('identification', $student->identification) }}"
                                   class="input-elegant @error('identification') border-red-500 @enderror">
                            @error('identification')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label for="city" class="label-elegant">Ciudad</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city', $student->city) }}"
                                   class="input-elegant @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="label-elegant">País</label>
                            <input type="text" 
                                   id="country" 
                                   name="country" 
                                   value="{{ old('country', $student->country) }}"
                                   class="input-elegant @error('country') border-red-500 @enderror">
                            @error('country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-4 sm:mt-6">
                        <label for="address" class="label-elegant">Dirección Completa</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="input-elegant @error('address') border-red-500 @enderror">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contacto de Emergencia -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Contacto de Emergencia
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Emergency Contact Name -->
                        <div>
                            <label for="emergency_contact_name" class="label-elegant">Nombre Completo</label>
                            <input type="text" 
                                   id="emergency_contact_name" 
                                   name="emergency_contact_name" 
                                   value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}"
                                   class="input-elegant @error('emergency_contact_name') border-red-500 @enderror">
                            @error('emergency_contact_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div>
                            <label for="emergency_contact_phone" class="label-elegant">Teléfono</label>
                            <input type="tel" 
                                   id="emergency_contact_phone" 
                                   name="emergency_contact_phone" 
                                   value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}"
                                   class="input-elegant @error('emergency_contact_phone') border-red-500 @enderror">
                            @error('emergency_contact_phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Emergency Contact Relationship -->
                        <div>
                            <label for="emergency_contact_relationship" class="label-elegant">Parentesco</label>
                            <input type="text" 
                                   id="emergency_contact_relationship" 
                                   name="emergency_contact_relationship" 
                                   value="{{ old('emergency_contact_relationship', $student->emergency_contact_relationship) }}"
                                   class="input-elegant @error('emergency_contact_relationship') border-red-500 @enderror">
                            @error('emergency_contact_relationship')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información Adicional
                    </h2>

                    <!-- Medical Notes -->
                    <div class="mb-4 sm:mb-6">
                        <label for="medical_notes" class="label-elegant">Notas Médicas</label>
                        <textarea id="medical_notes" 
                                  name="medical_notes" 
                                  rows="3"
                                  class="input-elegant @error('medical_notes') border-red-500 @enderror">{{ old('medical_notes', $student->medical_notes) }}</textarea>
                        @error('medical_notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emotional Notes -->
                    <div class="mb-4 sm:mb-6">
                        <label for="emotional_notes" class="label-elegant">Notas Emocionales</label>
                        <textarea id="emotional_notes" 
                                  name="emotional_notes" 
                                  rows="3"
                                  class="input-elegant @error('emotional_notes') border-red-500 @enderror">{{ old('emotional_notes', $student->emotional_notes) }}</textarea>
                        @error('emotional_notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Goals -->
                    <div>
                        <label for="goals" class="label-elegant">Metas y Objetivos</label>
                        <textarea id="goals" 
                                  name="goals" 
                                  rows="3"
                                  class="input-elegant @error('goals') border-red-500 @enderror">{{ old('goals', $student->goals) }}</textarea>
                        @error('goals')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(auth()->user()->hasPermission('students.delete'))
                            <button type="button" 
                                    onclick="showConfirmModal('¿Estás seguro de eliminar a {{ $student->full_name }}? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form').submit(); })"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Estudiante
                            </button>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('students.show', $student->id) }}" class="btn-secondary text-center">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Estado del Estudiante
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label for="status" class="label-elegant">Estado *</label>
                            <select id="status" 
                                    name="status" 
                                    class="input-elegant @error('status') border-red-500 @enderror"
                                    required>
                                <option value="prospecto" {{ old('status', $student->status) == 'prospecto' ? 'selected' : '' }}>Prospecto</option>
                                <option value="activo" {{ old('status', $student->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('status', $student->status) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="graduado" {{ old('status', $student->status) == 'graduado' ? 'selected' : '' }}>Graduado</option>
                                <option value="retirado" {{ old('status', $student->status) == 'retirado' ? 'selected' : '' }}>Retirado</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $student->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Estudiante Activo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Información
                    </h3>
                    
                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registrado:</span>
                            <span class="text-primary-dark font-medium">{{ $student->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Última actualización:</span>
                            <span class="text-primary-dark font-medium">{{ $student->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    @if(auth()->user()->hasPermission('students.delete'))
    <form id="delete-form" 
          method="POST" 
          action="{{ route('students.destroy', $student->id) }}"
          class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>
@endsection