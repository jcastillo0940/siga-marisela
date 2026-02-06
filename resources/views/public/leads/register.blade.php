@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 fade-in">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-display font-bold text-primary-dark">Formulario de Inscripción</h1>
        <p class="text-gray-600 mt-2">Academia Auténtica — Formación integral para la vida</p>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('public.leads.store') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
        @csrf

        <div class="card-premium mb-8">
            <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">1. ¿Qué curso deseas tomar?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-elegant">Curso *</label>
                    <select id="course_id" class="input-elegant" required onchange="loadOfferings(this.value)">
                        <option value="">Selecciona un curso</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label-elegant">Ubicación y Horario disponible *</label>
                    <select name="course_offering_id" id="course_offering_id" class="input-elegant" disabled required onchange="handleOfferingChange()">
                        <option value="">Primero elige un curso</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-premium mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-display font-semibold text-primary-dark">2. Información Personal</h2>
                <span class="text-xs font-medium bg-blue-100 text-blue-800 py-1 px-3 rounded-full">Estudiante Principal</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-elegant">Nombre completo *</label>
                    <input type="text" name="first_name" class="input-elegant" required placeholder="Nombres" value="{{ old('first_name') }}">
                </div>
                <div>
                    <label class="label-elegant">Apellidos *</label>
                    <input type="text" name="last_name" class="input-elegant" required placeholder="Apellidos" value="{{ old('last_name') }}">
                </div>
                <div>
                    <label class="label-elegant">Correo electrónico *</label>
                    <input type="email" name="email" class="input-elegant" required placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                </div>
                <div>
                    <label class="label-elegant">Celular de contacto *</label>
                    <input type="tel" name="phone" class="input-elegant" required placeholder="6000-0000" value="{{ old('phone') }}">
                </div>
                <div>
                    <label class="label-elegant">Fecha de nacimiento *</label>
                    <input type="date" name="birth_date_text" id="birth_date" class="input-elegant" required onchange="checkAge()" value="{{ old('birth_date_text') }}">
                </div>
                <div>
                    <label class="label-elegant">Fotografía (Opcional)</label>
                    <input type="file" name="student_photo" class="input-elegant" accept="image/*">
                </div>

                <div id="who_fills_form_container" class="md:col-span-2 hidden">
                    <label class="label-elegant">¿Quién completa este formulario? *</label>
                    <select name="who_fills_form" id="who_fills_form" class="input-elegant">
                        <option value="">Selecciona una opción</option>
                        <option value="Alumna" selected>La propia alumna</option>
                        <option value="Madre/Padre">Madre o Padre</option>
                        <option value="Tutor">Tutor Legal</option>
                    </select>
                </div>
                <input type="hidden" name="age" id="age_hidden" value="{{ old('age') }}">
            </div>
        </div>

        <div id="tutor_section" class="card-premium mb-8 hidden bg-slate-50 border-accent-red border-l-4">
            <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">3. Datos de Mamá / Tutora</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-elegant">Nombre del Tutor *</label>
                    <input type="text" name="parent_name" class="input-elegant tutor-field" placeholder="Nombre completo" value="{{ old('parent_name') }}">
                </div>
                <div>
                    <label class="label-elegant">Parentesco *</label>
                    <select name="parent_relationship" class="input-elegant tutor-field">
                        <option value="Madre" {{ old('parent_relationship') == 'Madre' ? 'selected' : '' }}>Madre</option>
                        <option value="Padre" {{ old('parent_relationship') == 'Padre' ? 'selected' : '' }}>Padre</option>
                        <option value="Tutor" {{ old('parent_relationship') == 'Tutor' ? 'selected' : '' }}>Tutor Legal</option>
                    </select>
                </div>
                <div>
                    <label class="label-elegant">Celular del Tutor *</label>
                    <input type="tel" name="parent_phone" class="input-elegant tutor-field" placeholder="6000-0000" value="{{ old('parent_phone') }}">
                </div>
                <div>
                    <label class="label-elegant">Ocupación del Tutor *</label>
                    <input type="text" name="parent_occupation" class="input-elegant tutor-field" value="{{ old('parent_occupation') }}">
                </div>
            </div>
        </div>

        <div class="card-premium mb-8">
            <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">4. Información Adicional</h2>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="label-elegant">Dirección / Ciudad *</label>
                    <textarea name="address_full" class="input-elegant" rows="2" required placeholder="Indica tu dirección residencial">{{ old('address_full') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">¿A qué te dedicas actualmente? *</label>
                        <input type="text" name="occupation" class="input-elegant" required placeholder="Estudiante, Profesional, etc." value="{{ old('occupation') }}">
                    </div>
                    <div>
                        <label class="label-elegant">Usuario de Instagram/TikTok</label>
                        <input type="text" name="social_media_handle" class="input-elegant" placeholder="@usuario" value="{{ old('social_media_handle') }}">
                    </div>
                </div>
                <div>
                    <label class="label-elegant">¿Hay algo que debamos saber? (Alergias, condiciones, etc.)</label>
                    <textarea name="medical_notes_lead" class="input-elegant" rows="2" placeholder="Notas médicas importantes">{{ old('medical_notes_lead') }}</textarea>
                </div>
            </div>
        </div>

        <div id="partners_section" class="card-premium mb-8 bg-blue-50 border-2 border-blue-200 hidden">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-display font-semibold text-primary-dark">5. Acompañantes (Opcional)</h2>
                    <p class="text-sm text-gray-600 mt-1">Este curso tiene descuentos grupales activos 🎉</p>
                </div>
                <button type="button" onclick="addPartner()" class="btn-secondary text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Agregar Acompañante
                </button>
            </div>

            <div id="partners_container" class="space-y-4">
                </div>

            <p class="text-xs text-gray-500 mt-4 italic text-center" id="promo_hint">
                💡 Tip: Los descuentos se aplican automáticamente al agregar personas.
            </p>
        </div>

        <div class="card-premium mb-8 bg-gray-900 text-white">
            <h2 class="text-xl font-display font-semibold mb-6 border-b border-gray-700 pb-4">6. Resumen de Pago</h2>
            
            <div id="price_loading" class="text-center py-8 text-gray-400">
                <svg class="animate-spin h-8 w-8 mx-auto mb-2 hidden" id="spinner_icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p id="loading_text">Selecciona un curso y horario para ver el precio.</p>
            </div>

            <div id="price_calculation_area" class="hidden animate-fade-in">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-gray-400">
                        <span>Número de personas:</span>
                        <span class="font-bold text-white text-lg"><span id="count_display">1</span> persona(s)</span>
                    </div>
                    
                    <div class="flex justify-between items-center text-gray-400">
                        <span>Precio regular c/u:</span>
                        <span class="font-mono text-gray-300" id="price_per_student_original">$0.00</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-400">
                        <span>Subtotal:</span>
                        <span class="font-mono text-gray-300 line-through" id="original_total_display">$0.00</span>
                    </div>
                    
                    <div id="discount_row" class="flex justify-between items-center text-green-400 font-medium hidden">
                        <span>✨ Descuento (<span id="rule_name_display"></span>):</span>
                        <span class="font-mono">-$<span id="savings_display">0.00</span></span>
                    </div>
                </div>

                <div class="border-t border-gray-700 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold">TOTAL A PAGAR:</span>
                        <span class="text-3xl font-display font-bold text-accent-red" id="final_total_display">$0.00</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-right">Monto único a transferir</p>
                </div>
            </div>
        </div>

        <div class="card-premium mb-8 border-2 border-accent-red/20">
            <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">7. Comprobante de Pago</h2>
            <div class="bg-accent-red/5 p-4 rounded-lg mb-6">
                <p class="text-sm text-gray-700 leading-relaxed">
                    <strong>Instrucciones:</strong> Realiza el pago vía Yappy o Transferencia por el monto total mostrado arriba. 
                    Sube la captura de pantalla o PDF aquí para que validemos tu inscripción.
                </p>
            </div>
            <div>
                <label class="label-elegant">Subir Comprobante de Pago (Único para todo el grupo) *</label>
                <input type="file" name="payment_receipt" class="input-elegant" accept="image/*,.pdf" required>
                <p class="text-xs text-gray-500 mt-2 italic">Formatos: JPG, PNG, PDF (Máx 5MB)</p>
            </div>
        </div>

        <div class="card-premium mb-8">
            <label class="label-elegant">Notas adicionales (Opcional)</label>
            <textarea name="notes" class="input-elegant" rows="3" placeholder="¿Algo más que quieras que sepamos?">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" id="submitBtn" class="btn-primary w-full py-4 text-lg shadow-elegant flex items-center justify-center transition-all duration-200">
            <span id="btnText">Finalizar Inscripción</span>
            <div id="btnLoader" class="hidden items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Procesando...</span>
            </div>
        </button>
    </form>
</div>

{{-- TEMPLATE PARA ACOMPAÑANTES --}}
<template id="partner_template">
    <div class="partner-row p-4 bg-white rounded-lg border-2 border-gray-200 relative animate-fade-in-down">
        <button type="button" onclick="removePartner(this)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
            <span class="bg-accent-red text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-2 partner-number"></span>
            Acompañante
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input type="text" name="partners[INDEX][first_name]" placeholder="Nombre *" class="input-elegant" required>
            <input type="text" name="partners[INDEX][last_name]" placeholder="Apellido *" class="input-elegant" required>
            <input type="email" name="partners[INDEX][email]" placeholder="Email *" class="input-elegant" required>
            <input type="tel" name="partners[INDEX][phone]" placeholder="Teléfono" class="input-elegant">
            <input type="date" name="partners[INDEX][birth_date_text]" placeholder="Fecha de nacimiento *" class="input-elegant" required>
            <input type="number" name="partners[INDEX][age]" placeholder="Edad *" class="input-elegant" required min="1">
            <div class="md:col-span-2">
                <select name="partners[INDEX][who_fills_form]" class="input-elegant">
                    <option value="Alumna">La propia alumna</option>
                    <option value="Madre/Padre">Madre o Padre</option>
                    <option value="Tutor">Tutor Legal</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    let partnerCount = 0;

    // 1. Carga dinámica de ofertas y seteo de reglas
    async function loadOfferings(courseId) {
        const offeringSelect = document.getElementById('course_offering_id');
        offeringSelect.disabled = true;
        offeringSelect.innerHTML = '<option value="">Cargando horarios...</option>';
        
        // Ocultar sección 5 y resetear resumen de pago al cambiar curso
        togglePartnersSection(false);
        showLoadingState(true, "Cargando horarios...");

        if (!courseId) {
            showLoadingState(false, "Selecciona un curso para ver el precio.");
            return;
        }

        try {
            const response = await fetch(`/api/courses/${courseId}/offerings`);
            const offerings = await response.json();

            offeringSelect.innerHTML = '<option value="">Selecciona un horario</option>';
            
            offerings.forEach(offering => {
                const option = document.createElement('option');
                option.value = offering.id;
                option.textContent = offering.display;
                // Guardamos si tiene reglas en el dataset
                option.dataset.hasRules = offering.has_pricing_rules; 
                offeringSelect.appendChild(option);
            });
            offeringSelect.disabled = false;
            
            showLoadingState(false, "Selecciona un horario para ver el precio.");

        } catch (e) {
            console.error("Error al cargar horarios", e);
            offeringSelect.innerHTML = '<option value="">Error al cargar</option>';
            showLoadingState(false, "Error al cargar.");
        }
    }

    // 2. Manejo de cambio de horario
    function handleOfferingChange() {
        const offeringSelect = document.getElementById('course_offering_id');
        const selectedOption = offeringSelect.options[offeringSelect.selectedIndex];
        
        // Leer si tiene reglas (true/false) desde el dataset
        // Nota: dataset.hasRules devuelve string "1", "0", "true" o "false"
        const hasRules = selectedOption.dataset.hasRules == "1" || selectedOption.dataset.hasRules === "true";
        
        // 1. Mostrar/Ocultar sección de compañeros
        togglePartnersSection(hasRules);

        // 2. Calcular precio inmediatamente
        calculateTotal();
    }

    // 3. Lógica para mostrar/ocultar Sección 5
    function togglePartnersSection(show) {
        const section = document.getElementById('partners_section');
        const container = document.getElementById('partners_container');
        
        if (show) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
            // Si ocultamos, BORRAMOS los partners para que el precio vuelva a ser individual
            container.innerHTML = ''; 
            partnerCount = 0;
        }
    }

    // 4. Agregar Acompañante
    function addPartner() {
        partnerCount++;
        const template = document.getElementById('partner_template').innerHTML;
        const container = document.getElementById('partners_container');
        
        const html = template.replace(/INDEX/g, partnerCount - 1);
        
        const div = document.createElement('div');
        div.innerHTML = html;
        const partnerElement = div.firstElementChild;
        partnerElement.querySelector('.partner-number').textContent = partnerCount;
        container.appendChild(partnerElement);
        
        calculateTotal();
    }

    // 5. Remover Acompañante
    function removePartner(btn) {
        btn.closest('.partner-row').remove();
        partnerCount--;
        
        // Re-enumerar
        document.querySelectorAll('.partner-number').forEach((el, index) => {
            el.textContent = index + 1;
        });
        
        calculateTotal();
    }

    // 6. Calcular Total (Llamada a API)
    async function calculateTotal() {
        const offeringId = document.getElementById('course_offering_id').value;
        const totalStudents = 1 + partnerCount;
        
        const calcArea = document.getElementById('price_calculation_area');
        const discountRow = document.getElementById('discount_row');

        if (!offeringId) {
            calcArea.classList.add('hidden');
            showLoadingState(false, "Selecciona un horario para ver el precio.");
            return;
        }

        // Mostrar spinner de cálculo
        showLoadingState(true, "Calculando mejor precio...");
        
        try {
            const response = await fetch('{{ route("api.public.calculate-price") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    course_offering_id: offeringId,
                    student_count: totalStudents
                })
            });

            const data = await response.json();
            
            // Ocultar loading y mostrar datos
            showLoadingState(false);
            calcArea.classList.remove('hidden');

            // Rellenar datos
            document.getElementById('count_display').textContent = totalStudents;
            document.getElementById('price_per_student_original').textContent = '$' + Number(data.original_price).toFixed(2);
            document.getElementById('original_total_display').textContent = '$' + (Number(data.original_price) * totalStudents).toFixed(2);
            document.getElementById('final_total_display').textContent = '$' + Number(data.total).toFixed(2);
            
            // Manejo de Descuento
            if (Number(data.savings) > 0) {
                document.getElementById('savings_display').textContent = Number(data.savings).toFixed(2);
                document.getElementById('rule_name_display').textContent = data.applied_rule || 'Promoción activa';
                discountRow.classList.remove('hidden');
            } else {
                discountRow.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error al calcular precio:', error);
            showLoadingState(false, "Error al calcular precio. Intenta cambiar el horario.");
        }
    }

    // Helper para estados de carga en la tarjeta de precio
    function showLoadingState(isLoading, text = "") {
        const loadingDiv = document.getElementById('price_loading');
        const spinner = document.getElementById('spinner_icon');
        const loadingText = document.getElementById('loading_text');
        const calcArea = document.getElementById('price_calculation_area');

        if (isLoading) {
            calcArea.classList.add('hidden');
            loadingDiv.classList.remove('hidden');
            spinner.classList.remove('hidden');
            loadingText.textContent = text;
        } else {
            spinner.classList.add('hidden');
            if (text) {
                // Si hay texto pero no carga, es mensaje de espera (ej: "Selecciona curso")
                loadingDiv.classList.remove('hidden');
                loadingText.textContent = text;
                calcArea.classList.add('hidden');
            } else {
                // Si no hay texto y no carga, ocultamos el div de loading (se muestran los datos)
                loadingDiv.classList.add('hidden');
            }
        }
    }

    // 7. Lógica de Edad (Existente)
    function checkAge() {
        const birthDateValue = document.getElementById('birth_date').value;
        const tutorSection = document.getElementById('tutor_section');
        const tutorFields = document.querySelectorAll('.tutor-field');
        const ageHidden = document.getElementById('age_hidden');
        const whoFillsContainer = document.getElementById('who_fills_form_container');
        const whoFillsField = document.getElementById('who_fills_form');

        if (!birthDateValue) return;

        const birthDate = new Date(birthDateValue);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        ageHidden.value = age;

        if (age < 18) {
            whoFillsContainer.classList.remove('hidden');
            whoFillsField.required = true;
            whoFillsField.value = '';

            tutorSection.classList.remove('hidden');
            tutorFields.forEach(field => field.required = true);
        } else {
            whoFillsContainer.classList.add('hidden');
            whoFillsField.required = false;
            whoFillsField.value = 'Alumna';

            tutorSection.classList.add('hidden');
            tutorFields.forEach(field => field.required = false);
        }
    }

    // Efecto de carga en submit
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const loader = document.getElementById('btnLoader');

        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-wait');
        text.classList.add('hidden');
        loader.classList.remove('hidden');
        loader.classList.add('flex');
    });

    window.onload = function() {
        if (document.getElementById('birth_date').value) {
            checkAge();
        }
    };
</script>
@endsection