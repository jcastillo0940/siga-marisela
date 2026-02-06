

<?php $__env->startSection('title', 'Mi Dashboard - ' . $student->full_name); ?>
<?php $__env->startSection('page-title', 'Dashboard del Estudiante'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <div class="card-premium mb-6 bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    <?php echo e(substr($student->first_name, 0, 1)); ?><?php echo e(substr($student->last_name, 0, 1)); ?>

                </div>
                <div>
                    <h1 class="text-4xl font-display font-bold text-primary-dark">
                        ¡Bienvenido, <?php echo e($student->first_name); ?>!
                    </h1>
                    <p class="text-gray-600 mt-1"><?php echo e($student->email); ?></p>
                    <?php if($student->identification): ?>
                        <p class="text-sm text-gray-500">ID: <?php echo e($student->identification); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <?php if(auth()->user()->hasAnyRole(['super-admin', 'admin', 'staff'])): ?>
                    
                    <a href="<?php echo e(route('student-dashboard.select')); ?>" class="btn-secondary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Cambiar Estudiante
                    </a>
                <?php else: ?>
                    
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-secondary border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Cursos Activos</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($stats['total_enrollments']); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Completados</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($stats['completed_courses']); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Pagado</p>
                    <p class="text-2xl font-bold text-white">$<?php echo e(number_format($stats['total_payments'], 2)); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Saldo Pendiente</p>
                    <p class="text-2xl font-bold text-white">$<?php echo e(number_format($stats['pending_balance'], 2)); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Certificados</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($stats['certificates_count']); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Próximas Clases
                </h3>

                <?php if($upcomingSessions->isEmpty()): ?>
                <p class="text-gray-500 text-center py-8">No tienes clases programadas próximamente</p>
                <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $upcomingSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-primary-dark"><?php echo e($item['course']->name); ?></h4>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($item['session']->topic ?? 'Sesión ' . $item['session']->session_number); ?></p>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <?php echo e(\Carbon\Carbon::parse($item['session']->session_date)->format('d/m/Y')); ?>

                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo e($item['session']->start_time); ?> - <?php echo e($item['session']->end_time); ?>

                                    </span>
                                </div>
                            </div>
                            <a href="<?php echo e(route('attendance.student-qr', $item['enrollment']->id)); ?>" class="btn-primary text-xs">
                                Ver QR
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Mis Cursos
                </h3>

                <?php if($activeEnrollments->isEmpty()): ?>
                <p class="text-gray-500 text-center py-8">No estás inscrito en ningún curso actualmente</p>
                <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $activeEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg text-primary-dark"><?php echo e($enrollment->courseOffering->course->name); ?></h4>
                                <p class="text-sm text-gray-600"><?php echo e($enrollment->courseOffering->formatted_schedule); ?></p>
                            </div>
                            <span class="status-badge status-badge-<?php echo e($enrollment->status === 'active' ? 'success' : 'warning'); ?>">
                                <?php echo e(ucfirst($enrollment->status)); ?>

                            </span>
                        </div>

                        <?php
                            $progress = $enrollment->courseOffering->sessions->count() > 0
                                ? ($enrollment->attendances->count() / $enrollment->courseOffering->sessions->count()) * 100
                                : 0;
                        ?>
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progreso</span>
                                <span><?php echo e(number_format($progress, 0)); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: <?php echo e($progress); ?>%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-center text-sm">
                            <div>
                                <p class="text-gray-500">Asistencia</p>
                                <p class="font-bold text-blue-600"><?php echo e($enrollment->attendances->count()); ?>/<?php echo e($enrollment->courseOffering->sessions->count()); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Saldo</p>
                                <p class="font-bold text-<?php echo e($enrollment->paymentPlan && $enrollment->paymentPlan->balance > 0 ? 'red' : 'green'); ?>-600">
                                    $<?php echo e(number_format($enrollment->paymentPlan ? $enrollment->paymentPlan->balance : 0, 2)); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Acciones</p>
                                <a href="<?php echo e(route('attendance.student-report', $enrollment->id)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            
            <?php if(!$pastEnrollments->isEmpty()): ?>
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Historial de Cursos Anteriores
                </h3>

                <div class="space-y-3">
                    <?php $__currentLoopData = $pastEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $past): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800"><?php echo e($past->courseOffering->course->name); ?></h4>
                            <p class="text-xs text-gray-500">Finalizado: <?php echo e($past->updated_at->format('d/m/Y')); ?></p>
                            <?php if($past->paymentPlan): ?>
                            <p class="text-xs text-gray-600 mt-1">
                                Pagado: $<?php echo e(number_format($past->total_paid, 2)); ?>

                                <?php if($past->paymentPlan->balance > 0): ?>
                                    <span class="text-red-600 font-semibold">(Saldo: $<?php echo e(number_format($past->paymentPlan->balance, 2)); ?>)</span>
                                <?php endif; ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold rounded <?php echo e($past->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                            <?php echo e($past->status === 'completed' ? 'Completado' : 'Cancelado'); ?>

                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Historial de Pagos
                </h3>

                <?php if($payments->isEmpty()): ?>
                <p class="text-gray-500 text-center py-8">No hay pagos registrados</p>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table-elegant">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Curso</th>
                                <th>Método</th>
                                <th class="text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($payment->payment_date->format('d/m/Y')); ?></td>
                                <td><?php echo e($payment->enrollment->courseOffering->course->name); ?></td>
                                <td><span class="badge badge-info"><?php echo e($payment->payment_method_label); ?></span></td>
                                <td class="text-right font-semibold text-green-600">$<?php echo e(number_format($payment->amount, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Material Didáctico
                </h3>

                <?php if($materials->isEmpty()): ?>
                <p class="text-gray-500 text-center py-8">No hay material disponible aún</p>
                <?php else: ?>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg border border-orange-200 hover:border-orange-400 transition-all">
                        <div class="flex items-start space-x-3">
                            <span class="text-3xl"><?php echo e($item['material']->type_icon); ?></span>
                            <div class="flex-1">
                                <h4 class="font-semibold text-primary-dark"><?php echo e($item['material']->title); ?></h4>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($item['course']->name); ?></p>
                                <?php if($item['material']->description): ?>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($item['material']->description, 80)); ?></p>
                                <?php endif; ?>
                                <div class="flex items-center space-x-3 mt-2">
                                    <span class="badge badge-warning"><?php echo e($item['material']->type_label); ?></span>
                                    <?php if($item['material']->file_size): ?>
                                    <span class="text-xs text-gray-500"><?php echo e($item['material']->formatted_file_size); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if($item['material']->type === 'link' || $item['material']->external_url): ?>
                            <a href="<?php echo e($item['material']->external_url); ?>" target="_blank" class="btn-primary text-xs">
                                Abrir
                            </a>
                            <?php elseif($item['material']->file_path): ?>
                            <a href="<?php echo e($item['material']->file_url); ?>" download class="btn-primary text-xs">
                                Descargar
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if(!$availableMenus->isEmpty()): ?>
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Selección de Menú
                </h3>

                <div class="space-y-4">
                    <?php $__currentLoopData = $availableMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $menu = $item['menu'];
                        $enrollment = $item['enrollment'];
                        $currentSelection = $menu->getStudentSelection($enrollment->id);
                    ?>
                    <div class="p-4 border-2 border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-primary-dark flex items-center">
                                    <span class="text-2xl mr-2"><?php echo e($menu->meal_type_icon); ?></span>
                                    <?php echo e($menu->meal_type_label); ?>

                                </h4>
                                <p class="text-sm text-gray-600"><?php echo e($menu->meal_date->format('d/m/Y')); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($item['course']->name); ?></p>
                            </div>
                            <?php if($currentSelection): ?>
                            <span class="status-badge status-badge-success">Seleccionado</span>
                            <?php endif; ?>
                        </div>

                        <?php if($menu->menu_description): ?>
                        <p class="text-sm text-gray-700 mb-3"><?php echo e($menu->menu_description); ?></p>
                        <?php endif; ?>

                        <?php if($currentSelection): ?>
                        <div class="p-3 bg-green-50 border border-green-200 rounded mb-3">
                            <p class="text-sm font-semibold text-green-800">Tu selección: <?php echo e($currentSelection->mealOption->name); ?></p>
                            <?php if($currentSelection->notes): ?>
                            <p class="text-xs text-green-600 mt-1">Nota: <?php echo e($currentSelection->notes); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('student-dashboard.select-meal', $student->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="meal_menu_id" value="<?php echo e($menu->id); ?>">
                            <input type="hidden" name="enrollment_id" value="<?php echo e($enrollment->id); ?>">

                            <div class="space-y-2 mb-3">
                                <?php $__currentLoopData = $menu->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-3 border-2 rounded <?php echo e($currentSelection && $currentSelection->meal_option_id == $option->id ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-blue-300'); ?> cursor-pointer transition-all">
                                    <input type="radio" name="meal_option_id" value="<?php echo e($option->id); ?>" class="w-4 h-4 text-accent-red" <?php echo e($currentSelection && $currentSelection->meal_option_id == $option->id ? 'checked' : ''); ?> required>
                                    <div class="ml-3 flex-1">
                                        <p class="font-semibold text-sm"><?php echo e($option->name); ?></p>
                                        <?php if($option->description): ?>
                                        <p class="text-xs text-gray-600"><?php echo e($option->description); ?></p>
                                        <?php endif; ?>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <?php $__currentLoopData = $option->dietary_labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="text-xs px-2 py-1 bg-<?php echo e($label['color']); ?>-100 text-<?php echo e($label['color']); ?>-700 rounded">
                                                <?php echo e($label['icon']); ?> <?php echo e($label['label']); ?>

                                            </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($option->available_quantity !== null): ?>
                                            <span class="text-xs text-gray-500">(<?php echo e($option->remaining_quantity); ?> disponibles)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mb-3">
                                <input type="text" name="notes" placeholder="Notas especiales (opcional)" class="input-elegant text-sm" value="<?php echo e($currentSelection->notes ?? ''); ?>">
                            </div>

                            <button type="submit" class="btn-primary w-full text-sm">
                                <?php echo e($currentSelection ? 'Actualizar Selección' : 'Guardar Selección'); ?>

                            </button>
                        </form>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="space-y-6">
            <div class="card-premium">
                <h3 class="text-lg font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Mis Certificados
                </h3>

                <?php if($certificates->isEmpty()): ?>
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No tienes certificados aún</p>
                </div>
                <?php else: ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-3 bg-gradient-to-r from-yellow-50 to-red-50 rounded-lg border border-yellow-200">
                        <h4 class="font-semibold text-sm text-primary-dark"><?php echo e($certificate->course->name); ?></h4>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($certificate->issued_at->format('d/m/Y')); ?></p>
                        <a href="<?php echo e(route('certificates.download', $certificate->id)); ?>" class="btn-primary text-xs w-full mt-2 inline-block text-center">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Descargar PDF
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="card-premium bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="<?php echo e(route('certificates.student', $student->id)); ?>" class="btn-secondary w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Ver Todos mis Certificados
                    </a>
                    <a href="<?php echo e(route('public.leads.create')); ?>" class="btn-primary w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Solicitar Nuevo Curso
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/student-dashboard/index.blade.php ENDPATH**/ ?>