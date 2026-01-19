@extends('layouts.app')

@section('title', 'Cursos')
@section('page-title', 'Gestión de Cursos')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra los cursos y programas de formación
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <!-- Toggle Inactive -->
            <form method="GET" action="{{ route('courses.index') }}" class="flex items-center">
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
            @if(auth()->user()->hasPermission('courses.create'))
            <a href="{{ route('courses.create') }}" class="btn-primary text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Curso
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Cursos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-primary-dark">{{ $courses->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Activos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-green-600">{{ $courses->where('is_active', true)->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Inactivos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-red-600">{{ $courses->where('is_active', false)->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Inscripciones</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-blue-600">{{ $courses->sum('enrollments_count') }}</p>
            </div>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="card-premium">
        @if($courses->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay cursos registrados</p>
                @if(auth()->user()->hasPermission('courses.create'))
                <a href="{{ route('courses.create') }}" class="btn-primary mt-4 inline-block">
                    Crear Primer Curso
                </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Categoría</th>
                            <th>Nivel</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Estudiantes</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <!-- Course Info -->
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr($course->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-primary-dark">{{ $course->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $course->code }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td>
                                @php
                                    $categoryLabels = [
                                        'cocina' => 'Cocina',
                                        'reposteria' => 'Repostería',
                                        'panaderia' => 'Panadería',
                                        'barista' => 'Barista',
                                        'otro' => 'Otro'
                                    ];
                                @endphp
                                <span class="text-sm text-gray-600">{{ $categoryLabels[$course->category] ?? 'N/A' }}</span>
                            </td>

                            <!-- Level -->
                            <td>
                                @php
                                    $levelColors = [
                                        'basico' => 'badge-info',
                                        'intermedio' => 'badge-warning',
                                        'avanzado' => 'badge-danger',
                                        'especializado' => 'badge badge-info'
                                    ];
                                    $levelLabels = [
                                        'basico' => 'Básico',
                                        'intermedio' => 'Intermedio',
                                        'avanzado' => 'Avanzado',
                                        'especializado' => 'Especializado'
                                    ];
                                @endphp
                                <span class="badge {{ $levelColors[$course->level] ?? 'badge-info' }}">
                                    {{ $levelLabels[$course->level] ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Duration -->
                            <td>
                                <span class="text-sm text-gray-600">{{ $course->duration_hours }}h</span>
                                @if($course->duration_weeks)
                                    <span class="text-xs text-gray-500 block">{{ $course->duration_weeks }} semanas</span>
                                @endif
                            </td>

                            <!-- Price -->
                            <td>
                                <span class="text-sm font-medium text-primary-dark">{{ $course->formatted_price }}</span>
                            </td>

                            <!-- Students -->
                            <td>
                                <span class="text-sm text-gray-600">{{ $course->enrollments_count }}/{{ $course->max_students }}</span>
                                @if($course->available_spots <= 5 && $course->available_spots > 0)
                                    <span class="text-xs text-orange-500 block">{{ $course->available_spots }} cupos</span>
                                @elseif($course->available_spots == 0)
                                    <span class="text-xs text-red-500 block">Lleno</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @if($course->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    @if(auth()->user()->hasPermission('courses.view'))
                                    <a href="{{ route('courses.show', $course->id) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Edit -->
                                    @if(auth()->user()->hasPermission('courses.edit'))
                                    <a href="{{ route('courses.edit', $course->id) }}" 
                                       class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Toggle Status -->
                                    @if(auth()->user()->hasPermission('courses.edit'))
                                    <form method="POST" action="{{ route('courses.toggle-status', $course->id) }}" class="inline" id="toggle-form-{{ $course->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                class="p-2 {{ $course->is_active ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} rounded transition-colors"
                                                title="{{ $course->is_active ? 'Desactivar' : 'Activar' }}"
                                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de {{ $course->name }}?', function() { document.getElementById('toggle-form-{{ $course->id }}').submit(); })">
                                            @if($course->is_active)
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
                                    @if(auth()->user()->hasPermission('courses.delete'))
                                    <form method="POST" action="{{ route('courses.destroy', $course->id) }}" class="inline" id="delete-form-{{ $course->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar {{ $course->name }}? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $course->id }}').submit(); })">
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