<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'opened_by',
        'closed_by',
        'opening_amount',
        'closing_amount',
        'expected_amount',
        'difference',
        'status',
        'opened_at',
        'closed_at',
        'opening_notes',
        'closing_notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_amount' => 'decimal:2',
        'closing_amount' => 'decimal:2',
        'expected_amount' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    // Relaciones
    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'cash_register_id');
    }

    // Accessors
    public function getTotalPaymentsAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    public function getFormattedOpeningAmountAttribute(): string
    {
        return '$' . number_format($this->opening_amount, 2);
    }

    public function getFormattedClosingAmountAttribute(): string
    {
        return '$' . number_format($this->closing_amount ?? 0, 2);
    }

    public function getFormattedDifferenceAttribute(): string
    {
        return '$' . number_format($this->difference ?? 0, 2);
    }

    // Boot method para generar código automático
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cashRegister) {
            if (empty($cashRegister->code)) {
                $cashRegister->code = 'CAJA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }
}