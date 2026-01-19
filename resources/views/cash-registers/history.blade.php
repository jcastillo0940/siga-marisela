    @extends('layouts.app')

@section('title', 'Historial de Cajas')
@section('page-title', 'Historial de Cajas Cerradas')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark">
            Historial de Cajas
        </h1>
        
        <a href="{{ route('cash-registers.index') }}" class="btn-secondary">
            Volver a Gestión de Caja
        </a>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">Filtros de Búsqueda</h3>
        
        <form method="GET" action="{{ route('cash-registers.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="label-elegant">Desde</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ $filters['date_from'] ?? '' }}"
                       class="input-elegant">
            </div>
            
            <div>
                <label class="label-elegant">Hasta</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ $filters['date_to'] ?? '' }}"
                       class="input-elegant">
            </div>
            
            <div>
                <label class="label-elegant">Cajero</label>
                <select name="user_id" class="input-elegant">
                    <option value="">Todos</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Buscar
                </button>
                <a href="{{ route('cash-registers.history') }}" class="btn-secondary">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="card-premium">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cajero</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apertura</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cierre</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Fondo Inicial</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Diferencia</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($registers as $register)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-primary-dark">
                                {{ $register->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $register->openedBy->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $register->opened_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $register->closed_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            ${{ number_format($register->opening_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right text-green-600">
                            ${{ number_format($register->total_payments, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">
                            <span class="{{ $register->difference >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($register->difference, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('cash-registers.report', $register->id) }}" 
                               class="text-blue-600 hover:text-blue-900 font-medium">
                                Ver Reporte
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            No se encontraron cajas cerradas con los filtros seleccionados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registers->hasPages())
        <div class="mt-6">
            {{ $registers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection