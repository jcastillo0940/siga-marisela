@extends('layouts.app')

@section('title', 'Editar Inscripción')
@section('page-title', 'Editar Inscripción')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('enrollments.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Inscripciones
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('enrollments.show', $enrollment->id) }}" class="text-gray-500 hover:text-accent-red transition-colors">
                            {{ $enrollment->enrollment_code }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Editar</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('enrollments.update', $enrollment->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header -->
                <div class="card-premium">
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold">
                            {{ substr($enrollment->student->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                                {{ $enrollment->student->full_name }}
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $enrollment->enrollment_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de la Inscripción -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información de la Inscripción
                    </h2>

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Student -->
                        <div>
                            <label for="student_id" class="label-elegant">Estudiante *</label>
                            <select id="student_id" 
                                    name="student_id" 
                                    class="input-elegant @error('student_id') border-red-500 @enderror"
                                    required
                                    onchange="loadStudentInfo()">
                                <option value="">Seleccionar estudiante</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                            data-email="{{ $student->email }}"
                                            data-phone="{{ $student->phone }}"
                                            {{ old('student_id', $enrollment->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->full_name }} - {{ $student->identification }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div id="student-info" class="mt-3 p-3 bg-neutral-bg rounded">
                                <p class="text-sm text-gray-600"><strong>Email:</strong> <span id="student-email">{{ $enrollment->student->email }}</span></p>
                                <p class="text-sm text-gray-600"><strong>Teléfono:</strong> <span id="student-phone">{{ $enrollment->student->phone }}</span></p>
                            </div>
                        </div>

                        <!-- Course Offering -->
                        <div>
                            <label for="course_offering_id" class="label-elegant">Curso/Programación *</label>
                            <select id="course_offering_id" 
                                    name="course_offering_id" 
                                    class="input-elegant @error('course_offering_id') border-red-500 @enderror"
                                    required
                                    onchange="loadOfferingInfo()">
                                <option value="">Seleccionar curso</option>
                                @foreach($offerings as $offering)
                                    <option value="{{ $offering->id }}"
                                            data-price="{{ $offering->price }}"
                                            data-available="{{ $offering->available_spots }}"
                                            data-location="{{ $offering->location }}"
                                            data-dates="{{ $offering->start_date->format('d/m/Y') }} - {{ $offering->end_date->format('d/m/Y') }}"
                                            {{ old('course_offering_id', $enrollment->course_offering_id) == $offering->id ? 'selected' : '' }}>
                                        {{ $offering->course->name }}
                                        @if($offering->is_generation && $offering->generation_name)
                                            - {{ $offering->generation_name }}
                                        @endif
                                        ({{ $offering->location }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_offering_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div id="offering-info" class="mt-3 p-3 bg-neutral-bg rounded">
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-gray-500">Ubicación:</p>
                                        <p class="font-medium text-primary-dark" id="offering-location">{{ $enrollment->courseOffering->location }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Fechas:</p>
                                        <p class="font-medium text-primary-dark" id="offering-dates">{{ $enrollment->courseOffering->start_date->format('d/m/Y') }} - {{ $enrollment->courseOffering->end_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Precio:</p>
                                        <p class="font-medium text-primary-dark text-lg" id="offering-price">{{ $enrollment->courseOffering->formatted_price }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Cupos disponibles:</p>
                                        <p class="font-medium text-green-600" id="offering-spots">{{ $enrollment->courseOffering->available_spots }} cupos</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Date -->
                        <div>
                            <label for="enrollment_date" class="label-elegant">Fecha de Inscripción *</label>
                            <input type="date" 
                                   id="enrollment_date" 
                                   name="enrollment_date" 
                                   value="{{ old('enrollment_date', $enrollment->enrollment_date->format('Y-m-d')) }}"
                                   class="input-elegant @error('enrollment_date') border-red-500 @enderror"
                                   required>
                            @error('enrollment_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Price Paid -->
                            <div>
                                <label for="price_paid" class="label-elegant">Precio a Pagar (USD) *</label>
                                <input type="number" 
                                       id="price_paid" 
                                       name="price_paid" 
                                       value="{{ old('price_paid', $enrollment->price_paid) }}"
                                       class="input-elegant @error('price_paid') border-red-500 @enderror"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('price_paid')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount -->
                            <div>
                                <label for="discount" class="label-elegant">Descuento (USD)</label>
                                <input type="number" 
                                       id="discount" 
                                       name="discount" 
                                       value="{{ old('discount', $enrollment->discount) }}"
                                       class="input-elegant @error('discount') border-red-500 @enderror"
                                       step="0.01"
                                       min="0"
                                       oninput="calculateTotal()">
                                @error('discount')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-primary-dark">Total a Pagar:</span>
                                <span class="text-2xl font-display font-bold text-primary-dark" id="total-display">${{ number_format($enrollment->final_price, 2) }}</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="label-elegant">Notas</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3"
                                      class="input-elegant @error('notes') border-red-500 @enderror">{{ old('notes', $enrollment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(auth()->user()->hasPermission('enrollments.delete'))
                            <button type="button" 
                                    onclick="showConfirmModal('¿Estás seguro de eliminar esta inscripción? Esta acción no se puede deshacer.', function() { document.getElementById('delete-form').submit(); })"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Inscripción
                            </button>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('enrollments.show', $enrollment->id) }}" class="btn-secondary text-center">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Settings -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Estado y Certificado
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="label-elegant">Estado de la Inscripción</label>
                            <select id="status" 
                                    name="status" 
                                    class="input-elegant @error('status') border-red-500 @enderror">
                                <option value="inscrito" {{ old('status', $enrollment->status) == 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                                <option value="en_curso" {{ old('status', $enrollment->status) == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="completado" {{ old('status', $enrollment->status) == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="retirado" {{ old('status', $enrollment->status) == 'retirado' ? 'selected' : '' }}>Retirado</option>
                                <option value="suspendido" {{ old('status', $enrollment->status) == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                            </select>
                        </div>

                        @if($enrollment->certificate_issued)
                        <div class="bg-green-50 border border-green-200 rounded p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Certificado Emitido</p>
                                    <p class="text-xs text-green-600">{{ $enrollment->certificate_issued_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Información
                    </h3>
                    
                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Código:</span>
                            <span class="text-primary-dark font-medium font-mono">{{ $enrollment->enrollment_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Creado:</span>
                            <span class="text-primary-dark font-medium">{{ $enrollment->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Última actualización:</span>
                            <span class="text-primary-dark font-medium">{{ $enrollment->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Hidden) -->
    @if(auth()->user()->hasPermission('enrollments.delete'))
    <form id="delete-form" 
          method="POST" 
          action="{{ route('enrollments.destroy', $enrollment->id) }}"
          class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
</div>

@push('scripts')
<script>
// Load student info
function loadStudentInfo() {
    const select = document.getElementById('student_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        document.getElementById('student-email').textContent = option.dataset.email || 'N/A';
        document.getElementById('student-phone').textContent = option.dataset.phone || 'N/A';
    }
}

// Load offering info
function loadOfferingInfo() {
    const select = document.getElementById('course_offering_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        const price = parseFloat(option.dataset.price);
        const available = parseInt(option.dataset.available);
        
        document.getElementById('offering-location').textContent = option.dataset.location || 'N/A';
        document.getElementById('offering-dates').textContent = option.dataset.dates || 'N/A';
        document.getElementById('offering-price').textContent = '$' + price.toFixed(2);
        
        const spotsElement = document.getElementById('offering-spots');
        spotsElement.textContent = available + ' cupos';
        spotsElement.className = 'font-medium ' + (available > 0 ? 'text-green-600' : 'text-red-600');
    }
}

// Calculate total
function calculateTotal() {
    const price = parseFloat(document.getElementById('price_paid').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const total = price - discount;
    
    document.getElementById('total-display').textContent = '$' + total.toFixed(2);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('price_paid').addEventListener('input', calculateTotal);
    calculateTotal();
});
</script>
@endpush
@endsection