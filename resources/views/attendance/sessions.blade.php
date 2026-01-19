@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b-4 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('attendance.index') }}" class="text-sm text-red-600 hover:text-red-700 mb-2 inline-block">← Volver a cursos</a>
                    <h1 class="text-3xl font-display font-bold text-gray-900">{{ $offering->full_name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $offering->start_date->format('d/m/Y') }} - {{ $offering->end_date->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('attendance.course-report', $offering->id) }}" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                    Ver Reporte Completo
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-600 mb-1">Total Sesiones</p>
                <p class="text-3xl font-display font-bold text-gray-900">{{ $offering->dates->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-600 mb-1">Estudiantes Inscritos</p>
                <p class="text-3xl font-display font-bold text-gray-900">{{ $offering->enrollments->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-600 mb-1">Sesiones Completadas</p>
                <p class="text-3xl font-display font-bold text-green-600">
                    {{ $offering->dates->where('class_date', '<', now())->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-600 mb-1">Sesiones Pendientes</p>
                <p class="text-3xl font-display font-bold text-blue-600">
                    {{ $offering->dates->where('class_date', '>=', now())->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-white">
                <h2 class="text-xl font-display font-semibold text-gray-900">Sesiones del Curso</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asistencia</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($offering->dates->sortBy('class_date') as $session)
                            @php
                                $isPast = $session->class_date->isPast();
                                $isToday = $session->class_date->isToday();
                                $attendanceCount = $session->attendances->whereIn('status', ['present', 'late'])->count();
                                $totalStudents = $offering->enrollments->count();
                                $attendanceRate = $totalStudents > 0 ? round(($attendanceCount / $totalStudents) * 100) : 0;
                            @endphp
                            <tr class="{{ $isToday ? 'bg-yellow-50' : '' }} hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $session->class_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 capitalize">
                                        {{ $session->class_date->locale('es')->isoFormat('dddd') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $session->formatted_time ?? 'No definido' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($session->is_cancelled)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                                    @elseif($isToday)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Hoy</span>
                                    @elseif($isPast)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Completada</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Próxima</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($session->attendances->count() > 0)
                                        <div class="flex items-center min-w-[120px]">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $attendanceCount }}/{{ $totalStudents }} ({{ $attendanceRate }}%)
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Sin registro</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('attendance.take', $session->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 shadow-sm">
                                        {{ $session->attendances->count() > 0 ? 'Editar' : 'Tomar' }} Asistencia
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p>No hay sesiones programadas para este curso</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection