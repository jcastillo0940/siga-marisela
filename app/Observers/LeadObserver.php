<?php

namespace App\Observers;

use App\Models\Lead;
use App\Jobs\SyncLeadWithKommo;

class LeadObserver
{
    /**
     * Variable global para desactivar la sincronización temporalmente
     * (Útil cuando recibimos datos de Kommo para no devolverlos)
     */
    public static bool $disableSync = false;

    public function created(Lead $lead): void
    {
        if (static::$disableSync) return;

        SyncLeadWithKommo::dispatch($lead);
    }

    public function updated(Lead $lead): void
    {
        if (static::$disableSync) return;

        // Solo sincronizamos si hubo cambios en campos relevantes
        if ($lead->wasChanged(['first_name', 'last_name', 'email', 'phone', 'status', 'assigned_to'])) {
            SyncLeadWithKommo::dispatch($lead);
        }
    }
}