@extends('layouts.app')

@section('title', 'Detalles de la Inscripción')
@section('page-title', 'Detalles de la Inscripción')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('enrollments.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Inscripciones
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $enrollment->enrollment_code }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-green-600 rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant">
                {{ substr($enrollment->student->first_name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $enrollment->student->full_name }}
                </h1>
                <p class="text-gray-600 text-sm sm:text-base">{{ $enrollment->enrollment_code }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
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
                        <span class="badge badge-success">Certificado Emitido</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('enrollments.edit'))
            <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif

            <a href="{{ route('enrollments.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6 lg:space-y-8">
            <!-- Información del Estudiante -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información del Estudiante
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Nombre Completo</label>
                        <a href="{{ route('students.show', $enrollment->student->id) }}" class="text-accent-red hover:underline font-medium">
                            {{ $enrollment->student->full_name }}
                        </a>
                    </div>

                    <div>
                        <label class="label-elegant">Identificación</label>
                        <p class="text-primary-dark font-medium">{{ $enrollment->student->identification }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Correo Electrónico</label>
                        <a href="mailto:{{ $enrollment->student->email }}" class="text-primary-dark font-medium hover:text-accent-red">
                            {{ $enrollment->student->email }}
                        </a>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono</label>
                        <a href="tel:{{ $enrollment->student->phone }}" class="text-primary-dark font-medium hover:text-accent-red">
                            {{ $enrollment->student->phone }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del Curso -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información del Curso
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="label-elegant">Curso</label>
                        <a href="{{ route('course-offerings.show', $enrollment->courseOffering->id) }}" class="text-accent-red hover:underline font-medium text-lg">
                            {{ $enrollment->courseOffering->course->name }}
                        </a>
                        @if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name)
                            <p class="text-gray-600 mt-1">{{ $enrollment->courseOffering->generation_name }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="label-elegant">Ubicación</label>
                        <p class="text-primary-dark font-medium">{{ $enrollment->courseOffering->location }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Código de Programación</label>
                        <p class="text-primary-dark font-medium font-mono">{{ $enrollment->courseOffering->code }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Fecha de Inicio</label>
                        <p class="text-primary-dark font-medium">{{ $enrollment->courseOffering->start_date->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Fecha de Fin</label>
                        <p class="text-primary-dark font-medium">{{ $enrollment->courseOffering->end_date->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Duración</label>
                        <p class="text-primary-dark font-medium">{{ $enrollment->courseOffering->duration_hours }} horas</p>
                    </div>
                </div>
            </div>

            <!-- Información Financiera -->
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información Financiera
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Precio del Curso</label>
                        <p class="text-primary-dark font-medium text-lg">${{ number_format($enrollment->price_paid, 2) }}</p>
                    </div>

                    <div>
                        <label class="label-elegant">Descuento Aplicado</label>
                        <p class="text-primary-dark font-medium text-lg {{ $enrollment->discount > 0 ? 'text-green-600' : '' }}">
                            @if($enrollment->discount > 0)
                                -${{ number_format($enrollment->discount, 2) }}
                            @else
                                $0.00
                            @endif
                        </p>
                    </div>

                    <div class="md:col-span-2">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-green-50 border border-green-200 rounded p-4">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-green-800">Abonado Real:</span>
                <span class="text-3xl font-display font-bold text-green-700">
                    ${{ number_format($enrollment->payments->sum('amount'), 2) }}
                </span>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded p-4">
            <div class="flex flex-col">
                <span class="text-sm font-medium text-red-800">Saldo Pendiente:</span>
                <span class="text-3xl font-display font-bold text-red-700">
                    ${{ number_format($enrollment->paymentPlan ? $enrollment->paymentPlan->balance : 0, 2) }}
                </span>
            </div>
        </div>
    </div>
    <p class="text-right text-xs text-gray-500 mt-2">
        Costo Total del Curso: ${{ number_format($enrollment->final_price, 2) }}
    </p>
</div>
                    <div>
                        <label class="label-elegant">Fecha de Inscripción</label>
                        <p class="text-primary-dark font-medium">{{ $enrollment->enrollment_date->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Calendario de Clases -->
            @if($enrollment->courseOffering->dates->isNotEmpty())
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Calendario de Clases
                </h2>

                <div class="space-y-3">
                    @foreach($enrollment->courseOffering->dates as $date)
                    <div class="border border-gray-200 rounded p-4 bg-neutral-bg">
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
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Notas -->
            @if($enrollment->notes)
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Notas
                </h2>

                <div class="bg-neutral-bg p-4 rounded">
                    <p class="text-primary-dark text-sm sm:text-base">{{ $enrollment->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Certificate -->
            @if($enrollment->certificate_issued)
            <div class="card-premium bg-green-50 border border-green-200">
                <div class="flex items-start space-x-3">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-display font-semibold text-green-800 text-lg mb-2">
                            Certificado Emitido
                        </h3>
                        <p class="text-sm text-green-700">
                            Emitido el {{ $enrollment->certificate_issued_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            @elseif($enrollment->status === 'completado' && $enrollment->courseOffering->certificate_included && auth()->user()->hasPermission('enrollments.edit'))
            <div class="card-premium bg-purple-50 border border-purple-200">
                <h3 class="font-display font-semibold text-primary-dark mb-3 text-sm sm:text-base">
                    Emitir Certificado
                </h3>
                <p class="text-xs sm:text-sm text-gray-700 mb-4">
                    El estudiante ha completado el curso y puede recibir su certificado.
                </p>
                <form method="POST" action="{{ route('enrollments.issue-certificate', $enrollment->id) }}" id="cert-form">
                    @csrf
                    <button type="button" 
                            onclick="showConfirmModal('¿Emitir certificado para {{ $enrollment->student->full_name }}?', function() { document.getElementById('cert-form').submit(); })"
                            class="w-full btn-primary bg-purple-600 hover:bg-purple-700">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Emitir Certificado
                    </button>
                </form>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Resumen
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Código</span>
                        <span class="font-display font-semibold text-primary-dark font-mono">{{ $enrollment->enrollment_code }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado</span>
                        <span class="badge {{ $statusColors[$enrollment->status] ?? 'badge-info' }}">
                            {{ ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pagado</span>
                        <span class="font-display font-semibold text-primary-dark text-lg">${{ number_format($enrollment->final_price, 2) }}</span>
                    </div>

                    @if($enrollment->certificate_issued)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Certificado</span>
                        <span class="badge badge-success">Emitido</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fechas -->
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Registro
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de inscripción</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $enrollment->enrollment_date->format('d/m/Y') }}
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de creación</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $enrollment->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $enrollment->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Última actualización</label>
                        <p class="text-primary-dark font-medium mt-1">
                            {{ $enrollment->updated_at->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $enrollment->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(auth()->user()->hasPermission('enrollments.edit'))
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-2">
                    <a href="{{ route('enrollments.edit', $enrollment->id) }}" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Editar inscripción</span>
                    </a>

                    <a href="{{ route('students.show', $enrollment->student->id) }}" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Ver estudiante</span>
                    </a>

                    <a href="{{ route('course-offerings.show', $enrollment->courseOffering->id) }}" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Ver programación</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection