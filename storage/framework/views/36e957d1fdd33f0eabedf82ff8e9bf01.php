
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
    
    <!-- Tailwind Config & Custom Styles -->
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
            /* Tipografía Premium */
            h1, h2, h3 {
                font-family: 'Playfair Display', serif;
                letter-spacing: 0.02em;
            }
            
            h1 {
                @apply text-3xl md:text-4xl lg:text-5xl font-semibold text-primary-dark;
            }
            
            h2 {
                @apply text-2xl md:text-3xl font-medium text-primary-dark;
            }
            
            body {
                font-family: 'Montserrat', sans-serif;
                @apply text-primary-dark;
            }
        }

        @layer components {
            /* Card Premium con sombra sutil */
            .card-premium {
                @apply bg-white rounded shadow-subtle p-4 sm:p-6 lg:p-8 transition-all duration-300;
            }
            
            .card-premium:hover {
                @apply shadow-elegant;
                transform: translateY(-2px);
            }
            
            /* Botón Principal - Rojo Poder */
            .btn-primary {
                @apply bg-accent-red text-white px-4 py-2 sm:px-6 sm:py-3 rounded font-medium uppercase text-xs sm:text-sm transition-all duration-300;
                letter-spacing: 0.1em;
            }
            
            .btn-primary:hover {
                @apply bg-red-700 shadow-lg;
                transform: translateY(-1px);
            }
            
            /* Botón Secundario */
            .btn-secondary {
                @apply bg-white text-primary-dark px-4 py-2 sm:px-6 sm:py-3 rounded font-medium border border-gray-200 transition-all duration-300 text-xs sm:text-sm;
            }
            
            .btn-secondary:hover {
                @apply border-accent-red text-accent-red;
            }
            
            /* Input Fields Elegantes */
            .input-elegant {
                @apply w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-200 rounded focus:outline-none focus:border-accent-red transition-all duration-300 text-sm sm:text-base;
                background: #FAFAFA;
            }
            
            .input-elegant:focus {
                @apply bg-white shadow-subtle;
            }
            
            /* Label Elegante */
            .label-elegant {
                @apply block text-xs sm:text-sm font-medium text-gray-700 mb-2 uppercase tracking-wider;
                letter-spacing: 0.05em;
            }
            
            /* Líneas Decorativas Editorial */
            .divider-elegant {
                @apply border-t border-gray-100 my-6 sm:my-8;
            }
            
            /* Fade In Animation */
            .fade-in {
                animation: fadeIn 0.4s ease-in;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Table Elegante */
            .table-elegant {
                @apply w-full;
            }
            
            .table-elegant thead {
                @apply bg-neutral-bg border-b border-gray-200;
            }
            
            .table-elegant thead th {
                @apply px-3 py-3 sm:px-6 sm:py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider;
                letter-spacing: 0.1em;
            }
            
            .table-elegant tbody tr {
                @apply border-b border-gray-100 hover:bg-neutral-bg transition-colors duration-200;
            }
            
            .table-elegant tbody td {
                @apply px-3 py-3 sm:px-6 sm:py-4 text-xs sm:text-sm text-gray-800;
            }
            
            /* Badge Premium */
            .badge {
                @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium uppercase tracking-wide;
                letter-spacing: 0.05em;
            }
            
            .badge-success {
                @apply bg-green-100 text-green-800;
            }
            
            .badge-danger {
                @apply bg-red-100 text-red-800;
            }
            
            .badge-warning {
                @apply bg-yellow-100 text-yellow-800;
            }
            
            .badge-info {
                @apply bg-blue-100 text-blue-800;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-body antialiased bg-white">
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" 
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"
         onclick="toggleMobileMenu()"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php echo $__env->make('layouts.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            <!-- Header -->
            <?php echo $__env->make('layouts.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-neutral-bg">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8 max-w-[1440px]">
                    <!-- Flash Messages -->
                    <?php echo $__env->make('layouts.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Toast Notifications -->
    <?php echo $__env->make('components.toast-notification', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Confirm Modal -->
    <?php echo $__env->make('components.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Scripts Consolidados -->
    <script>
        // Toggle mobile menu
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar').classList.remove('-translate-x-full');
                document.getElementById('mobile-overlay').classList.add('hidden');
            }
        });
        
        // Auto-show toasts from session
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                showToast("<?php echo e(session('success')); ?>", 'success');
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                showToast("<?php echo e(session('error')); ?>", 'error');
            <?php endif; ?>
            
            <?php if(session('warning')): ?>
                showToast("<?php echo e(session('warning')); ?>", 'warning');
            <?php endif; ?>
            
            <?php if(session('info')): ?>
                showToast("<?php echo e(session('info')); ?>", 'info');
            <?php endif; ?>
            
            <?php if($errors->any()): ?>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    showToast("<?php echo e($error); ?>", 'error');
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/layouts/app.blade.php ENDPATH**/ ?>