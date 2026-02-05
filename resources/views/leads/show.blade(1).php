@extends('layouts.app')

@section('title', 'Detalles del Lead')
@section('page-title', 'Detalles del Lead')

@section('content')
<div class="fade-in">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('leads.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Leads
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $lead->full_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Cabecera con Foto/Avatar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            {{-- Sección de Fotografía Corregida --}}
            <div class="flex-shrink-0">
                @if($lead->student_photo)
                    {{-- Cargamos la imagen directamente. Si falla el archivo, el JS mostrará la inicial --}}
                    <img src="{{ asset('storage/' . $lead->student_photo) }}" 
                         alt="{{ $lead->full_name }}" 
                         class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover shadow-elegant border-4 border-white ring-1 ring-gray-100"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    
                    {{-- Este div está oculto por defecto y solo se muestra si la imagen de arriba falla (Error 404) --}}
                    <div style="display: none;" class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant border-4 border-white">
                        {{ substr($lead->first_name, 0, 1) }}
                    </div>
                @else
                    {{-- Si no hay ruta de foto en la base de datos --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant border-4 border-white">
                        {{ substr($lead->first_name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $lead->full_name }}
                </h1>
                <div class="flex flex-wrap items-center gap-3 text-gray-600">
                    <a href="mailto:{{ $lead->email }}" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $lead->email }}
                    </a>
                    <a href="tel:{{ $lead->phone }}" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $lead->phone }}
                    </a>
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    @php
                        $statusColors = [
                            'nuevo' => 'badge-info',
                            'contactado' => 'badge-warning',
                            'interesado' => 'badge badge-info',
                            'negociacion' => 'badge badge-warning',
                            'inscrito' => 'badge-success',
                            'perdido' => 'badge-danger'
                        ];
                        $payStatusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'verified' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="badge {{ $statusColors[$lead->status] ?? 'badge-info' }}">
                        {{ ucfirst($lead->status) }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payStatusColors[$lead->payment_status] ?? 'bg-gray-100' }}">
                        Pago: {{ ucfirst($lead->payment_status) }}
                    </span>
                    @if($lead->isConverted())
                        <span class="badge badge-success">Convertido</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('leads.edit'))
            <a href="{{ route('leads.edit', $lead->id) }}" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif
            <a href="{{ route('leads.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    {{-- Sección de Validación de Pago --}}
    @if($lead->payment_status === 'pending' && !$lead->isConverted() && $lead->payment_receipt_path)
    <div class="card-premium border-2 border-accent-red mb-8 bg-red-50/20">
        <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 text-center sm:text-left">Verificación de Comprobante</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <label class="label-elegant mb-2 block">Imagen del recibo</label>
                <a href="{{ asset('storage/' . $lead->payment_receipt_path) }}" target="_blank" class="block group">
                    <img src="{{ asset('storage/' . $lead->payment_receipt_path) }}" class="w-full h-64 object-contain bg-white rounded-lg border shadow-sm transition-transform group-hover:scale-[1.01]">
                    <span class="text-xs text-blue-600 mt-2 block font-medium">Click para abrir en pantalla completa ↗</span>
                </a>
            </div>
            <div class="flex flex-col justify-center">
                <form action="{{ route('leads.verify-payment', $lead->id) }}" method="POST" id="verification-form" class="space-y-4">
                    @csrf
                    
                    {{-- Información del Curso --}}
                    <div class="bg-blue-50 border border-blue-200 rounded p-3">
                        <p class="text-sm font-medium text-primary-dark">
                            <strong>Curso:</strong> {{ $lead->courseOffering->course->name ?? 'No especificado' }}
                        </p>
                        @if($lead->courseOffering)
                        <p class="text-sm text-gray-600 mt-1">
                            <strong>Precio del curso:</strong> ${{ number_format($lead->courseOffering->price, 2) }}
                        </p>
                        @endif
                    </div>

                    {{-- Monto Pagado --}}
                    <div>
                        <label for="amount_paid" class="label-elegant">Monto Pagado (USD) *</label>
                        <input type="number" 
                               id="amount_paid" 
                               name="amount_paid" 
                               step="0.01" 
                               min="0.01"
                               class="input-elegant @error('amount_paid') border-red-500 @enderror" 
                               placeholder="0.00"
                               required
                               oninput="checkPaymentAmount()">
                        @error('amount_paid')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Ingrese el monto exacto que aparece en el comprobante</p>
                    </div>

                    {{-- Payment Status Message --}}
                    <div id="payment-status-message" class="hidden"></div>

                    {{-- Plan de Pagos Section --}}
                    <div id="payment-plan-section" class="hidden">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Pago parcial detectado.</strong> Se requiere configurar un plan de pagos para el saldo restante.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-neutral-bg p-4 rounded space-y-4">
                            <div>
                                <label class="label-elegant">Saldo Pendiente</label>
                                <p class="text-2xl font-bold text-red-600" id="remaining-balance">$0.00</p>
                            </div>

                            {{-- Payment Type --}}
                            <div>
                                <label class="label-elegant">Tipo de Pago *</label>
                                <div class="grid grid-cols-2 gap-3 mt-2">
                                    <label class="flex items-center p-3 border-2 rounded cursor-pointer transition-all hover:border-accent-red border-gray-200">
                                        <input type="radio" 
                                               name="payment_type" 
                                               value="cuotas" 
                                               checked
                                               onchange="togglePaymentPlanOptions()"
                                               class="w-4 h-4 text-accent-red">
                                        <div class="ml-2">
                                            <p class="font-medium text-primary-dark text-sm">Cuotas</p>
                                            <p class="text-xs text-gray-600">Pagos fraccionados</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Periodicity --}}
                            <div>
                                <label for="periodicity" class="label-elegant">Periodicidad *</label>
                                <select id="periodicity" 
                                        name="periodicity" 
                                        class="input-elegant"
                                        onchange="calculateLeadInstallments()">
                                    <option value="">Seleccionar periodicidad</option>
                                    <option value="semanal">Semanal</option>
                                    <option value="quincenal">Quincenal</option>
                                    <option value="mensual">Mensual</option>
                                </select>
                            </div>

                            {{-- Number of Installments --}}
                            <div>
                                <label for="number_of_installments" class="label-elegant">
                                    Número de Cuotas
                                    <span class="text-sm font-normal text-gray-500">(calculado automáticamente)</span>
                                </label>
                                <input type="number" 
                                       id="number_of_installments" 
                                       name="number_of_installments" 
                                       value="1"
                                       class="input-elegant"
                                       min="1"
                                       readonly
                                       oninput="updateLeadPaymentSchedule()">
                                <p class="text-xs text-gray-500 mt-1">
                                    Este valor se calcula automáticamente según la periodicidad y las fechas del curso.
                                </p>
                            </div>

                            {{-- Manual Override --}}
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" 
                                       id="manual_installments_lead" 
                                       class="w-4 h-4 text-accent-red border-gray-300 rounded focus:ring-accent-red"
                                       onchange="toggleLeadManualInstallments()">
                                <label for="manual_installments_lead" class="text-sm text-gray-700">
                                    Configurar número de cuotas manualmente
                                </label>
                            </div>

                            {{-- Schedule Preview --}}
                            <div id="schedule-preview-lead" class="hidden">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="label-elegant mb-0">Cronograma Estimado</label>
                                    <span class="text-sm text-gray-600">
                                        <strong id="estimated-installments-lead">0</strong> cuotas de 
                                        <strong id="estimated-amount-lead">$0.00</strong>
                                    </span>
                                </div>
                                <div class="bg-white rounded p-3 max-h-48 overflow-y-auto">
                                    <div id="schedule-list-lead" class="space-y-2 text-sm"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    * Este es un cronograma estimado. Las fechas pueden ajustarse automáticamente.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Notas de Verificación --}}
                    <div>
                        <label class="label-elegant">Notas de Verificación</label>
                        <textarea name="notes" class="input-elegant" rows="3" placeholder="Confirmación de Yappy, transferencia #..."></textarea>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                                name="status" 
                                value="verified" 
                                id="approve-btn"
                                class="btn-primary flex-1 bg-green-600 hover:bg-green-700"
                                disabled>
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Aprobar y Convertir
                        </button>
                        <button type="submit" 
                                name="status" 
                                value="rejected" 
                                class="btn-secondary flex-1 text-red-600 border-red-200">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rechazar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    let coursePrice = {{ $lead->courseOffering->price ?? 0 }};
    let courseStartDate = '{{ $lead->courseOffering->start_date->format('Y-m-d') ?? date('Y-m-d') }}';
    let enrollmentDate = '{{ date('Y-m-d') }}';

    // Check payment amount
    function checkPaymentAmount() {
        const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
        const approveBtn = document.getElementById('approve-btn');
        const paymentPlanSection = document.getElementById('payment-plan-section');
        const statusMessage = document.getElementById('payment-status-message');

        // Enable approve button if amount is entered
        approveBtn.disabled = amountPaid <= 0;

        if (amountPaid <= 0) {
            statusMessage.classList.add('hidden');
            paymentPlanSection.classList.add('hidden');
            return;
        }

        // Check if payment is complete or partial
        if (amountPaid >= coursePrice) {
            // Full payment
            statusMessage.innerHTML = `
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                <strong>¡Pago completo!</strong> El monto cubre el precio total del curso.
                            </p>
                        </div>
                    </div>
                </div>
            `;
            statusMessage.classList.remove('hidden');
            paymentPlanSection.classList.add('hidden');
            
            // Remove required from payment plan fields
            document.getElementById('periodicity').removeAttribute('required');
            document.getElementById('number_of_installments').removeAttribute('required');
        } else {
            // Partial payment - show payment plan
            const remaining = coursePrice - amountPaid;
            document.getElementById('remaining-balance').textContent = '$' + remaining.toFixed(2);
            
            statusMessage.classList.add('hidden');
            paymentPlanSection.classList.remove('hidden');
            
            // Add required to payment plan fields
            document.getElementById('periodicity').setAttribute('required', 'required');
            document.getElementById('number_of_installments').setAttribute('required', 'required');
            
            // Recalculate installments
            calculateLeadInstallments();
        }
    }

    // Toggle payment plan options
    function togglePaymentPlanOptions() {
        calculateLeadInstallments();
    }

    // Toggle manual installments
    function toggleLeadManualInstallments() {
        const checkbox = document.getElementById('manual_installments_lead');
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
            calculateLeadInstallments();
        }
    }

    // Calculate installments
    function calculateLeadInstallments() {
        const periodicity = document.getElementById('periodicity').value;
        const manualCheckbox = document.getElementById('manual_installments_lead');
        
        if (!periodicity || manualCheckbox.checked) {
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
        
        document.getElementById('number_of_installments').value = installments;
        updateLeadPaymentSchedule();
    }

    // Update payment schedule
    function updateLeadPaymentSchedule() {
        const periodicity = document.getElementById('periodicity').value;
        const installments = parseInt(document.getElementById('number_of_installments').value) || 1;
        const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
        const remaining = coursePrice - amountPaid;
        
        if (!periodicity || remaining <= 0 || installments < 1) {
            document.getElementById('schedule-preview-lead').classList.add('hidden');
            return;
        }
        
        const installmentAmount = remaining / installments;
        
        // Update summary
        document.getElementById('estimated-installments-lead').textContent = installments;
        document.getElementById('estimated-amount-lead').textContent = '$' + installmentAmount.toFixed(2);
        
        // Generate schedule list
        const scheduleList = document.getElementById('schedule-list-lead');
        scheduleList.innerHTML = '';
        
        const start = new Date(enrollmentDate);
        const end = new Date(courseStartDate);
        let currentDate = new Date(start);
        
        const monthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        
        for (let i = 1; i <= installments; i++) {
            let dueDate;
            
            if (i === installments) {
                dueDate = new Date(end);
            } else {
                dueDate = new Date(currentDate);
            }
            
            const amount = (i === installments) 
                ? (remaining - (installmentAmount * (installments - 1)))
                : installmentAmount;
            
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
        
        document.getElementById('schedule-preview-lead').classList.remove('hidden');
    }
    </script>
    @endpush
    @endif

    {{-- Resto de la cuadrícula de información --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">Información del Registro</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Fecha de Nacimiento</label>
                        <p class="text-primary-dark font-medium">{{ $lead->birth_date_text ?? 'No registrada' }}</p>
                    </div>
                    <div>
                        <label class="label-elegant">Edad</label>
                        <p class="text-primary-dark font-medium">{{ $lead->age ?? 'No calculada' }} años</p>
                    </div>
                    <div>
                        <label class="label-elegant">Ocupación / Actividad</label>
                        <p class="text-primary-dark font-medium">{{ $lead->occupation ?? 'No registrada' }}</p>
                    </div>
                    <div>
                        <label class="label-elegant">Instagram/TikTok</label>
                        <p class="text-primary-dark font-medium">{{ $lead->social_media_handle ?? 'No registrado' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-elegant">Dirección Completa</label>
                        <p class="text-primary-dark font-medium">{{ $lead->address_full ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>

            @if($lead->parent_name)
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">Información de Mamá / Tutor</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Nombre del Tutor</label>
                        <p class="text-primary-dark font-medium">{{ $lead->parent_name }} ({{ $lead->parent_relationship }})</p>
                    </div>
                    <div>
                        <label class="label-elegant">Teléfono del Tutor</label>
                        <p class="text-primary-dark font-medium">{{ $lead->parent_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-elegant">Ocupación del Tutor</label>
                        <p class="text-primary-dark font-medium">{{ $lead->parent_occupation ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Barra Lateral --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4">Detalles Técnicos</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-gray-600">ID del Registro</span>
                        <span class="font-semibold text-primary-dark">#{{ $lead->id }}</span>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase block mb-1">Fecha de registro</label>
                        <p class="text-primary-dark font-medium">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection