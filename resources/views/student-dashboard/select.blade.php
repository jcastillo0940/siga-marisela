@extends('layouts.app')

@section('title', 'Seleccionar Estudiante')
@section('page-title', 'Dashboard del Estudiante')

@section('content')
<div class="fade-in max-w-4xl mx-auto">
    <div class="card-premium">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-display font-bold text-primary-dark mb-2">Selecciona tu Perfil</h2>
            <p class="text-gray-600">Elige tu nombre para acceder a tu dashboard personalizado</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($students as $student)
            <a href="{{ route('student-dashboard.index', $student->id) }}"
               class="p-6 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 group">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-primary-dark group-hover:text-blue-600 transition-colors">
                            {{ $student->full_name }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ $student->email }}</p>
                        <p class="text-xs text-gray-400">{{ $student->identification }}</p>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>

        @if($students->isEmpty())
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <p class="text-xl text-gray-500 font-medium">No hay estudiantes registrados</p>
        </div>
        @endif
    </div>
</div>
@endsection
