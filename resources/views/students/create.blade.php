@extends('layouts.app')

@section('title', 'Crear Estudiante')
@section('page-title', 'Crear Nuevo Estudiante')

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
                        <span class="text-gray-700 font-medium">Nuevo Estudiante</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('students.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
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
                                   value="{{ old('first_name') }}"
                                   class="input-elegant @error('first_name') border-red-500 @enderror"
                                   placeholder="Ej: María"
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
                                   value="{{ old('last_name') }}"
                                   class="input-elegant @error('last_name') border-red-500 @enderror"
                                   placeholder="Ej: García"
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
                                   value="{{ old('email') }}"
                                   class="input-elegant @error('email') border-red-500 @enderror"
                                   placeholder="correo@ejemplo.com"
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
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
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
                                   value="{{ old('phone') }}"
                                   class="input-elegant @error('phone') border-red-500 @enderror"
                                   placeholder="+507 6000-0000">
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
                                   value="{{ old('phone_secondary') }}"
                                   class="input-elegant @error('phone_secondary') border-red-500 @enderror"
                                   placeholder="+507 6000-0000">
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
                                   value="{{ old('birth_date') }}"
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
                                <option value="cedula" {{ old('identification_type') == 'cedula' ? 'selected' : '' }}>Cédula</option>
                                <option value="passport" {{ old('identification_type') == 'passport' ? 'selected' : '' }}>Pasaporte</option>
                                <option value="otro" {{ old('identification_type') == 'otro' ? 'selected' : '' }}>Otro</option>
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
                                   value="{{ old('identification') }}"
                                   class="input-elegant @error('identification') border-red-500 @enderror"
                                   placeholder="8-1234-5678">
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
                                   value="{{ old('city') }}"
                                   class="input-elegant @error('city') border-red-500 @enderror"
                                   placeholder="Ciudad de Panamá">
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
                                   value="{{ old('country', 'Panamá') }}"
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
                                  class="input-elegant @error('address') border-red-500 @enderror"
                                  placeholder="Calle, edificio, apartamento...">{{ old('address') }}</textarea>
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
                                   value="{{ old('emergency_contact_name') }}"
                                   class="input-elegant @error('emergency_contact_name') border-red-500 @enderror"
                                   placeholder="Nombre del contacto">
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
                                   value="{{ old('emergency_contact_phone') }}"
                                   class="input-elegant @error('emergency_contact_phone') border-red-500 @enderror"
                                   placeholder="+507 6000-0000">
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
                                   value="{{ old('emergency_contact_relationship') }}"
                                   class="input-elegant @error('emergency_contact_relationship') border-red-500 @enderror"
                                   placeholder="Ej: Madre, Hermano">
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
                                  class="input-elegant @error('medical_notes') border-red-500 @enderror"
                                  placeholder="Alergias, condiciones médicas, medicamentos...">{{ old('medical_notes') }}</textarea>
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
                                  class="input-elegant @error('emotional_notes') border-red-500 @enderror"
                                  placeholder="Situaciones personales, aspectos emocionales...">{{ old('emotional_notes') }}</textarea>
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
                                  class="input-elegant @error('goals') border-red-500 @enderror"
                                  placeholder="¿Qué espera lograr con el programa?">{{ old('goals') }}</textarea>
                        @error('goals')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('students.index') }}" class="btn-secondary text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Estudiante
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Status -->
                <div class="card-premium mb-4 sm:mb-6">
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
                                <option value="prospecto" {{ old('status', 'prospecto') == 'prospecto' ? 'selected' : '' }}>Prospecto</option>
                                <option value="activo" {{ old('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('status') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="graduado" {{ old('status') == 'graduado' ? 'selected' : '' }}>Graduado</option>
                                <option value="retirado" {{ old('status') == 'retirado' ? 'selected' : '' }}>Retirado</option>
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
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Estudiante Activo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="card-premium">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-display font-semibold text-primary-dark text-sm sm:text-base">
                            Información
                        </h3>
                    </div>
                    
                    <div class="space-y-3 text-xs sm:text-sm text-gray-600">
                        <p>
                            <strong class="text-primary-dark">Campos requeridos:</strong> 
                            Solo nombre, apellido, email y género son obligatorios.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Notas confidenciales:</strong> 
                            La información médica y emocional es privada.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection