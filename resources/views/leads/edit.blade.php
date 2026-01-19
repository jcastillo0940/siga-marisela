@extends('layouts.app')

@section('title', 'Editar Lead')
@section('page-title', 'Editar Lead')

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
                        <a href="{{ route('leads.show', $lead->id) }}" class="text-gray-500 hover:text-accent-red transition-colors">
                            {{ $lead->full_name }}
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

    <form method="POST" action="{{ route('leads.update', $lead->id) }}" enctype="multipart/form-data" id="leadForm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="card-premium">
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                        @if($lead->student_photo)
                            <img src="{{ asset('storage/' . $lead->student_photo) }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-accent-red/20 shadow-sm">
                        @else
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-display font-semibold">
                                {{ substr($lead->first_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark">
                                {{ $lead->full_name }}
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $lead->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Información del Prospecto
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="first_name" class="label-elegant">Nombre *</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $lead->first_name) }}" class="input-elegant @error('first_name') border-red-500 @enderror" required>
                            @error('first_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="last_name" class="label-elegant">Apellido *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $lead->last_name) }}" class="input-elegant @error('last_name') border-red-500 @enderror" required>
                            @error('last_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="label-elegant">Correo Electrónico *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}" class="input-elegant @error('email') border-red-500 @enderror" required>
                            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="label-elegant">Teléfono Alumna *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" class="input-elegant @error('phone') border-red-500 @enderror" required>
                            @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="birth_date_text" class="label-elegant">Fecha de Nacimiento *</label>
                            <input type="date" id="birth_date_text" name="birth_date_text" value="{{ old('birth_date_text', $lead->birth_date_text) }}" class="input-elegant @error('birth_date_text') border-red-500 @enderror" required onchange="calculateAge()">
                            @error('birth_date_text') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="age" class="label-elegant">Edad</label>
                            <input type="text" id="age" name="age" value="{{ old('age', $lead->age) }}" class="input-elegant bg-gray-50" readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label for="student_photo" class="label-elegant">Actualizar Fotografía</label>
                            <input type="file" id="student_photo" name="student_photo" class="input-elegant" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Dejar vacío para mantener la foto actual.</p>
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
                            <input type="text" id="parent_name" name="parent_name" value="{{ old('parent_name', $lead->parent_name) }}" class="input-elegant tutor-input">
                        </div>
                        <div>
                            <label for="parent_relationship" class="label-elegant">Parentesco *</label>
                            <select id="parent_relationship" name="parent_relationship" class="input-elegant tutor-input">
                                <option value="Madre" {{ old('parent_relationship', $lead->parent_relationship) == 'Madre' ? 'selected' : '' }}>Madre</option>
                                <option value="Padre" {{ old('parent_relationship', $lead->parent_relationship) == 'Padre' ? 'selected' : '' }}>Padre</option>
                                <option value="Tutor" {{ old('parent_relationship', $lead->parent_relationship) == 'Tutor' ? 'selected' : '' }}>Tutor Legal</option>
                                <option value="Otro" {{ old('parent_relationship', $lead->parent_relationship) == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div>
                            <label for="parent_phone" class="label-elegant">Celular del Tutor *</label>
                            <input type="tel" id="parent_phone" name="parent_phone" value="{{ old('parent_phone', $lead->parent_phone) }}" class="input-elegant tutor-input">
                        </div>
                        <div>
                            <label for="parent_occupation" class="label-elegant">Ocupación del Tutor *</label>
                            <input type="text" id="parent_occupation" name="parent_occupation" value="{{ old('parent_occupation', $lead->parent_occupation) }}" class="input-elegant tutor-input">
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Perfil y Datos Adicionales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="md:col-span-2">
                            <label for="address_full" class="label-elegant">Dirección / Ciudad *</label>
                            <textarea id="address_full" name="address_full" rows="2" class="input-elegant" required>{{ old('address_full', $lead->address_full) }}</textarea>
                        </div>
                        <div>
                            <label for="occupation" class="label-elegant">Ocupación Actual *</label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $lead->occupation) }}" class="input-elegant" required>
                        </div>
                        <div>
                            <label for="social_media_handle" class="label-elegant">Instagram / TikTok</label>
                            <input type="text" id="social_media_handle" name="social_media_handle" value="{{ old('social_media_handle', $lead->social_media_handle) }}" class="input-elegant">
                        </div>
                        <div class="md:col-span-2">
                            <label for="motivation" class="label-elegant">¿Qué le llamó la atención del curso?</label>
                            <textarea id="motivation" name="motivation" rows="2" class="input-elegant">{{ old('motivation', $lead->motivation) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="has_previous_experience" value="1" class="form-checkbox text-accent-red rounded border-gray-300" {{ old('has_previous_experience', $lead->has_previous_experience) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">¿Ha tomado algún curso similar antes?</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label for="previous_experience_detail" class="label-elegant">Detalle de experiencia previa</label>
                            <input type="text" id="previous_experience_detail" name="previous_experience_detail" value="{{ old('previous_experience_detail', $lead->previous_experience_detail) }}" class="input-elegant">
                        </div>
                        <div class="md:col-span-2">
                            <label for="medical_notes_lead" class="label-elegant">Notas Médicas / Alergias / Condiciones</label>
                            <textarea id="medical_notes_lead" name="medical_notes_lead" rows="2" class="input-elegant">{{ old('medical_notes_lead', $lead->medical_notes_lead) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-premium">
                    <h2 class="text-xl sm:text-2xl font-display font-semibold text-primary-dark mb-6">
                        Inscripción y Seguimiento
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label for="course_offering_id" class="label-elegant">Curso Asociado</label>
                            <select id="course_offering_id" name="course_offering_id" class="input-elegant">
                                <option value="">Sin curso asignado</option>
                                @foreach($courseOfferings as $offering)
                                    <option value="{{ $offering->id }}" {{ old('course_offering_id', $lead->course_offering_id) == $offering->id ? 'selected' : '' }}>
                                        {{ $offering->course->name }} - {{ $offering->location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="label-elegant">Estado del Pago</label>
                            <select id="payment_status" name="payment_status" class="input-elegant">
                                <option value="pending" {{ old('payment_status', $lead->payment_status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="verified" {{ old('payment_status', $lead->payment_status) == 'verified' ? 'selected' : '' }}>Verificado</option>
                                <option value="rejected" {{ old('payment_status', $lead->payment_status) == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="payment_receipt" class="label-elegant">Actualizar Comprobante</label>
                            <input type="file" id="payment_receipt" name="payment_receipt" class="input-elegant" accept="image/*,.pdf">
                            @if($lead->payment_receipt_path)
                                <p class="mt-2 text-xs text-blue-600">
                                    <a href="{{ asset('storage/' . $lead->payment_receipt_path) }}" target="_blank">Ver comprobante actual ↗</a>
                                </p>
                            @endif
                        </div>

                        <div>
                            <label for="source" class="label-elegant">Fuente *</label>
                            <select id="source" name="source" class="input-elegant" required>
                                <option value="web" {{ old('source', $lead->source) == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="referido" {{ old('source', $lead->source) == 'referido' ? 'selected' : '' }}>Referido</option>
                                <option value="redes_sociales" {{ old('source', $lead->source) == 'redes_sociales' ? 'selected' : '' }}>Redes Sociales</option>
                                <option value="llamada" {{ old('source', $lead->source) == 'llamada' ? 'selected' : '' }}>Llamada</option>
                                <option value="evento" {{ old('source', $lead->source) == 'evento' ? 'selected' : '' }}>Evento</option>
                                <option value="otro" {{ old('source', $lead->source) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="label-elegant">Estado del Lead</label>
                            <select id="status" name="status" class="input-elegant">
                                <option value="nuevo" {{ old('status', $lead->status) == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                                <option value="contactado" {{ old('status', $lead->status) == 'contactado' ? 'selected' : '' }}>Contactado</option>
                                <option value="interesado" {{ old('status', $lead->status) == 'interesado' ? 'selected' : '' }}>Interesado</option>
                                <option value="negociacion" {{ old('status', $lead->status) == 'negociacion' ? 'selected' : '' }}>Negociación</option>
                                <option value="inscrito" {{ old('status', $lead->status) == 'inscrito' ? 'selected' : '' }}>Inscrito</option>
                                <option value="perdido" {{ old('status', $lead->status) == 'perdido' ? 'selected' : '' }}>Perdido</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="notes" class="label-elegant">Notas Internas / Historial</label>
                        <textarea id="notes" name="notes" rows="4" class="input-elegant">{{ old('notes', $lead->notes) }}</textarea>
                    </div>
                </div>

                <div class="card-premium">
                    <div class="flex items-center justify-between">
                        <div>
                            @if(auth()->user()->hasPermission('leads.delete'))
                            <button type="button" onclick="showConfirmModal('¿Eliminar a {{ $lead->full_name }}?', function() { document.getElementById('delete-form').submit(); })" class="text-red-600 hover:text-red-800 text-sm font-medium uppercase tracking-wide">
                                Eliminar Lead
                            </button>
                            @endif
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('leads.show', $lead->id) }}" class="btn-secondary">Cancelar</a>
                            <button type="submit" class="btn-primary">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                @if(!$lead->isConverted() && auth()->user()->hasPermission('students.create'))
                <div class="card-premium bg-purple-50 border border-purple-200">
                    <h3 class="font-display font-semibold text-primary-dark mb-3">Convertir Estudiante</h3>
                    <p class="text-xs text-gray-600 mb-4">Si el pago ya fue validado, puedes convertir este lead en estudiante oficial.</p>
                    <form method="POST" action="{{ route('leads.convert', $lead->id) }}" id="convert-form">
                        @csrf
                        <button type="button" onclick="showConfirmModal('¿Convertir {{ $lead->full_name }}?', function() { document.getElementById('convert-form').submit(); })" class="w-full btn-primary bg-purple-600 hover:bg-purple-700">
                            Convertir Ahora
                        </button>
                    </form>
                </div>
                @endif

                <div class="card-premium">
                    <h3 class="font-display font-semibold text-primary-dark mb-4 text-sm">Registro</h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Creado:</span>
                            <span>{{ $lead->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Actualizado:</span>
                            <span>{{ $lead->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(auth()->user()->hasPermission('leads.delete'))
    <form id="delete-form" method="POST" action="{{ route('leads.destroy', $lead->id) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif
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

    document.addEventListener('DOMContentLoaded', function() {
        calculateAge();
    });
</script>
@endsection