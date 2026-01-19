@extends('layouts.app')

@section('title', 'Estudiantes')
@section('page-title', 'Gestión de Estudiantes')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra los estudiantes de la academia
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <!-- Toggle Inactive -->
            <form method="GET" action="{{ route('students.index') }}" class="flex items-center">
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
            @if(auth()->user()->hasPermission('students.create'))
            <a href="{{ route('students.create') }}" class="btn-primary text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Estudiante
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-wider mb-1">Total</p>
                    <p class="text-2xl sm:text-3xl font-display font-semibold text-primary-dark">{{ $students->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-wider mb-1">Activos</p>
                    <p class="text-2xl sm:text-3xl font-display font-semibold text-green-600">{{ $students->where('status', 'activo')->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-wider mb-1">Prospectos</p>
                    <p class="text-2xl sm:text-3xl font-display font-semibold text-yellow-600">{{ $students->where('status', 'prospecto')->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 uppercase tracking-wider mb-1">Graduados</p>
                    <p class="text-2xl sm:text-3xl font-display font-semibold text-purple-600">{{ $students->where('status', 'graduado')->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card-premium">
        @if($students->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay estudiantes registrados</p>
                @if(auth()->user()->hasPermission('students.create'))
                <a href="{{ route('students.create') }}" class="btn-primary mt-4 inline-block">
                    Crear Primer Estudiante
                </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Contacto</th>
                            <th>Identificación</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <!-- Student Info -->
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-accent-red rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr($student->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-primary-dark">{{ $student->full_name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $student->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td>
                                <a href="mailto:{{ $student->email }}" class="text-accent-red hover:underline block text-sm">
                                    {{ $student->email }}
                                </a>
                                @if($student->phone)
                                <a href="tel:{{ $student->phone }}" class="text-gray-600 hover:text-accent-red block text-xs mt-1">
                                    {{ $student->phone }}
                                </a>
                                @endif
                            </td>

                            <!-- Identification -->
                            <td>
                                @if($student->identification)
                                    <span class="text-sm text-gray-600">{{ $student->identification }}</span>
                                    @if($student->identification_type)
                                        <span class="text-xs text-gray-500 block">{{ ucfirst($student->identification_type) }}</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">Sin registrar</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @php
                                    $statusColors = [
                                        'prospecto' => 'badge-warning',
                                        'activo' => 'badge-success',
                                        'inactivo' => 'badge-danger',
                                        'graduado' => 'badge-info',
                                        'retirado' => 'badge-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$student->status] ?? 'badge-info' }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    @if(auth()->user()->hasPermission('students.view'))
                                    <a href="{{ route('students.show', $student->id) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Edit -->
                                    @if(auth()->user()->hasPermission('students.edit'))
                                    <a href="{{ route('students.edit', $student->id) }}" 
                                       class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Toggle Status -->
                                    @if(auth()->user()->hasPermission('students.edit'))
                                    <form method="POST" action="{{ route('students.toggle-status', $student->id) }}" class="inline" id="toggle-form-{{ $student->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" 
                                                class="p-2 {{ $student->is_active ? 'text-orange-600 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50' }} rounded transition-colors"
                                                title="{{ $student->is_active ? 'Desactivar' : 'Activar' }}"
                                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de {{ $student->full_name }}?', function() { document.getElementById('toggle-form-{{ $student->id }}').submit(); })">
                                            @if($student->is_active)
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
                                    @if(auth()->user()->hasPermission('students.delete'))
                                    <form method="POST" action="{{ route('students.destroy', $student->id) }}" class="inline" id="delete-form-{{ $student->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar a {{ $student->full_name }}? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $student->id }}').submit(); })">
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