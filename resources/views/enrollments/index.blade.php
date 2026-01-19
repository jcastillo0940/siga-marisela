@extends('layouts.app')

@section('title', 'Inscripciones')
@section('page-title', 'Gestión de Inscripciones')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra las inscripciones de estudiantes a cursos
            </p>
        </div>
        
        <div class="flex space-x-4 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('enrollments.create'))
            <a href="{{ route('enrollments.create') }}" class="btn-primary text-center flex-1 sm:flex-none">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Inscripción
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-primary-dark">{{ $enrollments->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Inscritos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-blue-600">{{ $enrollments->where('status', 'inscrito')->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">En Curso</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-orange-600">{{ $enrollments->where('status', 'en_curso')->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Completados</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-green-600">{{ $enrollments->where('status', 'completado')->count() }}</p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Certificados</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-purple-600">{{ $enrollments->where('certificate_issued', true)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Enrollments Table -->
    <div class="card-premium">
        @if($enrollments->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay inscripciones registradas</p>
                @if(auth()->user()->hasPermission('enrollments.create'))
                <a href="{{ route('enrollments.create') }}" class="btn-primary mt-4 inline-block">
                    Crear Primera Inscripción
                </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Código</th>
                            <th>Fecha Inscripción</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                        <tr>
                            <!-- Student -->
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr($enrollment->student->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('students.show', $enrollment->student->id) }}" class="font-medium text-accent-red hover:underline">
                                            {{ $enrollment->student->full_name }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $enrollment->student->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Course -->
                            <td>
                                <div>
                                    <p class="font-medium text-primary-dark">{{ $enrollment->courseOffering->course->name }}</p>
                                    @if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name)
                                        <p class="text-xs text-gray-500">{{ $enrollment->courseOffering->generation_name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $enrollment->courseOffering->location }}</p>
                                </div>
                            </td>

                            <!-- Code -->
                            <td>
                                <span class="text-sm text-gray-600 font-mono">{{ $enrollment->enrollment_code }}</span>
                            </td>

                            <!-- Enrollment Date -->
                            <td>
                                <span class="text-sm text-gray-600">{{ $enrollment->enrollment_date->format('d/m/Y') }}</span>
                            </td>

                            <!-- Price -->
                            <td>
                                <div>
                                    <p class="text-sm font-medium text-primary-dark">${{ number_format($enrollment->final_price, 2) }}</p>
                                    @if($enrollment->discount > 0)
                                        <p class="text-xs text-green-600">-${{ number_format($enrollment->discount, 2) }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td>
                                @php
                                    $statusColors = [
                                        'inscrito' => 'badge-info',
                                        'en_curso' => 'badge-warning',
                                        'completado' => 'badge-success',
                                        'retirado' => 'badge-danger',
                                        'suspendido' => 'badge-danger'
                                    ];
                                @endphp
                                <div class="space-y-1">
                                    <span class="badge {{ $statusColors[$enrollment->status] ?? 'badge-info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                    </span>
                                    @if($enrollment->certificate_issued)
                                        <span class="badge badge-success block">Certificado</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    @if(auth()->user()->hasPermission('enrollments.view'))
                                    <a href="{{ route('enrollments.show', $enrollment->id) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Edit -->
                                    @if(auth()->user()->hasPermission('enrollments.edit'))
                                    <a href="{{ route('enrollments.edit', $enrollment->id) }}" 
                                       class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Issue Certificate -->
                                    @if(auth()->user()->hasPermission('enrollments.edit') && !$enrollment->certificate_issued && $enrollment->status === 'completado' && $enrollment->courseOffering->certificate_included)
                                    <form method="POST" action="{{ route('enrollments.issue-certificate', $enrollment->id) }}" class="inline" id="cert-form-{{ $enrollment->id }}">
                                        @csrf
                                        <button type="button" 
                                                class="p-2 text-purple-600 hover:bg-purple-50 rounded transition-colors"
                                                title="Emitir Certificado"
                                                onclick="showConfirmModal('¿Emitir certificado para {{ $enrollment->student->full_name }}?', function() { document.getElementById('cert-form-{{ $enrollment->id }}').submit(); })">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Delete -->
                                    @if(auth()->user()->hasPermission('enrollments.delete'))
                                    <form method="POST" action="{{ route('enrollments.destroy', $enrollment->id) }}" class="inline" id="delete-form-{{ $enrollment->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar esta inscripción? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-{{ $enrollment->id }}').submit(); })">
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