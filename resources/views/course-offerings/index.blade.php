@extends('layouts.app')

@section('title', 'Programación de Cursos')
@section('page-title', 'Programación de Cursos')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra las fechas y ubicaciones de los cursos
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <!-- Toggle Inactive -->
            <form method="GET" action="{{ route('course-offerings.index') }}" class="flex items-center">
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
            <a href="{{ route('course-offerings.create') }}" class="btn-primary text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Programar Curso
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-primary-dark">{{ $offerings->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Programados</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-blue-600">{{ $offerings->where('status', 'programado')->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">En Curso</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-orange-600">{{ $offerings->where('status', 'en_curso')->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Completados</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-green-600">{{ $offerings->where('status', 'completado')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Offerings Table -->
    <div class="card-premium">
        @if($offerings->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay cursos programados</p>
                @if(auth()->user()->hasPermission('courses.create'))
                <a href="{{ route('course-offerings.create') }}" class="btn-primary mt-4 inline-block">
                    Programar Primer Curso
                </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Ubicación</th>
                            <th>Fechas</th>
                            <th>Precio</th>
                            <th>Estudiantes</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offerings as $offering)
                        <tr>
                            <!-- Course Info -->
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr($offering->course->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-primary-dark">{{ $offering->course->name }}</p>
                                        @if($offering->is_generation && $offering->generation_name)
                                            <p class="text-xs text-gray-500">{{ $offering->generation_name }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $offering->code }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Location -->
                            <td>
                                <span class="text-sm text-gray-600">{{ $offering->location }}</span>
                            </td>

                            <!-- Dates -->
                            <td>
                                <div class="text-sm">
                                    <p class="text-gray-600">{{ $offering->start_date->format('d/m/Y') }}</p>
                                    <p class="text-gray-500 text-xs">al {{ $offering->end_date->format('d/m/Y') }}</p>
                                    @if($offering->dates->count() > 0)
                                        <p class="text-xs text-blue-600 mt-1">{{ $offering->dates->count() }} clases</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Price -->
                            <td>
                                <span class="text-sm font-medium text-primary-dark">{{ $offering->formatted_price }}</span>
                            </td>

                            <!-- Students -->
                            <td>
                                <span class="text-sm text-gray-600">{{ $offering->enrollments_count }}/{{ $offering->max_students }}</span>
                                @if($offering->available_spots <= 5 && $offering->available_spots > 0)
                                    <span class="text-xs text-orange-500 block">{{ $offering->available_spots }} cupos</span>
                                @elseif($offering->available_spots == 0)
                                    <span class="text-xs text-red-500 block">Lleno</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
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
                                    <span class="badge badge-danger block mt-1">Inactivo</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    @if(auth()->user()->hasPermission('courses.view'))
                                    <a href="{{ route('course-offerings.show', $offering->id) }}" 
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
                                    <a href="{{ route('course-offerings.edit', $offering->id) }}" 
                                       class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Toggle Status -->
                                    @if(auth()->user()->hasPermission('courses.edit'))
                                    <form method="POST" action="{{ route('course-offerings.toggle-status', $offering->id) }}" class="inline" id="toggle-form-{{ $offering->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                class="p-2 {{ $offering->is_active ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} rounded transition-colors"
                                                title="{{ $offering->is_active ? 'Desactivar' : 'Activar' }}"
                                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de esta programación?', function() { document.getElementById('toggle-form-{{ $offering->id }}').submit(); })">
                                            @if($offering->is_active)
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
                                    <form method="POST" action="{{ route('course-offerings.destroy', $offering->id) }}" class="inline" id="delete-form-{{ $offering->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar esta programación? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $offering->id }}').submit(); })">
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