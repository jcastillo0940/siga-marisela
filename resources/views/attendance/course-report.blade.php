@extends('layouts.app')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .shadow-md { box-shadow: none !important; border: 1px solid #e5e7eb; }
        .bg-gray-50 { background-color: white !important; }
    }
</style>

<div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b-4 border-red-600 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('attendance.sessions', $offering->id) }}" class="text-sm text-red-600 hover:text-red-700 mb-2 inline-block">← Volver a sesiones</a>
                    <h1 class="text-3xl font-display font-bold text-gray-900">Reporte de Asistencia</h1>
                    <p class="text-gray-600 mt-1">{{ $offering->full_name }}</p>
                </div>
                <button onclick="window.print()" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                    Imprimir Reporte
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-400">
                <p class="text-sm text-gray-600 mb-1">Total Estudiantes</p>
                <p class="text-3xl font-display font-bold text-gray-900">{{ $report['summary']['total_students'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-green-700 mb-1">Elegibles para Certificado</p>
                <p class="text-3xl font-display font-bold text-green-700">{{ $report['summary']['eligible_for_certificate'] }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-blue-700 mb-1">Promedio de Asistencia</p>
                <p class="text-3xl font-display font-bold text-blue-700">{{ number_format($report['summary']['average_attendance'], 1) }}%</p>
            </div>
            <div class="bg-purple-50 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-purple-700 mb-1">Total Sesiones</p>
                <p class="text-3xl font-display font-bold text-purple-700">{{ $report['course']['total_sessions'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-display font-semibold text-gray-900">Detalle por Estudiante</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Presentes</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tarde</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ausentes</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Justificados</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">% Asistencia</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Certificado</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider no-print">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($report['students'] as $student)
                            @php
                                $percentage = $student['stats']['percentage'];
                                $statusClass = match($student['stats']['status']) {
                                    'excellent' => 'bg-green-100 text-green-800',
                                    'good' => 'bg-blue-100 text-blue-800',
                                    'regular' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-red-100 text-red-800'
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $student['student_name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                    {{ $student['student_document'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-semibold">
                                    {{ $student['stats']['attended'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-600 font-semibold">
                                    {{ $student['stats']['late'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 font-semibold">
                                    {{ $student['stats']['absent'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-blue-600 font-semibold">
                                    {{ $student['stats']['excused'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-sm font-bold rounded-full {{ $statusClass }}">
                                        {{ number_format($percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($student['can_generate_certificate'])
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 uppercase tracking-tighter">Elegible</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 uppercase tracking-tighter">No elegible</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center no-print">
                                    <div class="flex gap-4 justify-center">
                                        <a href="{{ route('attendance.student-report', $student['enrollment_id']) }}" 
                                           class="inline-flex items-center text-red-600 hover:text-red-900 text-sm font-bold">
                                            Ver Detalle
                                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                        
                                        @if($student['can_generate_certificate'])
                                            <form action="{{ route('certificates.generate', $student['enrollment_id']) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center text-green-600 hover:text-green-900 text-sm font-bold"
                                                        onclick="return confirm('¿Generar certificado para {{ $student['student_name'] }}?')">
                                                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Generar Certificado
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-lg shadow-md p-6 border-t-2 border-gray-100">
            <h3 class="text-lg font-display font-semibold text-gray-900 mb-4">Escala de Valoración</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center p-2 rounded-lg bg-gray-50">
                    <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                    <span class="text-sm font-bold text-green-800 mr-2">Excelente</span>
                    <span class="text-xs text-gray-500">≥ 90%</span>
                </div>
                <div class="flex items-center p-2 rounded-lg bg-gray-50">
                    <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                    <span class="text-sm font-bold text-blue-800 mr-2">Bueno</span>
                    <span class="text-xs text-gray-500">80-89%</span>
                </div>
                <div class="flex items-center p-2 rounded-lg bg-gray-50">
                    <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                    <span class="text-sm font-bold text-yellow-800 mr-2">Regular</span>
                    <span class="text-xs text-gray-500">70-79%</span>
                </div>
                <div class="flex items-center p-2 rounded-lg bg-gray-50">
                    <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                    <span class="text-sm font-bold text-red-800 mr-2">Pobre</span>
                    <span class="text-xs text-gray-500">< 70%</span>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection