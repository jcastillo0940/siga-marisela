<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        AuditLog::logAction('created', 'User', $user->id, null, $user->toArray());
    }

    public function updated(User $user): void
    {
        AuditLog::logAction(
            'updated',
            'User',
            $user->id,
            $user->getOriginal(),
            $user->getChanges()
        );
    }

    public function deleted(User $user): void
    {
        AuditLog::logAction('deleted', 'User', $user->id, $user->toArray());
    }
}