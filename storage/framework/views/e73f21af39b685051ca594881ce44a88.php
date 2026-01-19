

<?php $__env->startSection('title', 'Leads'); ?>
<?php $__env->startSection('page-title', 'Gestión de Leads'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra los prospectos, inscripciones web y verificaciones de pago.
            </p>
        </div>
        
        <div class="w-full sm:w-auto">
            <?php if(auth()->user()->hasPermission('leads.create')): ?>
            <a href="<?php echo e(route('leads.create')); ?>" class="btn-primary w-full sm:w-auto text-center block sm:inline-block">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Lead
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-primary-dark"><?php echo e($leads->count()); ?></p>
            </div>
        </div>

        <div class="card-premium border-l-4 border-accent-red">
            <div class="text-center">
                <p class="text-xs text-red-500 uppercase tracking-wider mb-1 font-bold">Por Verificar</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-red-600">
                    <?php echo e($leads->where('payment_status', 'pending')->whereNotNull('payment_receipt_path')->count()); ?>

                </p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Nuevos</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-blue-600"><?php echo e($leads->where('status', 'nuevo')->count()); ?></p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Interesados</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-purple-600"><?php echo e($leads->where('status', 'interesado')->count()); ?></p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Inscritos</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-green-600"><?php echo e($leads->where('status', 'inscrito')->count()); ?></p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Perdidos</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-gray-400"><?php echo e($leads->where('status', 'perdido')->count()); ?></p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Convertidos</p>
                <p class="text-xl sm:text-2xl font-display font-semibold text-green-700"><?php echo e($leads->whereNotNull('converted_to_student_id')->count()); ?></p>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <?php if($leads->isEmpty()): ?>
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay leads registrados</p>
                <?php if(auth()->user()->hasPermission('leads.create')): ?>
                <a href="<?php echo e(route('leads.create')); ?>" class="btn-primary mt-4 inline-block">
                    Crear Primer Lead
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Prospecto</th>
                            <th>Contacto</th>
                            <th>Curso / Fuente</th>
                            <th>Estado Lead</th>
                            <th>Pago</th>
                            <th>Asignado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <?php if($lead->student_photo): ?>
                                        <img src="<?php echo e(asset('storage/' . $lead->student_photo)); ?>" class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                            <?php echo e(substr($lead->first_name, 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-primary-dark leading-none"><?php echo e($lead->full_name); ?></p>
                                        <p class="text-xs text-gray-400 mt-1"><?php echo e($lead->age ?? 'N/A'); ?> años</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <a href="mailto:<?php echo e($lead->email); ?>" class="text-accent-red hover:underline block text-sm">
                                    <?php echo e($lead->email); ?>

                                </a>
                                <p class="text-gray-600 text-xs mt-1"><?php echo e($lead->phone); ?></p>
                            </td>

                            <td>
                                <?php if($lead->courseOffering): ?>
                                    <span class="text-sm font-medium text-primary-dark block"><?php echo e($lead->courseOffering->course->name); ?></span>
                                <?php endif; ?>
                                <span class="text-xs text-gray-500 uppercase tracking-tighter"><?php echo e($lead->source); ?></span>
                            </td>

                            <td>
                                <?php
                                    $statusColors = [
                                        'nuevo' => 'badge-info',
                                        'contactado' => 'badge-warning',
                                        'interesado' => 'badge badge-info',
                                        'negociacion' => 'badge badge-warning',
                                        'inscrito' => 'badge-success',
                                        'perdido' => 'badge-danger'
                                    ];
                                ?>
                                <span class="badge <?php echo e($statusColors[$lead->status] ?? 'badge-info'); ?>">
                                    <?php echo e(ucfirst($lead->status)); ?>

                                </span>
                            </td>

                            <td>
                                <?php if($lead->payment_receipt_path && $lead->payment_status === 'pending'): ?>
                                    <span class="flex items-center text-red-600 text-xs font-bold animate-pulse">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                        </svg>
                                        POR VERIFICAR
                                    </span>
                                <?php elseif($lead->payment_status === 'verified'): ?>
                                    <span class="text-green-600 text-xs font-medium flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        Pagado
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">Sin pago</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="text-xs text-gray-600"><?php echo e($lead->assignedUser?->name ?? '---'); ?></span>
                            </td>

                            <td>
                                <div class="flex items-center justify-center space-x-1">
                                    <?php if(auth()->user()->hasPermission('leads.view')): ?>
                                    <a href="<?php echo e(route('leads.show', $lead->id)); ?>" 
                                       class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="Ver detalles y verificar pago">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if(auth()->user()->hasPermission('leads.edit')): ?>
                                    <a href="<?php echo e(route('leads.edit', $lead->id)); ?>" 
                                       class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <?php if(auth()->user()->hasPermission('leads.delete')): ?>
                                    <form method="POST" action="<?php echo e(route('leads.destroy', $lead->id)); ?>" class="inline" id="delete-form-<?php echo e($lead->id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" 
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                onclick="showConfirmModal('¿Eliminar prospecto <?php echo e($lead->full_name); ?>?', function() { document.getElementById('delete-form-<?php echo e($lead->id); ?>').submit(); })">
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
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/leads/index.blade.php ENDPATH**/ ?>