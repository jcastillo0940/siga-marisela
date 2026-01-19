

<?php $__env->startSection('title', 'Registrar Pago'); ?>
<?php $__env->startSection('page-title', 'Punto de Venta (POS)'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('payments.index')); ?>" class="text-gray-500 hover:text-accent-red transition-colors">
                        Pagos
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Registrar Pago</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Search -->
            <?php if(!$student): ?>
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Buscar Estudiante
                </h2>

                <div class="relative">
                    <label for="student_search" class="label-elegant">Nombre o Cédula del Estudiante</label>
                    <input type="text" 
                           id="student_search" 
                           class="input-elegant"
                           placeholder="Buscar estudiante con pagos pendientes..."
                           autocomplete="off"
                           oninput="searchStudents(this.value)">
                    
                    <!-- Search Results Dropdown -->
                    <div id="student-results" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg hidden max-h-64 overflow-y-auto">
                        <!-- Results will be populated here -->
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Student Info -->
            <div class="card-premium bg-gradient-to-r from-green-50 to-blue-50 border border-green-200">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold shadow-lg">
                            <?php echo e(substr($student->first_name, 0, 1)); ?>

                        </div>
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-display font-semibold text-primary-dark">
                                <?php echo e($student->full_name); ?>

                            </h2>
                            <p class="text-gray-600 mt-1"><?php echo e($student->identification); ?></p>
                            <p class="text-sm text-gray-500 mt-1"><?php echo e($student->email); ?></p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('payments.create')); ?>" class="btn-secondary text-sm">
                        Cambiar Estudiante
                    </a>
                </div>
            </div>

            <?php if($enrollments->isEmpty()): ?>
            <!-- No Enrollments -->
            <div class="card-premium">
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Este estudiante no tiene pagos pendientes</p>
                </div>
            </div>
            <?php else: ?>
            <!-- Payment Form -->
            <form method="POST" action="<?php echo e(route('payments.store')); ?>">
                <?php echo csrf_field(); ?>

                <div class="space-y-6">
                    <!-- Select Enrollment -->
                    <div class="card-premium">
                        <h3 class="text-xl font-display font-semibold text-primary-dark mb-4">
                            Seleccionar Curso
                        </h3>

                        <div class="space-y-3">
                            <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-start p-4 border-2 rounded cursor-pointer transition-all hover:border-accent-red <?php echo e(old('enrollment_id') == $enrollment->id ? 'border-accent-red bg-red-50' : 'border-gray-200'); ?>">
                                <input type="radio" 
                                       name="enrollment_id" 
                                       value="<?php echo e($enrollment->id); ?>" 
                                       <?php echo e(old('enrollment_id') == $enrollment->id ? 'checked' : ''); ?>

                                       onchange="loadEnrollmentSchedule(<?php echo e($enrollment->id); ?>)"
                                       class="w-5 h-5 text-accent-red mt-1"
                                       required>
                                <div class="ml-3 flex-1">
                                    <p class="font-semibold text-primary-dark"><?php echo e($enrollment->courseOffering->course->name); ?></p>
                                    <?php if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name): ?>
                                        <p class="text-sm text-gray-600"><?php echo e($enrollment->courseOffering->generation_name); ?></p>
                                    <?php endif; ?>
                                    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                                        <div>
                                            <span class="text-gray-500">Total:</span>
                                            <span class="font-medium text-primary-dark">$<?php echo e(number_format($enrollment->paymentPlan->total_amount, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Pagado:</span>
                                            <span class="font-medium text-green-600">$<?php echo e(number_format($enrollment->paymentPlan->total_paid, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Pendiente:</span>
                                            <span class="font-medium text-red-600">$<?php echo e(number_format($enrollment->paymentPlan->balance, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Cuotas pendientes:</span>
                                            <span class="font-medium text-orange-600"><?php echo e($enrollment->paymentPlan->schedules->count()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['enrollment_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Payment Schedule -->
                    <div id="schedule-container" class="hidden">
                        <div class="card-premium">
                            <h3 class="text-xl font-display font-semibold text-primary-dark mb-4">
                                Cronograma de Cuotas
                            </h3>

                            <div id="schedule-list" class="space-y-3">
                                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="enrollment-schedules hidden" data-enrollment="<?php echo e($enrollment->id); ?>">
                                    <?php $__currentLoopData = $enrollment->paymentPlan->schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start p-4 border-2 rounded cursor-pointer transition-all hover:border-blue-500 <?php echo e($schedule->is_overdue ? 'border-red-300 bg-red-50' : 'border-gray-200'); ?>">
                                        <input type="radio" 
                                               name="payment_schedule_id" 
                                               value="<?php echo e($schedule->id); ?>" 
                                               data-amount="<?php echo e($schedule->balance); ?>"
                                               onchange="updatePaymentAmount(<?php echo e($schedule->balance); ?>)"
                                               class="w-5 h-5 text-blue-600 mt-1">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="font-semibold text-primary-dark">Cuota #<?php echo e($schedule->installment_number); ?></p>
                                                <?php if($schedule->is_overdue): ?>
                                                    <span class="badge badge-danger">Vencida</span>
                                                <?php elseif($schedule->status === 'parcial'): ?>
                                                    <span class="badge badge-warning">Parcial</span>
                                                <?php else: ?>
                                                    <span class="badge badge-info">Pendiente</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mt-2 grid grid-cols-3 gap-2 text-sm">
                                                <div>
                                                    <span class="text-gray-500">Vencimiento:</span>
                                                    <p class="font-medium text-primary-dark"><?php echo e($schedule->due_date->format('d/m/Y')); ?></p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Monto:</span>
                                                    <p class="font-medium text-primary-dark">$<?php echo e(number_format($schedule->amount, 2)); ?></p>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500">Pendiente:</span>
                                                    <p class="font-medium text-red-600">$<?php echo e(number_format($schedule->balance, 2)); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <!-- Pago Sin Asignar -->
                                    <label class="flex items-start p-4 border-2 border-dashed border-gray-300 rounded cursor-pointer transition-all hover:border-purple-500">
                                        <input type="radio" 
                                               name="payment_schedule_id" 
                                               value=""
                                               onchange="document.getElementById('amount').focus()"
                                               class="w-5 h-5 text-purple-600 mt-1">
                                        <div class="ml-3 flex-1">
                                            <p class="font-semibold text-purple-600">Pago sin asignar a cuota específica</p>
                                            <p class="text-sm text-gray-600 mt-1">Se aplicará automáticamente a las cuotas pendientes en orden</p>
                                        </div>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="card-premium">
                        <h3 class="text-xl font-display font-semibold text-primary-dark mb-4">
                            Detalles del Pago
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Amount -->
                            <div>
                                <label for="amount" class="label-elegant">Monto a Pagar (USD) *</label>
                                <input type="number" 
                                       id="amount" 
                                       name="amount" 
                                       value="<?php echo e(old('amount')); ?>"
                                       class="input-elegant <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       step="0.01"
                                       min="0.01"
                                       required>
                                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="label-elegant">Método de Pago *</label>
                                <select id="payment_method" 
                                        name="payment_method" 
                                        class="input-elegant <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        onchange="toggleReferenceNumber()"
                                        required>
                                    <option value="">Seleccionar método</option>
                                    <option value="efectivo" <?php echo e(old('payment_method') == 'efectivo' ? 'selected' : ''); ?>>Efectivo</option>
                                    <option value="transferencia" <?php echo e(old('payment_method') == 'transferencia' ? 'selected' : ''); ?>>Transferencia</option>
                                    <option value="tarjeta_credito" <?php echo e(old('payment_method') == 'tarjeta_credito' ? 'selected' : ''); ?>>Tarjeta de Crédito</option>
                                    <option value="tarjeta_debito" <?php echo e(old('payment_method') == 'tarjeta_debito' ? 'selected' : ''); ?>>Tarjeta de Débito</option>
                                    <option value="yappy" <?php echo e(old('payment_method') == 'yappy' ? 'selected' : ''); ?>>Yappy</option>
                                    <option value="otro" <?php echo e(old('payment_method') == 'otro' ? 'selected' : ''); ?>>Otro</option>
                                </select>
                                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Reference Number -->
                            <div id="reference-container" class="hidden">
                                <label for="reference_number" class="label-elegant">Número de Referencia</label>
                                <input type="text" 
                                       id="reference_number" 
                                       name="reference_number" 
                                       value="<?php echo e(old('reference_number')); ?>"
                                       class="input-elegant <?php $__errorArgs = ['reference_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       placeholder="Ej: TRX123456">
                                <?php $__errorArgs = ['reference_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Notes -->
                            <div class="<?php echo e(old('payment_method') && old('payment_method') != 'efectivo' ? '' : 'md:col-span-2'); ?>">
                                <label for="notes" class="label-elegant">Notas</label>
                                <textarea id="notes" 
                                          name="notes" 
                                          rows="3"
                                          class="input-elegant <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                          placeholder="Notas adicionales sobre el pago..."><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-premium">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="<?php echo e(route('payments.index')); ?>" class="btn-secondary text-center">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Registrar Pago
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Help -->
            <div class="card-premium">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-primary-dark">
                        Información
                    </h3>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600">
                    <p>
                        <strong class="text-primary-dark">1. Buscar estudiante:</strong> 
                        Escriba el nombre o cédula del estudiante.
                    </p>
                    <p>
                        <strong class="text-primary-dark">2. Seleccionar curso:</strong> 
                        Elija el curso al que desea aplicar el pago.
                    </p>
                    <p>
                        <strong class="text-primary-dark">3. Seleccionar cuota:</strong> 
                        Elija una cuota específica o deje sin asignar para aplicar automáticamente.
                    </p>
                    <p>
                        <strong class="text-primary-dark">4. Registrar pago:</strong> 
                        Complete los detalles y registre el pago.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let searchTimeout = null;

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
                resultsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No se encontraron estudiantes con pagos pendientes</div>';
                resultsDiv.classList.remove('hidden');
                return;
            }
            
            resultsDiv.innerHTML = students.map(student => `
                <a href="<?php echo e(route('payments.create')); ?>?student_id=${student.id}"
                   class="block p-3 hover:bg-gray-100 cursor-pointer border-b last:border-b-0">
                    <p class="font-medium text-primary-dark">${student.first_name} ${student.last_name}</p>
                    <p class="text-sm text-gray-600">${student.identification}</p>
                </a>
            `).join('');
            
            resultsDiv.classList.remove('hidden');
            
        } catch (error) {
            console.error('Error searching students:', error);
        }
    }, 300);
}

// Load enrollment schedule
function loadEnrollmentSchedule(enrollmentId) {
    // Hide all schedules
    document.querySelectorAll('.enrollment-schedules').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show selected enrollment schedule
    const selectedSchedule = document.querySelector(`.enrollment-schedules[data-enrollment="${enrollmentId}"]`);
    if (selectedSchedule) {
        selectedSchedule.classList.remove('hidden');
        document.getElementById('schedule-container').classList.remove('hidden');
    }
}

// Update payment amount
function updatePaymentAmount(amount) {
    document.getElementById('amount').value = amount.toFixed(2);
}

// Toggle reference number field
function toggleReferenceNumber() {
    const method = document.getElementById('payment_method').value;
    const container = document.getElementById('reference-container');
    
    if (method && method !== 'efectivo') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const searchInput = document.getElementById('student_search');
    const resultsDiv = document.getElementById('student-results');
    
    if (searchInput && resultsDiv && !searchInput.contains(event.target) && !resultsDiv.contains(event.target)) {
        resultsDiv.classList.add('hidden');
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    toggleReferenceNumber();
    
    // Auto-select first enrollment if only one exists
    const enrollmentRadios = document.querySelectorAll('input[name="enrollment_id"]');
    if (enrollmentRadios.length === 1) {
        enrollmentRadios[0].checked = true;
        loadEnrollmentSchedule(enrollmentRadios[0].value);
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/payments/create.blade.php ENDPATH**/ ?>