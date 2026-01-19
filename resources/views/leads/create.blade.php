@extends('layouts.app')

@section('title', 'Crear Lead')
@section('page-title', 'Crear Nuevo Lead')

@section('content')
<div class="fade-in">
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
                        <span class="text-gray-700 font-medium">Nuevo Lead</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ route('leads.store') }}" enctype="multipart/form-data" id="leadForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información del Prospecto
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="first_name" class="label-elegant">Nombre *</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="input-elegant @error('first_name') border-red-500 @enderror" placeholder="Ej: María" required>
                            @error('first_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="last_name" class="label-elegant">Apellido *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="input-elegant @error('last_name') border-red-500 @enderror" placeholder="Ej: García" required>
                            @error('last_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="label-elegant">Correo Electrónico *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="input-elegant @error('email') border-red-500 @enderror" placeholder="correo@ejemplo.com" required>
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="label-elegant">Teléfono Alumna *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="input-elegant @error('phone') border-red-500 @enderror" placeholder="6000-0000" required>
                            @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="birth_date_text" class="label-elegant">Fecha de Nacimiento *</label>
                            <input type="date" id="birth_date_text" name="birth_date_text" value="{{ old('birth_date_text') }}" class="input-elegant @error('birth_date_text') border-red-500 @enderror" required onchange="calculateAge()">
                            @error('birth_date_text') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="age" class="label-elegant">Edad</label>
                            <input type="text" id="age" name="age" value="{{ old('age') }}" class="input-elegant bg-gray-50" readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label for="student_photo" class="label-elegant">Fotografía de la Alumna</label>
                            <input type="file" id="student_photo" name="student_photo" class="input-elegant" accept="image/*">
                        </div>
                    </div>
                </div>

                <div id="tutor_section" class="card-premium hidden bg-neutral-50 border-l-4 border-accent-red">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información del Tutor (Menores de Edad)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="parent_name" class="label-elegant">Nombre de Mamá / Tutor *</label>
                            <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name') }}" class="input-elegant tutor-input">
                        </div>
                        <div>
                            <label for="parent_relationship" class="label-elegant">Parentesco *</label>
                            <select id="parent_relationship" name="parent_relationship" class="input-elegant tutor-input">
                                <option value="Madre" {{ old('parent_relationship') == 'Madre' ? 'selected' : '' }}>Madre</option>
                                <option value="Padre" {{ old('parent_relationship') == 'Padre' ? 'selected' : '' }}>Padre</option>
                                <option value="Tutor" {{ old('parent_relationship') == 'Tutor' ? 'selected' : '' }}>Tutor Legal</option>
                                <option value="Otro" {{ old('parent_relationship') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div>
                            <label for="parent_phone" class="label-elegant">Celular del Tutor *</label>
                            <input type="tel" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" class="input-elegant tutor-input" placeholder="6000-0000">
                        </div>
                        <div>
                            <label for="parent_occupation" class="label-elegant">Ocupación del Tutor *</label>
                            <input type="text" id="parent_occupation" name="parent_occupation" value="{{ old('parent_occupation') }}" class="input-elegant tutor-input">
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Perfil y Datos de Inscripción
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="md:col-span-2">
                            <label for="address_full" class="label-elegant">Dirección / Ciudad *</label>
                            <textarea id="address_full" name="address_full" rows="2" class="input-elegant" required>{{ old('address_full') }}</textarea>
                        </div>
                        <div>
                            <label for="occupation" class="label-elegant">Ocupación Actual *</label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}" class="input-elegant" placeholder="Ej: Estudiante" required>
                        </div>
                        <div>
                            <label for="social_media_handle" class="label-elegant">Instagram / TikTok</label>
                            <input type="text" id="social_media_handle" name="social_media_handle" value="{{ old('social_media_handle') }}" class="input-elegant" placeholder="@usuario">
                        </div>
                        <div class="md:col-span-2">
                            <label for="motivation" class="label-elegant">¿Qué le llamó la atención del curso?</label>
                            <textarea id="motivation" name="motivation" rows="2" class="input-elegant">{{ old('motivation') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_previous_experience" value="1" class="form-checkbox text-accent-red rounded border-gray-300" {{ old('has_previous_experience') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">¿Ha tomado algún curso similar antes?</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label for="previous_experience_detail" class="label-elegant">Detalle de experiencia previa</label>
                            <input type="text" id="previous_experience_detail" name="previous_experience_detail" value="{{ old('previous_experience_detail') }}" class="input-elegant">
                        </div>
                        <div class="md:col-span-2">
                            <label for="medical_notes_lead" class="label-elegant">Notas Médicas / Alergias / Condiciones</label>
                            <textarea id="medical_notes_lead" name="medical_notes_lead" rows="2" class="input-elegant">{{ old('medical_notes_lead') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Inscripción y Seguimiento
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="course_offering_id" class="label-elegant">Curso Seleccionado</label>
                            <select id="course_offering_id" name="course_offering_id" class="input-elegant">
                                <option value="">Seleccionar curso programado</option>
                                @foreach($courseOfferings as $offering)
                                    <option value="{{ $offering->id }}" {{ old('course_offering_id') == $offering->id ? 'selected' : '' }}>
                                        {{ $offering->course->name }} - {{ $offering->location }} ({{ $offering->start_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="label-elegant">Estado del Pago</label>
                            <select id="payment_status" name="payment_status" class="input-elegant">
                                <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pendiente de Verificación</option>
                                <option value="verified" {{ old('payment_status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                                <option value="rejected" {{ old('payment_status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="payment_receipt" class="label-elegant">Comprobante de Pago</label>
                            <input type="file" id="payment_receipt" name="payment_receipt" class="input-elegant" accept="image/*,.pdf">
                        </div>

                        <div>
                            <label for="source" class="label-elegant">Fuente *</label>
                            <select id="source" name="source" class="input-elegant" required>
                                <option value="web" {{ old('source', 'web') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="referido" {{ old('source') == 'referido' ? 'selected' : '' }}>Referido</option>
                                <option value="redes_sociales" {{ old('source') == 'redes_sociales' ? 'selected' : '' }}>Redes Sociales</option>
                                <option value="llamada" {{ old('source') == 'llamada' ? 'selected' : '' }}>Llamada</option>
                                <option value="evento" {{ old('source') == 'evento' ? 'selected' : '' }}>Evento</option>
                                <option value="otro" {{ old('source') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="label-elegant">Estado del Lead</label>
                            <select id="status" name="status" class="input-elegant">
                                <option value="nuevo" {{ old('status', 'nuevo') == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                                <option value="contactado" {{ old('status') == 'contactado' ? 'selected' : '' }}>Contactado</option>
                                <option value="interesado" {{ old('status') == 'interesado' ? 'selected' : '' }}>Interesado</option>
                                <option value="negociacion" {{ old('status') == 'negociacion' ? 'selected' : '' }}>Negociación</option>
                                <option value="inscrito" {{ old('status') == 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('leads.index') }}" class="btn-secondary text-center">Cancelar</a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Crear Lead e Inscribir
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4">Ayuda de Registro</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p><strong class="text-primary-dark">Edad automática:</strong> Al ingresar la fecha de nacimiento, el sistema determinará si es menor de edad y activará la sección de tutor.</p>
                        <p><strong class="text-primary-dark">Comprobante:</strong> Puedes subir el pago ahora para validarlo manualmente después.</p>
                        <p><strong class="text-primary-dark">CSV:</strong> Se han incluido todos los campos necesarios que se utilizan actualmente en el formulario de inscripción.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function calculateAge() {
        const birthDateInput = document.getElementById('birth_date_text').value;
        const ageInput = document.getElementById('age');
        const tutorSection = document.getElementById('tutor_section');
        const tutorFields = document.querySelectorAll('.tutor-input');

        if (!birthDateInput) return;

        const birthDate = new Date(birthDateInput);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        ageInput.value = age + (age === 1 ? ' año' : ' años');

        if (age < 18) {
            tutorSection.classList.remove('hidden');
            tutorFields.forEach(field => field.required = true);
        } else {
            tutorSection.classList.add('hidden');
            tutorFields.forEach(field => field.required = false);
        }
    }

    // Ejecutar al cargar si hay datos viejos (old inputs)
    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('birth_date_text').value) {
            calculateAge();
        }
    });
</script>
@endsection