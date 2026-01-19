<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método Helper para registro rápido
    public static function logAction(
        string $action,
        string $model,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}