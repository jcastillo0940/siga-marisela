<?php $__env->startSection('title', 'Punto de Venta'); ?>
<?php $__env->startSection('page-title', 'POS - Punto de Venta'); ?>

<?php $__env->startSection('content'); ?>
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
                    <p class="font-semibold text-primary-dark"><?php echo e($activeCashRegister->code); ?></p>
                    <p class="text-xs text-gray-500">Abierta: <?php echo e($activeCashRegister->opened_at->format('d/m/Y H:i')); ?></p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Fondo Inicial</p>
                <p class="text-2xl font-display font-bold text-green-600"><?php echo e($activeCashRegister->formatted_opening_amount); ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT SIDE - Búsqueda y Selección -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Search -->
            <?php if(!$student): ?>
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
            <?php else: ?>
            <!-- Student Selected -->
            <div class="card-premium bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center text-white text-3xl font-display font-semibold shadow-lg">
                            <?php echo e(substr($student->first_name, 0, 1)); ?>

                        </div>
                        <div>
                            <h2 class="text-3xl font-display font-semibold text-primary-dark">
                                <?php echo e($student->full_name); ?>

                            </h2>
                            <p class="text-gray-600 mt-1"><?php echo e($student->identification); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($student->email); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('pos.index')); ?>" class="btn-secondary">
                        Cambiar Cliente
                    </a>
                </div>
            </div>

            <?php if($enrollments->isEmpty()): ?>
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
            <?php else: ?>
            <!-- Courses and Schedules -->
            <div class="space-y-4">
                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card-premium bg-white border-2 border-gray-200">
                    <!-- Course Header -->
                    <div class="flex items-center justify-between pb-4 border-b-2">
                        <div>
                            <h3 class="text-xl font-display font-semibold text-primary-dark">
                                <?php echo e($enrollment->courseOffering->course->name); ?>

                            </h3>
                            <?php if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name): ?>
                                <p class="text-sm text-gray-600"><?php echo e($enrollment->courseOffering->generation_name); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Saldo Pendiente</p>
                            <p class="text-3xl font-display font-bold text-red-600">$<?php echo e(number_format($enrollment->paymentPlan->balance, 2)); ?></p>
                        </div>
                    </div>

                    <!-- Payment Schedules -->
                    <div class="space-y-3 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-gray-700">Cuotas Pendientes:</p>
                            <button type="button" 
                                    onclick="selectAllSchedules(<?php echo e($enrollment->id); ?>)"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Seleccionar Todas
                            </button>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $enrollment->paymentPlan->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <label class="block cursor-pointer">
                            <input type="checkbox" 
                                   class="schedule-checkbox hidden"
                                   data-enrollment="<?php echo e($enrollment->id); ?>"
                                   data-schedule-id="<?php echo e($schedule->id); ?>"
                                   data-installment="<?php echo e($schedule->installment_number); ?>"
                                   data-amount="<?php echo e($schedule->balance); ?>"
                                   data-due-date="<?php echo e($schedule->due_date->format('d/m/Y')); ?>"
                                   data-is-overdue="<?php echo e($schedule->is_overdue ? 'true' : 'false'); ?>"
                                   data-course-name="<?php echo e($enrollment->courseOffering->course->name); ?>"
                                   onchange="toggleScheduleSelection(this)">
                            <div class="schedule-card p-4 rounded-lg border-2 transition-all <?php echo e($schedule->is_overdue ? 'bg-red-50 border-red-300' : 'bg-blue-50 border-blue-200'); ?>">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="checkbox-icon w-6 h-6 border-2 rounded flex items-center justify-center <?php echo e($schedule->is_overdue ? 'border-red-400' : 'border-blue-400'); ?>">
                                                <svg class="w-4 h-4 text-white hidden checkmark" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo e($schedule->is_overdue ? 'bg-red-200 text-red-800' : 'bg-blue-600 text-white'); ?>">
                                                Cuota #<?php echo e($schedule->installment_number); ?>

                                            </span>
                                            <?php if($schedule->is_overdue): ?>
                                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded animate-pulse">⚠ VENCIDA</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Vence: <?php echo e($schedule->due_date->format('d/m/Y')); ?>

                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-display font-bold text-primary-dark">$<?php echo e(number_format($schedule->balance, 2)); ?></p>
                                        <p class="text-xs text-gray-500">Pendiente</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <p class="text-gray-500">No hay cuotas pendientes para este curso</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- RIGHT SIDE - Payment Summary (Ticket) -->
        <div class="lg:col-span-1">
            <div class="card-premium bg-gradient-to-b from-white to-gray-50 sticky top-6">
                <div class="bg-primary-dark text-white p-4 -m-6 mb-6 rounded-t-lg">
                    <h3 class="text-xl font-display font-semibold">Resumen de Pago</h3>
                </div>

                <form method="POST" action="<?php echo e(route('payments.store')); ?>" id="payment-form">
                    <?php echo csrf_field(); ?>
                    
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

                        <!-- Payment Method Toggle -->
                        <div class="flex items-center justify-between mb-4">
                            <label class="label-elegant mb-0">Método de Pago</label>
                            <button type="button"
                                    id="toggle-multiple-methods"
                                    onclick="toggleMultiplePaymentMethods()"
                                    class="text-sm px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-medium transition-all">
                                + Pagar con múltiples métodos
                            </button>
                        </div>

                        <!-- Single Payment Method (Default) -->
                        <div id="single-payment-method">
                            <div class="grid grid-cols-2 gap-2 mb-4">
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

                            <!-- Reference Number -->
                            <div id="reference-container" class="hidden">
                                <label for="reference_number" class="label-elegant">Número de Referencia</label>
                                <input type="text"
                                       name="reference_number"
                                       id="reference_number"
                                       class="input-elegant"
                                       placeholder="Ej: TRX123456">
                            </div>
                        </div>

                        <!-- Multiple Payment Methods -->
                        <div id="multiple-payment-methods" class="hidden">
                            <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-4 mb-3">
                                <div class="flex items-start space-x-2 mb-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800">Pago Dividido</p>
                                        <p class="text-xs text-blue-700">La suma de todos los métodos debe ser igual al total</p>
                                    </div>
                                </div>

                                <div id="payment-methods-list" class="space-y-3">
                                    <!-- Payment methods will be added here dynamically -->
                                </div>

                                <button type="button"
                                        onclick="addPaymentMethod()"
                                        class="w-full mt-3 py-2 border-2 border-dashed border-blue-400 text-blue-700 rounded-lg hover:bg-blue-100 transition-all font-medium">
                                    + Agregar Método
                                </button>

                                <div class="mt-4 pt-3 border-t-2 border-blue-300">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700">Total Métodos:</span>
                                        <span class="text-xl font-bold" id="methods-sum">$0.00</span>
                                    </div>
                                    <div id="methods-validation" class="mt-2 text-xs hidden">
                                        <!-- Validation message -->
                                    </div>
                                </div>
                            </div>
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

