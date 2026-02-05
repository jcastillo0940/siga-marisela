<?php $__env->startSection('title', 'Pagos Registrados'); ?>
<?php $__env->startSection('page-title', 'Historial de Pagos'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra los pagos registrados en caja
            </p>
        </div>
        
        <div class="flex space-x-4 w-full sm:w-auto">
            <?php if(auth()->user()->hasPermission('payments.create')): ?>
            <a href="<?php echo e(route('payments.create')); ?>" class="btn-primary text-center flex-1 sm:flex-none">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Registrar Pago
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-6">
        <form method="GET" action="<?php echo e(route('payments.index')); ?>">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Date From -->
                <div>
                    <label for="date_from" class="label-elegant">Desde</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="<?php echo e(request('date_from')); ?>"
                           class="input-elegant">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="label-elegant">Hasta</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="<?php echo e(request('date_to')); ?>"
                           class="input-elegant">
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="label-elegant">Método de Pago</label>
                    <select id="payment_method" 
                            name="payment_method" 
                            class="input-elegant">
                        <option value="">Todos</option>
                        <option value="efectivo" <?php echo e(request('payment_method') == 'efectivo' ? 'selected' : ''); ?>>Efectivo</option>
                        <option value="transferencia" <?php echo e(request('payment_method') == 'transferencia' ? 'selected' : ''); ?>>Transferencia</option>
                        <option value="tarjeta_credito" <?php echo e(request('payment_method') == 'tarjeta_credito' ? 'selected' : ''); ?>>Tarjeta de Crédito</option>
                        <option value="tarjeta_debito" <?php echo e(request('payment_method') == 'tarjeta_debito' ? 'selected' : ''); ?>>Tarjeta de Débito</option>
                        <option value="yappy" <?php echo e(request('payment_method') == 'yappy' ? 'selected' : ''); ?>>Yappy</option>
                        <option value="otro" <?php echo e(request('payment_method') == 'otro' ? 'selected' : ''); ?>>Otro</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn-primary flex-1">
                        Filtrar
                    </button>
                    <a href="<?php echo e(route('payments.index')); ?>" class="btn-secondary">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Pagos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-primary-dark"><?php echo e($payments->total()); ?></p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Hoy</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-blue-600">
                    <?php echo e($payments->where('payment_date', today())->count()); ?>

                </p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Recaudado</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-green-600">
                    $<?php echo e(number_format($payments->sum('amount'), 2)); ?>

                </p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Promedio</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-purple-600">
                    $<?php echo e($payments->count() > 0 ? number_format($payments->sum('amount') / $payments->count(), 2) : '0.00'); ?>

                </p>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card-premium">
        <?php if($payments->isEmpty()): ?>
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay pagos registrados</p>
                <?php if(auth()->user()->hasPermission('payments.create')): ?>
                <a href="<?php echo e(route('payments.create')); ?>" class="btn-primary mt-4 inline-block">
                    Registrar Primer Pago
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Recibido Por</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <!-- Code -->
                            <td>
                                <span class="text-sm font-mono text-gray-600"><?php echo e($payment->payment_code); ?></span>
                            </td>

                            <!-- Date -->
                            <td>
                                <span class="text-sm text-gray-600"><?php echo e($payment->payment_date->format('d/m/Y')); ?></span>
                            </td>

                            <!-- Student -->
                            <td>
                                <a href="<?php echo e(route('students.show', $payment->enrollment->student->id)); ?>" class="font-medium text-accent-red hover:underline">
                                    <?php echo e($payment->enrollment->student->full_name); ?>

                                </a>
                            </td>

                            <!-- Course -->
                            <td>
                                <div>
                                    <p class="font-medium text-primary-dark text-sm"><?php echo e($payment->enrollment->courseOffering->course->name); ?></p>
                                    <?php if($payment->enrollment->courseOffering->is_generation && $payment->enrollment->courseOffering->generation_name): ?>
                                        <p class="text-xs text-gray-500"><?php echo e($payment->enrollment->courseOffering->generation_name); ?></p>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td>
                                <span class="text-lg font-display font-semibold text-green-600"><?php echo e($payment->formatted_amount); ?></span>
                            </td>

                            <!-- Payment Method -->
                            <td>
                                <span class="text-sm text-gray-600"><?php echo e($payment->payment_method_label); ?></span>
                                <?php if($payment->payment_method === 'multiple' && $payment->paymentMethods->isNotEmpty()): ?>
                                    <p class="text-xs text-blue-600 mt-1">
                                        <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <?php echo e($payment->paymentMethods->count()); ?> métodos
                                    </p>
                                <?php elseif($payment->reference_number): ?>
                                    <p class="text-xs text-gray-400">Ref: <?php echo e($payment->reference_number); ?></p>
                                <?php endif; ?>
                            </td>

                            <!-- Received By -->
                            <td>
                                <span class="text-sm text-gray-600"><?php echo e($payment->receivedBy->name ?? 'N/A'); ?></span>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    <?php if(auth()->user()->hasPermission('payments.view')): ?>
                                    <a href="<?php echo e(route('payments.show', $payment->id)); ?>" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Delete -->
                                    <?php if(auth()->user()->hasPermission('payments.delete')): ?>
                                    <form method="POST" action="<?php echo e(route('payments.destroy', $payment->id)); ?>" class="inline" id="delete-form-<?php echo e($payment->id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar este pago? Esta acción revertirá el pago del cronograma.', function() { document.getElementById('delete-form-<?php echo e($payment->id); ?>').submit(); })">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($payments->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/payments/index.blade.php ENDPATH**/ ?>