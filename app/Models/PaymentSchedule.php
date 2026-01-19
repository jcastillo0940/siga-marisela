<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_plan_id',
        'installment_number',
        'due_date',
        'amount',
        'amount_paid',
        'status',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    // Relaciones
    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getBalanceAttribute(): float
    {
        return $this->amount - $this->amount_paid;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'pagado' && $this->due_date->isPast();
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'pagado';
    }

    // Methods
    public function updateStatus()
    {
        if ($this->amount_paid >= $this->amount) {
            $this->status = 'pagado';
        } elseif ($this->amount_paid > 0) {
            $this->status = 'parcial';
        } elseif ($this->due_date->isPast()) {
            $this->status = 'vencido';
        } else {
            $this->status = 'pendiente';
        }
        
        $this->save();
    }

    public function addPayment(float $amount)
    {
        $this->amount_paid += $amount;
        $this->updateStatus();
    }
}