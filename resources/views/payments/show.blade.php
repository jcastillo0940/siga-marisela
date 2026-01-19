@extends('layouts.app')

@section('title', 'Recibo de Pago')
@section('page-title', 'Recibo de Pago')

@section('content')
<div class="fade-in">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('payments.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Pagos
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $payment->payment_code }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

<!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark">
            Recibo de Pago
        </h1>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('payments.pdf', $payment->id) }}" 
               target="_blank"
               class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Descargar PDF
            </a>

            <form method="POST" action="{{ route('payments.send-email', $payment->id) }}" class="inline">
                @csrf
                <button type="submit" class="btn-secondary bg-blue-600 hover:bg-blue-700 text-white">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Enviar por Email
                </button>
            </form>

            <a href="{{ route('payments.index') }}" class="btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <!-- Receipt Content -->
    <div class="max-w-4xl mx-auto">
        <div id="receipt-content" class="card-premium">
            <!-- Header -->
            <div class="text-center pb-6 border-b-2 border-gray-200">
                <h2 class="text-3xl font-display font-bold text-primary-dark mb-2">Academia Auténtica</h2>
                <p class="text-gray-600">Recibo de Pago</p>
                <p class="text-sm text-gray-500 mt-2">{{ $payment->payment_date->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Payment Info -->
            <div class="py-6 border-b border-gray-200">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-primary-dark mb-3">Información del Pago</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Código:</span>
                                <span class="font-mono font-medium text-primary-dark">{{ $payment->payment_code }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fecha:</span>
                                <span class="font-medium text-primary-dark">{{ $payment->payment_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Método:</span>
                                <span class="font-medium text-primary-dark">{{ $payment->payment_method_label }}</span>
                            </div>
                            @if($payment->payment_method === 'multiple' && $payment->paymentMethods->isNotEmpty())
                            <div class="col-span-2 mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
                                <p class="text-xs font-semibold text-blue-800 mb-2">Desglose de Métodos:</p>
                                @foreach($payment->paymentMethods as $method)
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-700">{{ $method->method_label }}:</span>
                                    <span class="font-semibold text-primary-dark">${{ number_format($method->amount, 2) }}</span>
                                </div>
                                @if($method->reference_number)
                                <div class="text-xs text-gray-600 ml-4 mb-2">
                                    Ref: {{ $method->reference_number }}
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @elseif($payment->reference_number)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Referencia:</span>
                                <span class="font-medium text-primary-dark">{{ $payment->reference_number }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-primary-dark mb-3">Información del Estudiante</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nombre:</span>
                                <span class="font-medium text-primary-dark">{{ $payment->enrollment->student->full_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cédula:</span>
                                <span class="font-medium text-primary-dark">{{ $payment->enrollment->student->identification }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-primary-dark text-xs">{{ $payment->enrollment->student->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Teléfono:</span>
                                <span class="font-medium text-primary-dark">{{ $payment->enrollment->student->phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Info -->
            <div class="py-6 border-b border-gray-200">
                <h3 class="font-semibold text-primary-dark mb-3">Información del Curso</h3>
                <div class="bg-neutral-bg rounded p-4">
                    <p class="font-semibold text-primary-dark text-lg">{{ $payment->enrollment->courseOffering->course->name }}</p>
                    @if($payment->enrollment->courseOffering->is_generation && $payment->enrollment->courseOffering->generation_name)
                        <p class="text-gray-600 mt-1">{{ $payment->enrollment->courseOffering->generation_name }}</p>
                    @endif
                    <div class="grid grid-cols-3 gap-4 mt-3 text-sm">
                        <div>
                            <span class="text-gray-500">Ubicación:</span>
                            <p class="font-medium text-primary-dark">{{ $payment->enrollment->courseOffering->location }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Fecha Inicio:</span>
                            <p class="font-medium text-primary-dark">{{ $payment->enrollment->courseOffering->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Código Inscripción:</span>
                            <p class="font-medium text-primary-dark font-mono text-xs">{{ $payment->enrollment->enrollment_code }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Schedule Info -->
            @if($payment->paymentSchedule)
            <div class="py-6 border-b border-gray-200">
                <h3 class="font-semibold text-primary-dark mb-3">Cuota Pagada</h3>
                <div class="bg-blue-50 border border-blue-200 rounded p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-primary-dark">Cuota #{{ $payment->paymentSchedule->installment_number }}</p>
                            <p class="text-sm text-gray-600 mt-1">Vencimiento: {{ $payment->paymentSchedule->due_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Monto de la cuota</p>
                            <p class="text-xl font-display font-bold text-primary-dark">${{ number_format($payment->paymentSchedule->amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Amount -->
            <div class="py-6 border-b-2 border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-display font-semibold text-primary-dark">Monto Pagado:</span>
                    <span class="text-4xl font-display font-bold text-green-600">{{ $payment->formatted_amount }}</span>
                </div>
            </div>

            <!-- Payment Plan Summary -->
            <div class="py-6 border-b border-gray-200">
                <h3 class="font-semibold text-primary-dark mb-3">Resumen del Plan de Pagos</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-neutral-bg rounded p-4 text-center">
                        <p class="text-sm text-gray-600 mb-1">Total del Curso</p>
                        <p class="text-xl font-display font-bold text-primary-dark">${{ number_format($payment->paymentPlan->total_amount, 2) }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded p-4 text-center">
                        <p class="text-sm text-gray-600 mb-1">Total Pagado</p>
                        <p class="text-xl font-display font-bold text-green-600">${{ number_format($payment->paymentPlan->total_paid, 2) }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded p-4 text-center">
                        <p class="text-sm text-gray-600 mb-1">Saldo Pendiente</p>
                        <p class="text-xl font-display font-bold text-red-600">${{ number_format($payment->paymentPlan->balance, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($payment->notes)
            <div class="py-6 border-b border-gray-200">
                <h3 class="font-semibold text-primary-dark mb-3">Notas</h3>
                <div class="bg-neutral-bg rounded p-4">
                    <p class="text-gray-700">{{ $payment->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="pt-6">
                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-600">Recibido por:</p>
                        <p class="font-medium text-primary-dark">{{ $payment->receivedBy->name ?? 'Sistema' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-600">Fecha de registro:</p>
                        <p class="font-medium text-primary-dark">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                    <p>Este es un comprobante válido de pago</p>
                    <p class="mt-1">Academia Auténtica - Sistema de Gestión Académica</p>
                </div>
            </div>
        </div>

        <!-- Actions (No Print) -->
        <div class="mt-6 no-print">
            @if(auth()->user()->hasPermission('payments.delete'))
            <div class="card-premium bg-red-50 border border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-red-800 mb-1">Zona de Peligro</h3>
                        <p class="text-sm text-red-700">Eliminar este pago revertirá el balance del plan de pagos</p>
                    </div>
                    <form method="POST" action="{{ route('payments.destroy', $payment->id) }}" id="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="showConfirmModal('¿Estás seguro de eliminar este pago? Esta acción revertirá el balance del plan de pagos y no se puede deshacer.', function() { document.getElementById('delete-form').submit(); })"
                                class="btn-secondary bg-red-600 hover:bg-red-700 text-white">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Pago
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .no-print, nav, .sidebar, header, footer {
        display: none !important;
    }
    
    body {
        background: white;
    }
    
    #receipt-content {
        box-shadow: none !important;
        border: none !important;
    }
}
</style>
@endpush
@endsection