@extends('layouts.guest')

@section('content')
<div class="max-w-2xl w-full fade-in">
    <div class="card-premium text-center py-12 px-8">
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-display font-bold text-slate-800 mb-4">
            ¡Inscripción Recibida!
        </h1>
        
        <p class="text-slate-600 text-lg mb-8 leading-relaxed">
            Gracias por elegir **Academia Auténtica**. Hemos recibido tus datos y tu comprobante de pago correctamente.
        </p>

        <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 mb-8 text-left">
            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider mb-3">¿Qué sigue ahora?</h3>
            <ul class="space-y-3 text-slate-600 text-sm">
                <li class="flex items-start">
                    <span class="text-rose-500 mr-2 font-bold">1.</span>
                    Nuestro equipo administrativo validará tu pago en un lapso de 24 a 48 horas hábiles.
                </li>
                <li class="flex items-start">
                    <span class="text-rose-500 mr-2 font-bold">2.</span>
                    Una vez verificado, recibirás un correo electrónico con tu confirmación oficial de cupo.
                </li>
                <li class="flex items-start">
                    <span class="text-rose-500 mr-2 font-bold">3.</span>
                    Te contactaremos vía WhatsApp para integrarte al grupo de tu curso.
                </li>
            </ul>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="https://wa.me/507XXXXXXXX" target="_blank" class="btn-primary inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 border-none">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                WhatsApp Soporte
            </a>
            <a href="/inscripcion" class="btn-primary inline-flex items-center justify-center bg-slate-200 text-slate-700 hover:bg-slate-300 border-none">
                Volver al inicio
            </a>
        </div>
    </div>
</div>
@endsection