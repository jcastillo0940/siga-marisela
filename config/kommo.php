<?php

return [
    'subdomain' => env('KOMMO_SUBDOMAIN'),
    'token' => env('KOMMO_LONG_LIVED_TOKEN'),
    'base_url' => "https://" . env('KOMMO_SUBDOMAIN') . ".kommo.com/api/v4/",

    /*
    |--------------------------------------------------------------------------
    | Pipeline ID
    |--------------------------------------------------------------------------
    | ID del embudo de ventas principal donde se gestionarán los leads.
    */
    'pipeline_id' => env('KOMMO_PIPELINE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Mapeo de estados Laravel => ID de estado en Kommo
    |--------------------------------------------------------------------------
    | Traduce tus enums de base de datos a los IDs numéricos de Kommo.
    */
    'status_mapping' => [
        'nuevo'       => (int) env('KOMMO_STATUS_NUEVO', 123456),
        'contactado'  => (int) env('KOMMO_STATUS_CONTACTADO', 234567),
        'interesado'  => (int) env('KOMMO_STATUS_INTERESADO', 345678),
        'negociacion' => (int) env('KOMMO_STATUS_NEGOCIACION', 456789),
        'inscrito'    => (int) env('KOMMO_STATUS_INSCRITO', 142), 
        'perdido'     => (int) env('KOMMO_STATUS_PERDIDO', 143),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mapeo de Campos Personalizados (Custom Fields)
    |--------------------------------------------------------------------------
    | IDs de los campos creados manualmente en el panel de Kommo.
    */
    'custom_fields' => [
        'source'    => env('KOMMO_CF_SOURCE_ID'),
        'interests' => env('KOMMO_CF_INTERESTS_ID'),
        'notes'     => env('KOMMO_CF_NOTES_ID'),
    ],
];