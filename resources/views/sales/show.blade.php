@extends('layouts.app')

@section('title', 'Detalle de Venta #' . $sale->sale_code)
@section('page-title', 'Detalle de Venta')

@section('content')
<div class="fade-in max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('sales.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Ventas
        </a>
    </div>

    <!-- Sale Header -->
    <div class="card-premium mb-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-3xl font-display font-bold text-primary-dark">Venta #{{ $sale->sale_code }}</h2>
                <p class="text-gray-600 mt-1">{{ $sale->sale_date->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                @if($sale->status === 'completado')
                <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ ucfirst($sale->status) }}
                </span>
                @elseif($sale->status === 'cancelado')
                <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ ucfirst($sale->status) }}
                </span>
                @else
                <span class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                    {{ ucfirst($sale->status) }}
                </span>
                @endif
            </div>
        </div>

        <!-- Sale Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Info -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Información del Cliente</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    @if($sale->customer_name)
                    <p class="text-sm"><span class="font-medium">Nombre:</span> {{ $sale->customer_name }}</p>
                    @endif
                    @if($sale->customer_document)
                    <p class="text-sm"><span class="font-medium">Documento:</span> {{ $sale->customer_document }}</p>
                    @endif
                    @if(!$sale->customer_name && !$sale->customer_document)
                    <p class="text-sm text-gray-500">Cliente no registrado</p>
                    @endif
                </div>
            </div>

            <!-- Sale Info -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Información de la Venta</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm"><span class="font-medium">Vendedor:</span> {{ $sale->soldBy->name ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-medium">Caja:</span> {{ $sale->cashRegister->code ?? 'N/A' }}</p>
                    <p class="text-sm"><span class="font-medium">Fecha:</span> {{ $sale->sale_date->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        @if($sale->notes)
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Notas</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-gray-700">{{ $sale->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sale Items -->
    <div class="card-premium mb-6">
        <h3 class="text-xl font-display font-semibold text-primary-dark mb-4">Artículos</h3>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left">Producto/Servicio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Descuento</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="font-medium">{{ $item->item_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">
                            @if($item->discount > 0)
                            <span class="text-red-600">-${{ number_format($item->discount, 2) }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2">
                        <td colspan="4" class="text-right font-semibold">Subtotal:</td>
                        <td class="text-right font-semibold">${{ number_format($sale->subtotal, 2) }}</td>
                    </tr>
                    @if($sale->discount > 0)
                    <tr>
                        <td colspan="4" class="text-right font-semibold text-red-600">Descuento:</td>
                        <td class="text-right font-semibold text-red-600">-${{ number_format($sale->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="border-t-2">
                        <td colspan="4" class="text-right text-xl font-bold text-primary-dark">TOTAL:</td>
                        <td class="text-right text-xl font-bold text-primary-dark">${{ number_format($sale->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="card-premium mb-6">
        <h3 class="text-xl font-display font-semibold text-primary-dark mb-4">Información de Pago</h3>

        @if($sale->payment_method === 'multiple')
        <!-- Multiple Payment Methods -->
        <div class="space-y-3">
            @foreach($sale->paymentMethods as $method)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold">{{ $sale->getPaymentMethodLabel($method->method) }}</p>
                    @if($method->reference_number)
                    <p class="text-sm text-gray-600">Ref: {{ $method->reference_number }}</p>
                    @endif
                </div>
                <p class="text-lg font-bold text-green-600">${{ number_format($method->amount, 2) }}</p>
            </div>
            @endforeach
            <div class="flex items-center justify-between p-4 bg-green-50 border-2 border-green-300 rounded-lg">
                <p class="font-bold text-green-800">Total Pagado:</p>
                <p class="text-xl font-bold text-green-600">${{ number_format($sale->paymentMethods->sum('amount'), 2) }}</p>
            </div>
        </div>
        @else
        <!-- Single Payment Method -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="font-semibold">Método:</p>
                <p class="text-lg font-bold text-green-600">{{ $sale->payment_method_label }}</p>
            </div>
            @if($sale->reference_number)
            <div class="flex items-center justify-between">
                <p class="font-semibold">Referencia:</p>
                <p class="text-gray-700">{{ $sale->reference_number }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Actions -->
    @if($sale->status === 'completado')
    <div class="card-premium">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Acciones</h3>
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta venta? Se restaurará el inventario.')">
                @csrf
                <button type="submit" class="btn-danger">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar Venta
                </button>
            </form>

            <button onclick="window.print()" class="btn-secondary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
@media print {
    .btn-secondary,
    .btn-danger,
    nav,
    .no-print {
        display: none !important;
    }
}
</style>
@endpush
@endsection
