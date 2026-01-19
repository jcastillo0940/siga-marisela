<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Observers\LeadObserver;
use App\Services\LeadService;
use App\DTOs\Lead\UpdateLeadDTO;
use App\DTOs\Lead\CreateLeadDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KommoWebhookController extends Controller
{
    public function __construct(
        protected LeadService $leadService
    ) {}

    public function handle(Request $request)
    {
        $data = $request->all();

        Log::info('Kommo Webhook recibido', $data);

        if (isset($data['leads']['update'])) {
            foreach ($data['leads']['update'] as $kommoLead) {
                $this->processUpdate($kommoLead);
            }
        }

        if (isset($data['leads']['add'])) {
            foreach ($data['leads']['add'] as $kommoLead) {
                $this->processAdd($kommoLead);
            }
        }

        return response()->json(['status' => 'success']);
    }

    protected function processUpdate(array $kommoData)
    {
        $lead = Lead::where('kommo_id', $kommoData['id'])->first();

        if ($lead) {
            LeadObserver::$disableSync = true;

            $statusMapping = array_flip(config('kommo.status_mapping'));
            $newStatus = $statusMapping[$kommoData['status_id']] ?? $lead->status;

            $dto = UpdateLeadDTO::fromRequest([
                'status' => $newStatus,
                'notes' => "Actualizado automáticamente desde Kommo"
            ]);

            $this->leadService->updateLead($lead->id, $dto);

            LeadObserver::$disableSync = false;
        }
    }

    protected function processAdd(array $kommoData)
    {
        if (Lead::where('kommo_id', $kommoData['id'])->exists()) {
            return;
        }

        $contact = $kommoData['_embedded']['contacts'][0] ?? null;
        $email = $this->getCustomFieldValue($contact, 'EMAIL') ?? "sin-email-{$kommoData['id']}@kommo.com";
        $phone = $this->getCustomFieldValue($contact, 'PHONE') ?? "00000000";

        $statusMapping = array_flip(config('kommo.status_mapping'));
        $status = $statusMapping[$kommoData['status_id']] ?? 'nuevo';

        $dto = new CreateLeadDTO(
            first_name: $contact['first_name'] ?? 'Contacto',
            last_name: $contact['last_name'] ?? 'Kommo',
            email: $email,
            phone: $phone,
            source: 'web',
            status: $status,
            notes: "Lead creado desde panel de Kommo (ID: {$kommoData['id']})"
        );

        LeadObserver::$disableSync = true;
        
        $lead = $this->leadService->createLead($dto);
        
        $lead->updateQuietly([
            'kommo_id' => $kommoData['id'],
            'kommo_contact_id' => $contact['id'] ?? null
        ]);

        LeadObserver::$disableSync = false;
    }

    protected function getCustomFieldValue(?array $entity, string $fieldCode): ?string
    {
        if (!$entity || !isset($entity['custom_fields_values'])) {
            return null;
        }

        foreach ($entity['custom_fields_values'] as $field) {
            if (($field['field_code'] ?? '') === $fieldCode) {
                return $field['values'][0]['value'] ?? null;
            }
        }

        return null;
    }
}