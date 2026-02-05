@extends('layouts.app')

@section('title', 'Ver Plantilla - ' . $certificateTemplate->name)
@section('page-title', 'Detalle de Plantilla')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card-premium">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">Información General</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="text-base text-gray-900">{{ $certificateTemplate->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                        <dd class="text-base text-gray-900 capitalize">{{ $certificateTemplate->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                        <dd class="text-base text-gray-900">{{ $certificateTemplate->description ?? 'Sin descripción' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Orientación</dt>
                        <dd class="text-base text-gray-900 capitalize">{{ $certificateTemplate->orientation }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tamaño</dt>
                        <dd class="text-base text-gray-900">{{ $certificateTemplate->size }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Asistencia Mínima</dt>
                        <dd class="text-base text-gray-900">{{ $certificateTemplate->min_attendance_percentage }}%</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $certificateTemplate->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $certificateTemplate->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                            @if($certificateTemplate->is_default)
                            <span class="ml-2 px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                Predeterminada
                            </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="card-premium">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">Acciones</h3>
                <div class="space-y-2">
                    <a href="{{ route('certificate-templates.edit', $certificateTemplate) }}" class="btn-primary w-full">
                        Editar Plantilla
                    </a>
                    <a href="{{ route('certificate-templates.preview', $certificateTemplate) }}" target="_blank" class="btn-secondary w-full">
                        Ver Vista Previa
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - Preview -->
        <div class="lg:col-span-2">
            <div class="card-premium">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">Vista Previa del Certificado</h3>
                <div class="bg-gray-50 p-4 rounded border">
                    <iframe 
                        src="{{ route('certificate-templates.preview', $certificateTemplate) }}" 
                        class="w-full h-[600px] border-0 rounded"
                        title="Vista previa del certificado">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection