@extends('layouts.app')

@section('title', 'Detalles de la Programación')
@section('page-title', 'Detalles de la Programación')

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
                        <span class="text-gray-700 font-medium">{{ $offering->full_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-indigo-600 rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant">
                {{ substr($offering->course->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $offering->course->name }}
                </h1>
                @if($offering->is_generation && $offering->generation_name)
                    <p class="text-lg text-gray-600">{{ $offering->generation_name }}</p>
                @endif
                <p class="text-gray-500 text-sm sm:text-base mt-1">{{ $offering->code }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    @php
                        $statusColors = [
                            'programado' => 'badge-info',
                            'en_curso' => 'badge-warning',
                            'completado' => 'badge-success',
                            'cancelado' => 'badge-danger'
                        ];
                        $statusLabels = [
                            'programado' => 'Programado',
                            'en_curso' => 'En Curso',
                            'completado' => 'Completado',
                            'cancelado' => 'Cancelado'
                        ];
                    @endphp
                    <span class="badge {{ $statusColors[$offering->status] ?? 'badge-info' }}">
                        {{ $statusLabels[$offering->status] ?? 'N/A' }}
                    </span>
                    @if(!$offering->is_active)
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                    @if($offering->certificate_included)
                        <span class="badge badge-info">Con Certificado</span>
                    @endif
                    @if($offering->is_generation)
                        <span class="badge badge-success">Generación</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('courses.edit'))
            <a href="{{ route('course-offerings.edit', $offering->id) }}" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif

            <a href="{{ route('course-offerings.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6 lg:space-y-8">
            <!-- Información General -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información General
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Curso Base</label>
                        <a href="{{ route('courses.show', $offering->course->id) }}" class="text-accent-red hover:underline font-medium">
                            {{ $offering->course->name }}
                        </a>
                    </div>

                    <div>
                        <label class="label-elegant">Código</label>
                        <p class="text-primary-dark font-medium">{{ $offering->code }}</p>
                    </div>

                    @if($offering->is_generation && $offering->generation_name)
                    <div>
                        <label class="label-elegant">Generación</label>
                        <p class="text-primary-dark font-medium">{{ $offering->generation_name }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="label-elegant">Ubicación</label>
                        <p class="text-primary-dark font-medium">{{ $offering->location }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Fecha de Inicio</label>
                        <p class="text-primary-dark font-medium">{{ $offering->start_date->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Fecha de Fin</label>
                        <p class="text-primary-dark font-medium">{{ $offering->end_date->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Duración</label>
                        <p class="text-primary-dark font-medium">{{ $offering->duration_hours }} horas</p>
                    </div>

                    <div>
                        <label class="label-elegant">Precio</label>
                        <p class="text-primary-dark font-medium text-lg">{{ $offering->formatted_price }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Capacidad</label>
                        <p class="text-primary-dark font-medium">
                            Mín: {{ $offering->min_students }} | Máx: {{ $offering->max_students }}
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Inscripciones</label>
                        <p class="text-primary-dark font-medium">
                            {{ $offering->current_enrollments_count }} / {{ $offering->max_students }}
                            @if($offering->available_spots > 0)
                                <span class="text-sm text-green-600">({{ $offering->available_spots }} disponibles)</span>
                            @else
                                <span class="text-sm text-red-600">(Lleno)</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($offering->notes)
                <div class="mt-6">
                    <label class="label-elegant">Notas</label>
                    <div class="bg-neutral-bg p-4 rounded mt-2">
                        <p class="text-primary-dark text-sm sm:text-base">{{ $offering->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Fechas de Clases -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Calendario de Clases
                </h2>

                @if($offering->dates->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($offering->dates as $date)
                        <div class="border border-gray-200 rounded p-4 {{ $date->is_cancelled ? 'bg-red-50' : 'bg-neutral-bg' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-medium text-primary-dark">
                                        {{ $date->class_date->format('l, d \d\e F \d\e Y') }}
                                    </p>
                                    @if($date->start_time && $date->end_time)
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $date->start_time }} - {{ $date->end_time }}
                                        </p>
                                    @endif
                                    @if($date->notes)
                                        <p class="text-sm text-gray-500 mt-2">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $date->notes }}
                                        </p>
                                    @endif
                                </div>
                                @if($date->is_cancelled)
                                    <span class="badge badge-danger ml-4">Cancelada</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">No hay fechas de clase programadas</p>
                    </div>
                @endif
            </div>

            <!-- Estudiantes Inscritos -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Estudiantes Inscritos
                </h2>

                @if($offering->enrollments->isNotEmpty())
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
                                @foreach($offering->enrollments as $enrollment)
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
                                        <span class="text-sm text-gray-600">{{ $enrollment->enrollment_date->format('d/m/Y') }}</span>
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

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Stats -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Estadísticas
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado</span>
                        <span class="badge {{ $statusColors[$offering->status] ?? 'badge-info' }}">
                            {{ $statusLabels[$offering->status] ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Duración</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $offering->duration_hours }}h</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Precio</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $offering->formatted_price }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Inscritos</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $offering->current_enrollments_count }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Cupos disponibles</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $offering->available_spots }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Clases programadas</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $offering->dates->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Registro
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de creación</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $offering->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $offering->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Última actualización</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $offering->updated_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $offering->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(auth()->user()->hasPermission('courses.edit'))
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-2">
                    <a href="{{ route('course-offerings.edit', $offering->id) }}" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Editar programación</span>
                    </a>

                    <form method="POST" action="{{ route('course-offerings.toggle-status', $offering->id) }}" id="toggle-status-form">
                        @csrf
                        @method('PATCH')
                        <button type="button" 
                                class="w-full flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm"
                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de esta programación?', function() { document.getElementById('toggle-status-form').submit(); })">
                            @if($offering->is_active)
                                <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Desactivar programación</span>
                            @else
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Activar programación</span>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection