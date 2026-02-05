@extends('layouts.app')

@section('title', 'Editar Menú')
@section('page-title', 'Editar Menú')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('meal-menus.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Menús
                    </a>
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

    <form action="{{ route('meal-menus.update', $mealMenu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Información del Menú -->
                <div class="card-premium">
                    <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">
                        Información del Menú
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Course Offering (Read-only) -->
                        <div class="md:col-span-2">
                            <label class="label-elegant">
                                Curso / Generación
                            </label>
                            <input type="text" 
                                   value="{{ $mealMenu->courseOffering->course->name }} - Gen {{ $mealMenu->courseOffering->generation_number }}"
                                   class="input-elegant bg-gray-50"
                                   readonly>
                            <input type="hidden" name="course_offering_id" value="{{ $mealMenu->course_offering_id }}">
                        </div>

                        <!-- Meal Date (Read-only) -->
                        <div>
                            <label class="label-elegant">
                                Fecha del Menú
                            </label>
                            <input type="text" 
                                   value="{{ $mealMenu->meal_date->format('d/m/Y') }}"
                                   class="input-elegant bg-gray-50"
                                   readonly>
                            <input type="hidden" name="meal_date" value="{{ $mealMenu->meal_date->format('Y-m-d') }}">
                        </div>

                        <!-- Meal Type -->
                        <div>
                            <label class="label-elegant">
                                Tipo de Comida <span class="text-red-500">*</span>
                            </label>
                            <select name="meal_type" class="input-elegant" required>
                                <option value="breakfast" {{ $mealMenu->meal_type == 'breakfast' ? 'selected' : '' }}>Desayuno</option>
                                <option value="lunch" {{ $mealMenu->meal_type == 'lunch' ? 'selected' : '' }}>Almuerzo</option>
                                <option value="dinner" {{ $mealMenu->meal_type == 'dinner' ? 'selected' : '' }}>Cena</option>
                                <option value="snack" {{ $mealMenu->meal_type == 'snack' ? 'selected' : '' }}>Merienda</option>
                            </select>
                            @error('meal_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="label-elegant">
                                Descripción del Menú <span class="text-red-500">*</span>
                            </label>
                            <textarea name="menu_description" 
                                      rows="3" 
                                      class="input-elegant" 
                                      required>{{ old('menu_description', $mealMenu->menu_description) }}</textarea>
                            @error('menu_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Menu Image -->
                        <div class="md:col-span-2">
                            <label class="label-elegant">
                                Imagen del Menú
                            </label>
                            
                            @if($mealMenu->menu_image)
                            <div class="mb-3">
                                <img src="{{ Storage::url($mealMenu->menu_image) }}" 
                                     alt="Imagen actual" 
                                     class="max-w-xs rounded-lg shadow-subtle">
                                <p class="text-xs text-gray-500 mt-1">Imagen actual</p>
                            </div>
                            @endif

                            <input type="file" 
                                   name="menu_image" 
                                   accept="image/*"
                                   class="input-elegant">
                            <p class="text-xs text-gray-500 mt-1">Dejar vacío para mantener la imagen actual. JPG, PNG o WEBP. Máximo 2MB</p>
                            @error('menu_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Opciones del Menú -->
                <div class="card-premium">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-sm text-yellow-700">
                                <strong>Nota:</strong> Las opciones del menú se gestionan desde la vista de detalles. 
                                Para modificarlas, guarda estos cambios y luego edita cada opción individualmente.
                            </p>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-primary-dark mb-3">
                        Opciones Actuales ({{ $mealMenu->options->count() }})
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($mealMenu->options as $option)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-sm">{{ $option->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $option->selections_count }} selecciones
                                    </p>
                                </div>
                                @if($option->is_active)
                                <span class="badge badge-success text-xs">Activo</span>
                                @else
                                <span class="badge badge-danger text-xs">Inactivo</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
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
                            Máximo de Selecciones
                        </label>
                        <input type="number" 
                               name="max_selections" 
                               value="{{ old('max_selections', $mealMenu->max_selections) }}" 
                               min="1" 
                               max="10"
                               class="input-elegant">
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   {{ $mealMenu->is_active ? 'checked' : '' }}
                                   class="w-5 h-5 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                            <span class="text-sm font-medium text-gray-700">Menú Activo</span>
                        </label>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card-premium bg-blue-50">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">
                        Información
                    </h3>
                    <div class="space-y-2 text-xs text-blue-700">
                        <p><strong>Creado:</strong> {{ $mealMenu->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Última actualización:</strong> {{ $mealMenu->updated_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Selecciones:</strong> {{ $mealMenu->selections->count() }}</p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card-premium">
                    <button type="submit" class="btn-primary w-full mb-3">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('meal-menus.show', $mealMenu->id) }}" class="btn-secondary w-full">
                        Cancelar
                    </a>
                </div>

                <!-- Danger Zone -->
                @if(auth()->user()->hasPermission('courses.delete'))
                <div class="card-premium border-2 border-red-200">
                    <h3 class="text-sm font-semibold text-red-900 mb-3">
                        Zona de Peligro
                    </h3>
                    
                    @if($mealMenu->selections->count() > 0)
                    <p class="text-xs text-red-600 mb-3">
                        No se puede eliminar este menú porque tiene {{ $mealMenu->selections->count() }} selección(es) registradas.
                    </p>
                    @else
                    <form method="POST" action="{{ route('meal-menus.destroy', $mealMenu->id) }}" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="showConfirmModal('¿Eliminar este menú permanentemente? Esta acción no se puede deshacer.', function() { document.getElementById('deleteForm').submit(); })"
                                class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition-colors">
                            Eliminar Menú
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection