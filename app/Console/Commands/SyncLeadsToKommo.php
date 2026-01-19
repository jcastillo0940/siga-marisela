<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Services\Kommo\KommoClient;
use App\Services\Kommo\KommoMapper;
use Illuminate\Console\Command;
use Exception;

class SyncLeadsToKommo extends Command
{
    protected $signature = 'kommo:sync {limit=2 : Cantidad de leads a sincronizar} {--force : Forzar la resincronización}';
    protected $description = 'Debug directo: Sincroniza leads con Kommo sin usar colas para ver errores';

    public function handle()
    {
        $limit = (int) $this->argument('limit');
        $force = $this->option('force');

        $query = Lead::query();
        if (!$force) {
            $query->whereNull('kommo_id');
        }

        $leads = $query->limit($limit)->get();

        if ($leads->isEmpty()) {
            $this->info('No hay leads pendientes por sincronizar.');
            return 0;
        }

        $this->warn("--- INICIANDO DEBUG DIRECTO (SIN COLAS) ---");

        foreach ($leads as $lead) {
            try {
                $this->line("Probando Lead ID: {$lead->id} ({$lead->full_name})...");
                
                // 1. Generamos el Payload usando tu Mapper
                $payload = KommoMapper::toComplexArray($lead);
                
                // 2. Instanciamos el cliente directamente
                $client = app(KommoClient::class);
                
                // 3. Realizamos la petición síncrona
                $response = $client->post('leads/complex', $payload);

                if ($response->successful()) {
                    $this->info("✅ ÉXITO: Lead sincronizado en Kommo.");
                    $this->line("Respuesta: " . $response->body());
                    
                    // Opcional: Actualizar el ID localmente si fue exitoso
                    $responseData = $response->json();
                    if (isset($responseData[0]['id'])) {
                        $lead->update(['kommo_id' => $responseData[0]['id']]);
                    }
                } else {
                    $this->error("❌ ERROR DE API (Status: " . $response->status() . ")");
                    
                    // Mostramos una tabla con el diagnóstico
                    $this->table(['Campo', 'Valor'], [
                        ['Subdominio', config('kommo.subdomain')],
                        ['Pipeline ID', config('kommo.pipeline_id')],
                        ['Payload Enviado', json_encode($payload, JSON_PRETTY_PRINT)],
                        ['Respuesta Kommo', $response->body()],
                    ]);
                }
            } catch (Exception $e) {
                $this->error("🚨 EXCEPCIÓN: " . $e->getMessage());
                $this->line("En archivo: " . $e->getFile() . " línea " . $e->getLine());
            }
            $this->line("-------------------------------------------");
        }

        return 0;
    }
}