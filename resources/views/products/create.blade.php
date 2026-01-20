@extends('layouts.app')

@section('title', 'Nuevo Producto/Servicio')
@section('page-title', 'Agregar Producto o Servicio')

@section('content')
<div class="fade-in max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Productos
        </a>
    </div>

    <div class="card-premium">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-4 pb-2 border-b">Información Básica</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="label-elegant">Nombre del Producto/Servicio *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="input-elegant @error('name') border-red-500 @enderror"
                                   required
                                   autofocus>
                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="label-elegant">Descripción</label>
                            <textarea name="description"
                                      rows="3"
                                      class="input-elegant @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label-elegant">Precio *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">$</span>
                                <input type="number"
                                       name="price"
                                       value="{{ old('price') }}"
                                       step="0.01"
                                       min="0"
                                       class="input-elegant pl-8 @error('price') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Inventory Management -->
                <div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-4 pb-2 border-b">Control de Inventario</h3>

                    <div class="mb-4">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox"
                                   name="track_inventory"
                                   id="track_inventory"
                                   class="w-5 h-5 text-accent-red rounded focus:ring-accent-red"
                                   {{ old('track_inventory') ? 'checked' : '' }}
                                   onchange="toggleInventoryFields()">
                            <div>
                                <span class="font-medium text-gray-700">Controlar inventario de este producto</span>
                                <p class="text-sm text-gray-500">Desactiva esto para servicios que no requieren stock</p>
                            </div>
                        </label>
                    </div>

                    <div id="inventory-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ old('track_inventory') ? '' : 'hidden' }}">
                        <div>
                            <label class="label-elegant">Stock Inicial</label>
                            <input type="number"
                                   name="stock"
                                   value="{{ old('stock', 0) }}"
                                   min="0"
                                   class="input-elegant @error('stock') border-red-500 @enderror">
                            @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label-elegant">Stock Mínimo</label>
                            <input type="number"
                                   name="min_stock"
                                   value="{{ old('min_stock', 0) }}"
                                   min="0"
                                   class="input-elegant @error('min_stock') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Recibirás alertas cuando el stock sea menor o igual a este valor</p>
                            @error('min_stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-lg font-semibold text-primary-dark mb-4 pb-2 border-b">Estado</h3>

                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox"
                               name="active"
                               class="w-5 h-5 text-accent-red rounded focus:ring-accent-red"
                               {{ old('active', true) ? 'checked' : '' }}>
                        <div>
                            <span class="font-medium text-gray-700">Producto activo</span>
                            <p class="text-sm text-gray-500">Los productos inactivos no aparecen en el POS</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t">
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleInventoryFields() {
    const checkbox = document.getElementById('track_inventory');
    const fields = document.getElementById('inventory-fields');

    if (checkbox.checked) {
        fields.classList.remove('hidden');
    } else {
        fields.classList.add('hidden');
    }
}
</script>
@endpush
@endsection
