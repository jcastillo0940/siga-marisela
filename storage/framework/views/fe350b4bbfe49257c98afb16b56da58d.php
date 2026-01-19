<header class="h-16 sm:h-20 bg-white border-b border-gray-100 flex items-center justify-between px-3 sm:px-6 lg:px-8 shadow-subtle sticky top-0 z-50">
    <div class="flex items-center space-x-2 sm:space-x-4 min-w-0">
        <button onclick="toggleMobileMenu()" class="lg:hidden text-gray-600 p-1.5 hover:bg-gray-100 rounded-md shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <h1 class="text-lg sm:text-2xl lg:text-3xl font-display text-primary-dark tracking-wide truncate">
            <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
        </h1>
    </div>
    
    <div class="flex items-center space-x-1 sm:space-x-3 lg:space-x-6">
        <div class="relative hidden lg:block">
            <input type="text" 
                   placeholder="Buscar..." 
                   class="w-48 lg:w-80 px-3 py-2 pl-9 bg-neutral-bg border-0 rounded-sm text-sm focus:outline-none focus:ring-1 focus:ring-accent-red">
            <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <button class="relative p-2 text-gray-500 hover:text-accent-red transition-colors shrink-0">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-2 right-2 w-2 h-2 bg-accent-red rounded-full border-2 border-white"></span>
        </button>
        
        <button class="hidden md:block p-2 text-gray-500 hover:text-accent-red transition-colors shrink-0">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </button>
        
        <div class="flex items-center pl-2 sm:pl-4 border-l border-gray-200 space-x-2 sm:space-x-4">
            <div class="text-right hidden sm:block shrink-0">
                <p class="text-sm font-medium text-primary-dark truncate max-w-[100px] lg:max-w-[150px]"><?php echo e(auth()->user()->name); ?></p>
                <p class="text-[10px] text-gray-500 uppercase tracking-tight">
                    <?php echo e(auth()->user()->roles->first()?->name ?? 'Usuario'); ?>

                </p>
            </div>

            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-accent-red rounded-full flex items-center justify-center text-white font-medium text-sm shrink-0">
                <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

            </div>

            <form method="POST" action="<?php echo e(route('logout')); ?>" class="flex items-center">
                <?php echo csrf_field(); ?>
                <button type="submit" class="p-2 text-gray-500 hover:text-accent-red transition-colors shrink-0" title="Cerrar Sesión">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/layouts/partials/header.blade.php ENDPATH**/ ?>