@extends('layouts.app')

@section('title', 'Mi Dashboard - ' . $student->full_name)
@section('page-title', 'Dashboard del Estudiante')

@section('content')
<div class="fade-in">
    <!-- Student Header -->
    <div class="card-premium mb-6 bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-4xl font-display font-bold text-primary-dark">
                        ¡Bienvenido, {{ $student->first_name }}!
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $student->email }}</p>
                    <p class="text-sm text-gray-500">ID: {{ $student->identification }}</p>
                </div>
            </div>
            <a href="{{ route('student-dashboard.select') }}" class="btn-secondary">
                Cambiar Estudiante
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Cursos Activos</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total_enrollments'] }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Completados</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['completed_courses'] }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Pagado</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($stats['total_payments'], 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-yellow-500 to-yellow-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Saldo Pendiente</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($stats['pending_balance'], 2) }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card bg-gradient-to-br from-red-500 to-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Certificados</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['certificates_count'] }}</p>
                </div>
                <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - 2/3 width -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Upcoming Sessions -->
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Próximas Clases
                </h3>

                @if($upcomingSessions->isEmpty())
                <p class="text-gray-500 text-center py-8">No tienes clases programadas próximamente</p>
                @else
                <div class="space-y-3">
                    @foreach($upcomingSessions as $item)
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-primary-dark">{{ $item['course']->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $item['session']->topic ?? 'Sesión ' . $item['session']->session_number }}</p>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $item['session']->session_date->format('d/m/Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $item['session']->start_time }} - {{ $item['session']->end_time }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('attendance.student-qr', $item['enrollment']->id) }}" class="btn-primary text-xs">
                                Ver QR
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Active Enrollments -->
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Mis Cursos
                </h3>

                @if($activeEnrollments->isEmpty())
                <p class="text-gray-500 text-center py-8">No estás inscrito en ningún curso actualmente</p>
                @else
                <div class="space-y-4">
                    @foreach($activeEnrollments as $enrollment)
                    <div class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-300 transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg text-primary-dark">{{ $enrollment->courseOffering->course->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $enrollment->courseOffering->formatted_schedule }}</p>
                            </div>
                            <span class="status-badge status-badge-{{ $enrollment->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $progress = $enrollment->courseOffering->sessions->count() > 0
                                ? ($enrollment->attendances->count() / $enrollment->courseOffering->sessions->count()) * 100
                                : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Progreso</span>
                                <span>{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-center text-sm">
                            <div>
                                <p class="text-gray-500">Asistencia</p>
                                <p class="font-bold text-blue-600">{{ $enrollment->attendances->count() }}/{{ $enrollment->courseOffering->sessions->count() }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Saldo</p>
                                <p class="font-bold text-{{ $enrollment->paymentPlan && $enrollment->paymentPlan->balance > 0 ? 'red' : 'green' }}-600">
                                    ${{ number_format($enrollment->paymentPlan ? $enrollment->paymentPlan->balance : 0, 2) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Acciones</p>
                                <a href="{{ route('attendance.student-report', $enrollment->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Payment History -->
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Historial de Pagos
                </h3>

                @if($payments->isEmpty())
                <p class="text-gray-500 text-center py-8">No hay pagos registrados</p>
                @else
                <div class="overflow-x-auto">
                    <table class="table-elegant">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Curso</th>
                                <th>Método</th>
                                <th class="text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                <td>{{ $payment->enrollment->courseOffering->course->name }}</td>
                                <td><span class="badge badge-info">{{ $payment->payment_method_label }}</span></td>
                                <td class="text-right font-semibold text-green-600">${{ number_format($payment->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <!-- Material Didáctico -->
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Material Didáctico
                </h3>

                @if($materials->isEmpty())
                <p class="text-gray-500 text-center py-8">No hay material disponible aún</p>
                @else
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($materials as $item)
                    <div class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg border border-orange-200 hover:border-orange-400 transition-all">
                        <div class="flex items-start space-x-3">
                            <span class="text-3xl">{{ $item['material']->type_icon }}</span>
                            <div class="flex-1">
                                <h4 class="font-semibold text-primary-dark">{{ $item['material']->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $item['course']->name }}</p>
                                @if($item['material']->description)
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($item['material']->description, 80) }}</p>
                                @endif
                                <div class="flex items-center space-x-3 mt-2">
                                    <span class="badge badge-warning">{{ $item['material']->type_label }}</span>
                                    @if($item['material']->file_size)
                                    <span class="text-xs text-gray-500">{{ $item['material']->formatted_file_size }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($item['material']->type === 'link' || $item['material']->external_url)
                            <a href="{{ $item['material']->external_url }}" target="_blank" class="btn-primary text-xs">
                                Abrir
                            </a>
                            @elseif($item['material']->file_path)
                            <a href="{{ $item['material']->file_url }}" download class="btn-primary text-xs">
                                Descargar
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Selección de Menú -->
            @if(!$availableMenus->isEmpty())
            <div class="card-premium">
                <h3 class="text-xl font-display font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Selección de Menú
                </h3>

                <div class="space-y-4">
                    @foreach($availableMenus as $item)
                    @php
                        $menu = $item['menu'];
                        $enrollment = $item['enrollment'];
                        $currentSelection = $menu->getStudentSelection($enrollment->id);
                    @endphp
                    <div class="p-4 border-2 border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-primary-dark flex items-center">
                                    <span class="text-2xl mr-2">{{ $menu->meal_type_icon }}</span>
                                    {{ $menu->meal_type_label }}
                                </h4>
                                <p class="text-sm text-gray-600">{{ $menu->meal_date->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $item['course']->name }}</p>
                            </div>
                            @if($currentSelection)
                            <span class="status-badge status-badge-success">Seleccionado</span>
                            @endif
                        </div>

                        @if($menu->menu_description)
                        <p class="text-sm text-gray-700 mb-3">{{ $menu->menu_description }}</p>
                        @endif

                        @if($currentSelection)
                        <div class="p-3 bg-green-50 border border-green-200 rounded mb-3">
                            <p class="text-sm font-semibold text-green-800">Tu selección: {{ $currentSelection->mealOption->name }}</p>
                            @if($currentSelection->notes)
                            <p class="text-xs text-green-600 mt-1">Nota: {{ $currentSelection->notes }}</p>
                            @endif
                        </div>
                        @endif

                        <form action="{{ route('student-dashboard.select-meal', $student->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="meal_menu_id" value="{{ $menu->id }}">
                            <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">

                            <div class="space-y-2 mb-3">
                                @foreach($menu->options as $option)
                                <label class="flex items-center p-3 border-2 rounded {{ $currentSelection && $currentSelection->meal_option_id == $option->id ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-blue-300' }} cursor-pointer transition-all">
                                    <input type="radio" name="meal_option_id" value="{{ $option->id }}" class="w-4 h-4 text-accent-red" {{ $currentSelection && $currentSelection->meal_option_id == $option->id ? 'checked' : '' }} required>
                                    <div class="ml-3 flex-1">
                                        <p class="font-semibold text-sm">{{ $option->name }}</p>
                                        @if($option->description)
                                        <p class="text-xs text-gray-600">{{ $option->description }}</p>
                                        @endif
                                        <div class="flex items-center space-x-2 mt-1">
                                            @foreach($option->dietary_labels as $label)
                                            <span class="text-xs px-2 py-1 bg-{{ $label['color'] }}-100 text-{{ $label['color'] }}-700 rounded">
                                                {{ $label['icon'] }} {{ $label['label'] }}
                                            </span>
                                            @endforeach
                                            @if($option->available_quantity !== null)
                                            <span class="text-xs text-gray-500">({{ $option->remaining_quantity }} disponibles)</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <input type="text" name="notes" placeholder="Notas especiales (opcional)" class="input-elegant text-sm" value="{{ $currentSelection->notes ?? '' }}">
                            </div>

                            <button type="submit" class="btn-primary w-full text-sm">
                                {{ $currentSelection ? 'Actualizar Selección' : 'Guardar Selección' }}
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - 1/3 width -->
        <div class="space-y-6">
            <!-- Certificates -->
            <div class="card-premium">
                <h3 class="text-lg font-semibold text-primary-dark mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    Mis Certificados
                </h3>

                @if($certificates->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">No tienes certificados aún</p>
                </div>
                @else
                <div class="space-y-3">
                    @foreach($certificates as $certificate)
                    <div class="p-3 bg-gradient-to-r from-yellow-50 to-red-50 rounded-lg border border-yellow-200">
                        <h4 class="font-semibold text-sm text-primary-dark">{{ $certificate->course->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $certificate->issued_at->format('d/m/Y') }}</p>
                        <a href="{{ route('certificates.download', $certificate->id) }}" class="btn-primary text-xs w-full mt-2 inline-block text-center">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Descargar PDF
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="card-premium bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">Acciones Rápidas</h3>
                <div class="space-y-2">
                    <a href="{{ route('certificates.student', $student->id) }}" class="btn-secondary w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Ver Todos mis Certificados
                    </a>
                    <a href="{{ route('public.leads.create') }}" class="btn-primary w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Solicitar Nuevo Curso
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
