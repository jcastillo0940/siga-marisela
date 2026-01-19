@extends('layouts.app')

@section('title', 'Punto de Venta')
@section('page-title', 'POS - Punto de Venta')

@section('content')
<div class="fade-in">
    <!-- Cash Register Info -->
    <div class="mb-6 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Caja Activa</p>
                    <p class="font-semibold text-primary-dark">{{ $activeCashRegister->code }}</p>
                    <p class="text-xs text-gray-500">Abierta: {{ $activeCashRegister->opened_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Fondo Inicial</p>
                <p class="text-2xl font-display font-bold text-green-600">{{ $activeCashRegister->formatted_opening_amount }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT SIDE - Búsqueda y Selección -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Search -->
            @if(!$student)
            <div class="card-premium">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-display font-semibold text-primary-dark">
                        Buscar Cliente
                    </h2>
                </div>

                <div class="relative">
                    <input type="text" 
                           id="student_search" 
                           class="input-elegant text-lg"
                           placeholder="Escribe nombre o cédula del estudiante..."
                           autocomplete="off"
                           autofocus
                           oninput="searchStudents(this.value)">
                    
                    <div id="student-results" class="absolute z-50 w-full mt-2 bg-white border-2 border-gray-300 rounded-lg shadow-2xl hidden max-h-96 overflow-y-auto">
                        <!-- Results will be populated here -->
                    </div>
                </div>
            </div>
            @else
            <!-- Student Selected -->
            <div class="card-premium bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center text-white text-3xl font-display font-semibold shadow-lg">
                            {{ substr($student->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-3xl font-display font-semibold text-primary-dark">
                                {{ $student->full_name }}
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $student->identification }}</p>
                            <p class="text-sm text-gray-500">{{ $student->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('pos.index') }}" class="btn-secondary">
                        Cambiar Cliente
                    </a>
                </div>
            </div>

            @if($enrollments->isEmpty())
            <!-- No Pending Payments -->
            <div class="card-premium">
                <div class="text-center py-12">
                    <svg class="w-20 h-20 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xl text-gray-600 font-medium">¡Este estudiante no tiene pagos pendientes!</p>
                    <p class="text-gray-500 mt-2">Todos sus cursos están al día</p>
                </div>
            </div>
            @else
            <!-- Courses and Schedules -->
            <div class="space-y-4">
                @foreach($enrollments as $enrollment)
                <div class="card-premium bg-white border-2 border-gray-200">
                    <!-- Course Header -->
                    <div class="flex items-center justify-between pb-4 border-b-2">
                        <div>
                            <h3 class="text-xl font-display font-semibold text-primary-dark">
                                {{ $enrollment->courseOffering->course->name }}
                            </h3>
                            @if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name)
                                <p class="text-sm text-gray-600">{{ $enrollment->courseOffering->generation_name }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Saldo Pendiente</p>
                            <p class="text-3xl font-display font-bold text-red-600">${{ number_format($enrollment->paymentPlan->balance, 2) }}</p>
                        </div>
                    </div>

                    <!-- Payment Schedules -->
                    <div class="space-y-3 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-gray-700">Cuotas Pendientes:</p>
                            <button type="button" 
                                    onclick="selectAllSchedules({{ $enrollment->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Seleccionar Todas
                            </button>
                        </div>
                        @forelse($enrollment->paymentPlan->schedules as $schedule)
                        <label class="block cursor-pointer">
                            <input type="checkbox" 
                                   class="schedule-checkbox hidden"
                                   data-enrollment="{{ $enrollment->id }}"
                                   data-schedule-id="{{ $schedule->id }}"
                                   data-installment="{{ $schedule->installment_number }}"
                                   data-amount="{{ $schedule->balance }}"
                                   data-due-date="{{ $schedule->due_date->format('d/m/Y') }}"
                                   data-is-overdue="{{ $schedule->is_overdue ? 'true' : 'false' }}"
                                   data-course-name="{{ $enrollment->courseOffering->course->name }}"
                                   onchange="toggleScheduleSelection(this)">
                            <div class="schedule-card p-4 rounded-lg border-2 transition-all {{ $schedule->is_overdue ? 'bg-red-50 border-red-300' : 'bg-blue-50 border-blue-200' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="checkbox-icon w-6 h-6 border-2 rounded flex items-center justify-center {{ $schedule->is_overdue ? 'border-red-400' : 'border-blue-400' }}">
                                                <svg class="w-4 h-4 text-white hidden checkmark" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $schedule->is_overdue ? 'bg-red-200 text-red-800' : 'bg-blue-600 text-white' }}">
                                                Cuota #{{ $schedule->installment_number }}
                                            </span>
                                            @if($schedule->is_overdue)
                                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded animate-pulse">⚠ VENCIDA</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Vence: {{ $schedule->due_date->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-display font-bold text-primary-dark">${{ number_format($schedule->balance, 2) }}</p>
                                        <p class="text-xs text-gray-500">Pendiente</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @empty
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">No hay cuotas pendientes para este curso</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            @endif
        </div>

        <!-- RIGHT SIDE - Payment Summary (Ticket) -->
        <div class="lg:col-span-1">
            <div class="card-premium bg-gradient-to-b from-white to-gray-50 sticky top-6">
                <div class="bg-primary-dark text-white p-4 -m-6 mb-6 rounded-t-lg">
                    <h3 class="text-xl font-display font-semibold">Resumen de Pago</h3>
                </div>

                <form method="POST" action="{{ route('payments.store') }}" id="payment-form">
                    @csrf
                    
                    <input type="hidden" name="enrollment_id" id="enrollment_id">
                    <input type="hidden" name="selected_schedules" id="selected_schedules">

                    <div id="no-selection" class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-400">Selecciona una o más cuotas</p>
                    </div>

                    <div id="payment-details" class="hidden space-y-6">
                        <!-- Selected Items Summary -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2">Curso</p>
                            <p class="font-semibold text-primary-dark mb-3" id="selected-course-name">-</p>
                            
                            <p class="text-sm text-gray-600 mb-2">Cuotas Seleccionadas</p>
                            <div id="selected-schedules-list" class="space-y-1 mb-3 max-h-32 overflow-y-auto">
                                <!-- Dynamic list -->
                            </div>
                            <div class="border-t-2 border-blue-300 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Total:</span>
                                    <span class="text-2xl font-display font-bold text-primary-dark" id="schedules-total">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Amount to Pay -->
                        <div>
                            <label class="label-elegant text-lg">Monto a Cobrar</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-3xl font-bold text-gray-400">$</span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount"
                                       class="w-full pl-12 pr-4 py-4 text-4xl font-display font-bold text-right border-4 border-green-500 rounded-lg focus:ring-4 focus:ring-green-200"
                                       step="0.01"
                                       min="0.01"
                                       required
                                       oninput="calculateChange()">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                💡 Puedes pagar más del total. El sobrante se aplicará a la siguiente cuota.
                            </p>
                        </div>

                        <!-- Overpayment Notice -->
                        <div id="overpayment-notice" class="hidden bg-green-50 border-2 border-green-300 rounded-lg p-3">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-green-800">Pago excede las cuotas seleccionadas</p>
                                    <p class="text-xs text-green-700 mt-1">
                                        Sobrante de <strong id="overpayment-amount">$0.00</strong> se aplicará a la siguiente cuota pendiente.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="label-elegant">Método de Pago</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-accent-red">
                                    <input type="radio" name="payment_method" value="efectivo" class="sr-only" checked onchange="toggleReference()">
                                    <span class="font-medium">💵 Efectivo</span>
                                </label>
                                <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-accent-red">
                                    <input type="radio" name="payment_method" value="transferencia" class="sr-only" onchange="toggleReference()">
                                    <span class="font-medium">🏦 Transfer</span>
                                </label>
                                <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-accent-red">
                                    <input type="radio" name="payment_method" value="tarjeta_debito" class="sr-only" onchange="toggleReference()">
                                    <span class="font-medium">💳 Débito</span>
                                </label>
                                <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all hover:border-accent-red">
                                    <input type="radio" name="payment_method" value="yappy" class="sr-only" onchange="toggleReference()">
                                    <span class="font-medium">📱 Yappy</span>
                                </label>
                            </div>
                        </div>

                        <!-- Reference Number -->
                        <div id="reference-container" class="hidden">
                            <label for="reference_number" class="label-elegant">Número de Referencia</label>
                            <input type="text" 
                                   name="reference_number" 
                                   id="reference_number"
                                   class="input-elegant"
                                   placeholder="Ej: TRX123456">
                        </div>

                        <!-- Change Calculator (Only for cash) -->
                        <div id="change-calculator" class="bg-green-50 border-2 border-green-300 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">A Cobrar:</span>
                                    <span class="font-bold text-primary-dark" id="total-display">$0.00</span>
                                </div>
                                <div class="flex justify-between text-lg">
                                    <span class="text-gray-700">Recibido:</span>
                                    <input type="number" 
                                           id="cash-received"
                                           class="w-32 text-right font-bold border-2 border-green-400 rounded px-2 py-1"
                                           step="0.01"
                                           oninput="calculateChange()"
                                           placeholder="0.00">
                                </div>
                                <div class="border-t-2 border-green-300 pt-2">
                                    <div class="flex justify-between text-2xl">
                                        <span class="font-bold text-gray-700">Cambio:</span>
                                        <span class="font-display font-bold text-green-600" id="change-display">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="label-elegant">Notas</label>
                            <textarea name="notes" 
                                      id="notes"
                                      rows="2"
                                      class="input-elegant"
                                      placeholder="Notas opcionales..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button type="submit" class="w-full btn-primary text-lg py-4">
                                <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Registrar Pago
                            </button>
                            <button type="button" onclick="clearSelection()" class="w-full btn-secondary py-3">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales
let searchTimeout = null;
let selectedSchedules = [];
let currentEnrollmentId = null;

// Search students
function searchStudents(query) {
    clearTimeout(searchTimeout);
    
    const resultsDiv = document.getElementById('student-results');
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`{{ route('api.payments.search-students') }}?q=${encodeURIComponent(query)}`);
            const students = await response.json();
            
            if (students.length === 0) {
                resultsDiv.innerHTML = '<div class="p-6 text-center text-gray-500">No se encontraron estudiantes con pagos pendientes</div>';
                resultsDiv.classList.remove('hidden');
                return;
            }
            
            resultsDiv.innerHTML = students.map(student => `
                <a href="{{ route('pos.index') }}?student_id=${student.id}"
                   class="block p-4 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition-all">
                    <p class="font-semibold text-lg text-primary-dark">${student.first_name} ${student.last_name}</p>
                    <p class="text-sm text-gray-600">${student.identification}</p>
                </a>
            `).join('');
            
            resultsDiv.classList.remove('hidden');
            
        } catch (error) {
            console.error('Error searching students:', error);
        }
    }, 300);
}

