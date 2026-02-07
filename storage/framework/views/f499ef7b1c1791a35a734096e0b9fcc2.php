<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Academia Auténtica')); ?> - Inscripción</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary-dark: #1e293b;
            --accent-red: #e11d48;
        }
        
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .font-display { font-family: 'Playfair Display', serif; }
        
        .card-premium {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            border: 1px solid #f1f5f9;
        }

        .input-elegant {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s;
            outline: none;
        }

        .input-elegant:focus {
            border-color: var(--accent-red);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.1);
        }

        .label-elegant {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-dark);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s;
            padding: 0.75rem 1.5rem;
        }

        .btn-primary:hover {
            background-color: #0f172a;
            transform: translateY(-1px);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased text-slate-900">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="/" class="text-2xl font-display font-bold text-slate-800">
                ACADEMIA <span class="text-rose-600">AUTÉNTICA</span>
            </a>
        </div>

        <div class="w-full max-w-4xl mb-6">
            
            
            <?php if(session('success')): ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-lg shadow-sm flex items-start fade-in mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-emerald-800">¡Acción exitosa!</p>
                        <p class="text-sm text-emerald-700"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if(session('error')): ?>
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-lg shadow-sm flex items-start fade-in mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-rose-800">Ha ocurrido un problema</p>
                        <p class="text-sm text-rose-700"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-lg shadow-sm fade-in mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-amber-800">Verifica los datos ingresados:</p>
                            <ul class="mt-1 list-disc list-inside text-sm text-amber-700">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php echo $__env->yieldContent('content'); ?>

        <div class="mt-8 text-center text-slate-400 text-sm">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. Todos los derechos reservados.
        </div>
    </div>
</body>
</html><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/layouts/guest.blade.php ENDPATH**/ ?>