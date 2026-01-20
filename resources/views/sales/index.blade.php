@extends('layouts.app')

@section('title', 'Ventas')
@section('page-title', 'Historial de Ventas')

@section('content')
<div class="fade-in">
    <!-- Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div class="mb-4 md:mb-0">
            <p class="text-gray-600">Gestión de ventas de productos y servicios</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('sales.pos') }}" class="btn-primary">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Venta
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-6">
        <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                <input type="date"
                       name="date_from"
                       value="{{ request('date_from') }}"
                       class="input-elegant">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                <input type="date"
                       name="date_to"
                       value="{{ request('date_to') }}"
                       class="input-elegant">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="status" class="input-elegant">
                    <option value="">Todos</option>
                    <option value="completado" {{ request('status') === 'completado' ? 'selected' : '' }}>Completado</option>
                    <option value="pendiente" {{ request('status') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="cancelado" {{ request('status') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    <option value="reembolsado" {{ request('status') === 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-secondary w-full">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Sales Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Ventas</p>
                    <p class="text-3xl font-bold text-white">{{ $sales->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Ingresos Totales</p>
                    <p class="text-3xl font-bold text-white">${{ number_format($sales->sum('total'), 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Promedio por Venta</p>
                    <p class="text-3xl font-bold text-white">${{ $sales->count() > 0 ? number_format($sales->sum('total') / $sales->count(), 2) : '0.00' }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Ventas Hoy</p>
                    <p class="text-3xl font-bold text-white">{{ $sales->where('sale_date', '>=', today())->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card-premium">
        @if($sales->isEmpty())
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-xl text-gray-500 font-medium">No hay ventas registradas</p>
            <p class="text-gray-400 mt-2">Comienza registrando tu primera venta</p>
            <a href="{{ route('sales.pos') }}" class="btn-primary mt-4 inline-flex">
                Nueva Venta
            </a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left">Código</th>
                        <th class="text-left">Fecha</th>
                        <th class="text-left">Cliente</th>
                        <th class="text-center">Items</th>
                        <th class="text-right">Total</th>
                        <th class="text-left">Pago</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td class="font-semibold text-primary-dark">{{ $sale->sale_code }}</td>
                        <td>
                            <p class="font-medium">{{ $sale->sale_date->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $sale->sale_date->format('H:i') }}</p>
                        </td>
                        <td>
                            @if($sale->customer_name)
                            <p class="font-medium">{{ $sale->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $sale->customer_document }}</p>
                            @else
                            <span class="text-gray-400 text-sm">Cliente no registrado</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full text-sm font-semibold">
                                {{ $sale->items->count() }}
                            </span>
                        </td>
                        <td class="text-right">
                            <p class="font-bold text-lg text-green-600">${{ number_format($sale->total, 2) }}</p>
                        </td>
                        <td>
                            @if($sale->payment_method === 'multiple')
                            <span class="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded-full font-semibold">
                                Múltiple
                            </span>
                            @else
                            <span class="text-xs text-gray-600">{{ $sale->payment_method_label }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($sale->status === 'completado')
                            <span class="status-badge status-badge-success">Completado</span>
                            @elseif($sale->status === 'cancelado')
                            <span class="status-badge status-badge-danger">Cancelado</span>
                            @elseif($sale->status === 'reembolsado')
                            <span class="status-badge status-badge-warning">Reembolsado</span>
                            @else
                            <span class="status-badge status-badge-info">{{ ucfirst($sale->status) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn-icon" title="Ver detalle">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sales->hasPages())
        <div class="mt-6">
            {{ $sales->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
