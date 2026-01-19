@extends('layouts.app')

@section('title', 'Reporte de Cierre - ' . $cash_register->code)
@section('page-title', 'Reporte de Cierre (Corte Z)')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                Corte Z - {{ $cash_register->code }}
            </h1>
            <p class="text-gray-600">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $cash_register->status === 'cerrada' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                    {{ $cash_register->status === 'cerrada' ? '🔒 Cerrada' : '🔓 Abierta' }}
                </span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('cash-registers.report-pdf', $cash_register->id) }}" 
               target="_blank"
               class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Descargar PDF
            </a>
            
            @if($cash_register->status === 'cerrada')
            <a href="{{ route('cash-registers.history') }}" class="btn-secondary">
                Volver al Historial
            </a>
            @else
            <a href="{{ route('cash-registers.index') }}" class="btn-secondary">
                Volver
            </a>
            @endif
        </div>
    </div>

    <!-- Cash Register Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Opening Info -->
        <div class="card-premium bg-gradient-to-br from-green-50 to-blue-50">
            <h3 class="text-lg font-semibold text-primary-dark mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Apertura de Caja
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Abierto por:</span>
                    <span class="font-semibold">{{ $cash_register->openedBy->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha/Hora:</span>
                    <span class="font-semibold">{{ $cash_register->opened_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fondo Inicial:</span>
                    <span class="text-2xl font-display font-bold text-green-600">
                        ${{ number_format($cash_register->opening_amount, 2) }}
                    </span>
                </div>
                @if($cash_register->opening_notes)
                <div class="pt-3 border-t">
                    <p class="text-sm text-gray-600 mb-1">Notas:</p>
                    <p class="text-sm">{{ $cash_register->opening_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Closing Info -->
        @if($cash_register->status === 'cerrada')
        <div class="card-premium bg-gradient-to-br from-red-50 to-orange-50">
            <h3 class="text-lg font-semibold text-primary-dark mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Cierre de Caja
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Cerrado por:</span>
                    <span class="font-semibold">{{ $cash_register->closedBy->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha/Hora:</span>
                    <span class="font-semibold">{{ $cash_register->closed_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Monto Contado:</span>
                    <span class="text-2xl font-display font-bold text-red-600">
                        ${{ number_format($cash_register->closing_amount, 2) }}
                    </span>
                </div>
                @if($cash_register->closing_notes)
                <div class="pt-3 border-t">
                    <p class="text-sm text-gray-600 mb-1">Notas:</p>
                    <p class="text-sm">{{ $cash_register->closing_notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
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
            <p class="text-sm text-gray-600 mb-1">Promedio por Transacción</p>
            <p class="text-3xl font-display font-bold text-purple-600">${{ number_format($stats['average_transaction'], 2) }}</p>
        </div>

        @if($cash_register->status === 'cerrada')
        <div class="card-premium border-l-4 {{ $cash_register->difference >= 0 ? 'bg-green-50 border-green-600' : 'bg-red-50 border-red-600' }}">
            <p class="text-sm text-gray-600 mb-1">Diferencia</p>
            <p class="text-3xl font-display font-bold {{ $cash_register->difference >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ${{ number_format($cash_register->difference, 2) }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $cash_register->difference > 0 ? 'Sobrante' : ($cash_register->difference < 0 ? 'Faltante' : 'Exacto') }}
            </p>
        </div>
        @endif
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
                        {{ $data['count'] }} trans.
                    </span>
                </div>
                <p class="text-3xl font-display font-bold text-primary-dark">
                    ${{ number_format($data['total'], 2) }}
                </p>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                No hay transacciones registradas
            </div>
            @endforelse
        </div>
    </div>

    @if($cash_register->status === 'cerrada')
    <!-- Summary Table -->
    <div class="card-premium mb-6">
        <h3 class="text-xl font-semibold text-primary-dark mb-4">Resumen Final</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Fondo Inicial</td>
                        <td class="px-6 py-4 text-sm text-right font-semibold">${{ number_format($cash_register->opening_amount, 2) }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">+ Total Recaudado</td>
                        <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">${{ number_format($stats['total_collected'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">= Total Esperado</td>
                        <td class="px-6 py-4 text-sm text-right font-semibold">${{ number_format($cash_register->expected_amount, 2) }}</td>
                    </tr>
                    <tr class="bg-blue-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Monto Contado</td>
                        <td class="px-6 py-4 text-sm text-right font-bold text-blue-600">${{ number_format($cash_register->closing_amount, 2) }}</td>
                    </tr>
                    <tr class="bg-{{ $cash_register->difference >= 0 ? 'green' : 'red' }}-50 border-t-2 border-{{ $cash_register->difference >= 0 ? 'green' : 'red' }}-200">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Diferencia</td>
                        <td class="px-6 py-4 text-sm text-right font-bold text-{{ $cash_register->difference >= 0 ? 'green' : 'red' }}-600">
                            ${{ number_format($cash_register->difference, 2) }}
                            <span class="text-xs ml-2">
                                ({{ $cash_register->difference > 0 ? 'Sobrante' : ($cash_register->difference < 0 ? 'Faltante' : 'Exacto') }})
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Transactions List -->
    <div class="card-premium">
        <h3 class="text-xl font-semibold text-primary-dark mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            Detalle de Transacciones ({{ $payments->count() }})
        </h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->payment_date->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('payments.show', $payment->id) }}" 
                               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                {{ $payment->payment_code }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $payment->enrollment->student->full_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $payment->enrollment->courseOffering->course->name }}
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
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No hay transacciones registradas en esta caja
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection