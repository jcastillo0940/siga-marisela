@extends('layouts.app')

@section('title', 'Detalles del Curso')
@section('page-title', 'Detalles del Curso')

@section('content')
<div class="fade-in">
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
                        <span class="text-gray-700 font-medium">{{ $course->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-purple-600 rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant">
                {{ substr($course->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $course->name }}
                </h1>
                <p class="text-gray-600 text-sm sm:text-base">{{ $course->code }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
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
                        $categoryLabels = [
                            'cocina' => 'Cocina',
                            'reposteria' => 'Repostería',
                            'panaderia' => 'Panadería',
                            'barista' => 'Barista',
                            'otro' => 'Otro'
                        ];
                    @endphp
                    <span class="badge {{ $levelColors[$course->level] ?? 'badge-info' }}">
                        {{ $levelLabels[$course->level] ?? 'N/A' }}
                    </span>
                    <span class="badge badge-info">{{ $categoryLabels[$course->category] ?? 'N/A' }}</span>
                    @if($course->is_active)
                        <span class="badge badge-success">Activo</span>
                    @else
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                    @if($course->certificate_included)
                        <span class="badge badge-info">Con Certificado</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('courses.edit'))
            <a href="{{ route('courses.edit', $course->id) }}" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif

            <a href="{{ route('courses.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 space-y-6 lg:space-y-8">
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información General
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Código</label>
                        <p class="text-primary-dark font-medium">{{ $course->code }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Nombre</label>
                        <p class="text-primary-dark font-medium">{{ $course->name }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Categoría</label>
                        <p class="text-primary-dark font-medium">{{ $categoryLabels[$course->category] ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Nivel</label>
                        <p class="text-primary-dark font-medium">{{ $levelLabels[$course->level] ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Duración</label>
                        <p class="text-primary-dark font-medium">
                            {{ $course->duration_hours }} horas
                            @if($course->duration_weeks)
                                <span class="text-sm text-gray-500">({{ $course->duration_weeks }} semanas)</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Precio</label>
                        <p class="text-primary-dark font-medium text-lg">{{ $course->formatted_price }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Capacidad</label>
                        <p class="text-primary-dark font-medium">
                            Mínimo: {{ $course->min_students }} | Máximo: {{ $course->max_students }}
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Inscripciones Actuales</label>
                        <p class="text-primary-dark font-medium">
                            {{ $course->current_enrollments_count }} / {{ $course->max_students }}
                            @if($course->available_spots > 0)
                                <span class="text-sm text-green-600">({{ $course->available_spots }} disponibles)</span>
                            @else
                                <span class="text-sm text-red-600">(Lleno)</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($course->description)
                <div class="mt-6">
                    <label class="label-elegant">Descripción</label>
                    <div class="bg-neutral-bg p-4 rounded mt-2">
                        <p class="text-primary-dark text-sm sm:text-base">{{ $course->description }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Estudiantes Inscritos
                </h2>

                {{-- Corrección línea 222: Se añade Nullsafe operator --}}
                @if($course->enrollments?->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="table-elegant">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Código</th>
                                    <th>Fecha Inscripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->enrollments as $enrollment)
                                <tr>
                                    <td>
                                        <a href="{{ route('students.show', $enrollment->student->id) }}" class="text-accent-red hover:underline font-medium">
                                            {{ $enrollment->student->full_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-600">{{ $enrollment->enrollment_code }}</span>
                                    </td>
                                    <td>
                                        {{-- Corrección línea 245: Se usa enrollment_date y Nullsafe format --}}
                                        <span class="text-sm text-gray-600">{{ $enrollment->enrollment_date?->format('d/m/Y') ?? 'Sin fecha' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $enrollmentStatusColors = [
                                                'inscrito' => 'badge-info',
                                                'en_curso' => 'badge-warning',
                                                'completado' => 'badge-success',
                                                'retirado' => 'badge-danger',
                                                'suspendido' => 'badge-danger'
                                            ];
                                        @endphp
                                        <span class="badge {{ $enrollmentStatusColors[$enrollment->status] ?? 'badge-info' }}">
                                            {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="text-gray-500">No hay estudiantes inscritos</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Estadísticas
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Código</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $course->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duración</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $course->duration_hours }}h</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Inscritos</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $course->current_enrollments_count }}</span>
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Registro
                </h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de creación</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $course->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="divider-elegant"></div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Última actualización</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $course->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection