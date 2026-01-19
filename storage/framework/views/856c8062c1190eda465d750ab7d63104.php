<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Academia Auténtica')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent-red': '#D11C1D',
                        'primary-dark': '#1A1A1B',
                        'neutral-bg': '#F5F5F5',
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'body': ['Montserrat', 'sans-serif'],
                    },
                    boxShadow: {
                        'subtle': '0px 4px 20px rgba(0, 0, 0, 0.05)',
                        'elegant': '0px 8px 30px rgba(0, 0, 0, 0.08)',
                    },
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer base {
            body {
                font-family: 'Montserrat', sans-serif;
            }
            
            h1, h2 {
                font-family: 'Playfair Display', serif;
            }
        }
        
        @layer components {
            .input-elegant {
                @apply w-full px-4 py-3 border border-gray-200 rounded focus:outline-none focus:border-accent-red transition-all duration-300;
                background: #FAFAFA;
            }
            
            .input-elegant:focus {
                @apply bg-white shadow-subtle;
            }
            
            .btn-primary {
                @apply bg-accent-red text-white px-6 py-3 rounded font-medium uppercase text-sm transition-all duration-300;
                letter-spacing: 0.1em;
            }
            
            .btn-primary:hover {
                @apply bg-red-700 shadow-lg;
            }
            
            .label-elegant {
                @apply block text-sm font-medium text-gray-700 mb-2 uppercase tracking-wider;
            }
        }
    </style>
</head>
<body class="font-body antialiased bg-neutral-bg">
    <?php echo $__env->yieldContent('content'); ?>
</body>
</html><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/layouts/auth.blade.php ENDPATH**/ ?>