@extends('layouts.app')

@section('title', 'Corte X - ' . $cash_register->code)
@section('page-title', 'Corte X - Consulta de Caja')

@section('content')
<div class="fade-in">
    <!-- Alert Notice -->
    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Corte X:</strong> Este es un reporte informativo. La caja permanece abierta y puedes continuar registrando pagos.
                </p>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                Corte X - {{ $cash_register->code }}
            </h1>
            <p class="text-gray-600">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    🔓 Caja Abierta - {{ $cash_register->opened_at->format('d/m/Y H:i') }}
                </span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <button onclick="window.print()" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
            
            <a href="{{ route('pos.index') }}" class="btn-secondary bg-green-600 hover:bg-green-700 text-white">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Volver al POS
            </a>
            
            <a href="{{ route('cash-registers.index') }}" class="btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <!-- Cash Register Info -->
    <div class="card-premium bg-gradient-to-br from-green-50 to-blue-50 mb-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Información de Caja
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="text-sm text-gray-600">Cajero:</span>
                <p class="font-semibold text-lg">{{ $cash_register->openedBy->name }}</p>
            </div>
            <div>
                <span class="text-sm text-gray-600">Fecha de Apertura:</span>
                <p class="font-semibold text-lg">{{ $cash_register->opened_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <span class="text-sm text-gray-600">Fondo Inicial:</span>
                <p class="text-2xl font-display font-bold text-green-600">
                    ${{ number_format($cash_register->opening_amount, 2) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-premium bg-blue-50 border-l-4 border-blue-600">
            <p class="text-sm text-gray-600 mb-1">Total Transacciones</p>
            <p class="text-3xl font-display font-bold text-blue-600">{{ $stats['total_transactions'] }}</p>
        </div>
        
        <div class="card-premium bg-green-50 border-l-4 border-green-600">
            <p class="text-sm text-gray-600 mb-1">Total Recaudado</p>
            <p class="text-3xl font-display font-bold text-green-600">${{ number_format($stats['total_collected'], 2) }}</p>
        </div>
        
        <div class="card-premium bg-purple-50 border-l-4 border-purple-600">
            <p class="text-sm text-gray-600 mb-1">Promedio</p>
            <p class="text-3xl font-display font-bold text-purple-600">${{ number_format($stats['average_transaction'], 2) }}</p>
        </div>

        <div class="card-premium bg-orange-50 border-l-4 border-orange-600">
            <p class="text-sm text-gray-600 mb-1">Total en Caja</p>
            <p class="text-3xl font-display font-bold text-orange-600">
                ${{ number_format($cash_register->opening_amount + $stats['total_collected'], 2) }}
            </p>
        </div>
    </div>

    <!-- Payment Methods Breakdown -->
    <div class="card-premium mb-6">
        <h3 class="text-xl font-semibold text-primary-dark mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Desglose por Método de Pago
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($payments_by_method as $method => $data)
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-lg border-2 border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-600 uppercase">
                        @switch($method)
                            @case('efectivo') 💵 Efectivo @break
                            @case('transferencia') 🏦 Transferencia @break
                            @case('tarjeta_debito') 💳 Tarjeta Débito @break
                            @case('tarjeta_credito') 💳 Tarjeta Crédito @break
                            @case('yappy') 📱 Yappy @break
                            @default {{ $method }} @break
                        @endswitch
                    </span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                        {{ $data['count'] }}
                    </span>
                </div>
                <p class="text-3xl font-display font-bold text-primary-dark">
                    ${{ number_format($data['total'], 2) }}
                </p>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                No hay transacciones registradas aún
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card-premium">
        <h3 class="text-xl font-semibold text-primary-dark mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Últimas Transacciones ({{ $payments->count() }})
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments->take(20) as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->payment_date->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ $payment->payment_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $payment->enrollment->student->full_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $payment->payment_method_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right text-green-600">
                            ${{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No hay transacciones registradas aún
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->count() > 20)
        <div class="mt-4 text-center text-sm text-gray-500">
            Mostrando las últimas 20 transacciones de {{ $payments->count() }} totales
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    @media print {
        .btn-primary, .btn-secondary, nav, footer {
            display: none !important;
        }
    }
</style>
@endpush
@endsection