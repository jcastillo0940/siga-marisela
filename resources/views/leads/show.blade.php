@extends('layouts.app')

@section('title', 'Detalles del Lead')
@section('page-title', 'Detalles del Lead')

@section('content')
<div class="fade-in">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('leads.index') }}" class="text-gray-500 hover:text-accent-red transition-colors">
                        Leads
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">{{ $lead->full_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Cabecera con Foto/Avatar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            {{-- Sección de Fotografía Corregida --}}
            <div class="flex-shrink-0">
                @if($lead->student_photo)
                    {{-- Cargamos la imagen directamente. Si falla el archivo, el JS mostrará la inicial --}}
                    <img src="{{ asset('storage/' . $lead->student_photo) }}" 
                         alt="{{ $lead->full_name }}" 
                         class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover shadow-elegant border-4 border-white ring-1 ring-gray-100"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    
                    {{-- Este div está oculto por defecto y solo se muestra si la imagen de arriba falla (Error 404) --}}
                    <div style="display: none;" class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant border-4 border-white">
                        {{ substr($lead->first_name, 0, 1) }}
                    </div>
                @else
                    {{-- Si no hay ruta de foto en la base de datos --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white text-3xl sm:text-4xl font-display font-semibold shadow-elegant border-4 border-white">
                        {{ substr($lead->first_name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div>
                <h1 class="text-3xl sm:text-4xl font-display font-semibold text-primary-dark mb-2">
                    {{ $lead->full_name }}
                </h1>
                <div class="flex flex-wrap items-center gap-3 text-gray-600">
                    <a href="mailto:{{ $lead->email }}" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $lead->email }}
                    </a>
                    <a href="tel:{{ $lead->phone }}" class="flex items-center hover:text-accent-red transition-colors text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $lead->phone }}
                    </a>
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    @php
                        $statusColors = [
                            'nuevo' => 'badge-info',
                            'contactado' => 'badge-warning',
                            'interesado' => 'badge badge-info',
                            'negociacion' => 'badge badge-warning',
                            'inscrito' => 'badge-success',
                            'perdido' => 'badge-danger'
                        ];
                        $payStatusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'verified' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="badge {{ $statusColors[$lead->status] ?? 'badge-info' }}">
                        {{ ucfirst($lead->status) }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payStatusColors[$lead->payment_status] ?? 'bg-gray-100' }}">
                        Pago: {{ ucfirst($lead->payment_status) }}
                    </span>
                    @if($lead->isConverted())
                        <span class="badge badge-success">Convertido</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex space-x-3 w-full sm:w-auto">
            @if(auth()->user()->hasPermission('leads.edit'))
            <a href="{{ route('leads.edit', $lead->id) }}" class="btn-primary flex-1 sm:flex-none text-center">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            @endif
            <a href="{{ route('leads.index') }}" class="btn-secondary flex-1 sm:flex-none text-center">
                Volver
            </a>
        </div>
    </div>

    {{-- Sección de Validación de Pago --}}
    @if($lead->payment_status === 'pending' && !$lead->isConverted() && $lead->payment_receipt_path)
    <div class="card-premium border-2 border-accent-red mb-8 bg-red-50/20">
        <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 text-center sm:text-left">Verificación de Comprobante</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <label class="label-elegant mb-2 block">Imagen del recibo</label>
                <a href="{{ asset('storage/' . $lead->payment_receipt_path) }}" target="_blank" class="block group">
                    <img src="{{ asset('storage/' . $lead->payment_receipt_path) }}" class="w-full h-64 object-contain bg-white rounded-lg border shadow-sm transition-transform group-hover:scale-[1.01]">
                    <span class="text-xs text-blue-600 mt-2 block font-medium">Click para abrir en pantalla completa ↗</span>
                </a>
            </div>
            <div class="flex flex-col justify-center">
                <form action="{{ route('leads.verify-payment', $lead->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label-elegant">Notas de Verificación</label>
                        <textarea name="notes" class="input-elegant" rows="3" placeholder="Confirmación de Yappy, transferencia #..."></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" name="status" value="verified" class="btn-primary flex-1 bg-green-600 hover:bg-green-700">
                            Aprobar y Convertir
                        </button>
                        <button type="submit" name="status" value="rejected" class="btn-secondary flex-1 text-red-600 border-red-200">
                            Rechazar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Resto de la cuadrícula de información --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">Información del Registro</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Fecha de Nacimiento</label>
                        <p class="text-primary-dark font-medium">{{ $lead->birth_date_text ?? 'No registrada' }}</p>
                    </div>
                    <div>
                        <label class="label-elegant">Edad</label>
                        <p class="text-primary-dark font-medium">{{ $lead->age ?? 'No calculada' }} años</p>
                    </div>
                    <div>
                        <label class="label-elegant">Ocupación / Actividad</label>
                        <p class="text-primary-dark font-medium">{{ $lead->occupation ?? 'No registrada' }}</p>
                    </div>
                    <div>
                        <label class="label-elegant">Instagram/TikTok</label>
                        <p class="text-primary-dark font-medium">{{ $lead->social_media_handle ?? 'No registrado' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-elegant">Dirección Completa</label>
                        <p class="text-primary-dark font-medium">{{ $lead->address_full ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>

            @if($lead->parent_name)
            <div class="card-premium">
                <h2 class="text-xl font-display font-semibold text-primary-dark mb-6">Información de Mamá / Tutor</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label-elegant">Nombre del Tutor</label>
                        <p class="text-primary-dark font-medium">{{ $lead->parent_name }} ({{ $lead->parent_relationship }})</p>
                    </div>
                    <div>
                        <label class="label-elegant">Teléfono del Tutor</label>
                        <p class="text-primary-dark font-medium">{{ $lead->parent_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="label-elegant">Ocupación del Tutor</label>
                        <p class="text-primary-dark font-medium">{{ $lead->parent_occupation ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Barra Lateral --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="card-premium">
                <h3 class="font-display font-semibold text-primary-dark mb-4">Detalles Técnicos</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-gray-600">ID del Registro</span>
                        <span class="font-semibold text-primary-dark">#{{ $lead->id }}</span>
                    </div>
                    <div>
                        <label class="text-gray-500 text-xs uppercase block mb-1">Fecha de registro</label>
                        <p class="text-primary-dark font-medium">{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection