<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Services\Kommo\KommoClient;
use App\Services\Kommo\KommoMapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncLeadWithKommo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Reintentos automáticos si la API falla (p.ej. por límite de rate)
    public $tries = 3;
    public $backoff = 60;

    public function __construct(
        protected Lead $lead
    ) {}

    public function handle(KommoClient $client): void
    {
        // Si el lead no tiene kommo_id, es una creación (complex lead)
        if (!$this->lead->kommo_id) {
            $data = KommoMapper::toComplexArray($this->lead);
            $response = $client->post('leads/complex', $data);

            if ($response->successful()) {
                $result = $response->json();
                // Guardamos los IDs que nos devuelve Kommo
                $this->lead->updateQuietly([
                    'kommo_id' => $result['_embedded']['leads'][0]['id'] ?? null,
                    'kommo_contact_id' => $result['_embedded']['leads'][0]['_embedded']['contacts'][0]['id'] ?? null,
                    'last_synced_at' => now(),
                ]);
            }
            return;
        }

        // Si ya tiene ID, es una actualización (PATCH)
        // Aquí llamaríamos a un método del Mapper para solo actualización
        // $client->patch("leads/{$this->lead->kommo_id}", KommoMapper::toUpdateArray($this->lead));
    }
}