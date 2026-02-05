@extends('layouts.app')

@section('title', 'Editar Inscripción')
@section('page-title', 'Editar Inscripción')

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
                        <a href="{{ route('enrollments.show', $enrollment->id) }}" class="text-gray-500 hover:text-accent-red transition-colors">
                            {{ $enrollment->enrollment_code }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Editar</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('enrollments.update', $enrollment->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header -->
                <div class="card-premium">
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold">
                            {{ substr($enrollment->student->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                                {{ $enrollment->student->full_name }}
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $enrollment->enrollment_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de la Inscripción -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información de la Inscripción
                    </h2>

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Student -->
                        <div>
                            <label for="student_id" class="label-elegant">Estudiante *</label>
                            <select id="student_id" 
                                    name="student_id" 
                                    class="input-elegant @error('student_id') border-red-500 @enderror"
                                    required
                                    onchange="loadStudentInfo()">
                                <option value="">Seleccionar estudiante</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            data-email="{{ $student->email }}"
                                            data-phone="{{ $student->phone }}"
                                            {{ old('student_id', $enrollment->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }} - {{ $student->identification }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div id="student-info" class="mt-3 p-3 bg-neutral-bg rounded">
                                <p class="text-sm text-gray-600"><strong>Email:</strong> <span id="student-email">{{ $enrollment->student->email }}</span></p>
                                <p class="text-sm text-gray-600"><strong>Teléfono:</strong> <span id="student-phone">{{ $enrollment->student->phone }}</span></p>
                            </div>
                        </div>

                        <!-- Course Offering -->
                        <div>
                            <label for="course_offering_id" class="label-elegant">Curso/Programación *</label>
                            <select id="course_offering_id" 
                                    name="course_offering_id" 
                                    class="input-elegant @error('course_offering_id') border-red-500 @enderror"
                                    required
                                    onchange="loadOfferingInfo()">
                                <option value="">Seleccionar curso</option>
                                @foreach($offerings as $offering)
                                    <option value="{{ $offering->id }}"
                                            data-price="{{ $offering->price }}"
                                            data-available="{{ $offering->available_spots }}"
                                            data-location="{{ $offering->location }}"
                                            data-start="{{ $offering->start_date->format('Y-m-d') }}"
                                            data-dates="{{ $offering->start_date->format('d/m/Y') }} - {{ $offering->end_date->format('d/m/Y') }}"
                                            {{ old('course_offering_id', $enrollment->course_offering_id) == $offering->id ? 'selected' : '' }}>
                                        {{ $offering->course->name }}
                                        @if($offering->is_generation && $offering->generation_name)
                                            - {{ $offering->generation_name }}
                                        @endif
                                        ({{ $offering->location }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_offering_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="offering-info" class="mt-3 p-3 bg-neutral-bg rounded">
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-gray-500">Ubicación:</p>
                                        <p class="font-medium text-primary-dark" id="offering-location">{{ $enrollment->courseOffering->location }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Fechas:</p>
                                        <p class="font-medium text-primary-dark" id="offering-dates">{{ $enrollment->courseOffering->start_date->format('d/m/Y') }} - {{ $enrollment->courseOffering->end_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Precio:</p>
                                        <p class="font-medium text-primary-dark text-lg" id="offering-price">{{ $enrollment->courseOffering->formatted_price }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Cupos disponibles:</p>
                                        <p class="font-medium text-green-600" id="offering-spots">{{ $enrollment->courseOffering->available_spots }} cupos</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Date -->
                        <div>
                            <label for="enrollment_date" class="label-elegant">Fecha de Inscripción *</label>
                            <input type="date" 
                                   id="enrollment_date" 
                                   name="enrollment_date" 
                                   value="{{ old('enrollment_date', $enrollment->enrollment_date->format('Y-m-d')) }}"
                                   class="input-elegant @error('enrollment_date') border-red-500 @enderror"
                                   required
                                   onchange="updatePaymentSchedule()">
                            @error('enrollment_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Price Paid -->
                            <div>
                                <label for="price_paid" class="label-elegant">Precio Total (USD) *</label>
                                <input type="number" 
                                       id="price_paid" 
                                       name="price_paid" 
                                       value="{{ old('price_paid', $enrollment->price_paid) }}"
                                       class="input-elegant @error('price_paid') border-red-500 @enderror"
                                       step="0.01"
                                       min="0"
                                       required
                                       oninput="calculateTotal()">
                                @error('price_paid')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount -->
                            <div>
                                <label for="discount" class="label-elegant">Descuento (USD)</label>
                                <input type="number" 
                                       id="discount" 
                                       name="discount" 
                                       value="{{ old('discount', $enrollment->discount) }}"
                                       class="input-elegant @error('discount') border-red-500 @enderror"
                                       step="0.01"
                                       min="0"
                                       oninput="calculateTotal()">
                                @error('discount')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-primary-dark">Total a Pagar:</span>
                                <span class="text-2xl font-display font-bold text-primary-dark" id="total-display">${{ number_format($enrollment->final_price, 2) }}</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="label-elegant">Notas</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3"
                                      class="input-elegant @error('notes') border-red-500 @enderror">{{ old('notes', $enrollment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Plan de Pagos -->
                <div class="card-premium">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                            Plan de Pagos
                        </h2>
                        @if($enrollment->payment_type == 'cuotas' && $enrollment->installments && $enrollment->installments->count() > 0 && $enrollment->installments->where('status', 'pagado')->count() == $enrollment->installments->count())
                        <span class="flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Completamente Pagado
                        </span>
                        @endif
                    </div>

                    @php
                        $isFullyPaid = $enrollment->payment_type == 'cuotas' 
                            && $enrollment->installments 
                            && $enrollment->installments->count() > 0 
                            && $enrollment->installments->where('status', 'pagado')->count() == $enrollment->installments->count();
                    @endphp

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Payment Type -->
                        <div>
                            <label class="label-elegant">Tipo de Pago *</label>
                            <div class="grid grid-cols-2 gap-4 mt-2">
                                <label class="flex items-center p-4 border-2 rounded {{ $isFullyPaid ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }} transition-all hover:border-accent-red {{ old('payment_type', $enrollment->payment_type) == 'contado' ? 'border-accent-red bg-red-50' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="payment_type" 
                                           value="contado" 
                                           {{ old('payment_type', $enrollment->payment_type) == 'contado' ? 'checked' : '' }}
                                           {{ $isFullyPaid ? 'disabled' : '' }}
                                           onchange="togglePaymentType()"
                                           class="w-4 h-4 text-accent-red {{ $isFullyPaid ? 'cursor-not-allowed' : '' }}">
                                    <div class="ml-3">
                                        <p class="font-medium text-primary-dark">Pago de Contado</p>
                                        <p class="text-xs text-gray-600">Pago único completo</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 rounded {{ $isFullyPaid ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }} transition-all hover:border-accent-red {{ old('payment_type', $enrollment->payment_type) == 'cuotas' ? 'border-accent-red bg-red-50' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="payment_type" 
                                           value="cuotas" 
                                           {{ old('payment_type', $enrollment->payment_type) == 'cuotas' ? 'checked' : '' }}
                                           {{ $isFullyPaid ? 'disabled' : '' }}
                                           onchange="togglePaymentType()"
                                           class="w-4 h-4 text-accent-red {{ $isFullyPaid ? 'cursor-not-allowed' : '' }}">
                                    <div class="ml-3">
                                        <p class="font-medium text-primary-dark">Pago en Cuotas</p>
                                        <p class="text-xs text-gray-600">Pago fraccionado</p>
                                    </div>
                                </label>
                            </div>
                            @error('payment_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            @if($isFullyPaid)
                            <div class="mt-3 p-4 bg-green-50 border-l-4 border-green-400 rounded">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            <strong>¡Excelente!</strong> El cliente ha pagado todas las cuotas del plan de pagos. 
                                            No es posible modificar el plan de pagos porque ya está completamente pagado.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Cuotas Options -->
                        <div id="cuotas-options" class="{{ old('payment_type', $enrollment->payment_type) == 'cuotas' ? '' : 'hidden' }}">
                            <div class="bg-neutral-bg p-4 rounded space-y-4 {{ $isFullyPaid ? 'opacity-75' : '' }}">
                                <!-- Existing Installments Warning -->
                                @if($enrollment->payment_type == 'cuotas' && $enrollment->installments && $enrollment->installments->count() > 0)
                                    @if(!$isFullyPaid)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    <strong>Atención:</strong> Esta inscripción ya tiene un plan de pagos con 
                                                    <strong>{{ $enrollment->installments->count() }} cuota(s)</strong> generada(s), 
                                                    de las cuales <strong>{{ $enrollment->installments->where('status', 'pagado')->count() }} está(n) pagada(s)</strong>.
                                                    Si modificas el plan de pagos, se recalcularán las cuotas pendientes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                <!-- Existing Installments List -->
                                <div class="bg-white border border-gray-200 rounded p-4">
                                    <h4 class="font-semibold text-primary-dark mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Cuotas Actuales
                                    </h4>
                                    <div class="space-y-2 max-h-64 overflow-y-auto">
                                        @foreach($enrollment->installments->sortBy('installment_number') as $installment)
                                        <div class="flex justify-between items-center p-3 rounded {{ $installment->status == 'pagado' ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    @if($installment->status == 'pagado')
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    @else
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    @endif
                                                    <span class="font-medium text-sm {{ $installment->status == 'pagado' ? 'text-green-800' : 'text-gray-700' }}">
                                                        Cuota {{ $installment->installment_number }}
                                                    </span>
                                                    @if($installment->status == 'pagado')
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded font-semibold">Pagado</span>
                                                    @elseif($installment->status == 'vencido')
                                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded font-semibold">Vencido</span>
                                                    @else
                                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded font-semibold">Pendiente</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Vence: {{ $installment->due_date->format('d/m/Y') }}
                                                    @if($installment->status == 'pagado' && $installment->paid_at)
                                                        | Pagado: {{ $installment->paid_at->format('d/m/Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="text-right ml-4">
                                                <p class="font-bold {{ $installment->status == 'pagado' ? 'text-green-700' : 'text-primary-dark' }}">
                                                    ${{ number_format($installment->amount, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Summary -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="grid grid-cols-3 gap-4 text-sm">
                                            <div class="text-center">
                                                <p class="text-gray-600">Total</p>
                                                <p class="font-bold text-primary-dark">${{ number_format($enrollment->installments->sum('amount'), 2) }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-gray-600">Pagado</p>
                                                <p class="font-bold text-green-600">${{ number_format($enrollment->installments->where('status', 'pagado')->sum('amount'), 2) }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-gray-600">Pendiente</p>
                                                <p class="font-bold text-red-600">${{ number_format($enrollment->installments->whereIn('status', ['pendiente', 'vencido'])->sum('amount'), 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!$isFullyPaid)
                                <!-- Periodicity -->
                                <div>
                                    <label for="periodicity" class="label-elegant">Periodicidad *</label>
                                    <select id="periodicity" 
                                            name="periodicity" 
                                            class="input-elegant @error('periodicity') border-red-500 @enderror"
                                            onchange="calculateInstallments()">
                                        <option value="">Seleccionar periodicidad</option>
                                        <option value="semanal" {{ old('periodicity', $enrollment->periodicity) == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                        <option value="quincenal" {{ old('periodicity', $enrollment->periodicity) == 'quincenal' ? 'selected' : '' }}>Quincenal</option>
                                        <option value="mensual" {{ old('periodicity', $enrollment->periodicity) == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                    </select>
                                    @error('periodicity')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Number of Installments -->
                                <div>
                                    <label for="number_of_installments" class="label-elegant">
                                        Número de Cuotas
                                        <span class="text-sm font-normal text-gray-500">(calculado automáticamente)</span>
                                    </label>
                                    <input type="number" 
                                           id="number_of_installments" 
                                           name="number_of_installments" 
                                           value="{{ old('number_of_installments', $enrollment->number_of_installments ?? 1) }}"
                                           class="input-elegant @error('number_of_installments') border-red-500 @enderror"
                                           min="1"
                                           readonly
                                           oninput="updatePaymentSchedule()">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Este valor se calcula automáticamente según la periodicidad y las fechas del curso.
                                    </p>
                                    @error('number_of_installments')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Manual Override Option -->
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           id="manual_installments" 
                                           class="w-4 h-4 text-accent-red border-gray-300 rounded focus:ring-accent-red"
                                           onchange="toggleManualInstallments()">
                                    <label for="manual_installments" class="text-sm text-gray-700">
                                        Configurar número de cuotas manualmente
                                    </label>
                                </div>

                                <!-- Estimated Schedule Preview -->
                                <div id="schedule-preview" class="hidden">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="label-elegant mb-0">Cronograma Estimado (Nuevo Plan)</label>
                                        <span class="text-sm text-gray-600">
                                            <strong id="estimated-installments">0</strong> cuotas de 
                                            <strong id="estimated-amount">$0.00</strong>
                                        </span>
                                    </div>
                                    <div class="bg-white rounded p-3 max-h-48 overflow-y-auto">
                                        <div id="schedule-list" class="space-y-2 text-sm"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        * Este es un cronograma estimado del nuevo plan. Las fechas pueden ajustarse automáticamente.
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(auth()->user()->hasPermission('enrollments.delete'))
                            <button type="button" 
                                    onclick="showConfirmModal('¿Estás seguro de eliminar esta inscripción? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form').submit(); })"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Inscripción
                            </button>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('enrollments.show', $enrollment->id) }}" class="btn-secondary text-center">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Settings -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Estado y Certificado
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="label-elegant">Estado de la Inscripción</label>
                            <select id="status" 
                                    name="status" 
                                    class="input-elegant @error('status') border-red-500 @enderror">
                                <option value="inscrito" {{ old('status', $enrollment->status) == 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                                <option value="en_curso" {{ old('status', $enrollment->status) == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="completado" {{ old('status', $enrollment->status) == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="retirado" {{ old('status', $enrollment->status) == 'retirado' ? 'selected' : '' }}>Retirado</option>
                                <option value="suspendido" {{ old('status', $enrollment->status) == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                        </div>

                        @if($enrollment->certificate_issued)
                        <div class="bg-green-50 border border-green-200 rounded p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Certificado Emitido</p>
                                    <p class="text-xs text-green-600">{{ $enrollment->certificate_issued_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
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
                            <strong class="text-primary-dark">Plan de Pagos:</strong> 
                            Seleccione cómo el estudiante pagará el curso.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Contado:</strong> 
                            Pago único el día de la inscripción.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Cuotas:</strong> 
                            Primera cuota hoy, última cuota el día del curso.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Periodicidad:</strong> 
                            Determina cada cuánto se generan los pagos.
                        </p>
                    </div>
                </div>

                <!-- Info -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Información
                    </h3>
                    
                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Código:</span>
                            <span class="text-primary-dark font-medium font-mono">{{ $enrollment->enrollment_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Creado:</span>
                            <span class="text-primary-dark font-medium">{{ $enrollment->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Última actualización:</span>
                            <span class="text-primary-dark font-medium">{{ $enrollment->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    @if(auth()->user()->hasPermission('enrollments.delete'))
    <form id="delete-form" 
          method="POST" 
          action="{{ route('enrollments.destroy', $enrollment->id) }}"
          class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>

@push('scripts')
<script>
let courseStartDate = '{{ $enrollment->courseOffering->start_date->format('Y-m-d') }}';

// Load student info
function loadStudentInfo() {
    const select = document.getElementById('student_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('student-email').textContent = option.dataset.email || 'N/A';
        document.getElementById('student-phone').textContent = option.dataset.phone || 'N/A';
    }
}

// Load offering info
function loadOfferingInfo() {
    const select = document.getElementById('course_offering_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        const price = parseFloat(option.dataset.price);
        const available = parseInt(option.dataset.available);
        courseStartDate = option.dataset.start;
        
        document.getElementById('offering-location').textContent = option.dataset.location || 'N/A';
        document.getElementById('offering-dates').textContent = option.dataset.dates || 'N/A';
        document.getElementById('offering-price').textContent = '$' + price.toFixed(2);
        
        const spotsElement = document.getElementById('offering-spots');
        spotsElement.textContent = available + ' cupos';
        spotsElement.className = 'font-medium ' + (available > 0 ? 'text-green-600' : 'text-red-600');
        
        calculateInstallments();
    }
}

// Calculate total
function calculateTotal() {
    const price = parseFloat(document.getElementById('price_paid').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const total = price - discount;
    
    document.getElementById('total-display').textContent = '$' + total.toFixed(2);
    updatePaymentSchedule();
}

// Toggle payment type
function togglePaymentType() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    const cuotasOptions = document.getElementById('cuotas-options');
    
    if (paymentType === 'cuotas') {
        cuotasOptions.classList.remove('hidden');
        calculateInstallments();
    } else {
        cuotasOptions.classList.add('hidden');
        document.getElementById('schedule-preview').classList.add('hidden');
    }
}

// Toggle manual installments
function toggleManualInstallments() {
    const checkbox = document.getElementById('manual_installments');
    const input = document.getElementById('number_of_installments');
    
    if (checkbox.checked) {
        input.removeAttribute('readonly');
        input.classList.add('bg-white');
        input.classList.remove('bg-gray-100');
        input.focus();
    } else {
        input.setAttribute('readonly', true);
        input.classList.remove('bg-white');
        input.classList.add('bg-gray-100');
        calculateInstallments();
    }
}

// Calculate installments based on periodicity
function calculateInstallments() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value;
    
    if (paymentType !== 'cuotas') return;
    
    const periodicity = document.getElementById('periodicity').value;
    const enrollmentDate = document.getElementById('enrollment_date').value;
    const manualCheckbox = document.getElementById('manual_installments');
    
    if (!periodicity || !enrollmentDate || !courseStartDate || manualCheckbox.checked) {
        return;
    }
    
    // Calculate days between enrollment and course start
    const start = new Date(enrollmentDate);
    const end = new Date(courseStartDate);
    const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    
    let installments = 1;
    
    if (periodicity === 'semanal') {
        installments = Math.max(1, Math.ceil(days / 7));
    } else if (periodicity === 'quincenal') {
        installments = Math.max(1, Math.ceil(days / 15));
    } else if (periodicity === 'mensual') {
        installments = Math.max(1, Math.ceil(days / 30));
    }
    
    // Update input
    document.getElementById('number_of_installments').value = installments;
    
    // Update schedule preview
    updatePaymentSchedule();
}

// Update payment schedule preview
function updatePaymentSchedule() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value;
    
    if (paymentType !== 'cuotas') return;
    
    const periodicity = document.getElementById('periodicity').value;
    const enrollmentDate = document.getElementById('enrollment_date').value;
    const installments = parseInt(document.getElementById('number_of_installments').value) || 1;
    const total = parseFloat(document.getElementById('price_paid').value || 0) - parseFloat(document.getElementById('discount').value || 0);
    
    if (!periodicity || !enrollmentDate || !courseStartDate || total <= 0 || installments < 1) {
        document.getElementById('schedule-preview').classList.add('hidden');
        return;
    }
    
    const installmentAmount = total / installments;
    
    // Update summary
    document.getElementById('estimated-installments').textContent = installments;
    document.getElementById('estimated-amount').textContent = '$' + installmentAmount.toFixed(2);
    
    // Generate schedule list
    const scheduleList = document.getElementById('schedule-list');
    scheduleList.innerHTML = '';
    
    const start = new Date(enrollmentDate);
    const end = new Date(courseStartDate);
    let currentDate = new Date(start);
    
    // Meses en español abreviados
    const monthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    
    for (let i = 1; i <= installments; i++) {
        let dueDate;
        
        if (i === installments) {
            // Last installment is always on course start date
            dueDate = new Date(end);
        } else {
            dueDate = new Date(currentDate);
        }
        
        const amount = (i === installments) 
            ? (total - (installmentAmount * (installments - 1)))
            : installmentAmount;
        
        // Formatear fecha: DD-MMM-YYYY
        const day = String(dueDate.getDate()).padStart(2, '0');
        const month = monthNames[dueDate.getMonth()];
        const year = dueDate.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        
        const div = document.createElement('div');
        div.className = 'flex justify-between p-2 bg-neutral-bg rounded';
        div.innerHTML = `
            <span class="text-gray-600">Cuota ${i}:</span>
            <span class="font-medium text-primary-dark">${formattedDate} - $${amount.toFixed(2)}</span>
        `;
        scheduleList.appendChild(div);
        
        // Calculate next date based on periodicity
        if (i < installments) {
            if (periodicity === 'semanal') {
                currentDate.setDate(currentDate.getDate() + 7);
            } else if (periodicity === 'quincenal') {
                currentDate.setDate(currentDate.getDate() + 15);
            } else if (periodicity === 'mensual') {
                currentDate.setMonth(currentDate.getMonth() + 1);
            }
        }
    }
    
    document.getElementById('schedule-preview').classList.remove('hidden');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('price_paid').addEventListener('input', calculateTotal);
    calculateTotal();
    
    // Initialize payment type
    togglePaymentType();
    
    // If cuotas is selected, show preview
    if (document.querySelector('input[name="payment_type"]:checked')?.value === 'cuotas') {
        const periodicity = document.getElementById('periodicity').value;
        if (periodicity) {
            updatePaymentSchedule();
        }
    }
});
</script>
@endpush
@endsection