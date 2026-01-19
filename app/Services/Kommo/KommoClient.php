<?php

namespace App\Services\Kommo;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class KommoClient
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('kommo.base_url');
        $this->token = config('kommo.token');
    }

    /**
     * Cliente HTTP pre-configurado con el Long-Lived Token
     */
    protected function request()
    {
        return Http::withToken($this->token)
            ->baseUrl($this->baseUrl)
            ->acceptJson()
            ->retry(3, 100); // Reintenta 3 veces en caso de error de red
    }

    /**
     * Nuevo método para obtener datos (Importación)
     */
    public function get(string $endpoint, array $query = []): Response
    {
        $response = $this->request()->get($endpoint, $query);
        $this->logErrors($endpoint, $response);
        return $response;
    }

    public function post(string $endpoint, array $data): Response
    {
        $response = $this->request()->post($endpoint, $data);
        $this->logErrors($endpoint, $response);
        return $response;
    }

    public function patch(string $endpoint, array $data): Response
    {
        $response = $this->request()->patch($endpoint, $data);
        $this->logErrors($endpoint, $response);
        return $response;
    }

    private function logErrors(string $endpoint, Response $response): void
    {
        if ($response->failed()) {
            Log::error("Kommo API Error @ {$endpoint}", [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
        }
    }
}