// Toggle schedule selection
window.toggleScheduleSelection = function(checkbox) {
    const scheduleData = {
        enrollment_id: checkbox.dataset.enrollment,
        schedule_id: checkbox.dataset.scheduleId,
        installment: checkbox.dataset.installment,
        amount: parseFloat(checkbox.dataset.amount),
        due_date: checkbox.dataset.dueDate,
        is_overdue: checkbox.dataset.isOverdue === 'true',
        course_name: checkbox.dataset.courseName
    };

    if (checkbox.checked) {
        selectedSchedules.push(scheduleData);
        currentEnrollmentId = scheduleData.enrollment_id;
        // Visual feedback
        const card = checkbox.nextElementSibling;
        card.classList.add('ring-4', 'ring-green-400', 'border-green-500');
        const checkIcon = card.querySelector('.checkbox-icon');
        checkIcon.classList.add('bg-green-500', 'border-green-500');
        checkIcon.querySelector('.checkmark').classList.remove('hidden');
    } else {
        selectedSchedules = selectedSchedules.filter(s => s.schedule_id !== scheduleData.schedule_id);
        // Remove visual feedback
        const card = checkbox.nextElementSibling;
        card.classList.remove('ring-4', 'ring-green-400', 'border-green-500');
        const checkIcon = card.querySelector('.checkbox-icon');
        checkIcon.classList.remove('bg-green-500', 'border-green-500');
        checkIcon.querySelector('.checkmark').classList.add('hidden');
    }

    updatePaymentSummary();
};

