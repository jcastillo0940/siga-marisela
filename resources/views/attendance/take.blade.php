@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b-4 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <a href="{{ route('attendance.sessions', $session->course_offering_id) }}" class="text-sm text-red-600 hover:text-red-700 mb-2 inline-block">← Volver a sesiones</a>
                    <h1 class="text-3xl font-display font-bold text-gray-900">Tomar Asistencia</h1>
                    <p class="text-gray-600 mt-1">{{ $session->courseOffering->full_name }} - {{ $session->formatted_date }}</p>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4">
                <p class="text-xs text-gray-600 mb-1">Total</p>
                <p class="text-2xl font-display font-bold text-gray-900">{{ $stats['total_students'] }}</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-md p-4">
                <p class="text-xs text-green-700 mb-1">Presentes</p>
                <p class="text-2xl font-display font-bold text-green-700">{{ $stats['present'] }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow-md p-4">
                <p class="text-xs text-yellow-700 mb-1">Tarde</p>
                <p class="text-2xl font-display font-bold text-yellow-700">{{ $stats['late'] }}</p>
            </div>
            <div class="bg-red-50 rounded-lg shadow-md p-4">
                <p class="text-xs text-red-700 mb-1">Ausentes</p>
                <p class="text-2xl font-display font-bold text-red-700">{{ $stats['absent'] }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-md p-4">
                <p class="text-xs text-blue-700 mb-1">Justificados</p>
                <p class="text-2xl font-display font-bold text-blue-700">{{ $stats['excused'] }}</p>
            </div>
        </div>

        <form action="{{ route('attendance.store', $session->id) }}" method="POST" id="attendanceForm">
            @csrf
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-display font-semibold text-gray-900">Lista de Estudiantes</h2>
                    <div class="flex gap-2">
                        <button type="button" onclick="markAll('present')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                            Todos Presentes
                        </button>
                        <button type="button" onclick="markAll('absent')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                            Todos Ausentes
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($session->courseOffering->enrollments->sortBy('student.last_name') as $enrollment)
                                @php
                                    $existingAttendance = $session->attendances->where('enrollment_id', $enrollment->id)->first();
                                    $currentStatus = $existingAttendance->status ?? 'present';
                                    $currentNotes = $existingAttendance->notes ?? '';
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $enrollment->student->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $enrollment->student->identification }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="hidden" name="attendances[{{ $loop->index }}][enrollment_id]" value="{{ $enrollment->id }}">
                                        
                                        <div class="flex gap-2">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" 
                                                       name="attendances[{{ $loop->index }}][status]" 
                                                       value="present" 
                                                       class="form-radio text-green-600 focus:ring-green-500"
                                                       {{ $currentStatus === 'present' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Presente</span>
                                            </label>
                                            
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" 
                                                       name="attendances[{{ $loop->index }}][status]" 
                                                       value="late" 
                                                       class="form-radio text-yellow-600 focus:ring-yellow-500"
                                                       {{ $currentStatus === 'late' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Tarde</span>
                                            </label>
                                            
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" 
                                                       name="attendances[{{ $loop->index }}][status]" 
                                                       value="absent" 
                                                       class="form-radio text-red-600 focus:ring-red-500"
                                                       {{ $currentStatus === 'absent' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Ausente</span>
                                            </label>
                                            
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" 
                                                       name="attendances[{{ $loop->index }}][status]" 
                                                       value="excused" 
                                                       class="form-radio text-blue-600 focus:ring-blue-500"
                                                       {{ $currentStatus === 'excused' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Justificado</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" 
                                               name="attendances[{{ $loop->index }}][notes]" 
                                               value="{{ $currentNotes }}"
                                               placeholder="Notas opcionales"
                                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('attendance.sessions', $session->course_offering_id) }}" 
                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                    Guardar Asistencia
                </button>
            </div>
        </form>
    </main>
</div>

<script>
    function markAll(status) {
        const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
        radios.forEach(radio => {
            radio.checked = true;
        });
    }

    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Guardando...';
    });
</script>
@endsection