<?php $__env->startPush('scripts'); ?>
<script>
// Variables globales
let searchTimeout = null;
let selectedSchedules = [];
let currentEnrollmentId = null;
let paymentMethods = [];
let paymentMethodCounter = 0;
let useMultipleMethods = false;

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
            const response = await fetch(`<?php echo e(route('api.payments.search-students')); ?>?q=${encodeURIComponent(query)}`);
            const students = await response.json();
            
            if (students.length === 0) {
                resultsDiv.innerHTML = '<div class="p-6 text-center text-gray-500">No se encontraron estudiantes con pagos pendientes</div>';
                resultsDiv.classList.remove('hidden');
                return;
            }
            
            resultsDiv.innerHTML = students.map(student => `
                <a href="<?php echo e(route('pos.index')); ?>?student_id=${student.id}"
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

// Toggle between single and multiple payment methods
window.toggleMultiplePaymentMethods = function() {
    useMultipleMethods = !useMultipleMethods;
    const singleMethodDiv = document.getElementById('single-payment-method');
    const multipleMethodsDiv = document.getElementById('multiple-payment-methods');
    const toggleBtn = document.getElementById('toggle-multiple-methods');

    if (useMultipleMethods) {
        singleMethodDiv.classList.add('hidden');
        multipleMethodsDiv.classList.remove('hidden');
        toggleBtn.textContent = '← Un solo método';
        toggleBtn.classList.remove('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');
        toggleBtn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');

        // Clear payment methods and add first one
        paymentMethods = [];
        document.getElementById('payment-methods-list').innerHTML = '';
        addPaymentMethod();
    } else {
        singleMethodDiv.classList.remove('hidden');
        multipleMethodsDiv.classList.add('hidden');
        toggleBtn.textContent = '+ Pagar con múltiples métodos';
        toggleBtn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        toggleBtn.classList.add('bg-blue-100', 'text-blue-700', 'hover:bg-blue-200');

        // Clear payment methods
        paymentMethods = [];
    }
};

// Add a payment method
window.addPaymentMethod = function() {
    const id = ++paymentMethodCounter;
    const methodHtml = `
        <div class="payment-method-item bg-white border-2 border-gray-300 rounded-lg p-3" data-method-id="${id}">
            <div class="flex items-start justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Método #${paymentMethods.length + 1}</span>
                <button type="button"
                        onclick="removePaymentMethod(${id})"
                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                    ✕ Quitar
                </button>
            </div>

            <div class="space-y-2">
                <select class="input-elegant text-sm payment-method-select" data-method-id="${id}" onchange="updateMethodsSum()">
                    <option value="">Seleccionar método...</option>
                    <option value="efectivo">💵 Efectivo</option>
                    <option value="transferencia">🏦 Transferencia</option>
                    <option value="tarjeta_credito">💳 Tarjeta Crédito</option>
                    <option value="tarjeta_debito">💳 Tarjeta Débito</option>
                    <option value="yappy">📱 Yappy</option>
                    <option value="otro">📋 Otro</option>
                </select>

                <input type="number"
                       class="input-elegant text-sm payment-method-amount"
                       data-method-id="${id}"
                       placeholder="Monto $"
                       step="0.01"
                       min="0.01"
                       oninput="updateMethodsSum()">

                <input type="text"
                       class="input-elegant text-sm payment-method-reference"
                       data-method-id="${id}"
                       placeholder="Referencia (opcional)">
            </div>
        </div>
    `;

    document.getElementById('payment-methods-list').insertAdjacentHTML('beforeend', methodHtml);
    paymentMethods.push({ id, method: '', amount: 0, reference: '' });
};

// Remove a payment method
window.removePaymentMethod = function(id) {
    const element = document.querySelector(`.payment-method-item[data-method-id="${id}"]`);
    if (element) {
        element.remove();
        paymentMethods = paymentMethods.filter(m => m.id !== id);

        // Renumber remaining methods
        document.querySelectorAll('.payment-method-item').forEach((item, index) => {
            item.querySelector('span').textContent = `Método #${index + 1}`;
        });

        updateMethodsSum();
    }
};

// Update the sum of all payment methods
window.updateMethodsSum = function() {
    let sum = 0;

    document.querySelectorAll('.payment-method-amount').forEach(input => {
        const amount = parseFloat(input.value) || 0;
        sum += amount;
    });

    document.getElementById('methods-sum').textContent = '$' + sum.toFixed(2);

    // Validate against total
    const totalAmount = parseFloat(document.getElementById('amount').value) || 0;
    const validationDiv = document.getElementById('methods-validation');

    if (sum > 0 && totalAmount > 0) {
        const diff = Math.abs(sum - totalAmount);

        if (diff < 0.01) {
            // Valid
            validationDiv.innerHTML = '<span class="text-green-700 font-semibold">✓ Los métodos suman correctamente</span>';
            validationDiv.classList.remove('hidden', 'text-red-700');
            validationDiv.classList.add('text-green-700');
        } else if (sum < totalAmount) {
            // Insufficient
            validationDiv.innerHTML = '<span class="text-yellow-700 font-semibold">⚠ Falta $' + (totalAmount - sum).toFixed(2) + '</span>';
            validationDiv.classList.remove('hidden', 'text-green-700');
            validationDiv.classList.add('text-yellow-700');
        } else {
            // Exceeds
            validationDiv.innerHTML = '<span class="text-red-700 font-semibold">✕ Excede por $' + (sum - totalAmount).toFixed(2) + '</span>';
            validationDiv.classList.remove('hidden', 'text-green-700');
            validationDiv.classList.add('text-red-700');
        }
    } else {
        validationDiv.classList.add('hidden');
    }
};

// Override form submission to handle multiple payment methods
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (useMultipleMethods) {
                e.preventDefault();

                // Collect payment methods data
                const methodsData = [];
                document.querySelectorAll('.payment-method-item').forEach(item => {
                    const id = item.dataset.methodId;
                    const method = item.querySelector('.payment-method-select').value;
                    const amount = parseFloat(item.querySelector('.payment-method-amount').value) || 0;
                    const reference = item.querySelector('.payment-method-reference').value;

                    if (method && amount > 0) {
                        methodsData.push({ method, amount, reference_number: reference });
                    }
                });

                if (methodsData.length === 0) {
                    alert('Debes agregar al menos un método de pago');
                    return;
                }

                // Validate sum
                const totalAmount = parseFloat(document.getElementById('amount').value) || 0;
                const sum = methodsData.reduce((acc, m) => acc + m.amount, 0);

                if (Math.abs(sum - totalAmount) > 0.01) {
                    alert('La suma de los métodos de pago debe ser igual al total');
                    return;
                }

                // Add payment methods as hidden inputs
                methodsData.forEach((method, index) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `payment_methods[${index}][method]`;
                    input.value = method.method;
                    form.appendChild(input);

                    const amountInput = document.createElement('input');
                    amountInput.type = 'hidden';
                    amountInput.name = `payment_methods[${index}][amount]`;
                    amountInput.value = method.amount;
                    form.appendChild(amountInput);

                    if (method.reference_number) {
                        const refInput = document.createElement('input');
                        refInput.type = 'hidden';
                        refInput.name = `payment_methods[${index}][reference_number]`;
                        refInput.value = method.reference_number;
                        form.appendChild(refInput);
                    }
                });

                // Submit form
                form.submit();
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/pos/index.blade.php ENDPATH**/ ?>