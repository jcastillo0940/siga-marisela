@extends('layouts.app')

@section('title', 'Programar Curso')
@section('page-title', 'Programar Nuevo Curso')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('course-offerings.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Programación
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Nueva Programación</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('course-offerings.store') }}" id="offering-form">
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
                        <!-- Course -->
                        <div class="md:col-span-2">
                            <label for="course_id" class="label-elegant">Curso *</label>
                            <select id="course_id" 
                                    name="course_id" 
                                    class="input-elegant @error('course_id') border-red-500 @enderror"
                                    required
                                    onchange="loadCourseDefaults()">
                                <option value="">Seleccionar curso</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" 
                                            data-price="{{ $course->price }}"
                                            data-duration="{{ $course->duration_hours }}"
                                            data-max="{{ $course->max_students }}"
                                            data-min="{{ $course->min_students }}"
                                            data-certificate="{{ $course->certificate_included ? '1' : '0' }}"
                                            {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} ({{ $course->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Generation -->
                        <div class="md:col-span-2">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_generation" 
                                       id="is_generation"
                                       value="1"
                                       {{ old('is_generation') ? 'checked' : '' }}
                                       onchange="toggleGenerationName()"
                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Este curso se imparte por Generaciones</span>
                            </label>
                        </div>

                        <!-- Generation Name -->
                        <div class="md:col-span-2" id="generation-name-container" style="display: none;">
                            <label for="generation_name" class="label-elegant">Nombre de la Generación</label>
                            <input type="text" 
                                   id="generation_name" 
                                   name="generation_name" 
                                   value="{{ old('generation_name') }}"
                                   class="input-elegant @error('generation_name') border-red-500 @enderror"
                                   placeholder="Ej: Generación 19">
                            @error('generation_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <label for="location" class="label-elegant">Ubicación *</label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location') }}"
                                   class="input-elegant @error('location') border-red-500 @enderror"
                                   placeholder="Ej: Academia Auténtica - Sede Principal"
                                   required>
                            @error('location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="label-elegant">Fecha de Inicio *</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}"
                                   class="input-elegant @error('start_date') border-red-500 @enderror"
                                   required>
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="label-elegant">Fecha de Fin *</label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}"
                                   class="input-elegant @error('end_date') border-red-500 @enderror"
                                   required>
                            @error('end_date')
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
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('price')
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
                                   min="1"
                                   required>
                            @error('duration_hours')
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
                    </div>

                    <!-- Notes -->
                    <div class="mt-4 sm:mt-6">
                        <label for="notes" class="label-elegant">Notas</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  class="input-elegant @error('notes') border-red-500 @enderror"
                                  placeholder="Notas adicionales sobre esta programación...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fechas de Clases -->
                <div class="card-premium">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                            Fechas de Clases
                        </h2>
                        <button type="button" 
                                onclick="addClassDate()" 
                                class="btn-secondary text-sm">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Fecha
                        </button>
                    </div>

                    <div id="class-dates-container" class="space-y-4">
                        <!-- Las fechas se agregarán aquí dinámicamente -->
                    </div>

                    <p class="text-sm text-gray-500 mt-4">
                        * Agregue las fechas específicas en las que se impartirán las clases
                    </p>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('course-offerings.index') }}" class="btn-secondary text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Programar Curso
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
                            <label for="status" class="label-elegant">Estado</label>
                            <select id="status" 
                                    name="status" 
                                    class="input-elegant @error('status') border-red-500 @enderror">
                                <option value="programado" {{ old('status', 'programado') == 'programado' ? 'selected' : '' }}>Programado</option>
                                <option value="en_curso" {{ old('status') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="completado" {{ old('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="certificate_included" 
                                       id="certificate_included"
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
                                <span class="label-elegant mb-0">Activo</span>
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
                            <strong class="text-primary-dark">Generaciones:</strong> 
                            Activa esta opción para cursos que se imparten en grupos programados.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Fechas:</strong> 
                            Especifica cada fecha de clase. Puedes saltar fechas festivas.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Ubicación:</strong> 
                            Define dónde se impartirá el curso.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let classDateIndex = 0;

// Toggle generation name
function toggleGenerationName() {
    const isChecked = document.getElementById('is_generation').checked;
    document.getElementById('generation-name-container').style.display = isChecked ? 'block' : 'none';
}

// Load course defaults
function loadCourseDefaults() {
    const select = document.getElementById('course_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('price').value = option.dataset.price || '';
        document.getElementById('duration_hours').value = option.dataset.duration || '';
        document.getElementById('max_students').value = option.dataset.max || 20;
        document.getElementById('min_students').value = option.dataset.min || 5;
        document.getElementById('certificate_included').checked = option.dataset.certificate === '1';
    }
}

// Add class date
function addClassDate() {
    const container = document.getElementById('class-dates-container');
    const dateDiv = document.createElement('div');
    dateDiv.className = 'border border-gray-200 rounded p-4 bg-neutral-bg';
    dateDiv.id = `class-date-${classDateIndex}`;
    
    dateDiv.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-primary-dark">Clase #${classDateIndex + 1}</h4>
            <button type="button" 
                    onclick="removeClassDate(${classDateIndex})" 
                    class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="label-elegant">Fecha *</label>
                <input type="date" 
                       name="class_dates[${classDateIndex}][date]" 
                       class="input-elegant" 
                       required>
            </div>
            <div>
                <label class="label-elegant">Hora Inicio</label>
                <input type="time" 
                       name="class_dates[${classDateIndex}][start_time]" 
                       class="input-elegant">
            </div>
            <div>
                <label class="label-elegant">Hora Fin</label>
                <input type="time" 
                       name="class_dates[${classDateIndex}][end_time]" 
                       class="input-elegant">
            </div>
        </div>
        
        <div class="mt-3">
            <label class="label-elegant">Notas</label>
            <input type="text" 
                   name="class_dates[${classDateIndex}][notes]" 
                   class="input-elegant" 
                   placeholder="Ej: Salta por Carnaval">
        </div>
    `;
    
    container.appendChild(dateDiv);
    classDateIndex++;
}

// Remove class date
function removeClassDate(index) {
    const element = document.getElementById(`class-date-${index}`);
    if (element) {
        element.remove();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Check if generation checkbox should be checked
    if (document.getElementById('is_generation').checked) {
        toggleGenerationName();
    }
    
    // Add one initial date if none exist
    if (document.getElementById('class-dates-container').children.length === 0) {
        addClassDate();
    }
});
</script>
@endpush
@endsection