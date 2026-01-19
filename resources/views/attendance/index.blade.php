@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b-4 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-display font-bold text-gray-900">Gestión de Asistencia</h1>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-red-600">← Volver al inicio</a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h2 class="text-xl font-display font-semibold text-gray-800 mb-2">Seleccione un Curso</h2>
            <p class="text-gray-600">Seleccione el curso para gestionar la asistencia de sus sesiones</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-red-600 bg-red-50 rounded-full">
                                {{ $course->category }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-display font-semibold text-gray-900 mb-2">
                            {{ $course->name }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $course->description }}
                        </p>

                        <div class="border-t pt-4 mt-4">
                            <p class="text-sm text-gray-600 mb-3">Generaciones activas:</p>
                            @forelse($course->activeOfferings as $offering)
                                <div class="mb-2 pb-2 border-b last:border-b-0">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">{{ $offering->generation_name ?? 'Generación ' . $offering->code }}</p>
                                            <p class="text-xs text-gray-500">{{ $offering->start_date->format('d/m/Y') }} - {{ $offering->end_date->format('d/m/Y') }}</p>
                                        </div>
                                        <a href="{{ route('attendance.sessions', $offering->id) }}" 
                                           class="ml-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            Ver Sesiones
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 italic">Sin generaciones activas</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <p class="text-gray-500 text-lg">No hay cursos activos disponibles</p>
                        <a href="{{ route('courses.create') }}" class="inline-block mt-4 px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                            Crear Curso
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection