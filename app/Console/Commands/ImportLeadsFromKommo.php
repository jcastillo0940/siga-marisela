<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Services\Kommo\KommoClient;
use Illuminate\Console\Command;

class ImportLeadsFromKommo extends Command
{
    protected $signature = 'kommo:import {limit=10}';
    protected $description = 'Importa leads desde Kommo a la DB local evitando errores de email duplicado';

    public function handle()
    {
        $limit = (int) $this->argument('limit');
        $client = app(KommoClient::class);

        $this->info("Consultando Kommo...");

        $response = $client->get('leads', [
            'limit' => $limit,
            'with' => 'contacts', 
        ]);

        if (!$response->successful()) {
            $this->error("Error: " . $response->body());
            return 1;
        }

        $leads = $response->json()['_embedded']['leads'] ?? [];
        $bar = $this->output->createProgressBar(count($leads));
        $bar->start();

        foreach ($leads as $kLead) {
            $fullName = $kLead['name'] ?? 'Lead #' . $kLead['id'];
            $parts = explode(' ', $fullName, 2);
            $firstName = $parts[0];
            $lastName = $parts[1] ?? '';

            $email = null;
            $phone = '';

            // Extraer email y teléfono de los contactos embebidos
            if (!empty($kLead['_embedded']['contacts'])) {
                foreach ($kLead['_embedded']['contacts'][0]['custom_fields_values'] ?? [] as $field) {
                    if ($field['field_code'] === 'PHONE') $phone = $field['values'][0]['value'] ?? '';
                    if ($field['field_code'] === 'EMAIL') $email = $field['values'][0]['value'] ?? null;
                }
            }

            // --- SOLUCIÓN AL ERROR DE DUPLICADO ---
            // Si el email está vacío, le asignamos uno único basado en su ID de Kommo
            if (empty($email)) {
                $email = "sin-email-{$kLead['id']}@kommo.com";
            }

            Lead::updateOrCreate(
                ['kommo_id' => $kLead['id']],
                [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'phone'      => $phone,
                    'created_at' => date('Y-m-d H:i:s', $kLead['created_at']),
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Importación finalizada con éxito.");
    }
}