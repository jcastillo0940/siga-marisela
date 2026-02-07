

<?php $__env->startSection('title', 'Detalles del Estudiante'); ?>
<?php $__env->startSection('page-title', 'Detalles del Estudiante'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('students.index')); ?>" class="text-gray-500 hover:text-accent-red transition-colors">
                        Estudiantes
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium"><?php echo e($student->full_name); ?></span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-accent-red rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant">
                <?php echo e(substr($student->first_name, 0, 1)); ?>

            </div>
            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    <?php echo e($student->full_name); ?>

                </h1>
                <div class="flex flex-wrap items-center gap-3 text-gray-600">
                    <a href="mailto:<?php echo e($student->email); ?>" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <?php echo e($student->email); ?>

                    </a>
                    <?php if($student->phone): ?>
                    <a href="tel:<?php echo e($student->phone); ?>" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <?php echo e($student->phone); ?>

                    </a>
                    <?php endif; ?>
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <?php
                        $statusColors = [
                            'prospecto' => 'badge-warning',
                            'activo' => 'badge-success',
                            'inactivo' => 'badge-danger',
                            'graduado' => 'badge-info',
                            'retirado' => 'badge-danger'
                        ];
                    ?>
                    <span class="badge <?php echo e($statusColors[$student->status] ?? 'badge-info'); ?>">
                        <?php echo e(ucfirst($student->status)); ?>

                    </span>
                    <?php if(!$student->is_active): ?>
                        <span class="badge badge-danger">Inactivo</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            <?php if(auth()->user()->hasPermission('students.edit')): ?>
            <a href="<?php echo e(route('students.edit', $student->id)); ?>" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            <?php endif; ?>

            <a href="<?php echo e(route('students.index')); ?>" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 space-y-6 lg:space-y-8">
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información Personal
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Nombre Completo</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->full_name); ?></p>
                    </div>

                    <div>
                        <label class="label-elegant">Correo Electrónico</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->email); ?></p>
                    </div>

                    <div>
                        <label class="label-elegant">Género</label>
                        <p class="text-primary-dark font-medium">
                            <?php
                                $genderLabels = [
                                    'male' => 'Masculino',
                                    'female' => 'Femenino',
                                    'other' => 'Otro'
                                ];
                            ?>
                            <?php echo e($genderLabels[$student->gender] ?? 'No especificado'); ?>

                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Fecha de Nacimiento</label>
                        <p class="text-primary-dark font-medium">
                            <?php if($student->birth_date): ?>
                                <?php echo e($student->birth_date->format('d/m/Y')); ?>

                                <span class="text-sm text-gray-500">(<?php echo e($student->birth_date->age); ?> años)</span>
                            <?php else: ?>
                                No registrada
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono Principal</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->phone ?? 'No registrado'); ?></p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono Secundario</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->phone_secondary ?? 'No registrado'); ?></p>
                    </div>

                    <div>
                        <label class="label-elegant">Identificación</label>
                        <p class="text-primary-dark font-medium">
                            <?php if($student->identification): ?>
                                <?php echo e($student->identification); ?>

                                <?php if($student->identification_type): ?>
                                    <span class="text-sm text-gray-500">(<?php echo e(ucfirst($student->identification_type)); ?>)</span>
                                <?php endif; ?>
                            <?php else: ?>
                                No registrada
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <label class="label-elegant">Ubicación</label>
                        <p class="text-primary-dark font-medium">
                            <?php echo e($student->city ?? 'N/A'); ?><?php echo e($student->city && $student->country ? ', ' : ''); ?><?php echo e($student->country ?? ''); ?>

                        </p>
                    </div>

                    <?php if($student->address): ?>
                    <div class="md:col-span-2">
                        <label class="label-elegant">Dirección</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->address); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($student->emergency_contact_name || $student->emergency_contact_phone): ?>
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Contacto de Emergencia
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="label-elegant">Nombre</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->emergency_contact_name ?? 'No registrado'); ?></p>
                    </div>

                    <div>
                        <label class="label-elegant">Teléfono</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->emergency_contact_phone ?? 'No registrado'); ?></p>
                    </div>

                    <div>
                        <label class="label-elegant">Parentesco</label>
                        <p class="text-primary-dark font-medium"><?php echo e($student->emergency_contact_relationship ?? 'No especificado'); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($student->medical_notes || $student->emotional_notes || $student->goals): ?>
            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Información Adicional
                </h2>

                <div class="space-y-6">
                    <?php if($student->medical_notes): ?>
                    <div>
                        <label class="label-elegant">Notas Médicas</label>
                        <div class="bg-neutral-bg p-4 rounded">
                            <p class="text-primary-dark text-sm sm:text-base"><?php echo e($student->medical_notes); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($student->emotional_notes): ?>
                    <div>
                        <label class="label-elegant">Notas Emocionales</label>
                        <div class="bg-neutral-bg p-4 rounded">
                            <p class="text-primary-dark text-sm sm:text-base"><?php echo e($student->emotional_notes); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($student->goals): ?>
                    <div>
                        <label class="label-elegant">Metas y Objetivos</label>
                        <div class="bg-neutral-bg p-4 rounded">
                            <p class="text-primary-dark text-sm sm:text-base"><?php echo e($student->goals); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="card-premium">
                <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                    Inscripciones
                </h2>

                <?php if($student->enrollments->isEmpty()): ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">No hay inscripciones registradas</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $student->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded p-4 hover:border-accent-red transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <a href="<?php echo e(route('enrollments.show', $enrollment->id)); ?>" class="text-lg font-semibold text-accent-red hover:underline">
                                        <?php echo e($enrollment->courseOffering->course->name ?? 'Curso no especificado'); ?>

                                    </a>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-sm">
                                        <div>
                                            <span class="text-gray-500">Total a Pagar:</span>
                                            <p class="font-medium text-primary-dark">$<?php echo e(number_format($enrollment->final_price, 2)); ?></p>
                                        </div>
                                    </div>

                                    <?php if($enrollment->has_payment_plan && $enrollment->paymentPlan): ?>
                                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded p-3">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600 font-medium">Total Pagado:</span>
                                                
                                                <p class="font-bold text-green-600 text-base">$<?php echo e(number_format($enrollment->total_paid, 2)); ?></p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 font-medium">Saldo Pendiente:</span>
                                                
                                                <p class="font-bold text-red-600 text-base">
                                                    $<?php echo e(number_format($enrollment->final_price - $enrollment->total_paid, 2)); ?>

                                                </p>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 font-medium">Estado Plan:</span>
                                                <div class="mt-1">
                                                    <span class="badge <?php echo e($planStatusColors[$enrollment->paymentPlan->status] ?? 'badge-info'); ?>">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $enrollment->paymentPlan->status))); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Información General
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Estudiante</span>
                        <span class="font-display font-semibold text-primary-dark">#<?php echo e($student->id); ?></span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado</span>
                        <span class="badge <?php echo e($statusColors[$student->status] ?? 'badge-info'); ?>">
                            <?php echo e(ucfirst($student->status)); ?>

                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Inscripciones</span>
                        <span class="font-display font-semibold text-primary-dark"><?php echo e($student->enrollments->count()); ?></span>
                    </div>
                </div>
            </div>

            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Registro
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Fecha de registro</label>
                        <p class="text-primary-dark font-medium mt-1">
                            <?php echo e($student->created_at->format('d/m/Y H:i')); ?>

                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?php echo e($student->created_at->diffForHumans()); ?>

                        </p>
                    </div>

                    <div class="divider-elegant"></div>

                    <div>
                        <label class="text-gray-500 text-xs uppercase tracking-wider">Última actualización</label>
                        <p class="text-primary-dark font-medium mt-1">
                            <?php echo e($student->updated_at->format('d/m/Y H:i')); ?>

                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?php echo e($student->updated_at->diffForHumans()); ?>

                        </p>
                    </div>
                </div>
            </div>

            <?php if(auth()->user()->hasPermission('students.edit')): ?>
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                    Acciones Rápidas
                </h3>
                
                <div class="space-y-2">
                    <a href="<?php echo e(route('students.edit', $student->id)); ?>" 
                       class="flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-primary-dark font-medium">Editar información</span>
                    </a>

                    <form method="POST" action="<?php echo e(route('students.toggle-status', $student->id)); ?>" id="toggle-status-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="button" 
                                class="w-full flex items-center p-3 bg-neutral-bg rounded hover:bg-gray-100 transition-colors text-sm"
                                onclick="showConfirmModal('¿Estás seguro de cambiar el estado de <?php echo e($student->full_name); ?>?', function() { document.getElementById('toggle-status-form').submit(); })">
                            <?php if($student->is_active): ?>
                                <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Desactivar estudiante</span>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-primary-dark font-medium">Activar estudiante</span>
                            <?php endif; ?>
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/students/show.blade.php ENDPATH**/ ?>