// Select all schedules for an enrollment
window.selectAllSchedules = function(enrollmentId) {
    const checkboxes = document.querySelectorAll(`.schedule-checkbox[data-enrollment="${enrollmentId}"]`);
    checkboxes.forEach(cb => {
        if (!cb.checked) {
            cb.checked = true;
            window.toggleScheduleSelection(cb);
        }
    });
};

// Update payment summary
function updatePaymentSummary() {
    if (selectedSchedules.length === 0) {
        const noSelection = document.getElementById('no-selection');
        const paymentDetails = document.getElementById('payment-details');
        if (noSelection) noSelection.classList.remove('hidden');
        if (paymentDetails) paymentDetails.classList.add('hidden');
        return;
    }

    const noSelection = document.getElementById('no-selection');
    const paymentDetails = document.getElementById('payment-details');
    
    if (noSelection) noSelection.classList.add('hidden');
    if (paymentDetails) paymentDetails.classList.remove('hidden');

    // Update enrollment ID
    const enrollmentInput = document.getElementById('enrollment_id');
    if (enrollmentInput) enrollmentInput.value = currentEnrollmentId;
    
    // Update course name
    const courseNameElement = document.getElementById('selected-course-name');
    if (courseNameElement && selectedSchedules.length > 0) {
        courseNameElement.textContent = selectedSchedules[0].course_name;
    }
    
    // Update schedules list
    const listHtml = selectedSchedules.map(s => `
        <div class="flex justify-between text-sm py-1">
            <span class="text-gray-600">Cuota #${s.installment}${s.is_overdue ? ' ⚠️' : ''}</span>
            <span class="font-medium text-primary-dark">$${s.amount.toFixed(2)}</span>
        </div>
    `).join('');
    
    const schedulesList = document.getElementById('selected-schedules-list');
    if (schedulesList) schedulesList.innerHTML = listHtml;

    // Update total
    const total = selectedSchedules.reduce((sum, s) => sum + s.amount, 0);
    const schedulesTotal = document.getElementById('schedules-total');
    if (schedulesTotal) schedulesTotal.textContent = '$' + total.toFixed(2);
    
    // Set amount to total
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.value = total.toFixed(2);
        amountInput.min = '0.01';
    }

    // Update hidden field with schedule IDs
    const selectedSchedulesInput = document.getElementById('selected_schedules');
    if (selectedSchedulesInput) {
        selectedSchedulesInput.value = JSON.stringify(
            selectedSchedules.map(s => s.schedule_id)
        );
    }

    calculateChange();
}

