@extends('layouts.app')

@section('title', 'Crear Curso')
@section('page-title', 'Crear Nuevo Curso')

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
                        <span class="text-gray-700 font-medium">Nuevo Curso</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('courses.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
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
                                   value="{{ old('code') }}"
                                   class="input-elegant @error('code') border-red-500 @enderror"
                                   placeholder="Ej: COC-BAS-001"
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
                                   value="{{ old('name') }}"
                                   class="input-elegant @error('name') border-red-500 @enderror"
                                   placeholder="Ej: Cocina Básica Internacional"
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
                                <option value="cocina" {{ old('category') == 'cocina' ? 'selected' : '' }}>Cocina</option>
                                <option value="reposteria" {{ old('category') == 'reposteria' ? 'selected' : '' }}>Repostería</option>
                                <option value="panaderia" {{ old('category') == 'panaderia' ? 'selected' : '' }}>Panadería</option>
                                <option value="barista" {{ old('category') == 'barista' ? 'selected' : '' }}>Barista</option>
                                <option value="otro" {{ old('category') == 'otro' ? 'selected' : '' }}>Otro</option>
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
                                <option value="basico" {{ old('level') == 'basico' ? 'selected' : '' }}>Básico</option>
                                <option value="intermedio" {{ old('level') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="avanzado" {{ old('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                                <option value="especializado" {{ old('level') == 'especializado' ? 'selected' : '' }}>Especializado</option>
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
                                   value="{{ old('duration_hours') }}"
                                   class="input-elegant @error('duration_hours') border-red-500 @enderror"
                                   placeholder="Ej: 40"
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
                                   value="{{ old('duration_weeks') }}"
                                   class="input-elegant @error('duration_weeks') border-red-500 @enderror"
                                   placeholder="Ej: 8"
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
                                   value="{{ old('price') }}"
                                   class="input-elegant @error('price') border-red-500 @enderror"
                                   placeholder="Ej: 350.00"
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
                                   value="{{ old('max_students', 20) }}"
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
                                   value="{{ old('min_students', 5) }}"
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
                                  class="input-elegant @error('description') border-red-500 @enderror"
                                  placeholder="Descripción general del curso...">{{ old('description') }}</textarea>
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
                                  class="input-elegant @error('requirements') border-red-500 @enderror"
                                  placeholder="Requisitos previos para tomar el curso...">{{ old('requirements') }}</textarea>
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
                                  class="input-elegant @error('objectives') border-red-500 @enderror"
                                  placeholder="Objetivos de aprendizaje del curso...">{{ old('objectives') }}</textarea>
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
                                  class="input-elegant @error('content_outline') border-red-500 @enderror"
                                  placeholder="Temario y contenidos que se verán...">{{ old('content_outline') }}</textarea>
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
                                  class="input-elegant @error('materials_included') border-red-500 @enderror"
                                  placeholder="Materiales, herramientas o recursos incluidos...">{{ old('materials_included') }}</textarea>
                        @error('materials_included')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('courses.index') }}" class="btn-secondary text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Curso
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Settings -->
                <div class="card-premium mb-4 sm:mb-6">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Configuración
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="certificate_included" 
                                       value="1"
                                       {{ old('certificate_included', true) ? 'checked' : '' }}
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Incluye Certificado</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Curso Activo</span>
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
                            <strong class="text-primary-dark">Código único:</strong> 
                            El código debe ser único para cada curso.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Capacidad:</strong> 
                            Define el mínimo y máximo de estudiantes.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Certificado:</strong> 
                            Indica si el curso incluye certificación.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection