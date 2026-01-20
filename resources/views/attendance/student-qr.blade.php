@extends('layouts.app')

@section('title', 'Mi Código QR')
@section('page-title', 'Código QR para Asistencia')

@section('content')
<div class="fade-in max-w-2xl mx-auto">
    <!-- Student Info Card -->
    <div class="card-premium bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-300 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-display font-semibold shadow-lg">
                {{ substr($enrollment->student->first_name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-display font-semibold text-primary-dark">
                    {{ $enrollment->student->full_name }}
                </h2>
                <p class="text-gray-600">{{ $enrollment->student->identification }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    {{ $enrollment->courseOffering->course->name }}
                </p>
            </div>
        </div>
    </div>

    <!-- QR Code Card -->
    <div class="card-premium text-center">
        <div class="mb-6">
            <h3 class="text-2xl font-display font-semibold text-primary-dark mb-2">Tu Código QR Personal</h3>
            <p class="text-gray-600">Muestra este código al profesor para registrar tu asistencia</p>
        </div>

        <!-- QR Code Display -->
        <div class="inline-block bg-white p-8 rounded-lg shadow-xl border-4 border-primary-dark">
            <div id="qr-code"></div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 p-6 bg-blue-50 border-2 border-blue-200 rounded-lg text-left">
            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Instrucciones de Uso
            </h4>
            <ol class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full mr-2 flex-shrink-0 text-xs font-bold">1</span>
                    <span>Llega a clase a tiempo</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full mr-2 flex-shrink-0 text-xs font-bold">2</span>
                    <span>Muestra este código QR al profesor cuando lo solicite</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full mr-2 flex-shrink-0 text-xs font-bold">3</span>
                    <span>El profesor escaneará tu código para registrar tu asistencia</span>
                </li>
                <li class="flex items-start">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white rounded-full mr-2 flex-shrink-0 text-xs font-bold">4</span>
                    <span>Si llegas 15 minutos después del inicio, se marcará como "Tardanza"</span>
                </li>
            </ol>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex gap-3 justify-center">
            <button onclick="refreshQR()" class="btn-secondary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Actualizar Código
            </button>

            <a href="{{ route('attendance.student-report', $enrollment->id) }}" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Ver Mi Asistencia
            </a>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-xs text-yellow-800">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <strong>Código Personal:</strong> No compartas tu código QR. Es único y personal para tu asistencia.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<!-- QRCode.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
let qrCodeInstance = null;
let currentQRData = @json($qrCode);

function generateQR(data) {
    const container = document.getElementById('qr-code');
    container.innerHTML = '';

    qrCodeInstance = new QRCode(container, {
        text: data,
        width: 256,
        height: 256,
        colorDark: '#1e3a8a',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });
}

function refreshQR() {
    // En producción, esto debería hacer una llamada al servidor para generar un nuevo QR
    // Por ahora, regeneramos el mismo
    const btn = event.target.closest('button');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 inline-block mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Actualizando...';

    setTimeout(() => {
        generateQR(currentQRData);
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Actualizar Código';
    }, 500);
}

// Generate QR on page load
document.addEventListener('DOMContentLoaded', function() {
    generateQR(currentQRData);
});
</script>
@endpush
@endsection
