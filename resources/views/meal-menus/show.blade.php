@extends('layouts.app')

@section('title', 'Detalles del Menú')
@section('page-title', 'Detalles del Menú')

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
                        <span class="text-gray-700 font-medium">{{ $mealMenu->meal_type_label }} - {{ $mealMenu->meal_date->format('d/m/Y') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-display font-semibold text-primary-dark mb-2">
                {{ $mealMenu->meal_type_label }}
            </h1>
            <p class="text-gray-600">
                {{ $mealMenu->courseOffering->course->name }} - Gen {{ $mealMenu->courseOffering->generation_number }}
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if(auth()->user()->hasPermission('courses.edit'))
            <!-- Send Notifications -->
            @if($mealMenu->meal_date >= today())
            <form method="POST" action="{{ route('meal-menus.send-notifications', $mealMenu->id) }}" id="notifyForm">
                @csrf
                <button type="button" 
                        onclick="showConfirmModal('¿Enviar recordatorios de menú a los estudiantes?', function() { document.getElementById('notifyForm').submit(); })"
                        class="btn-secondary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Enviar Notificaciones
                </button>
            </form>
            @endif

            <!-- Report -->
            <a href="{{ route('meal-menus.report', $mealMenu->id) }}" 
               target="_blank"
               class="btn-secondary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Ver Reporte
            </a>

            <!-- Edit -->
            @if($mealMenu->meal_date >= today())
            <a href="{{ route('meal-menus.edit', $mealMenu->id) }}" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="card-premium text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Estudiantes</p>
                    <p class="text-2xl font-display font-semibold text-primary-dark">{{ $stats['total_students'] }}</p>
                </div>

                <div class="card-premium text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Han Seleccionado</p>
                    <p class="text-2xl font-display font-semibold text-green-600">{{ $stats['total_selections'] }}</p>
                </div>

                <div class="card-premium text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Pendientes</p>
                    <p class="text-2xl font-display font-semibold text-orange-600">{{ $stats['pending_selections'] }}</p>
                </div>

                <div class="card-premium text-center">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Completado</p>
                    @php
                        $percentage = $stats['total_students'] > 0 
                            ? round(($stats['total_selections'] / $stats['total_students']) * 100) 
                            : 0;
                    @endphp
                    <p class="text-2xl font-display font-semibold text-blue-600">{{ $percentage }}%</p>
                </div>
            </div>

            <!-- Menu Info -->
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-4">
                    Información del Menú
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Fecha</p>
                        <p class="font-medium text-primary-dark">
                            {{ $mealMenu->meal_date->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Tipo de Comida</p>
                        <p class="font-medium text-primary-dark">{{ $mealMenu->meal_type_label }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Curso</p>
                        <p class="font-medium text-primary-dark">
                            {{ $mealMenu->courseOffering->course->name }}
                        </p>
                        <p class="text-xs text-gray-500">Generación {{ $mealMenu->courseOffering->generation_number }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Estado</p>
                        @if($mealMenu->is_active)
                            @if($mealMenu->meal_date < today())
                                <span class="badge badge-secondary">Pasado</span>
                            @elseif($mealMenu->meal_date == today())
                                <span class="badge badge-warning">Hoy</span>
                            @else
                                <span class="badge badge-success">Activo</span>
                            @endif
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-1">Descripción</p>
                        <p class="text-gray-700">{{ $mealMenu->menu_description }}</p>
                    </div>

                    @if($mealMenu->menu_image)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500 mb-2">Imagen del Menú</p>
                        <img src="{{ Storage::url($mealMenu->menu_image) }}" 
                             alt="Menú" 
                             class="max-w-md rounded-lg shadow-subtle">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Opciones del Menú -->
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-4">
                    Opciones del Menú
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($mealMenu->options as $option)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-subtle transition-shadow">
                        @if($option->image)
                        <img src="{{ Storage::url($option->image) }}" 
                             alt="{{ $option->name }}"
                             class="w-full h-32 object-cover rounded-lg mb-3">
                        @endif

                        <h3 class="font-semibold text-primary-dark mb-2">{{ $option->name }}</h3>
                        
                        @if($option->description)
                        <p class="text-sm text-gray-600 mb-3">{{ $option->description }}</p>
                        @endif

                        <!-- Dietary Labels -->
                        @if(count($option->dietary_labels) > 0)
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($option->dietary_labels as $label)
                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $label['color'] }}-100 text-{{ $label['color'] }}-700">
                                {{ $label['label'] }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Stats -->
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500">Selecciones</p>
                                <p class="font-semibold text-primary-dark">{{ $option->selections_count }}</p>
                            </div>

                            @if($option->available_quantity !== null)
                            <div>
                                <p class="text-xs text-gray-500">Disponibles</p>
                                <p class="font-semibold {{ $option->remaining_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $option->remaining_quantity }}
                                </p>
                            </div>
                            @endif

                            <div>
                                @if($option->is_active)
                                <span class="badge badge-success text-xs">Activo</span>
                                @else
                                <span class="badge badge-danger text-xs">Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-8 text-gray-500">
                        No hay opciones configuradas
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Selecciones por Opción -->
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-4">
                    Distribución de Selecciones
                </h2>

                <div class="space-y-4">
                    @foreach($stats['selections_by_option'] as $stat)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $stat['name'] }}</span>
                            <span class="text-sm text-gray-600">{{ $stat['count'] }} selecciones</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $percent = $stats['total_selections'] > 0 
                                    ? round(($stat['count'] / $stats['total_selections']) * 100) 
                                    : 0;
                            @endphp
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card-premium">
                <h3 class="text-lg font-display font-semibold text-primary-dark mb-4">
                    Acciones Rápidas
                </h3>

                <div class="space-y-2">
                    <a href="{{ route('meal-menus.report', $mealMenu->id) }}" 
                       target="_blank"
                       class="btn-secondary w-full text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Generar Reporte
                    </a>

                    @if($mealMenu->meal_date >= today())
                    <form method="POST" action="{{ route('meal-menus.send-notifications', $mealMenu->id) }}" id="notifyFormSidebar">
                        @csrf
                        <button type="button" 
                                onclick="showConfirmModal('¿Enviar recordatorios?', function() { document.getElementById('notifyFormSidebar').submit(); })"
                                class="btn-secondary w-full text-sm">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            Enviar Recordatorios
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('course-offerings.show', $mealMenu->courseOffering->id) }}" 
                       class="btn-secondary w-full text-sm">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Ver Curso
                    </a>
                </div>
            </div>

            <!-- Course Info -->
            <div class="card-premium">
                <h3 class="text-lg font-display font-semibold text-primary-dark mb-4">
                    Información del Curso
                </h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Curso</p>
                        <p class="font-medium text-primary-dark">{{ $mealMenu->courseOffering->course->name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Generación</p>
                        <p class="font-medium text-primary-dark">{{ $mealMenu->courseOffering->generation_number }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Fecha Inicio</p>
                        <p class="font-medium text-primary-dark">
                            {{ $mealMenu->courseOffering->start_date->format('d/m/Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Estudiantes Inscritos</p>
                        <p class="font-medium text-primary-dark">
                            {{ $mealMenu->courseOffering->enrollments->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection