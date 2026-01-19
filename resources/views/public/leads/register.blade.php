@extends('layouts.guest')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 fade-in">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-display font-bold text-primary-dark">Formulario de Inscripción</h1>
        <p class="text-gray-600 mt-2">Academia Auténtica — Formación integral para la vida</p>
    </div>

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
                    <select name="course_offering_id" id="course_offering_id" class="input-elegant" disabled required>
                        <option value="">Primero elige un curso</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-premium mb-8">
            <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">2. Información Personal</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label-elegant">Nombre completo de la alumna *</label>
                    <input type="text" name="first_name" class="input-elegant" required placeholder="Nombres" value="{{ old('first_name') }}">
                </div>
                <div>
                    <label class="label-elegant">Apellidos completo de la alumna *</label>
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
                    <label class="label-elegant">Fotografía de la alumna (Opcional)</label>
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

        <div class="card-premium mb-8 border-2 border-accent-red/20">
            <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">5. Comprobante de Pago</h2>
            <div class="bg-accent-red/5 p-4 rounded-lg mb-6">
                <p class="text-sm text-gray-700 leading-relaxed">
                    <strong>Instrucciones:</strong> Realiza el pago vía Yappy o Transferencia. 
                    Sube la captura de pantalla o PDF aquí para que validemos tu inscripción manualmente.
                </p>
            </div>
            <div>
                <label class="label-elegant">Subir Comprobante de Pago *</label>
                <input type="file" name="payment_receipt" class="input-elegant" accept="image/*,.pdf" required>
                <p class="text-xs text-gray-500 mt-2 italic">Formatos: JPG, PNG, PDF (Máx 5MB)</p>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn-primary w-full py-4 text-lg shadow-elegant flex items-center justify-center transition-all duration-200">
            <span id="btnText">Finalizar Inscripción</span>
            <div id="btnLoader" class="hidden items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Procesando... no cierres la ventana</span>
            </div>
        </button>
    </form>
</div>

<script>
    // 1. Carga dinámica de ofertas
    async function loadOfferings(courseId) {
        const offeringSelect = document.getElementById('course_offering_id');
        offeringSelect.disabled = true;
        offeringSelect.innerHTML = '<option value="">Cargando horarios...</option>';

        if (!courseId) return;

        try {
            const response = await fetch(`/api/courses/${courseId}/offerings`);
            const offerings = await response.json();

            offeringSelect.innerHTML = '<option value="">Selecciona un horario</option>';
            offerings.forEach(offering => {
                const option = document.createElement('option');
                option.value = offering.id;
                option.textContent = offering.display;
                offeringSelect.appendChild(option);
            });
            offeringSelect.disabled = false;
        } catch (e) {
            console.error("Error al cargar horarios");
            offeringSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // 2. Cálculo de edad y gestión de campos condicionales
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

        // Asignamos la edad calculada al campo oculto que espera el servidor
        ageHidden.value = age;

        if (age < 18) {
            // Menor de edad: mostrar ambos campos (quien completa y datos de tutor)
            whoFillsContainer.classList.remove('hidden');
            whoFillsField.required = true;
            whoFillsField.value = ''; // Limpiar para que el usuario elija

            tutorSection.classList.remove('hidden');
            tutorFields.forEach(field => field.required = true);
        } else {
            // Mayor de edad: ocultar campos y establecer valor por defecto
            whoFillsContainer.classList.add('hidden');
            whoFillsField.required = false;
            whoFillsField.value = 'Alumna'; // Por defecto "La propia alumna"

            tutorSection.classList.add('hidden');
            tutorFields.forEach(field => field.required = false);
        }
    }

    // 3. Efecto de carga en el envío
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const loader = document.getElementById('btnLoader');

        // Deshabilitar el botón y mostrar loader
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-wait');
        text.classList.add('hidden');
        loader.classList.remove('hidden');
        loader.classList.add('flex');
    });

    // Ejecutar checkAge si el usuario regresa a la página con errores
    window.onload = function() {
        if (document.getElementById('birth_date').value) {
            checkAge();
        }
    };
</script>
@endsection