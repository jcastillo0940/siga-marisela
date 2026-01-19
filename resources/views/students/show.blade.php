@extends('layouts.app')

@section('title', 'Detalles del Estudiante')
@section('page-title', 'Detalles del Estudiante')

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
                        <span class="text-gray-700 font-medium">{{ $student->full_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-accent-red rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant">
                {{ substr($student->first_name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $student->full_name }}
                </h1>
                <div class="flex flex-wrap items-center gap-3 text-gray-600">
                    <a href="mailto:{{ $student->email }}" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $student->email }}
                    </a>
                    @if($student->phone)
                    <a href="tel:{{ $student->phone }}" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $student->phone }}
                    </a>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-3">
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
                    @if(!$student->is_active)
                        <span class="badge badge-danger">Inactivo</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('students.edit'))
            <a href="{{ route('students.edit', $student->id) }}" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif

            <a href="{{ route('students.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6 lg:space-y-8">
            <!-- Información Personal -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información Personal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Nombre Completo</label>
                        <p class="text-primary-dark font-medium">{{ $student->full_name }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Correo Electrónico</label>
                        <p class="text-primary-dark font-medium">{{ $student->email }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Género</label>
                        <p class="text-primary-dark font-medium">
                            @php
                                $genderLabels = [
                                    'male' => 'Masculino',
                                    'female' => 'Femenino',
                                    'other' => 'Otro'
                                ];
                            @endphp
                            {{ $genderLabels[$student->gender] ?? 'No especificado' }}
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Fecha de Nacimiento</label>
                        <p class="text-primary-dark font-medium">
                            @if($student->birth_date)
                                {{ $student->birth_date->format('d/m/Y') }}
                                <span class="text-sm text-gray-500">({{ $student->birth_date->age }} años)</span>
                            @else
                                No registrada
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono Principal</label>
                        <p class="text-primary-dark font-medium">{{ $student->phone ?? 'No registrado' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono Secundario</label>
                        <p class="text-primary-dark font-medium">{{ $student->phone_secondary ?? 'No registrado' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Identificación</label>
                        <p class="text-primary-dark font-medium">
                            @if($student->identification)
                                {{ $student->identification }}
                                @if($student->identification_type)
                                    <span class="text-sm text-gray-500">({{ ucfirst($student->identification_type) }})</span>
                                @endif
                            @else
                                No registrada
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Ubicación</label>
                        <p class="text-primary-dark font-medium">
                            {{ $student->city ?? 'N/A' }}{{ $student->city && $student->country ? ', ' : '' }}{{ $student->country ?? '' }}
                        </p>
                    </div>

                    @if($student->address)
                    <div class="md:col-span-2">
                        <label class="label-elegant">Dirección</label>
                        <p class="text-primary-dark font-medium">{{ $student->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contacto de Emergencia -->
            @if($student->emergency_contact_name || $student->emergency_contact_phone)
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Contacto de Emergencia
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="label-elegant">Nombre</label>
                        <p class="text-primary-dark font-medium">{{ $student->emergency_contact_name ?? 'No registrado' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono</label>
                        <p class="text-primary-dark font-medium">{{ $student->emergency_contact_phone ?? 'No registrado' }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Parentesco</label>
                        <p class="text-primary-dark font-medium">{{ $student->emergency_contact_relationship ?? 'No especificado' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Información Adicional -->
            @if($student->medical_notes || $student->emotional_notes || $student->goals)
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información Adicional
                </h2>

                <div class="space-y-6">
                    @if($student->medical_notes)
                    <div>
                        <label class="label-elegant">Notas Médicas</label>
                        <div class="bg-neutral-bg p-4 rounded">
                            <p class="text-primary-dark text-sm sm:text-base">{{ $student->medical_notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($student->emotional_notes)
                    <div>
                        <label class="label-elegant">Notas Emocionales</label>
                        <div class="bg-neutral-bg p-4 rounded">
                            <p class="text-primary-dark text-sm sm:text-base">{{ $student->emotional_notes }}</p>
                        </div>
                    </div>
                    @endif

                    @if($student->goals)
                    <div>
                        <label class="label-elegant">Metas y Objetivos</label>
                        <div class="bg-neutral-bg p-4 rounded">
                            <p class="text-primary-dark text-sm sm:text-base">{{ $student->goals }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Inscripciones -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Inscripciones
                </h2>

                @if($student->enrollments->isEmpty())
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">No hay inscripciones registradas</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($student->enrollments as $enrollment)
                        <div class="border border-gray-200 rounded p-4 hover:border-accent-red transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <a href="{{ route('enrollments.show', $enrollment->id) }}" class="text-lg font-semibold text-accent-red hover:underline">
                                        {{ $enrollment->courseOffering->course->name }}
                                    </a>
                                    @if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name)
                                        <p class="text-sm text-gray-600 mt-1">{{ $enrollment->courseOffering->generation_name }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-sm">
                                        <div>
                                            <span class="text-gray-500">Código:</span>
                                            <p class="font-medium text-primary-dark font-mono">{{ $enrollment->enrollment_code }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Inscripción:</span>
                                            <p class="font-medium text-primary-dark">{{ $enrollment->enrollment_date->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Ubicación:</span>
                                            <p class="font-medium text-primary-dark">{{ $enrollment->courseOffering->location }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Total:</span>
                                            <p class="font-medium text-primary-dark">${{ number_format($enrollment->final_price, 2) }}</p>
                                        </div>
                                    </div>

                                    @if($enrollment->has_payment_plan)
                                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded p-3">
                                        <div class="grid grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Total Pagado:</span>
                                                <p class="font-medium text-green-600">${{ number_format($enrollment->paymentPlan->total_paid, 2) }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Saldo Pendiente:</span>
                                                <p class="font-medium text-red-600">${{ number_format($enrollment->paymentPlan->balance, 2) }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Estado Plan:</span>
                                                @php
                                                    $planStatusColors = [
                                                        'pendiente' => 'badge-info',
                                                        'en_proceso' => 'badge-warning',
                                                        'completado' => 'badge-success',
                                                        'vencido' => 'badge-danger'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $planStatusColors[$enrollment->paymentPlan->status] ?? 'badge-info' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $enrollment->paymentPlan->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="ml-4">
                                    @php
                                        $statusColors = [
                                            'inscrito' => 'badge-info',
                                            'en_curso' => 'badge-warning',
                                            'completado' => 'badge-success',
                                            'retirado' => 'badge-danger',
                                            'suspendido' => 'badge-danger'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$enrollment->status] ?? 'badge-info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                    </span>
                                    @if($enrollment->certificate_issued)
                                        <span class="badge badge-success block mt-2">Certificado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Stats -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Información General
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Estudiante</span>
                        <span class="font-display font-semibold text-primary-dark">#{{ $student->id }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado</span>
                        <span class="badge {{ $statusColors[$student->status] ?? 'badge-info' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Inscripciones</span>
                        <span class="font-display font-semibold text-primary-dark">{{ $student->enrollments->count() }}</span>
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
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de registro</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $student->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $student->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Última actualización</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $student->updated_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $student->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(auth()->user()->hasPermission('students.edit'))
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-2">
                    <a href="{{ route('students.edit', $student->id) }}" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Editar información</span>
                    </a>

                    <form method="POST" action="{{ route('students.toggle-status', $student->id) }}" id="toggle-status-form">
                        @csrf
                        @method('PATCH')
                        <button type="button" 
                                class="w-full flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm"
                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de {{ $student->full_name }}?', function() { document.getElementById('toggle-status-form').submit(); })">
                            @if($student->is_active)
                                <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Desactivar estudiante</span>
                            @else
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Activar estudiante</span>
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