// Clear selection
window.clearSelection = function() {
    // Uncheck all
    document.querySelectorAll('.schedule-checkbox').forEach(cb => {
        if (cb.checked) {
            cb.checked = false;
            const card = cb.nextElementSibling;
            card.classList.remove('ring-4', 'ring-green-400', 'border-green-500');
            const checkIcon = card.querySelector('.checkbox-icon');
            checkIcon.classList.remove('bg-green-500', 'border-green-500');
            checkIcon.querySelector('.checkmark').classList.add('hidden');
        }
    });
    
    selectedSchedules = [];
    currentEnrollmentId = null;
    document.getElementById('enrollment_id').value = '';
    document.getElementById('selected_schedules').value = '';
    document.getElementById('amount').value = '';
    document.getElementById('cash-received').value = '';
    document.getElementById('no-selection').classList.remove('hidden');
    document.getElementById('payment-details').classList.add('hidden');
    const overpaymentNotice = document.getElementById('overpayment-notice');
    if (overpaymentNotice) overpaymentNotice.classList.add('hidden');
};

// Toggle reference number
window.toggleReference = function() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;
    const refContainer = document.getElementById('reference-container');
    const changeCalc = document.getElementById('change-calculator');
    
    if (method === 'efectivo') {
        refContainer.classList.add('hidden');
        changeCalc.classList.remove('hidden');
    } else {
        refContainer.classList.remove('hidden');
        changeCalc.classList.add('hidden');
    }
};

// Calculate change and overpayment
window.calculateChange = function() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const total = selectedSchedules.reduce((sum, s) => sum + s.amount, 0);
    const cashReceived = parseFloat(document.getElementById('cash-received').value) || 0;
    const change = cashReceived - amount;
    
    document.getElementById('total-display').textContent = '$' + amount.toFixed(2);
    document.getElementById('change-display').textContent = '$' + change.toFixed(2);
    
    // Change color based on sufficient payment
    const changeDisplay = document.getElementById('change-display');
    if (change < 0) {
        changeDisplay.classList.remove('text-green-600');
        changeDisplay.classList.add('text-red-600');
    } else {
        changeDisplay.classList.remove('text-red-600');
        changeDisplay.classList.add('text-green-600');
    }

    // Check for underpayment WARNING
    const amountInput = document.getElementById('amount');
    if (amount > 0 && amount < total) {
        if (amountInput) {
            amountInput.classList.add('border-yellow-500');
            amountInput.classList.remove('border-green-500');
        }
    } else {
        if (amountInput) {
            amountInput.classList.remove('border-yellow-500');
            amountInput.classList.add('border-green-500');
        }
    }

    // Check for overpayment
    const overpaymentNotice = document.getElementById('overpayment-notice');
    if (amount > total && amount > 0) {
        const overpayment = amount - total;
        const overpaymentAmount = document.getElementById('overpayment-amount');
        if (overpaymentAmount) overpaymentAmount.textContent = '$' + overpayment.toFixed(2);
        if (overpaymentNotice) overpaymentNotice.classList.remove('hidden');
    } else {
        if (overpaymentNotice) overpaymentNotice.classList.add('hidden');
    }
};

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const searchInput = document.getElementById('student_search');
    const resultsDiv = document.getElementById('student-results');
    
    if (searchInput && resultsDiv && !searchInput.contains(event.target) && !resultsDiv.contains(event.target)) {
        resultsDiv.classList.add('hidden');
    }
});

// Style selected payment method
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function() {
            paymentMethods.forEach(r => {
                r.parentElement.classList.remove('border-accent-red', 'bg-red-50');
                r.parentElement.classList.add('border-gray-200');
            });
            if (this.checked) {
                this.parentElement.classList.remove('border-gray-200');
                this.parentElement.classList.add('border-accent-red', 'bg-red-50');
            }
        });
    });
    
    // Trigger initial state
    const checkedMethod = document.querySelector('input[name="payment_method"]:checked');
    if (checkedMethod) {
        checkedMethod.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection