@extends('layouts.app')

@section('title', 'Nueva Inscripción')
@section('page-title', 'Nueva Inscripción')

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
                        <span class="text-gray-700 font-medium">Nueva Inscripción</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('enrollments.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información de la Inscripción -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información de la Inscripción
                    </h2>

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Student -->
                        <div>
                            <label for="student_search" class="label-elegant">Estudiante *</label>
                            <div class="relative">
                                <input type="text" 
                                       id="student_search" 
                                       class="input-elegant @error('student_id') border-red-500 @enderror"
                                       placeholder="Buscar por nombre o cédula..."
                                       autocomplete="off"
                                       oninput="searchStudents(this.value)">
                                
                                <!-- Hidden input for actual student_id -->
                                <input type="hidden" 
                                       id="student_id" 
                                       name="student_id" 
                                       value="{{ old('student_id') }}"
                                       required>
                                
                                <!-- Search Results Dropdown -->
                                <div id="student-results" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded shadow-lg hidden max-h-64 overflow-y-auto">
                                    <!-- Results will be populated here -->
                                </div>
                                
                                <!-- Selected Student Display -->
                                <div id="selected-student" class="mt-3 p-3 bg-green-50 border border-green-200 rounded hidden">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-primary-dark" id="selected-student-name"></p>
                                            <p class="text-sm text-gray-600" id="selected-student-id"></p>
                                        </div>
                                        <button type="button" 
                                                onclick="clearStudentSelection()"
                                                class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('student_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <div id="student-info" class="mt-3 p-3 bg-neutral-bg rounded hidden">
                                <p class="text-sm text-gray-600"><strong>Email:</strong> <span id="student-email"></span></p>
                                <p class="text-sm text-gray-600"><strong>Teléfono:</strong> <span id="student-phone"></span></p>
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
                                            data-start="{{ $offering->start_date->format('Y-m-d') }}"
                                            data-dates="{{ $offering->start_date->format('d/m/Y') }} - {{ $offering->end_date->format('d/m/Y') }}"
                                            data-generation="{{ $offering->generation_name ?? '' }}"
                                            {{ old('course_offering_id') == $offering->id ? 'selected' : '' }}>
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

                            <div id="offering-info" class="mt-3 p-3 bg-neutral-bg rounded hidden">
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <p class="text-gray-500">Ubicación:</p>
                                        <p class="font-medium text-primary-dark" id="offering-location"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Fechas:</p>
                                        <p class="font-medium text-primary-dark" id="offering-dates"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Precio:</p>
                                        <p class="font-medium text-primary-dark text-lg" id="offering-price"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Cupos disponibles:</p>
                                        <p class="font-medium" id="offering-spots"></p>
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
                                   value="{{ old('enrollment_date', date('Y-m-d')) }}"
                                   class="input-elegant @error('enrollment_date') border-red-500 @enderror"
                                   required
                                   onchange="updatePaymentSchedule()">
                            @error('enrollment_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Price Paid -->
                            <div>
                                <label for="price_paid" class="label-elegant">Precio Total (USD) *</label>
                                <input type="number" 
                                       id="price_paid" 
                                       name="price_paid" 
                                       value="{{ old('price_paid') }}"
                                       class="input-elegant @error('price_paid') border-red-500 @enderror"
                                       step="0.01"
                                       min="0"
                                       required
                                       oninput="calculateTotal()">
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
                                       value="{{ old('discount', 0) }}"
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
                                <span class="text-2xl font-display font-bold text-primary-dark" id="total-display">$0.00</span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="label-elegant">Notas</label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="input-elegant @error('notes') border-red-500 @enderror"
                                      placeholder="Notas adicionales sobre la inscripción...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Requires Approval -->
                        <div class="flex items-center space-x-3 p-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg">
                            <input type="checkbox"
                                   id="requires_approval"
                                   name="requires_approval"
                                   value="1"
                                   {{ old('requires_approval') ? 'checked' : '' }}
                                   class="w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                            <label for="requires_approval" class="flex-1">
                                <span class="font-semibold text-gray-900">Requiere aprobación de dirección</span>
                                <p class="text-sm text-gray-600 mt-1">
                                    Marca esta casilla si la inscripción necesita ser aprobada por dirección antes de ser procesada completamente.
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Plan de Pagos -->
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Plan de Pagos
                    </h2>

                    <div class="grid grid-cols-1 gap-4 sm:gap-6">
                        <!-- Payment Type -->
                        <div>
                            <label class="label-elegant">Tipo de Pago *</label>
                            <div class="grid grid-cols-2 gap-4 mt-2">
                                <label class="flex items-center p-4 border-2 rounded cursor-pointer transition-all hover:border-accent-red {{ old('payment_type', 'contado') == 'contado' ? 'border-accent-red bg-red-50' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="payment_type" 
                                           value="contado" 
                                           {{ old('payment_type', 'contado') == 'contado' ? 'checked' : '' }}
                                           onchange="togglePaymentType()"
                                           class="w-4 h-4 text-accent-red">
                                    <div class="ml-3">
                                        <p class="font-medium text-primary-dark">Pago de Contado</p>
                                        <p class="text-xs text-gray-600">Pago único completo</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 rounded cursor-pointer transition-all hover:border-accent-red {{ old('payment_type') == 'cuotas' ? 'border-accent-red bg-red-50' : 'border-gray-200' }}">
                                    <input type="radio" 
                                           name="payment_type" 
                                           value="cuotas" 
                                           {{ old('payment_type') == 'cuotas' ? 'checked' : '' }}
                                           onchange="togglePaymentType()"
                                           class="w-4 h-4 text-accent-red">
                                    <div class="ml-3">
                                        <p class="font-medium text-primary-dark">Pago en Cuotas</p>
                                        <p class="text-xs text-gray-600">Pago fraccionado</p>
                                    </div>
                                </label>
                            </div>
                            @error('payment_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cuotas Options -->
                        <div id="cuotas-options" class="{{ old('payment_type', 'contado') == 'cuotas' ? '' : 'hidden' }}">
                            <div class="bg-neutral-bg p-4 rounded space-y-4">
                                <!-- Periodicity -->
                                <div>
                                    <label for="periodicity" class="label-elegant">Periodicidad *</label>
                                    <select id="periodicity" 
                                            name="periodicity" 
                                            class="input-elegant @error('periodicity') border-red-500 @enderror"
                                            onchange="calculateInstallments()">
                                        <option value="">Seleccionar periodicidad</option>
                                        <option value="semanal" {{ old('periodicity') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                        <option value="quincenal" {{ old('periodicity') == 'quincenal' ? 'selected' : '' }}>Quincenal</option>
                                        <option value="mensual" {{ old('periodicity') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                    </select>
                                    @error('periodicity')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Number of Installments -->
                                <div>
                                    <label for="number_of_installments" class="label-elegant">
                                        Número de Cuotas
                                        <span class="text-sm font-normal text-gray-500">(calculado automáticamente)</span>
                                    </label>
                                    <input type="number" 
                                           id="number_of_installments" 
                                           name="number_of_installments" 
                                           value="{{ old('number_of_installments', 1) }}"
                                           class="input-elegant @error('number_of_installments') border-red-500 @enderror"
                                           min="1"
                                           readonly
                                           oninput="updatePaymentSchedule()">
                                    <p class="text-xs text-gray-500 mt-1">
                                        Este valor se calcula automáticamente según la periodicidad y las fechas del curso.
                                    </p>
                                    @error('number_of_installments')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Manual Override Option -->
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           id="manual_installments" 
                                           class="w-4 h-4 text-accent-red border-gray-300 rounded focus:ring-accent-red"
                                           onchange="toggleManualInstallments()">
                                    <label for="manual_installments" class="text-sm text-gray-700">
                                        Configurar número de cuotas manualmente
                                    </label>
                                </div>

                                <!-- Estimated Schedule Preview -->
                                <div id="schedule-preview" class="hidden">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="label-elegant mb-0">Cronograma Estimado</label>
                                        <span class="text-sm text-gray-600">
                                            <strong id="estimated-installments">0</strong> cuotas de 
                                            <strong id="estimated-amount">$0.00</strong>
                                        </span>
                                    </div>
                                    <div class="bg-white rounded p-3 max-h-48 overflow-y-auto">
                                        <div id="schedule-list" class="space-y-2 text-sm"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        * Este es un cronograma estimado. Las fechas pueden ajustarse automáticamente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-premium">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('enrollments.index') }}" class="btn-secondary text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Inscripción
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Settings -->
                <div class="card-premium mb-4 sm:mb-6">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm sm:text-base">
                        Estado
                    </h3>
                    
                    <div>
                        <label for="status" class="label-elegant">Estado de la Inscripción</label>
                        <select id="status" 
                                name="status" 
                                class="input-elegant @error('status') border-red-500 @enderror">
                            <option value="inscrito" {{ old('status', 'inscrito') == 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                            <option value="en_curso" {{ old('status') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                            <option value="completado" {{ old('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="retirado" {{ old('status') == 'retirado' ? 'selected' : '' }}>Retirado</option>
                            <option value="suspendido" {{ old('status') == 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                        </select>
                    </div>
                </div>

                <!-- Help -->
                <div class="card-premium">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-display font-semibold text-primary-dark text-sm sm:text-base">
                            Información
                        </h3>
                    </div>
                    
                    <div class="space-y-3 text-xs sm:text-sm text-gray-600">
                        <p>
                            <strong class="text-primary-dark">Plan de Pagos:</strong> 
                            Seleccione cómo el estudiante pagará el curso.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Contado:</strong> 
                            Pago único el día de la inscripción.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Cuotas:</strong> 
                            Primera cuota hoy, última cuota el día del curso.
                        </p>
                        <p>
                            <strong class="text-primary-dark">Periodicidad:</strong> 
                            Determina cada cuánto se generan los pagos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let courseStartDate = null;
let searchTimeout = null;
let selectedStudentData = null;

// Search students
function searchStudents(query) {
    clearTimeout(searchTimeout);
    
    const resultsDiv = document.getElementById('student-results');
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`{{ route('api.students.search') }}?q=${encodeURIComponent(query)}`);
            const students = await response.json();
            
            if (students.length === 0) {
                resultsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No se encontraron estudiantes</div>';
                resultsDiv.classList.remove('hidden');
                return;
            }
            
            resultsDiv.innerHTML = students.map(student => `
                <div class="p-3 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                     onclick='selectStudent(${JSON.stringify(student)})'>
                    <p class="font-medium text-primary-dark">${student.first_name} ${student.last_name}</p>
                    <p class="text-sm text-gray-600">${student.identification}</p>
                    <p class="text-xs text-gray-500">${student.email || ''}</p>
                </div>
            `).join('');
            
            resultsDiv.classList.remove('hidden');
            
        } catch (error) {
            console.error('Error searching students:', error);
        }
    }, 300);
}

// Select student
function selectStudent(student) {
    selectedStudentData = student;
    
    // Set hidden input
    document.getElementById('student_id').value = student.id;
    
    // Clear search input
    document.getElementById('student_search').value = '';
    
    // Hide results
    document.getElementById('student-results').classList.add('hidden');
    
    // Show selected student
    document.getElementById('selected-student-name').textContent = `${student.first_name} ${student.last_name}`;
    document.getElementById('selected-student-id').textContent = student.identification;
    document.getElementById('selected-student').classList.remove('hidden');
    
    // Show student info
    document.getElementById('student-email').textContent = student.email || 'N/A';
    document.getElementById('student-phone').textContent = student.phone || 'N/A';
    document.getElementById('student-info').classList.remove('hidden');
}

// Clear student selection
function clearStudentSelection() {
    selectedStudentData = null;
    document.getElementById('student_id').value = '';
    document.getElementById('student_search').value = '';
    document.getElementById('selected-student').classList.add('hidden');
    document.getElementById('student-info').classList.add('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const searchInput = document.getElementById('student_search');
    const resultsDiv = document.getElementById('student-results');
    
    if (searchInput && resultsDiv && !searchInput.contains(event.target) && !resultsDiv.contains(event.target)) {
        resultsDiv.classList.add('hidden');
    }
});

// Load offering info
function loadOfferingInfo() {
    const select = document.getElementById('course_offering_id');
    const option = select.options[select.selectedIndex];
    const infoDiv = document.getElementById('offering-info');
    
    if (option.value) {
        const price = parseFloat(option.dataset.price);
        const available = parseInt(option.dataset.available);
        courseStartDate = option.dataset.start;
        
        document.getElementById('offering-location').textContent = option.dataset.location || 'N/A';
        document.getElementById('offering-dates').textContent = option.dataset.dates || 'N/A';
        document.getElementById('offering-price').textContent = '$' + price.toFixed(2);
        
        const spotsElement = document.getElementById('offering-spots');
        spotsElement.textContent = available + ' cupos';
        spotsElement.className = 'font-medium ' + (available > 0 ? 'text-green-600' : 'text-red-600');
        
        // Auto-fill price
        document.getElementById('price_paid').value = price.toFixed(2);
        calculateTotal();
        calculateInstallments();
        
        infoDiv.classList.remove('hidden');
    } else {
        infoDiv.classList.add('hidden');
        courseStartDate = null;
    }
}

// Calculate total
function calculateTotal() {
    const price = parseFloat(document.getElementById('price_paid').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const total = price - discount;
    
    document.getElementById('total-display').textContent = '$' + total.toFixed(2);
    updatePaymentSchedule();
}

// Toggle payment type
function togglePaymentType() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    const cuotasOptions = document.getElementById('cuotas-options');
    
    if (paymentType === 'cuotas') {
        cuotasOptions.classList.remove('hidden');
        calculateInstallments();
    } else {
        cuotasOptions.classList.add('hidden');
        document.getElementById('schedule-preview').classList.add('hidden');
    }
}

// Toggle manual installments
function toggleManualInstallments() {
    const checkbox = document.getElementById('manual_installments');
    const input = document.getElementById('number_of_installments');
    
    if (checkbox.checked) {
        input.removeAttribute('readonly');
        input.classList.add('bg-white');
        input.classList.remove('bg-gray-100');
        input.focus();
    } else {
        input.setAttribute('readonly', true);
        input.classList.remove('bg-white');
        input.classList.add('bg-gray-100');
        calculateInstallments();
    }
}

// Calculate installments based on periodicity
function calculateInstallments() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value;
    
    if (paymentType !== 'cuotas') return;
    
    const periodicity = document.getElementById('periodicity').value;
    const enrollmentDate = document.getElementById('enrollment_date').value;
    const manualCheckbox = document.getElementById('manual_installments');
    
    if (!periodicity || !enrollmentDate || !courseStartDate || manualCheckbox.checked) {
        return;
    }
    
    // Calculate days between enrollment and course start
    const start = new Date(enrollmentDate);
    const end = new Date(courseStartDate);
    const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    
    let installments = 1;
    
    if (periodicity === 'semanal') {
        installments = Math.max(1, Math.ceil(days / 7));
    } else if (periodicity === 'quincenal') {
        installments = Math.max(1, Math.ceil(days / 15));
    } else if (periodicity === 'mensual') {
        installments = Math.max(1, Math.ceil(days / 30));
    }
    
    // Update input
    document.getElementById('number_of_installments').value = installments;
    
    // Update schedule preview
    updatePaymentSchedule();
}

// Update payment schedule preview
function updatePaymentSchedule() {
    const paymentType = document.querySelector('input[name="payment_type"]:checked')?.value;
    
    if (paymentType !== 'cuotas') return;
    
    const periodicity = document.getElementById('periodicity').value;
    const enrollmentDate = document.getElementById('enrollment_date').value;
    const installments = parseInt(document.getElementById('number_of_installments').value) || 1;
    const total = parseFloat(document.getElementById('price_paid').value || 0) - parseFloat(document.getElementById('discount').value || 0);
    
    if (!periodicity || !enrollmentDate || !courseStartDate || total <= 0 || installments < 1) {
        document.getElementById('schedule-preview').classList.add('hidden');
        return;
    }
    
    const installmentAmount = total / installments;
    
    // Update summary
    document.getElementById('estimated-installments').textContent = installments;
    document.getElementById('estimated-amount').textContent = '$' + installmentAmount.toFixed(2);
    
    // Generate schedule list
    const scheduleList = document.getElementById('schedule-list');
    scheduleList.innerHTML = '';
    
    const start = new Date(enrollmentDate);
    const end = new Date(courseStartDate);
    let currentDate = new Date(start);
    
    // Meses en español abreviados
    const monthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    
    for (let i = 1; i <= installments; i++) {
        let dueDate;
        
        if (i === installments) {
            // Last installment is always on course start date
            dueDate = new Date(end);
        } else {
            dueDate = new Date(currentDate);
        }
        
        const amount = (i === installments) 
            ? (total - (installmentAmount * (installments - 1)))
            : installmentAmount;
        
        // Formatear fecha: DD-MMM-YYYY
        const day = String(dueDate.getDate()).padStart(2, '0');
        const month = monthNames[dueDate.getMonth()];
        const year = dueDate.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;
        
        const div = document.createElement('div');
        div.className = 'flex justify-between p-2 bg-neutral-bg rounded';
        div.innerHTML = `
            <span class="text-gray-600">Cuota ${i}:</span>
            <span class="font-medium text-primary-dark">${formattedDate} - $${amount.toFixed(2)}</span>
        `;
        scheduleList.appendChild(div);
        
        // Calculate next date based on periodicity
        if (i < installments) {
            if (periodicity === 'semanal') {
                currentDate.setDate(currentDate.getDate() + 7);
            } else if (periodicity === 'quincenal') {
                currentDate.setDate(currentDate.getDate() + 15);
            } else if (periodicity === 'mensual') {
                currentDate.setMonth(currentDate.getMonth() + 1);
            }
        }
    }
    
    document.getElementById('schedule-preview').classList.remove('hidden');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Load info if student already selected (from old input)
    const studentIdValue = document.getElementById('student_id').value;
    if (studentIdValue && selectedStudentData) {
        document.getElementById('selected-student').classList.remove('hidden');
        document.getElementById('student-info').classList.remove('hidden');
    }
    
    if (document.getElementById('course_offering_id').value) {
        loadOfferingInfo();
    }
    
    // Setup listeners
    document.getElementById('price_paid').addEventListener('input', calculateTotal);
    
    // Initialize payment type
    togglePaymentType();
});
</script>
@endpush
@endsection