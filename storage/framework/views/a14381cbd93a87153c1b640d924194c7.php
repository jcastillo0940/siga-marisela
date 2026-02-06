

<?php $__env->startSection('title', 'Menús de Comida'); ?>
<?php $__env->startSection('page-title', 'Gestión de Menús'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Administra los menús de almuerzo para las sesiones de cursos
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <!-- Filters -->
            <form method="GET" action="<?php echo e(route('meal-menus.index')); ?>" class="flex items-center space-x-2">
                <select name="course_offering_id" 
                        onchange="this.form.submit()"
                        class="input-elegant text-sm">
                    <option value="">Todos los cursos</option>
                    <?php $__currentLoopData = $courseOfferings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offering): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($offering->id); ?>" <?php echo e(request('course_offering_id') == $offering->id ? 'selected' : ''); ?>>
                        <?php echo e($offering->course->name); ?> - Gen <?php echo e($offering->generation_number); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <select name="meal_type" 
                        onchange="this.form.submit()"
                        class="input-elegant text-sm">
                    <option value="">Todos los tipos</option>
                    <option value="breakfast" <?php echo e(request('meal_type') == 'breakfast' ? 'selected' : ''); ?>>Desayuno</option>
                    <option value="lunch" <?php echo e(request('meal_type') == 'lunch' ? 'selected' : ''); ?>>Almuerzo</option>
                    <option value="dinner" <?php echo e(request('meal_type') == 'dinner' ? 'selected' : ''); ?>>Cena</option>
                    <option value="snack" <?php echo e(request('meal_type') == 'snack' ? 'selected' : ''); ?>>Merienda</option>
                </select>
            </form>
            
            <!-- Create Button -->
            <?php if(auth()->user()->hasPermission('courses.create')): ?>
            <a href="<?php echo e(route('meal-menus.create')); ?>" class="btn-primary text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Menú
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Menús</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-primary-dark"><?php echo e($menus->total()); ?></p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Próximos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-blue-600">
                    <?php echo e($menus->where('meal_date', '>=', today())->count()); ?>

                </p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Activos</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-green-600">
                    <?php echo e($menus->where('is_active', true)->count()); ?>

                </p>
            </div>
        </div>

        <div class="card-premium">
            <div class="text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Selecciones</p>
                <p class="text-xl sm:text-2xl md:text-3xl font-display font-semibold text-purple-600">
                    <?php echo e($menus->sum(fn($m) => $m->selections->count())); ?>

                </p>
            </div>
        </div>
    </div>

    <!-- Menus Table -->
    <div class="card-premium">
        <?php if($menus->isEmpty()): ?>
            <div class="text-center py-12">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="text-gray-500 text-base sm:text-lg">No hay menús registrados</p>
                <?php if(auth()->user()->hasPermission('courses.create')): ?>
                <a href="<?php echo e(route('meal-menus.create')); ?>" class="btn-primary mt-4 inline-block">
                    Crear Primer Menú
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Curso</th>
                            <th>Tipo</th>
                            <th>Opciones</th>
                            <th>Selecciones</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <!-- Date -->
                            <td>
                                <div>
                                    <p class="font-medium text-primary-dark">
                                        <?php echo e($menu->meal_date->format('d/m/Y')); ?>

                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo e($menu->meal_date->locale('es')->isoFormat('dddd')); ?>

                                    </p>
                                </div>
                            </td>

                            <!-- Course -->
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                        G<?php echo e($menu->courseOffering->generation_number); ?>

                                    </div>
                                    <div>
                                        <p class="font-medium text-primary-dark text-sm">
                                            <?php echo e($menu->courseOffering->course->name); ?>

                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Gen <?php echo e($menu->courseOffering->generation_number); ?>

                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Meal Type -->
                            <td>
                                <?php
                                    $typeColors = [
                                        'breakfast' => 'bg-yellow-100 text-yellow-800',
                                        'lunch' => 'bg-orange-100 text-orange-800',
                                        'dinner' => 'bg-purple-100 text-purple-800',
                                        'snack' => 'bg-green-100 text-green-800',
                                    ];
                                ?>
                                <span class="badge <?php echo e($typeColors[$menu->meal_type] ?? 'badge-info'); ?>">
                                    <?php echo e($menu->meal_type_label); ?>

                                </span>
                            </td>

                            <!-- Options Count -->
                            <td>
                                <span class="text-sm text-gray-600">
                                    <?php echo e($menu->options->count()); ?> opciones
                                </span>
                            </td>

                            <!-- Selections -->
                            <td>
                                <?php
                                    $totalStudents = $menu->courseOffering->enrollments->count();
                                    $selectionsCount = $menu->selections->count();
                                    $percentage = $totalStudents > 0 ? round(($selectionsCount / $totalStudents) * 100) : 0;
                                ?>
                                <div>
                                    <p class="text-sm font-medium text-primary-dark">
                                        <?php echo e($selectionsCount); ?>/<?php echo e($totalStudents); ?>

                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                        <div class="bg-green-600 h-1.5 rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo e($percentage); ?>% completado</p>
                                </div>
                            </td>

                            <!-- Status -->
                            <td>
                                <?php if($menu->is_active): ?>
                                    <?php if($menu->meal_date < today()): ?>
                                        <span class="badge badge-secondary">Pasado</span>
                                    <?php elseif($menu->meal_date == today()): ?>
                                        <span class="badge badge-warning">Hoy</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Próximo</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View -->
                                    <?php if(auth()->user()->hasPermission('courses.view')): ?>
                                    <a href="<?php echo e(route('meal-menus.show', $menu->id)); ?>" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Send Notifications -->
                                    <?php if(auth()->user()->hasPermission('courses.edit') && $menu->meal_date >= today()): ?>
                                    <form method="POST" action="<?php echo e(route('meal-menus.send-notifications', $menu->id)); ?>" class="inline" id="notify-form-<?php echo e($menu->id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="button" 
                                                class="p-2 text-purple-600 hover:bg-purple-50 rounded transition-colors"
                                                title="Enviar notificaciones"
                                                onclick="showConfirmModal('¿Enviar recordatorios de menú a los estudiantes?', function() { document.getElementById('notify-form-<?php echo e($menu->id); ?>').submit(); })">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>

                                    <!-- Report -->
                                    <?php if(auth()->user()->hasPermission('courses.view')): ?>
                                    <a href="<?php echo e(route('meal-menus.report', $menu->id)); ?>" 
                                       class="p-2 text-green-600 hover:bg-green-50 rounded transition-colors"
                                       title="Ver reporte"
                                       target="_blank">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Edit -->
                                    <?php if(auth()->user()->hasPermission('courses.edit') && $menu->meal_date >= today()): ?>
                                    <a href="<?php echo e(route('meal-menus.edit', $menu->id)); ?>" 
                                       class="p-2 text-orange-600 hover:bg-orange-50 rounded transition-colors"
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <?php endif; ?>

                                    <!-- Delete -->
                                    <?php if(auth()->user()->hasPermission('courses.delete')): ?>
                                    <form method="POST" action="<?php echo e(route('meal-menus.destroy', $menu->id)); ?>" class="inline" id="delete-form-<?php echo e($menu->id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Eliminar"
                                                onclick="showConfirmModal('¿Estás seguro de eliminar este menú? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form-<?php echo e($menu->id); ?>').submit(); })">
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
            <?php if($menus->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($menus->links()); ?>

            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/meal-menus/index.blade.php ENDPATH**/ ?>