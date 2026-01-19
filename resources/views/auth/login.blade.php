@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex">
    <!-- Panel Izquierdo - Rojo Premium -->
    <div class="hidden lg:flex lg:w-1/2 bg-accent-red items-center justify-center relative overflow-hidden">
        <!-- Patrón decorativo sutil -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="relative z-10 text-white px-16 max-w-lg">
            <div class="mb-8">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-6">
                    <span class="text-accent-red font-display text-4xl font-bold">A</span>
                </div>
            </div>
            
            <h1 class="font-display text-5xl font-semibold mb-6 leading-tight">
                Academia<br>Auténtica
            </h1>
            
            <p class="text-red-100 text-lg leading-relaxed font-light">
                Sistema de gestión integral para la transformación personal y académica.
            </p>
            
            <div class="mt-12 space-y-3">
                <div class="flex items-center space-x-3 text-red-100">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm tracking-wide">Gestión académica completa</span>
                </div>
                <div class="flex items-center space-x-3 text-red-100">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm tracking-wide">Control financiero integrado</span>
                </div>
                <div class="flex items-center space-x-3 text-red-100">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm tracking-wide">Acompañamiento emocional</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel Derecho - Formulario -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-12">
        <div class="max-w-md w-full">
            <!-- Logo móvil -->
            <div class="lg:hidden mb-8 text-center">
                <div class="inline-flex items-center space-x-3">
                    <div class="w-12 h-12 bg-accent-red rounded-full flex items-center justify-center">
                        <span class="text-white font-display text-2xl font-bold">A</span>
                    </div>
                    <span class="text-2xl font-display text-primary-dark">Academia</span>
                </div>
            </div>
            
            <div class="mb-10">
                <h2 class="font-display text-4xl font-semibold text-primary-dark mb-2">
                    Bienvenida
                </h2>
                <p class="text-gray-600 text-sm tracking-wide">
                    Ingresa tus credenciales para acceder al sistema
                </p>
            </div>
            
            <!-- Flash Messages -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email -->
                <div>
                    <label for="email" class="label-elegant">
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="input-elegant @error('email') border-red-500 @enderror" 
                           placeholder="tu@email.com"
                           required 
                           autofocus>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="label-elegant">
                        Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="input-elegant @error('password') border-red-500 @enderror" 
                           placeholder="••••••••"
                           required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 text-accent-red border-gray-300 rounded focus:ring-accent-red">
                        <span class="ml-2 text-sm text-gray-700">Recordarme</span>
                    </label>
                    
                    <a href="#" class="text-sm text-accent-red hover:text-red-700 transition-colors">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full">
                    Iniciar Sesión
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500 tracking-wide">
                    © 2025 Academia Auténtica. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection