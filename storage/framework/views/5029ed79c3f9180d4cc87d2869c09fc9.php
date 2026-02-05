<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Almuerzos - <?php echo e($mealMenu->meal_date->format('d/m/Y')); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Print Button -->
        <div class="no-print mb-6 flex justify-end gap-3">
            <button onclick="window.print()" 
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                🖨️ Imprimir Reporte
            </button>
            <button onclick="window.close()" 
                    class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition-colors">
                Cerrar
            </button>
        </div>

        <!-- Header -->
        <div class="text-center mb-8 pb-6 border-b-2 border-red-600">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                ACADEMIA AUTÉNTICA
            </h1>
            <h2 class="text-xl font-semibold text-red-600">
                ORDEN DE ALMUERZOS
            </h2>
        </div>

        <!-- Info General -->
        <div class="grid grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-lg">
            <div>
                <p class="text-sm text-gray-600 mb-1">Curso:</p>
                <p class="font-semibold text-gray-900"><?php echo e($mealMenu->courseOffering->course->name); ?></p>
                <p class="text-sm text-gray-600">Generación <?php echo e($mealMenu->courseOffering->generation_number); ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-600 mb-1">Fecha:</p>
                <p class="font-semibold text-gray-900">
                    <?php echo e($mealMenu->meal_date->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')); ?>

                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600 mb-1">Tipo de Comida:</p>
                <p class="font-semibold text-gray-900"><?php echo e($mealMenu->meal_type_label); ?></p>
            </div>

            <div>
                <p class="text-sm text-gray-600 mb-1">Hora de Entrega:</p>
                <p class="font-semibold text-gray-900">12:30 PM</p>
            </div>
        </div>

        <!-- Resumen -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 bg-red-600 text-white px-4 py-2 rounded">
                RESUMEN
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-2xl font-bold text-blue-600">
                        <?php echo e($selections->sum(fn($group) => $group->count())); ?>

                    </p>
                    <p class="text-sm text-gray-600">Total Almuerzos</p>
                </div>

                <?php $__currentLoopData = $selections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionId => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <p class="text-2xl font-bold text-green-600"><?php echo e($group->count()); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($group->first()->mealOption->name); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($noSelections->count() > 0): ?>
                <div class="bg-gray-100 p-4 rounded-lg text-center">
                    <p class="text-2xl font-bold text-gray-600"><?php echo e($noSelections->count()); ?></p>
                    <p class="text-sm text-gray-600">Sin Almuerzo</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detalle por Plato -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 bg-red-600 text-white px-4 py-2 rounded">
                DETALLE POR PLATO
            </h3>

            <?php $__currentLoopData = $selections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionId => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $option = $group->first()->mealOption; ?>
            <div class="mb-6 break-inside-avoid">
                <div class="bg-gray-100 px-4 py-2 rounded-t-lg">
                    <div class="flex justify-between items-center">
                        <h4 class="font-bold text-gray-900"><?php echo e($option->name); ?></h4>
                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <?php echo e($group->count()); ?> órdenes
                        </span>
                    </div>
                    <?php if($option->description): ?>
                    <p class="text-sm text-gray-600 mt-1"><?php echo e($option->description); ?></p>
                    <?php endif; ?>
                </div>

                <table class="w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm">#</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm">Estudiante</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm">Notas Especiales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $selection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($index % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
                            <td class="border border-gray-300 px-4 py-2 text-sm"><?php echo e($index + 1); ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-sm font-medium">
                                <?php echo e($selection->enrollment->student->full_name); ?>

                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                <?php echo e($selection->notes ?: '-'); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Estudiantes sin Almuerzo -->
        <?php if($noSelections->count() > 0): ?>
        <div class="mb-8 break-inside-avoid">
            <h3 class="text-lg font-bold text-gray-900 mb-4 bg-gray-600 text-white px-4 py-2 rounded">
                ESTUDIANTES QUE NO LLEVAN ALMUERZO
            </h3>

            <table class="w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm">#</th>
                        <th class="border border-gray-300 px-4 py-2 text-left text-sm">Nombre Completo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $noSelections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($index % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
                        <td class="border border-gray-300 px-4 py-2 text-sm"><?php echo e($index + 1); ?></td>
                        <td class="border border-gray-300 px-4 py-2 text-sm font-medium">
                            <?php echo e($enrollment->student->full_name); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Notas Importantes -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
            <h4 class="font-bold text-yellow-800 mb-2">📌 NOTAS IMPORTANTES</h4>
            <ul class="text-sm text-yellow-700 space-y-1">
                <li>• La entrega debe realizarse en el salón de clases</li>
                <li>• Verificar las notas especiales de cada estudiante (alergias, restricciones)</li>
                <li>• Mantener los alimentos a temperatura adecuada</li>
                <li>• Incluir cubiertos y servilletas descartables</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="border-t-2 border-gray-300 pt-6 mt-8">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Recibido por (Hotel):</p>
                    <div class="border-b-2 border-gray-400 pb-8 mb-2"></div>
                    <p class="text-xs text-gray-500">Nombre y Firma</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Entregado por (Academia):</p>
                    <div class="border-b-2 border-gray-400 pb-8 mb-2"></div>
                    <p class="text-xs text-gray-500">Nombre y Firma</p>
                </div>
            </div>

            <div class="text-center mt-6 text-xs text-gray-500">
                <p>Reporte generado el <?php echo e(now()->format('d/m/Y H:i')); ?></p>
                <p>Academia Auténtica - Sistema de Gestión</p>
            </div>
        </div>

        <!-- Page break for printing -->
        <div class="page-break"></div>

    </div>

    <script>
        // Auto-print cuando se carga (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/meal-menus/report.blade.php ENDPATH**/ ?>