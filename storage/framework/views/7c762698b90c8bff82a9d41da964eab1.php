

<?php $__env->startSection('title', 'Crear Menú'); ?>
<?php $__env->startSection('page-title', 'Nuevo Menú de Comida'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('meal-menus.index')); ?>" class="text-gray-500 hover:text-accent-red transition-colors">
                        Menús
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Nuevo</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="<?php echo e(route('meal-menus.store')); ?>" method="POST" enctype="multipart/form-data" id="menuForm">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Información del Menú -->
                <div class="card-premium">
                    <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">
                        Información del Menú
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Course Offering -->
                        <div class="md:col-span-2">
                            <label class="label-elegant">
                                Curso / Generación <span class="text-red-500">*</span>
                            </label>
                            <select name="course_offering_id" id="courseOffering" class="input-elegant" required>
                                <option value="">Seleccione un curso</option>
                                <?php $__currentLoopData = $courseOfferings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offering): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($offering->id); ?>" data-dates='<?php echo e($offering->dates_json); ?>'>
                                    <?php echo e($offering->course->name); ?> - Gen <?php echo e($offering->generation_number); ?> 
                                    (<?php echo e($offering->start_date->format('d/m/Y')); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['course_offering_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Meal Date -->
                        <div>
                            <label class="label-elegant">
                                Fecha del Menú <span class="text-red-500">*</span>
                            </label>
                            <select name="meal_date" id="mealDate" class="input-elegant" required disabled>
                                <option value="">Primero seleccione un curso</option>
                            </select>
                            <?php $__errorArgs = ['meal_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Meal Type -->
                        <div>
                            <label class="label-elegant">
                                Tipo de Comida <span class="text-red-500">*</span>
                            </label>
                            <select name="meal_type" class="input-elegant" required>
                                <option value="">Seleccione</option>
                                <option value="breakfast">Desayuno</option>
                                <option value="lunch">Almuerzo</option>
                                <option value="dinner">Cena</option>
                                <option value="snack">Merienda</option>
                            </select>
                            <?php $__errorArgs = ['meal_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="label-elegant">
                                Descripción del Menú <span class="text-red-500">*</span>
                            </label>
                            <textarea name="menu_description" 
                                      rows="3" 
                                      class="input-elegant" 
                                      placeholder="Ej: Menú especial del hotel AC Marriott..."
                                      required><?php echo e(old('menu_description')); ?></textarea>
                            <?php $__errorArgs = ['menu_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Menu Image -->
                        <div class="md:col-span-2">
                            <label class="label-elegant">
                                Imagen del Menú (Opcional)
                            </label>
                            <input type="file" 
                                   name="menu_image" 
                                   accept="image/*"
                                   class="input-elegant">
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG o WEBP. Máximo 2MB</p>
                            <?php $__errorArgs = ['menu_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Opciones del Menú -->
                <div class="card-premium">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-display font-semibold text-primary-dark">
                            Opciones del Menú
                        </h2>
                        <button type="button" 
                                onclick="addOption()" 
                                class="btn-secondary text-sm">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Opción
                        </button>
                    </div>

                    <div id="optionsContainer" class="space-y-4">
                        <!-- Las opciones se agregarán dinámicamente aquí -->
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Settings Card -->
                <div class="card-premium">
                    <h3 class="text-lg font-display font-semibold text-primary-dark mb-4">
                        Configuración
                    </h3>

                    <!-- Max Selections -->
                    <div class="mb-4">
                        <label class="label-elegant">
                            Máximo de Selecciones por Estudiante
                        </label>
                        <input type="number" 
                               name="max_selections" 
                               value="1" 
                               min="1" 
                               max="10"
                               class="input-elegant">
                        <p class="text-xs text-gray-500 mt-1">Usualmente es 1</p>
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   checked
                                   class="w-5 h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                            <span class="text-sm font-medium text-gray-700">Menú Activo</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Los estudiantes podrán seleccionar</p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card-premium">
                    <button type="submit" class="btn-primary w-full mb-3">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Crear Menú
                    </button>
                    <a href="<?php echo e(route('meal-menus.index')); ?>" class="btn-secondary w-full">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template para opciones -->
<template id="optionTemplate">
    <div class="option-item border border-gray-200 rounded-lg p-4 relative">
        <button type="button" 
                onclick="removeOption(this)" 
                class="absolute top-2 right-2 text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
            <!-- Name -->
            <div class="md:col-span-2">
                <label class="label-elegant text-sm">
                    Nombre del Plato <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="options[INDEX][name]" 
                       class="input-elegant" 
                       placeholder="Ej: Ensalada César con Pollo"
                       required>
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label class="label-elegant text-sm">Descripción</label>
                <textarea name="options[INDEX][description]" 
                          rows="2" 
                          class="input-elegant text-sm"
                          placeholder="Ingredientes, preparación..."></textarea>
            </div>

            <!-- Image -->
            <div class="md:col-span-2">
                <label class="label-elegant text-sm">Imagen del Plato</label>
                <input type="file" 
                       name="options[INDEX][image]" 
                       accept="image/*"
                       class="input-elegant text-sm">
            </div>

            <!-- Available Quantity -->
            <div>
                <label class="label-elegant text-sm">Cantidad Disponible</label>
                <input type="number" 
                       name="options[INDEX][available_quantity]" 
                       min="0"
                       class="input-elegant"
                       placeholder="Dejar vacío si es ilimitado">
            </div>

            <!-- Dietary Flags -->
            <div class="space-y-2">
                <label class="label-elegant text-sm">Características</label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="options[INDEX][is_vegetarian]" value="1" class="w-4 h-4 text-green-600 rounded">
                    <span class="text-sm">Vegetariano</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="options[INDEX][is_vegan]" value="1" class="w-4 h-4 text-green-600 rounded">
                    <span class="text-sm">Vegano</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="options[INDEX][is_gluten_free]" value="1" class="w-4 h-4 text-yellow-600 rounded">
                    <span class="text-sm">Sin Gluten</span>
                </label>
            </div>

            <!-- Is Active -->
            <div class="md:col-span-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="options[INDEX][is_active]" value="1" checked class="w-4 h-4 text-accent-red rounded">
                    <span class="text-sm font-medium">Opción Activa</span>
                </label>
            </div>
        </div>
    </div>
</template>

<?php $__env->startPush('scripts'); ?>
<script>
let optionIndex = 0;

// Agregar opción
function addOption() {
    const template = document.getElementById('optionTemplate');
    const container = document.getElementById('optionsContainer');
    
    const clone = template.content.cloneNode(true);
    const html = clone.querySelector('.option-item').outerHTML.replace(/INDEX/g, optionIndex);
    
    container.insertAdjacentHTML('beforeend', html);
    optionIndex++;
}

// Remover opción
function removeOption(button) {
    if (confirm('¿Eliminar esta opción del menú?')) {
        button.closest('.option-item').remove();
    }
}

// Cargar fechas de sesiones al seleccionar curso
document.getElementById('courseOffering').addEventListener('change', function() {
    const select = document.getElementById('mealDate');
    const option = this.options[this.selectedIndex];
    
    select.innerHTML = '<option value="">Seleccione una fecha</option>';
    select.disabled = !this.value;
    
    if (this.value && option.dataset.dates) {
        try {
            const dates = JSON.parse(option.dataset.dates);
            
            if (dates && dates.length > 0) {
                dates.forEach(date => {
                    const opt = document.createElement('option');
                    opt.value = date.class_date;  // Usar class_date en lugar de session_date
                    
                    // Formatear la fecha
                    const dateObj = new Date(date.class_date + 'T00:00:00');
                    const formatted = dateObj.toLocaleDateString('es-PA', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    opt.textContent = `Sesión ${date.session_number || date.class_number || ''} - ${formatted}`;
                    select.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'No hay sesiones programadas';
                opt.disabled = true;
                select.appendChild(opt);
            }
        } catch (e) {
            console.error('Error parsing dates:', e);
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Error cargando fechas';
            opt.disabled = true;
            select.appendChild(opt);
        }
    }
});

// Agregar primera opción al cargar
document.addEventListener('DOMContentLoaded', function() {
    addOption();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/meal-menus/create.blade.php ENDPATH**/ ?>