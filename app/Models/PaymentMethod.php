<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'method',
        'amount',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relación
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Accessor para etiqueta legible del método de pago
    public function getMethodLabelAttribute(): string
    {
        $labels = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta de Crédito',
            'tarjeta_debito' => 'Tarjeta de Débito',
            'yappy' => 'Yappy',
            'otro' => 'Otro'
        ];

        return $labels[$this->method] ?? 'N/A';
    }

    // Accessor para monto formateado
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }
}
