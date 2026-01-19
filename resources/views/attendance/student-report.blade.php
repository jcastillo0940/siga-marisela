@extends('layouts.app')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .shadow-md { shadow: none !important; border: 1px solid #e5e7eb; }
        .bg-gray-50 { background-color: white !important; }
    }
</style>

<div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b-4 border-red-600 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('attendance.course-report', $enrollment->course_offering_id) }}" class="text-sm text-red-600 hover:text-red-700 mb-2 inline-block">← Volver al reporte del curso</a>
                    <h1 class="text-3xl font-display font-bold text-gray-900">Reporte Individual de Asistencia</h1>
                    <p class="text-gray-600 mt-1">{{ $enrollment->student->full_name }}</p>
                </div>
                <button onclick="window.print()" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                    Imprimir Reporte
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-display font-semibold text-gray-900 mb-4 border-b pb-2">Información del Estudiante</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-gray-500">Nombre Completo</dt>
                            <dd class="text-base font-medium text-gray-900">{{ $enrollment->student->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-gray-500">Documento de Identidad</dt>
                            <dd class="text-base font-medium text-gray-900">{{ $enrollment->student->identification }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $enrollment->student->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-gray-500">Teléfono</dt>
                                <dd class="text-sm text-gray-900">{{ $enrollment->student->phone }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
                <div>
                    <h2 class="text-xl font-display font-semibold text-gray-900 mb-4 border-b pb-2">Información del Curso</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-gray-500">Curso</dt>
                            <dd class="text-base font-medium text-gray-900">{{ $enrollment->courseOffering->full_name }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-gray-500">Código de Inscripción</dt>
                                <dd class="text-sm font-mono text-gray-900">{{ $enrollment->enrollment_code }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wider text-gray-500">Fecha de Alta</dt>
                                <dd class="text-sm text-gray-900">{{ $enrollment->enrollment_date->format('d/m/Y') }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-gray-500">Estado de Matrícula</dt>
                            <dd class="mt-1">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 uppercase">
                                    {{ $enrollment->status }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-gray-400">
                <p class="text-xs text-gray-600 mb-1">Total Sesiones</p>
                <p class="text-2xl font-display font-bold text-gray-900">{{ $stats['total_sessions'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-md p-4 border-l-4 border-green-500">
                <p class="text-xs text-green-700 mb-1">Presentes</p>
                <p class="text-2xl font-display font-bold text-green-700">{{ $stats['attended'] }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                <p class="text-xs text-yellow-700 mb-1">Tarde</p>
                <p class="text-2xl font-display font-bold text-yellow-700">{{ $stats['late'] }}</p>
            </div>
            <div class="bg-red-50 rounded-lg shadow-md p-4 border-l-4 border-red-500">
                <p class="text-xs text-red-700 mb-1">Ausentes</p>
                <p class="text-2xl font-display font-bold text-red-700">{{ $stats['absent'] }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-md p-4 border-l-4 border-blue-500">
                <p class="text-xs text-blue-700 mb-1">Justificados</p>
                <p class="text-2xl font-display font-bold text-blue-700">{{ $stats['excused'] }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <p class="text-xs text-purple-700 mb-1">% Asistencia</p>
                <p class="text-2xl font-display font-bold text-purple-700">{{ number_format($stats['percentage'], 1) }}%</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-display font-semibold text-gray-900">Detalle Cronológico</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sesión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($enrollment->attendances->sortBy('courseSession.class_date') as $index => $attendance)
                            @php
                                $statusConfig = match($attendance->status) {
                                    'present' => ['label' => 'Presente', 'class' => 'bg-green-100 text-green-800'],
                                    'late' => ['label' => 'Tarde', 'class' => 'bg-yellow-100 text-yellow-800'],
                                    'absent' => ['label' => 'Ausente', 'class' => 'bg-red-100 text-red-800'],
                                    'excused' => ['label' => 'Justificado', 'class' => 'bg-blue-100 text-blue-800'],
                                    default => ['label' => 'Sin registro', 'class' => 'bg-gray-100 text-gray-800']
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Sesión #{{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $attendance->courseSession->class_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 capitalize">{{ $attendance->courseSession->class_date->locale('es')->isoFormat('dddd') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->notes ?: '---' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">
                                    No se encontraron registros de asistencia para este curso.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 {{ $stats['percentage'] >= 80 ? 'border-green-500' : 'border-red-500' }}">
            <h3 class="text-lg font-display font-semibold text-gray-900 mb-4">Elegibilidad para Certificación</h3>
            @if($stats['percentage'] >= 80)
                <div class="flex items-center p-4 bg-green-50 rounded-lg text-green-800">
                    <svg class="h-8 w-8 text-green-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-bold">Elegible para certificado</p>
                        <p class="text-sm">El estudiante ha superado el requisito mínimo del 80% de asistencia.</p>
                    </div>
                </div>
            @else
                <div class="flex items-center p-4 bg-red-50 rounded-lg text-red-800">
                    <svg class="h-8 w-8 text-red-500 mr-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-bold">No elegible para certificado</p>
                        <p class="text-sm">Se requiere un mínimo de 80% de asistencia. Faltan {{ number_format(80 - $stats['percentage'], 1) }}% para alcanzar el objetivo.</p>
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
@endsection