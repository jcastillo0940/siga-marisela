<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'payment_plan_id',
        'payment_schedule_id',
        'payment_code',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'received_by',
        'cash_register_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relaciones
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function paymentSchedule()
    {
        return $this->belongsTo(PaymentSchedule::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    
    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta de Crédito',
            'tarjeta_debito' => 'Tarjeta de Débito',
            'yappy' => 'Yappy',
            'otro' => 'Otro'
        ];

        return $labels[$this->payment_method] ?? 'N/A';
    }

    // Boot method para generar código automático
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_code)) {
                $payment->payment_code = 'PAY-' . strtoupper(uniqid());
            }
        });
    }
}