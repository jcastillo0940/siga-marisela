@extends('layouts.app')

@section('title', 'Gestión de Caja')
@section('page-title', 'Gestión de Caja')

@section('content')
<div class="fade-in">
    @if($activeCashRegister)
    <!-- Active Cash Register -->
    <div class="mb-8">
        <div class="card-premium bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Caja Activa</p>
                        <h2 class="text-2xl font-display font-semibold text-primary-dark">{{ $activeCashRegister->code }}</h2>
                        <p class="text-sm text-gray-500">Operador: {{ $activeCashRegister->openedBy->name }}</p>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-sm text-gray-600">Fondo Inicial</p>
                    <p class="text-3xl font-display font-bold text-green-600">{{ $activeCashRegister->formatted_opening_amount }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activeCashRegister->opened_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Recaudado</p>
                    <p class="text-2xl font-display font-bold text-blue-600">${{ number_format($activeCashRegister->total_payments, 2) }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Transacciones</p>
                    <p class="text-2xl font-display font-bold text-purple-600">{{ $activeCashRegister->payments->count() }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Total en Caja</p>
                    <p class="text-2xl font-display font-bold text-green-600">${{ number_format($activeCashRegister->opening_amount + $activeCashRegister->total_payments, 2) }}</p>
                </div>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('pos.index') }}" class="btn-primary flex-1 text-center">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Ir al POS
                </a>
                <button type="button" 
                        onclick="showCloseModal()"
                        class="btn-secondary bg-red-600 hover:bg-red-700 text-white flex-1">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cerrar Caja (Corte Z)
                </button>
            </div>
        </div>
    </div>
    @else
    <!-- Open Cash Register -->
    <div class="max-w-2xl mx-auto">
        <div class="card-premium">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-display font-semibold text-primary-dark mb-2">Abrir Caja</h2>
                <p class="text-gray-600">Ingresa el fondo inicial para comenzar el turno</p>
            </div>

            <form method="POST" action="{{ route('cash-registers.open') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="opening_amount" class="label-elegant text-lg">Fondo Inicial (USD) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-2xl font-bold text-gray-400">$</span>
                            <input type="number" 
                                   id="opening_amount" 
                                   name="opening_amount" 
                                   value="{{ old('opening_amount', 0) }}"
                                   class="w-full pl-12 pr-4 py-4 text-3xl font-display font-bold text-right border-2 border-blue-500 rounded-lg focus:ring-4 focus:ring-blue-200 @error('opening_amount') border-red-500 @enderror"
                                   step="0.01"
                                   min="0"
                                   required
                                   autofocus>
                        </div>
                        @error('opening_amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-2">Cantidad de efectivo con la que inicias el turno</p>
                    </div>

                    <div>
                        <label for="opening_notes" class="label-elegant">Notas de Apertura</label>
                        <textarea id="opening_notes" 
                                  name="opening_notes" 
                                  rows="3"
                                  class="input-elegant @error('opening_notes') border-red-500 @enderror"
                                  placeholder="Notas u observaciones al abrir caja...">{{ old('opening_notes') }}</textarea>
                        @error('opening_notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror>
                    </div>

                    <button type="submit" class="w-full btn-primary text-lg py-4">
                        <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Close Cash Register Modal -->
<div id="close-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4">
        <form method="POST" action="{{ route('cash-registers.close', $activeCashRegister->id ?? 0) }}">
            @csrf
            
            <div class="p-6">
                <h3 class="text-2xl font-display font-semibold text-primary-dark mb-4">Cerrar Caja</h3>
                <p class="text-gray-600 mb-6">Ingresa el monto total en efectivo que hay en caja</p>

                <div class="space-y-4">
                    <div>
                        <label for="closing_amount" class="label-elegant">Monto Final en Caja (USD) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-xl font-bold text-gray-400">$</span>
                            <input type="number" 
                                   id="closing_amount" 
                                   name="closing_amount" 
                                   class="w-full pl-12 pr-4 py-3 text-2xl font-display font-bold text-right border-2 border-red-500 rounded-lg"
                                   step="0.01"
                                   min="0"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label for="closing_notes" class="label-elegant">Notas de Cierre</label>
                        <textarea id="closing_notes" 
                                  name="closing_notes" 
                                  rows="2"
                                  class="input-elegant"
                                  placeholder="Observaciones al cerrar caja..."></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex space-x-3 rounded-b-lg">
                <button type="button" onclick="hideCloseModal()" class="flex-1 btn-secondary">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 btn-primary bg-red-600 hover:bg-red-700">
                    Cerrar Caja
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showCloseModal() {
    document.getElementById('close-modal').classList.remove('hidden');
    document.getElementById('close-modal').classList.add('flex');
    document.getElementById('closing_amount').focus();
}

function hideCloseModal() {
    document.getElementById('close-modal').classList.add('hidden');
    document.getElementById('close-modal').classList.remove('flex');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideCloseModal();
    }
});
</script>
@endpush
@endsection