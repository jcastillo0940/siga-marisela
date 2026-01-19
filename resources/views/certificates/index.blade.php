@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b-4 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-display font-bold text-gray-900">Gestión de Certificados</h1>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-red-600">← Volver al inicio</a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-600 mb-1">Total Certificados</p>
                <p class="text-3xl font-display font-bold text-gray-900">{{ $certificates->total() }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-md p-6">
                <p class="text-sm text-green-700 mb-1">Emitidos</p>
                <p class="text-3xl font-display font-bold text-green-700">{{ $certificates->where('status', 'issued')->count() }}</p>
            </div>
            <div class="bg-red-50 rounded-lg shadow-md p-6">
                <p class="text-sm text-red-700 mb-1">Revocados</p>
                <p class="text-3xl font-display font-bold text-red-700">{{ $certificates->where('status', 'revoked')->count() }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-md p-6">
                <p class="text-sm text-blue-700 mb-1">Plantillas Activas</p>
                <p class="text-3xl font-display font-bold text-blue-700">{{ $templates->count() }}</p>
            </div>
        </div>

        <!-- Certificates List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-display font-semibold text-gray-900">Certificados Generados</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asistencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Emisión</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($certificates as $certificate)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $certificate->certificate_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $certificate->verification_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $certificate->student_full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $certificate->student_document }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $certificate->course_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ number_format($certificate->attendance_percentage, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $certificate->issued_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($certificate->status === 'issued')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Emitido</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Revocado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('certificates.download', $certificate->id) }}" 
                                       class="text-red-600 hover:text-red-900 mr-3">
                                        Descargar
                                    </a>
                                    <a href="{{ route('certificates.student', $certificate->student_id) }}" 
                                       class="text-gray-600 hover:text-gray-900">
                                        Ver Todos
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    No hay certificados generados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($certificates->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection