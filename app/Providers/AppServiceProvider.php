<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Lead;
use App\Observers\UserObserver;
use App\Observers\LeadObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Esto corrige el error "Cannot resolve public path" vinculando 
        // correctamente la carpeta public_html de Hostinger
        $this->app->bind('path.public', function() {
            return base_path('public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Observador para Usuarios
        User::observe(UserObserver::class);

        // Observador para Leads (Integración Kommo)
        Lead::observe(LeadObserver::class);
    }
}