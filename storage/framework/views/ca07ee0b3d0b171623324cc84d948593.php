

<?php $__env->startSection('title', 'Editar Estudiante'); ?>
<?php $__env->startSection('page-title', 'Editar Estudiante'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Breadcrumb -->
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
                        <a href="<?php echo e(route('students.show', $student->id)); ?>" class="text-gray-500 hover:text-accent-red transition-colors">
                            <?php echo e($student->full_name); ?>

                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Editar</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="<?php echo e(route('students.update', $student->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header with Avatar -->
                <div class="card-premium">
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-accent-red rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold">
                            <?php echo e(substr($student->first_name, 0, 1)); ?>

                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                                <?php echo e($student->full_name); ?>

                            </h2>
                            <p class="text-gray-600 mt-1"><?php echo e($student->email); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Información Personal -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información Personal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="label-elegant">Nombre *</label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="<?php echo e(old('first_name', $student->first_name)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   required>
                            <?php $__errorArgs = ['first_name'];
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

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="label-elegant">Apellido *</label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="<?php echo e(old('last_name', $student->last_name)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   required>
                            <?php $__errorArgs = ['last_name'];
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

                        <!-- Email -->
                        <div>
                            <label for="email" class="label-elegant">Correo Electrónico *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e(old('email', $student->email)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   required>
                            <?php $__errorArgs = ['email'];
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

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="label-elegant">Género *</label>
                            <select id="gender" 
                                    name="gender" 
                                    class="input-elegant <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <option value="">Seleccionar género</option>
                                <option value="female" <?php echo e(old('gender', $student->gender) == 'female' ? 'selected' : ''); ?>>Femenino</option>
                                <option value="male" <?php echo e(old('gender', $student->gender) == 'male' ? 'selected' : ''); ?>>Masculino</option>
                                <option value="other" <?php echo e(old('gender', $student->gender) == 'other' ? 'selected' : ''); ?>>Otro</option>
                            </select>
                            <?php $__errorArgs = ['gender'];
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

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="label-elegant">Teléfono Principal</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?php echo e(old('phone', $student->phone)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['phone'];
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

                        <!-- Phone Secondary -->
                        <div>
                            <label for="phone_secondary" class="label-elegant">Teléfono Secundario</label>
                            <input type="tel" 
                                   id="phone_secondary" 
                                   name="phone_secondary" 
                                   value="<?php echo e(old('phone_secondary', $student->phone_secondary)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['phone_secondary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['phone_secondary'];
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

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="label-elegant">Fecha de Nacimiento</label>
                            <input type="date" 
                                   id="birth_date" 
                                   name="birth_date" 
                                   value="<?php echo e(old('birth_date', $student->birth_date?->format('Y-m-d'))); ?>"
                                   class="input-elegant <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['birth_date'];
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

                        <!-- Identification Type -->
                        <div>
                            <label for="identification_type" class="label-elegant">Tipo de Identificación</label>
                            <select id="identification_type" 
                                    name="identification_type" 
                                    class="input-elegant <?php $__errorArgs = ['identification_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Seleccionar tipo</option>
                                <option value="cedula" <?php echo e(old('identification_type', $student->identification_type) == 'cedula' ? 'selected' : ''); ?>>Cédula</option>
                                <option value="passport" <?php echo e(old('identification_type', $student->identification_type) == 'passport' ? 'selected' : ''); ?>>Pasaporte</option>
                                <option value="otro" <?php echo e(old('identification_type', $student->identification_type) == 'otro' ? 'selected' : ''); ?>>Otro</option>
                            </select>
                            <?php $__errorArgs = ['identification_type'];
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

                        <!-- Identification -->
                        <div>
                            <label for="identification" class="label-elegant">Número de Identificación</label>
                            <input type="text" 
                                   id="identification" 
                                   name="identification" 
                                   value="<?php echo e(old('identification', $student->identification)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['identification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['identification'];
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

                        <!-- City -->
                        <div>
                            <label for="city" class="label-elegant">Ciudad</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="<?php echo e(old('city', $student->city)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['city'];
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

                        <!-- Country -->
                        <div>
                            <label for="country" class="label-elegant">País</label>
                            <input type="text" 
                                   id="country" 
                                   name="country" 
                                   value="<?php echo e(old('country', $student->country)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['country'];
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

                    <!-- Address -->
                    <div class="mt-4 sm:mt-6">
                        <label for="address" class="label-elegant">Dirección Completa</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="input-elegant <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('address', $student->address)); ?></textarea>
                        <?php $__errorArgs = ['address'];
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

                <!-- Contacto de Emergencia -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Contacto de Emergencia
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                        <!-- Emergency Contact Name -->
                        <div>
                            <label for="emergency_contact_name" class="label-elegant">Nombre Completo</label>
                            <input type="text" 
                                   id="emergency_contact_name" 
                                   name="emergency_contact_name" 
                                   value="<?php echo e(old('emergency_contact_name', $student->emergency_contact_name)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['emergency_contact_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['emergency_contact_name'];
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

                        <!-- Emergency Contact Phone -->
                        <div>
                            <label for="emergency_contact_phone" class="label-elegant">Teléfono</label>
                            <input type="tel" 
                                   id="emergency_contact_phone" 
                                   name="emergency_contact_phone" 
                                   value="<?php echo e(old('emergency_contact_phone', $student->emergency_contact_phone)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['emergency_contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['emergency_contact_phone'];
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

                        <!-- Emergency Contact Relationship -->
                        <div>
                            <label for="emergency_contact_relationship" class="label-elegant">Parentesco</label>
                            <input type="text" 
                                   id="emergency_contact_relationship" 
                                   name="emergency_contact_relationship" 
                                   value="<?php echo e(old('emergency_contact_relationship', $student->emergency_contact_relationship)); ?>"
                                   class="input-elegant <?php $__errorArgs = ['emergency_contact_relationship'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['emergency_contact_relationship'];
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

                <!-- Información Adicional -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información Adicional
                    </h2>

                    <!-- Medical Notes -->
                    <div class="mb-4 sm:mb-6">
                        <label for="medical_notes" class="label-elegant">Notas Médicas</label>
                        <textarea id="medical_notes" 
                                  name="medical_notes" 
                                  rows="3"
                                  class="input-elegant <?php $__errorArgs = ['medical_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('medical_notes', $student->medical_notes)); ?></textarea>
                        <?php $__errorArgs = ['medical_notes'];
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

                    <!-- Emotional Notes -->
                    <div class="mb-4 sm:mb-6">
                        <label for="emotional_notes" class="label-elegant">Notas Emocionales</label>
                        <textarea id="emotional_notes" 
                                  name="emotional_notes" 
                                  rows="3"
                                  class="input-elegant <?php $__errorArgs = ['emotional_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('emotional_notes', $student->emotional_notes)); ?></textarea>
                        <?php $__errorArgs = ['emotional_notes'];
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

                    <!-- Goals -->
                    <div>
                        <label for="goals" class="label-elegant">Metas y Objetivos</label>
                        <textarea id="goals" 
                                  name="goals" 
                                  rows="3"
                                  class="input-elegant <?php $__errorArgs = ['goals'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('goals', $student->goals)); ?></textarea>
                        <?php $__errorArgs = ['goals'];
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

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex items-center justify-between">
                        <div>
                            <?php if(auth()->user()->hasPermission('students.delete')): ?>
                            <button type="button" 
                                    onclick="showConfirmModal('¿Estás seguro de eliminar a <?php echo e($student->full_name); ?>? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form').submit(); })"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Estudiante
                            </button>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn-secondary text-center">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Estado del Estudiante
                    </h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label for="status" class="label-elegant">Estado *</label>
                            <select id="status" 
                                    name="status" 
                                    class="input-elegant <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <option value="prospecto" <?php echo e(old('status', $student->status) == 'prospecto' ? 'selected' : ''); ?>>Prospecto</option>
                                <option value="activo" <?php echo e(old('status', $student->status) == 'activo' ? 'selected' : ''); ?>>Activo</option>
                                <option value="inactivo" <?php echo e(old('status', $student->status) == 'inactivo' ? 'selected' : ''); ?>>Inactivo</option>
                                <option value="graduado" <?php echo e(old('status', $student->status) == 'graduado' ? 'selected' : ''); ?>>Graduado</option>
                                <option value="retirado" <?php echo e(old('status', $student->status) == 'retirado' ? 'selected' : ''); ?>>Retirado</option>
                            </select>
                            <?php $__errorArgs = ['status'];
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

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       <?php echo e(old('is_active', $student->is_active) ? 'checked' : ''); ?>

                                       class="w-4 h-4 sm:w-5 sm:h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                                <span class="label-elegant mb-0">Estudiante Activo</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Información
                    </h3>
                    
                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Registrado:</span>
                            <span class="text-primary-dark font-medium"><?php echo e($student->created_at->format('d/m/Y')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Última actualización:</span>
                            <span class="text-primary-dark font-medium"><?php echo e($student->updated_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    <?php if(auth()->user()->hasPermission('students.delete')): ?>
    <form id="delete-form" 
          method="POST" 
          action="<?php echo e(route('students.destroy', $student->id)); ?>"
          class="hidden">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/students/edit.blade.php ENDPATH**/ ?>