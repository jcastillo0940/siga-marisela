<?php

namespace App\Services\Kommo;

use App\Models\Lead;

class KommoMapper
{
    public static function toComplexArray(Lead $lead): array
    {
        return [
            [
                'name' => 'Lead SIGA: ' . $lead->full_name,
                'pipeline_id' => 12353547,
                // Quitamos status_id para que Kommo use el primero por defecto
                '_embedded' => [
                    'contacts' => [
                        [
                            'name' => $lead->full_name,
                            'custom_fields_values' => [
                                [
                                    'field_code' => 'PHONE',
                                    'values' => [['value' => $lead->phone]]
                                ],
                                [
                                    'field_code' => 'EMAIL',
                                    'values' => [['value' => $lead->email]]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    protected static function mapLeadCustomFields(Lead $lead): array
    {
        $fields = [];

        if ($lead->source) {
            $fields[] = [
                'field_id' => (int) config('kommo.custom_fields.source'),
                'values' => [['value' => $lead->source]]
            ];
        }

        if ($lead->interests) {
            $fields[] = [
                'field_id' => (int) config('kommo.custom_fields.interests'),
                'values' => [['value' => $lead->interests]]
            ];
        }

        if ($lead->notes) {
            $fields[] = [
                'field_id' => (int) config('kommo.custom_fields.notes'),
                'values' => [['value' => $lead->notes]]
            ];
        }

        return $fields;
    }
}