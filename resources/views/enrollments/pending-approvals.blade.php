@extends('layouts.app')

@section('title', 'Inscripciones Pendientes de Aprobación')
@section('page-title', 'Inscripciones Pendientes de Aprobación')

@section('content')
<div class="fade-in">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-premium bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pendientes</p>
                    <p class="text-4xl font-display font-bold text-yellow-600">{{ $enrollments->count() }}</p>
                </div>
                <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @if($enrollments->isEmpty())
    <!-- No pending enrollments -->
    <div class="card-premium">
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xl text-gray-600 font-medium">¡No hay inscripciones pendientes de aprobación!</p>
            <p class="text-gray-500 mt-2">Todas las inscripciones han sido procesadas</p>
        </div>
    </div>
    @else
    <!-- Pending enrollments list -->
    <div class="space-y-4">
        @foreach($enrollments as $enrollment)
        <div class="card-premium bg-white border-2 border-yellow-300">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <!-- Student Info -->
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-display font-semibold shadow-lg">
                            {{ substr($enrollment->student->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-display font-semibold text-primary-dark">
                                {{ $enrollment->student->full_name }}
                            </h3>
                            <p class="text-gray-600">{{ $enrollment->student->identification }}</p>
                            <p class="text-sm text-gray-500">{{ $enrollment->student->email }}</p>
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Curso</p>
                                <p class="font-semibold text-primary-dark">{{ $enrollment->courseOffering->course->name }}</p>
                                @if($enrollment->courseOffering->is_generation && $enrollment->courseOffering->generation_name)
                                    <p class="text-sm text-gray-600">{{ $enrollment->courseOffering->generation_name }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Precio</p>
                                <p class="text-2xl font-display font-bold text-green-600">${{ number_format($enrollment->final_price, 2) }}</p>
                                @if($enrollment->discount > 0)
                                    <p class="text-xs text-gray-500">Descuento: ${{ number_format($enrollment->discount, 2) }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Fecha de Inscripción</p>
                                <p class="font-medium">{{ $enrollment->enrollment_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Código</p>
                                <p class="font-mono text-sm">{{ $enrollment->enrollment_code }}</p>
                            </div>
                        </div>
                    </div>

                    @if($enrollment->notes)
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-1">Notas:</p>
                        <p class="text-sm text-gray-600">{{ $enrollment->notes }}</p>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex items-center space-x-3">
                        <!-- Approve Button -->
                        <button type="button"
                                onclick="showApprovalModal({{ $enrollment->id }}, '{{ $enrollment->student->full_name }}', 'approve')"
                                class="flex-1 btn-primary py-3">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Aprobar
                        </button>

                        <!-- Reject Button -->
                        <button type="button"
                                onclick="showApprovalModal({{ $enrollment->id }}, '{{ $enrollment->student->full_name }}', 'reject')"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rechazar
                        </button>

                        <!-- View Details -->
                        <a href="{{ route('enrollments.show', $enrollment->id) }}"
                           class="btn-secondary py-3 px-6">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Approval Modal -->
<div id="approval-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-2xl font-display font-semibold text-primary-dark mb-4" id="modal-title">Confirmar Acción</h3>

            <p class="text-gray-600 mb-6" id="modal-message"></p>

            <form id="approval-form" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="approval_notes" class="label-elegant">
                        <span id="notes-label">Notas (Opcional)</span>
                    </label>
                    <textarea id="approval_notes"
                              name="approval_notes"
                              rows="3"
                              class="input-elegant"
                              placeholder="Agrega comentarios o razones..."></textarea>
                </div>

                <div class="flex items-center space-x-3">
                    <button type="button"
                            onclick="closeApprovalModal()"
                            class="flex-1 btn-secondary py-3">
                        Cancelar
                    </button>
                    <button type="submit"
                            id="modal-submit-btn"
                            class="flex-1 py-3 px-6 rounded-lg font-semibold transition-all shadow-md">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApprovalModal(enrollmentId, studentName, action) {
    const modal = document.getElementById('approval-modal');
    const form = document.getElementById('approval-form');
    const title = document.getElementById('modal-title');
    const message = document.getElementById('modal-message');
    const submitBtn = document.getElementById('modal-submit-btn');
    const notesLabel = document.getElementById('notes-label');
    const notesField = document.getElementById('approval_notes');

    if (action === 'approve') {
        form.action = `/enrollments/${enrollmentId}/approve`;
        title.textContent = '✓ Aprobar Inscripción';
        message.innerHTML = `¿Confirmas que deseas <strong class="text-green-600">APROBAR</strong> la inscripción de <strong>${studentName}</strong>?`;
        submitBtn.className = 'flex-1 btn-primary py-3';
        submitBtn.textContent = 'Aprobar';
        notesLabel.textContent = 'Notas (Opcional)';
        notesField.required = false;
    } else {
        form.action = `/enrollments/${enrollmentId}/reject`;
        title.textContent = '✕ Rechazar Inscripción';
        message.innerHTML = `¿Confirmas que deseas <strong class="text-red-600">RECHAZAR</strong> la inscripción de <strong>${studentName}</strong>?`;
        submitBtn.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md';
        submitBtn.textContent = 'Rechazar';
        notesLabel.textContent = 'Razón del Rechazo (Requerido)';
        notesField.required = true;
    }

    notesField.value = '';
    modal.classList.remove('hidden');
}

function closeApprovalModal() {
    document.getElementById('approval-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('approval-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApprovalModal();
    }
});
</script>
@endpush
@endsection
