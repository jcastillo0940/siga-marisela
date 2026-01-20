@extends('layouts.app')

@section('title', 'Cuentas por Cobrar')
@section('page-title', 'Reporte de Cuentas por Cobrar')

@section('content')
<div class="fade-in">
    <!-- Header with Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <p class="text-gray-600 mt-2">
                Resumen consolidado de todas las cuentas pendientes de cobro
            </p>
        </div>

        <div class="flex gap-3">
            <form method="GET" action="{{ route('reports.accounts-receivable.pdf') }}" class="inline">
                @foreach(request()->except('_token') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <button type="submit" class="btn-secondary bg-red-600 hover:bg-red-700 text-white">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-premium bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total por Cobrar</p>
                    <p class="text-4xl font-display font-bold text-blue-600">${{ number_format($totalReceivable, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $paymentPlans->count() }} planes de pago activos</p>
                </div>
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Vigente (No Vencido)</p>
                    <p class="text-4xl font-display font-bold text-green-600">${{ number_format($totalCurrent, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Cuotas dentro del plazo</p>
                </div>
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-premium bg-gradient-to-br from-red-50 to-red-100 border-2 border-red-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Vencido (Atrasado)</p>
                    <p class="text-4xl font-display font-bold text-red-600">${{ number_format($totalOverdue, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Requiere seguimiento urgente</p>
                </div>
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-premium mb-6">
        <form method="GET" action="{{ route('reports.accounts-receivable') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="course_offering_id" class="label-elegant">Filtrar por Curso</label>
                    <select id="course_offering_id" name="course_offering_id" class="input-elegant">
                        <option value="">Todos los cursos</option>
                        @foreach($courseOfferings as $offering)
                            <option value="{{ $offering->id }}" {{ request('course_offering_id') == $offering->id ? 'selected' : '' }}>
                                {{ $offering->course->name }} - {{ $offering->location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="label-elegant">Estado del Plan</label>
                    <select id="payment_status" name="payment_status" class="input-elegant">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('payment_status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="parcial" {{ request('payment_status') == 'parcial' ? 'selected' : '' }}>Parcial</option>
                        <option value="vencido" {{ request('payment_status') == 'vencido' ? 'selected' : '' }}>Vencido</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="overdue_only" value="1" {{ request('overdue_only') ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm text-gray-700">Solo vencidos</span>
                    </label>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        Filtrar
                    </button>
                    <a href="{{ route('reports.accounts-receivable') }}" class="btn-secondary">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary by Course -->
    @if($byCourse->isNotEmpty())
    <div class="card-premium mb-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">Resumen por Curso</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($byCourse as $summary)
            <div class="bg-neutral-bg rounded-lg p-4 border-2 border-gray-200">
                <p class="font-semibold text-primary-dark">{{ $summary['course_name'] }}</p>
                <p class="text-xs text-gray-600 mb-2">{{ $summary['offering_name'] }}</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-gray-600">{{ $summary['count'] }} {{ $summary['count'] == 1 ? 'estudiante' : 'estudiantes' }}</span>
                    <span class="text-lg font-display font-bold text-red-600">${{ number_format($summary['total_balance'], 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Accounts Receivable Table -->
    <div class="card-premium">
        @if($paymentPlans->isEmpty())
            <div class="text-center py-12">
                <svg class="w-20 h-20 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xl text-gray-600 font-medium">¡No hay cuentas pendientes!</p>
                <p class="text-gray-500 mt-2">Todos los pagos están al día</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-elegant">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Plan</th>
                            <th class="text-right">Total Curso</th>
                            <th class="text-right">Pagado</th>
                            <th class="text-right">Saldo</th>
                            <th>Próximo Vencimiento</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentPlans as $plan)
                        <tr class="{{ $plan->schedules->where('status', 'vencido')->isNotEmpty() ? 'bg-red-50' : '' }}">
                            <td>
                                <div>
                                    <a href="{{ route('students.show', $plan->enrollment->student->id) }}" class="font-medium text-accent-red hover:underline">
                                        {{ $plan->enrollment->student->full_name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $plan->enrollment->student->identification }}</p>
                                </div>
                            </td>

                            <td>
                                <div>
                                    <p class="font-medium text-primary-dark">{{ $plan->enrollment->courseOffering->course->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $plan->enrollment->courseOffering->location }}</p>
                                </div>
                            </td>

                            <td>
                                <span class="text-sm">{{ ucfirst($plan->payment_type) }}</span>
                                @if($plan->payment_type === 'cuotas')
                                    <p class="text-xs text-gray-500">{{ $plan->number_of_installments }} cuotas</p>
                                @endif
                            </td>

                            <td class="text-right">
                                <span class="font-medium">${{ number_format($plan->total_amount, 2) }}</span>
                            </td>

                            <td class="text-right">
                                <span class="text-green-600 font-medium">${{ number_format($plan->total_paid, 2) }}</span>
                            </td>

                            <td class="text-right">
                                <span class="text-red-600 font-bold text-lg">${{ number_format($plan->balance, 2) }}</span>
                            </td>

                            <td>
                                @php
                                    $nextSchedule = $plan->schedules->sortBy('due_date')->first();
                                @endphp
                                @if($nextSchedule)
                                    <div>
                                        <p class="text-sm">{{ $nextSchedule->due_date->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">${{ number_format($nextSchedule->balance, 2) }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    $hasOverdue = $plan->schedules->where('status', 'vencido')->isNotEmpty();
                                    $statusClass = $hasOverdue ? 'badge-danger' : 'badge-warning';
                                    $statusText = $hasOverdue ? 'Vencido' : 'Vigente';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="5" class="text-right">TOTALES:</td>
                            <td class="text-right text-red-600 text-xl">${{ number_format($totalReceivable, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
