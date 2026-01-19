<?php
namespace App\Services\Kommo;

use Illuminate\Support\Facades\Http;
use App\Models\Lead;

class KommoService {
    protected string $baseUrl;
    protected string $token;

    public function __construct() {
        $this->baseUrl = config('kommo.base_url');
        $this->token = config('kommo.token');
    }

    protected function client() {
        return Http::withToken($this->token)->baseUrl($this->baseUrl)->acceptJson();
    }

    public function syncLead(Lead $lead): void {
        // Lógica para enviar Lead + Contacto (endpoint leads/complex)
    }
}