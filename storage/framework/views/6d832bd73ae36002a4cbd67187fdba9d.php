<?php $__env->startSection('title', 'Productos y Servicios'); ?>
<?php $__env->startSection('page-title', 'Gestión de Productos y Servicios'); ?>

<?php $__env->startSection('content'); ?>
<div class="fade-in">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <p class="text-gray-600">Administra tu catálogo de productos y servicios</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('products.create')); ?>" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Producto/Servicio
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Productos</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($products->total()); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Productos Activos</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($products->where('active', true)->count()); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Con Inventario</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($products->where('track_inventory', true)->count()); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Stock Bajo</p>
                    <p class="text-3xl font-bold text-white"><?php echo e($products->filter(fn($p) => $p->track_inventory && $p->stock <= $p->min_stock)->count()); ?></p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card-premium">
        <?php if($products->isEmpty()): ?>
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <p class="text-xl text-gray-500 font-medium">No hay productos registrados</p>
            <p class="text-gray-400 mt-2">Comienza agregando tu primer producto o servicio</p>
            <a href="<?php echo e(route('products.create')); ?>" class="btn-primary mt-4 inline-flex">
                Agregar Producto
            </a>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left">Nombre</th>
                        <th class="text-right">Precio</th>
                        <th class="text-center">Inventario</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Stock Mínimo</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($product->track_inventory && $product->stock <= $product->min_stock ? 'bg-red-50' : ''); ?>">
                        <td>
                            <p class="font-semibold"><?php echo e($product->name); ?></p>
                            <?php if($product->description): ?>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e(Str::limit($product->description, 50)); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <span class="text-lg font-bold text-green-600">$<?php echo e(number_format($product->price, 2)); ?></span>
                        </td>
                        <td class="text-center">
                            <?php if($product->track_inventory): ?>
                            <span class="status-badge status-badge-info">
                                <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sí
                            </span>
                            <?php else: ?>
                            <span class="status-badge bg-gray-100 text-gray-600">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($product->track_inventory): ?>
                                <?php if($product->stock <= $product->min_stock): ?>
                                <span class="text-red-600 font-bold"><?php echo e($product->stock); ?></span>
                                <?php else: ?>
                                <span class="font-semibold"><?php echo e($product->stock); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                            <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($product->track_inventory): ?>
                            <span class="text-sm text-gray-600"><?php echo e($product->min_stock); ?></span>
                            <?php else: ?>
                            <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <form action="<?php echo e(route('products.toggle-status', $product)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="status-badge <?php echo e($product->active ? 'status-badge-success' : 'status-badge-danger'); ?> cursor-pointer hover:opacity-80">
                                    <?php echo e($product->active ? 'Activo' : 'Inactivo'); ?>

                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn-icon" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-icon text-red-600 hover:text-red-800" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($products->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($products->links()); ?>

        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u545703374/domains/test.supercarnes.com/resources/views/products/index.blade.php ENDPATH**/ ?>