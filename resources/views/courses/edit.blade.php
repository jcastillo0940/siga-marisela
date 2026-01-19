@extends('layouts.app')

@section('title', 'Editar Curso')
@section('page-title', 'Editar Curso')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('courses.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Cursos
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('courses.show', $course->id) }}" class="text-gray-500 hover:text-accent-red transition-colors">
                            {{ $course->name }}
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

    <form method="POST" action="{{ route('courses.update', $course->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header with Icon -->
                <div class="card-premium">
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-purple-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold">
                            {{ substr($course->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                                {{ $course->name }}
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $course->code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información Básica -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información Básica
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Code -->
                        <div>
                            <label for="code" class="label-elegant">Código del Curso *</label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $course->code) }}"
                                   class="input-elegant @error('code') border-red-500 @enderror"
                                   required>
                            @error('code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="label-elegant">Nombre del Curso *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $course->name) }}"
                                   class="input-elegant @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="label-elegant">Categoría *</label>
                            <select id="category" 
                                    name="category" 
                                    class="input-elegant @error('category') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccionar categoría</option>
                                <option value="cocina" {{ old('category', $course->category) == 'cocina' ? 'selected' : '' }}>Cocina</option>
                                <option value="reposteria" {{ old('category', $course->category) == 'reposteria' ? 'selected' : '' }}>Repostería</option>
                                <option value="panaderia" {{ old('category', $course->category) == 'panaderia' ? 'selected' : '' }}>Panadería</option>
                                <option value="barista" {{ old('category', $course->category) == 'barista' ? 'selected' : '' }}>Barista</option>
                                <option value="otro" {{ old('category', $course->category) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level -->
                        <div>
                            <label for="level" class="label-elegant">Nivel *</label>
                            <select id="level" 
                                    name="level" 
                                    class="input-elegant @error('level') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccionar nivel</option>
                                <option value="basico" {{ old('level', $course->level) == 'basico' ? 'selected' : '' }}>Básico</option>
                                <option value="intermedio" {{ old('level', $course->level) == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="avanzado" {{ old('level', $course->level) == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                <option value="especializado" {{ old('level', $course->level) == 'especializado' ? 'selected' : '' }}>Especializado</option>
                            </select>
                            @error('level')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration Hours -->
                        <div>
                            <label for="duration_hours" class="label-elegant">Duración (Horas) *</label>
                            <input type="number" 
                                   id="duration_hours" 
                                   name="duration_hours" 
                                   value="{{ old('duration_hours', $course->duration_hours) }}"
                                   class="input-elegant @error('duration_hours') border-red-500 @enderror"
                                   min="1"
                                   required>
                            @error('duration_hours')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration Weeks -->
                        <div>
                            <label for="duration_weeks" class="label-elegant">Duración (Semanas)</label>
                            <input type="number" 
                                   id="duration_weeks" 
                                   name="duration_weeks" 
                                   value="{{ old('duration_weeks', $course->duration_weeks) }}"
                                   class="input-elegant @error('duration_weeks') border-red-500 @enderror"
                                   min="1">
                            @error('duration_weeks')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="label-elegant">Precio (USD) *</label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $course->price) }}"
                                   class="input-elegant @error('price') border-red-500 @enderror"
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Students -->
                        <div>
                            <label for="max_students" class="label-elegant">Máximo de Estudiantes *</label>
                            <input type="number" 
                                   id="max_students" 
                                   name="max_students" 
                                   value="{{ old('max_students', $course->max_students) }}"
                                   class="input-elegant @error('max_students') border-red-500 @enderror"
                                   min="1"
                                   required>
                            @error('max_students')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Min Students -->
                        <div>
                            <label for="min_students" class="label-elegant">Mínimo de Estudiantes *</label>
                            <input type="number" 
                                   id="min_students" 
                                   name="min_students" 
                                   value="{{ old('min_students', $course->min_students) }}"
                                   class="input-elegant @error('min_students') border-red-500 @enderror"
                                   min="1"
                                   required>
                            @error('min_students')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-4 sm:mt-6">
                        <label for="description" class="label-elegant">Descripción</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="input-elegant @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Detalles del Curso -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Detalles del Curso
                    </h2>

                    <!-- Requirements -->
                    <div class="mb-4 sm:mb-6">
                        <label for="requirements" class="label-elegant">Requisitos</label>
                        <textarea id="requirements" 
                                  name="requirements" 
                                  rows="3"
                                  class="input-elegant @error('requirements') border-red-500 @enderror">{{ old('requirements', $course->requirements) }}</textarea>
                        @error('requirements')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Objectives -->
                    <div class="mb-4 sm:mb-6">
                        <label for="objectives" class="label-elegant">Objetivos</label>
                        <textarea id="objectives" 
                                  name="objectives" 
                                  rows="4"
                                  class="input-elegant @error('objectives') border-red-500 @enderror">{{ old('objectives', $course->objectives) }}</textarea>
                        @error('objectives')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content Outline -->
                    <div class="mb-4 sm:mb-6">
                        <label for="content_outline" class="label-elegant">Contenido del Curso</label>
                        <textarea id="content_outline" 
                                  name="content_outline" 
                                  rows="5"
                                  class="input-elegant @error('content_outline') border-red-500 @enderror">{{ old('content_outline', $course->content_outline) }}</textarea>
                        @error('content_outline')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Materials Included -->
                    <div>
                        <label for="materials_included" class="label-elegant">Materiales Incluidos</label>
                        <textarea id="materials_included" 
                                  name="materials_included" 
                                  rows="3"
                                  class="input-elegant @error('materials_included') border-red-500 @enderror">{{ old('materials_included', $course->materials_included) }}</textarea>
                        @error('materials_included')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(auth()->user()->hasPermission('courses.delete'))
                            <button type="button" 
                                    onclick="showConfirmModal('¿Estás seguro de eliminar {{ $course->name }}? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form').submit(); })"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Curso
                            </button>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('courses.show', $course->id) }}" class="btn-secondary text-center">
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
                <!-- Settings -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Configuración
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="certificate_included" 
                                       value="1"
                                       {{ old('certificate_included', $course->certificate_included) ? 'checked' : '' }}
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Incluye Certificado</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $course->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Curso Activo</span>
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
                            <span class="text-gray-600">Inscripciones:</span>
                            <span class="text-primary-dark font-medium">{{ $course->enrollments->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cupos disponibles:</span>
                            <span class="text-primary-dark font-medium">{{ $course->available_spots }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Creado:</span>
                            <span class="text-primary-dark font-medium">{{ $course->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Última actualización:</span>
                            <span class="text-primary-dark font-medium">{{ $course->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    @if(auth()->user()->hasPermission('courses.delete'))
    <form id="delete-form" 
          method="POST" 
          action="{{ route('courses.destroy', $course->id) }}"
          class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>
@endsection