@extends('layouts.app')

@section('title', 'Editar Plantilla - ' . $certificateTemplate->name)
@section('page-title', 'Editar Plantilla de Certificado')

@section('content')
<div class="fade-in max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('certificate-templates.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Plantillas
        </a>
    </div>

    <form action="{{ route('certificate-templates.update', $certificateTemplate) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Settings -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic Information -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Información Básica</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="label-elegant">Nombre de la Plantilla *</label>
                            <input type="text" name="name" value="{{ old('name', $certificateTemplate->name) }}" class="input-elegant @error('name') border-red-500 @enderror" required>
                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label-elegant">Tipo *</label>
                            <select name="type" class="input-elegant @error('type') border-red-500 @enderror" required>
                                <option value="course" {{ old('type', $certificateTemplate->type) === 'course' ? 'selected' : '' }}>Curso</option>
                                <option value="workshop" {{ old('type', $certificateTemplate->type) === 'workshop' ? 'selected' : '' }}>Taller</option>
                                <option value="seminar" {{ old('type', $certificateTemplate->type) === 'seminar' ? 'selected' : '' }}>Seminario</option>
                                <option value="other" {{ old('type', $certificateTemplate->type) === 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label-elegant">Descripción</label>
                            <textarea name="description" rows="3" class="input-elegant @error('description') border-red-500 @enderror">{{ old('description', $certificateTemplate->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Page Setup -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Configuración de Página</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="label-elegant">Orientación *</label>
                            <select name="orientation" class="input-elegant" required>
                                <option value="landscape" {{ old('orientation', $certificateTemplate->orientation) === 'landscape' ? 'selected' : '' }}>Horizontal</option>
                                <option value="portrait" {{ old('orientation', $certificateTemplate->orientation) === 'portrait' ? 'selected' : '' }}>Vertical</option>
                            </select>
                        </div>

                        <div>
                            <label class="label-elegant">Tamaño *</label>
                            <select name="size" class="input-elegant" required>
                                <option value="A4" {{ old('size', $certificateTemplate->size) === 'A4' ? 'selected' : '' }}>A4</option>
                                <option value="Letter" {{ old('size', $certificateTemplate->size) === 'Letter' ? 'selected' : '' }}>Letter</option>
                            </select>
                        </div>

                        <div>
                            <label class="label-elegant">Imagen de Fondo</label>
                            @if($certificateTemplate->background_image)
                            <div class="mb-2 p-3 bg-gray-50 rounded border">
                                <p class="text-sm text-gray-600 mb-2">Imagen actual:</p>
                                <img src="{{ asset('storage/' . $certificateTemplate->background_image) }}" alt="Background" class="max-w-full h-32 object-contain border rounded">
                            </div>
                            @endif
                            <input type="file" name="background_image" accept="image/jpeg,image/png,image/jpg" class="input-elegant">
                            <p class="text-xs text-gray-500 mt-1">JPG o PNG, máx 5MB. Dejar vacío para mantener la actual</p>
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Requisitos</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="label-elegant">Asistencia Mínima (%) *</label>
                            <input type="number" name="min_attendance_percentage" value="{{ old('min_attendance_percentage', $certificateTemplate->min_attendance_percentage) }}" min="0" max="100" class="input-elegant" required>
                        </div>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="requires_payment_complete" class="w-5 h-5 text-accent-red rounded" {{ old('requires_payment_complete', $certificateTemplate->requires_payment_complete) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Requiere pago completo</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="requires_all_sessions" class="w-5 h-5 text-accent-red rounded" {{ old('requires_all_sessions', $certificateTemplate->requires_all_sessions) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Requiere asistencia a todas las sesiones</span>
                        </label>
                    </div>
                </div>

                <!-- Status -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Estado</h3>

                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_active" class="w-5 h-5 text-accent-red rounded" {{ old('is_active', $certificateTemplate->is_active) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Plantilla activa</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_default" class="w-5 h-5 text-accent-red rounded" {{ old('is_default', $certificateTemplate->is_default) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Establecer como predeterminada</span>
                        </label>
                    </div>

                    @if($certificateTemplate->certificates->count() > 0)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                        <p class="text-xs text-yellow-800">
                            <strong>Nota:</strong> Esta plantilla tiene {{ $certificateTemplate->certificates->count() }} certificados emitidos.
                            Los certificados existentes no se verán afectados por los cambios.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Code Editors -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Preview Button -->
                <div class="card-premium bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-primary-dark mb-1">Vista Previa</h3>
                            <p class="text-sm text-gray-600">Visualiza cómo se verá el certificado con datos de ejemplo</p>
                        </div>
                        <a href="{{ route('certificate-templates.preview', $certificateTemplate) }}" target="_blank" class="btn-primary">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Abrir Vista Previa
                        </a>
                    </div>
                </div>

                <!-- Variables Reference -->
                <div class="card-premium bg-blue-50 border-2 border-blue-200">
                    <h3 class="text-lg font-semibold text-primary-dark mb-3">Variables Disponibles</h3>
                    <p class="text-sm text-gray-600 mb-3">Usa estas variables en tu HTML (ejemplo: <code class="bg-white px-2 py-1 rounded">{{'{{'}}student_name{{'}}'}}</code>)</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                        @foreach($availableVariables as $key => $description)
                        <div class="bg-white p-2 rounded border border-blue-200">
                            <code class="text-blue-600 font-semibold">{{'{{'}}{{ $key }}{{'}}'}}</code>
                            <p class="text-gray-600 text-xs mt-1">{{ $description }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- HTML Template Editor -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Código HTML *</h3>
                    <textarea name="html_template" id="html_template" rows="20" class="font-mono text-sm input-elegant @error('html_template') border-red-500 @enderror" required>{{ old('html_template', $certificateTemplate->html_template) }}</textarea>
                    @error('html_template')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Define la estructura HTML del certificado</p>
                </div>

                <!-- CSS Styles Editor -->
                <div class="card-premium">
                    <h3 class="text-lg font-semibold text-primary-dark mb-4">Estilos CSS (Opcional)</h3>
                    <textarea name="css_styles" id="css_styles" rows="10" class="font-mono text-sm input-elegant @error('css_styles') border-red-500 @enderror">{{ old('css_styles', $certificateTemplate->css_styles) }}</textarea>
                    @error('css_styles')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">CSS adicional para personalizar el diseño</p>
                </div>

                <!-- Submit Buttons -->
                <div class="card-premium">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('certificate-templates.index') }}" class="btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
