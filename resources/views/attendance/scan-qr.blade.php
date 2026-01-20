@extends('layouts.app')

@section('title', 'Escanear QR')
@section('page-title', 'Escanear Códigos QR para Asistencia')

@section('content')
<div class="fade-in">
    <!-- Session Info -->
    <div class="card-premium bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-display font-semibold text-primary-dark mb-2">
                    {{ $session->courseOffering->course->name }}
                </h2>
                <p class="text-gray-600">{{ $session->courseOffering->location }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ $session->class_date->format('d/m/Y') }} • {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : 'N/A' }} - {{ $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('H:i') : 'N/A' }}
                </p>
            </div>
            <div class="text-right">
                <a href="{{ route('attendance.take', $session->id) }}" class="btn-secondary">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver a Asistencia Manual
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Scanner Section -->
        <div class="card-premium">
            <h3 class="text-xl font-semibold text-primary-dark mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                Escáner QR
            </h3>

            <!-- Video Preview -->
            <div class="mb-4">
                <div id="scanner-container" class="relative bg-black rounded-lg overflow-hidden" style="min-height: 300px;">
                    <video id="qr-video" class="w-full h-full"></video>
                    <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-64 h-64 border-4 border-blue-500 rounded-lg shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Scanner Controls -->
            <div class="flex gap-3">
                <button id="start-scan-btn" onclick="startScanning()" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Iniciar Escáner
                </button>
                <button id="stop-scan-btn" onclick="stopScanning()" class="btn-secondary flex-1" disabled>
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    Detener Escáner
                </button>
            </div>

            <!-- Scanner Status -->
            <div id="scanner-status" class="mt-4 p-3 bg-gray-100 rounded-lg text-center text-sm text-gray-600">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Haz clic en "Iniciar Escáner" para comenzar
            </div>
        </div>

        <!-- Recent Check-ins -->
        <div class="card-premium">
            <h3 class="text-xl font-semibold text-primary-dark mb-4 flex items-center justify-between">
                <span class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Check-ins Recientes
                </span>
                <span id="checkin-count" class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">0</span>
            </h3>

            <div id="recent-checkins" class="space-y-2 max-h-96 overflow-y-auto">
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p>Aún no hay check-ins registrados</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrCode = null;
let isScanning = false;
let checkinsList = [];
const sessionId = {{ $session->id }};

function startScanning() {
    const startBtn = document.getElementById('start-scan-btn');
    const stopBtn = document.getElementById('stop-scan-btn');
    const statusDiv = document.getElementById('scanner-status');

    startBtn.disabled = true;
    statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Iniciando cámara...';

    html5QrCode = new Html5Qrcode("scanner-container");

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess,
        onScanError
    ).then(() => {
        isScanning = true;
        startBtn.disabled = true;
        stopBtn.disabled = false;
        statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="text-green-600 font-semibold">Escaneando... Acerca un código QR</span>';
    }).catch(err => {
        console.error('Error starting scanner:', err);
        statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> <span class="text-red-600">Error al acceder a la cámara</span>';
        startBtn.disabled = false;
    });
}

function stopScanning() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            isScanning = false;
            document.getElementById('start-scan-btn').disabled = false;
            document.getElementById('stop-scan-btn').disabled = true;
            document.getElementById('scanner-status').innerHTML = '<svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Escáner detenido';
        }).catch(err => {
            console.error('Error stopping scanner:', err);
        });
    }
}

async function onScanSuccess(decodedText, decodedResult) {
    // Pausar el escáner temporalmente
    if (html5QrCode && isScanning) {
        await html5QrCode.pause(true);
    }

    // Procesar el check-in
    await processCheckIn(decodedText);

    // Reanudar después de 2 segundos
    setTimeout(() => {
        if (html5QrCode && isScanning) {
            html5QrCode.resume();
        }
    }, 2000);
}

function onScanError(errorMessage) {
    // Errores normales del escáner, no hacer nada
}

async function processCheckIn(qrCode) {
    const statusDiv = document.getElementById('scanner-status');
    statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Procesando check-in...';

    try {
        const response = await fetch('/api/attendances/check-in-qr', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                qr_code: qrCode,
                course_session_id: sessionId
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Success
            statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="text-green-600 font-semibold">✓ Check-in exitoso</span>';
            addCheckInToList(data.attendance);
            playSuccessSound();
        } else {
            // Error
            statusDiv.innerHTML = `<svg class="w-5 h-5 inline-block mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> <span class="text-red-600">${data.message}</span>`;
            playErrorSound();
        }
    } catch (error) {
        console.error('Error processing check-in:', error);
        statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> <span class="text-red-600">Error de conexión</span>';
        playErrorSound();
    }

    // Volver al estado de escaneo después de 2 segundos
    setTimeout(() => {
        if (isScanning) {
            statusDiv.innerHTML = '<svg class="w-5 h-5 inline-block mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> <span class="text-green-600 font-semibold">Escaneando... Acerca un código QR</span>';
        }
    }, 2000);
}

function addCheckInToList(attendance) {
    checkinsList.unshift(attendance);

    const container = document.getElementById('recent-checkins');
    const statusBadge = attendance.status === 'late'
        ? '<span class="badge badge-warning">Tardanza</span>'
        : '<span class="badge badge-success">Presente</span>';

    const html = `
        <div class="p-3 bg-green-50 border-2 border-green-300 rounded-lg animate-fade-in">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="font-semibold text-primary-dark">${attendance.enrollment.student.first_name} ${attendance.enrollment.student.last_name}</p>
                    <p class="text-xs text-gray-600">${attendance.enrollment.student.identification}</p>
                </div>
                <div class="text-right">
                    ${statusBadge}
                    <p class="text-xs text-gray-500 mt-1">${new Date(attendance.checked_in_at).toLocaleTimeString()}</p>
                </div>
            </div>
        </div>
    `;

    if (checkinsList.length === 1) {
        container.innerHTML = html;
    } else {
        container.insertAdjacentHTML('afterbegin', html);
    }

    document.getElementById('checkin-count').textContent = checkinsList.length;
}

function playSuccessSound() {
    // Sonido de éxito (opcional)
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGe67OekTgwPUKXi8LljHAU7k9r0z3gsBS170fDgl0YNFnDD7OyrWBUIRZzd8sFuIwU5iM70z3UqBSh+y/LajDkJGWe66+ekURANU6jo8LxmHgU8lNny0HotBi2B0fHdjzwKE164'+
        '6+qnVxcKSqDh8sFxJAU1iM/00HYrBSh/zPPajTsJGWi76+alURAOVKno8L1nHwU9ldr00XwuBi+D0/HekD0LFWa+7OupWhkKSaHi88FyJgU2idD00XYrBSiAzfPajTsJGWi76+alUBAOVKjo8L1nHwU9ldr00XwuBi+D0/HekD0LFWa+7OupWhkKSaHi88FyJgU2idD00XYrBSiAzfPajTsJGWi76+alUBAOVKjo8L1nHwU9ldr00XwuBi+D0/HekD0LFWa+7OupWhkKSaHi88FyJgU2idD00XYrBSiAzfPajTsJGWi76+alUBAOVKjo8L1nHwU9ldr00XwuBi+D0/HekD0LFWa+7OupWhkKSaHi88FyJgU2idD00XYrBSiAzfPajTsJGWi76+alUBAOVKjo8L1nHwU9ldr00XwuBi+D0/HekD0LFWa+7OupWhkKSaHi88FyJgU2idD00XYrBSiAzfPajTsJGWi76+alUBAOVKjo8L1nHwU9ldr00XwuBi+D0/HekD0LFWa+7OupWhkK');
    // audio.play().catch(e => {}); // Opcional
}

function playErrorSound() {
    // Sonido de error (opcional)
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (html5QrCode && isScanning) {
        html5QrCode.stop();
    }
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

#qr-video {
    object-fit: cover;
}
</style>
@endpush
@endsection
