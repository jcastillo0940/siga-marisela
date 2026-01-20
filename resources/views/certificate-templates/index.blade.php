@extends('layouts.app')

@section('title', 'Plantillas de Certificados')
@section('page-title', 'Gestión de Plantillas de Certificados')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <p class="text-gray-600">Diseña y gestiona plantillas reutilizables para certificados</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('certificate-templates.create') }}" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Plantilla
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Plantillas</p>
                    <p class="text-3xl font-bold text-white">{{ $templates->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Activas</p>
                    <p class="text-3xl font-bold text-white">{{ $templates->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Predeterminada</p>
                    <p class="text-2xl font-bold text-white">{{ $templates->where('is_default', true)->count() > 0 ? '✓' : '-' }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Certificados Emitidos</p>
                    <p class="text-3xl font-bold text-white">{{ $templates->sum(fn($t) => $t->certificates->count()) }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Grid -->
    @if($templates->isEmpty())
    <div class="card-premium">
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-xl text-gray-500 font-medium">No hay plantillas creadas</p>
            <p class="text-gray-400 mt-2">Crea tu primera plantilla de certificado</p>
            <a href="{{ route('certificate-templates.create') }}" class="btn-primary mt-4 inline-flex">
                Nueva Plantilla
            </a>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($templates as $template)
        <div class="card-premium border-2 {{ $template->is_default ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }} relative">
            @if($template->is_default)
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-xs font-bold">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                    Predeterminada
                </span>
            </div>
            @endif

            <div class="mb-4">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-2">{{ $template->name }}</h3>
                @if($template->description)
                <p class="text-sm text-gray-600">{{ Str::limit($template->description, 80) }}</p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                <div>
                    <p class="text-gray-500">Tipo:</p>
                    <p class="font-semibold text-gray-700">{{ ucfirst($template->type) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Orientación:</p>
                    <p class="font-semibold text-gray-700">{{ ucfirst($template->orientation) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Tamaño:</p>
                    <p class="font-semibold text-gray-700">{{ $template->size }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Asistencia mín:</p>
                    <p class="font-semibold text-gray-700">{{ $template->min_attendance_percentage }}%</p>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4 text-xs">
                <div class="flex items-center space-x-2">
                    @if($template->requires_payment_complete)
                    <span class="badge badge-warning">Pago completo</span>
                    @endif
                    @if($template->requires_all_sessions)
                    <span class="badge badge-info">Todas las sesiones</span>
                    @endif
                </div>
                <div>
                    @if($template->is_active)
                    <span class="status-badge status-badge-success">Activa</span>
                    @else
                    <span class="status-badge status-badge-danger">Inactiva</span>
                    @endif
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-xs text-gray-500 mb-3">
                    <strong>{{ $template->certificates->count() }}</strong> certificados emitidos
                </p>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('certificate-templates.preview', $template) }}"
                       target="_blank"
                       class="btn-secondary text-xs flex-1">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Vista Previa
                    </a>
                    <a href="{{ route('certificate-templates.edit', $template) }}" class="btn-icon" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('certificate-templates.duplicate', $template) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-icon" title="Duplicar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </form>
                    @if($template->certificates->count() === 0)
                    <form action="{{ route('certificate-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta plantilla?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon text-red-600 hover:text-red-800" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
