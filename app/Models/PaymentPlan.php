<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'payment_type',
        'total_amount',
        'total_paid',
        'balance',
        'number_of_installments',
        'periodicity',
        'first_payment_date',
        'last_payment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'first_payment_date' => 'date',
        'last_payment_date' => 'date',
        'total_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relaciones
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function schedules()
    {
        return $this->hasMany(PaymentSchedule::class)->orderBy('installment_number');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getPaymentProgressAttribute(): float
    {
        if ($this->total_amount == 0) return 0;
        return ($this->total_paid / $this->total_amount) * 100;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->balance <= 0;
    }

    public function getHasOverduePaymentsAttribute(): bool
    {
        return $this->schedules()
            ->where('status', 'pendiente')
            ->where('due_date', '<', now())
            ->exists();
    }

    // Methods
    public function updateBalance()
    {
        $this->total_paid = $this->payments()->sum('amount');
        $this->balance = $this->total_amount - $this->total_paid;
        
        if ($this->balance <= 0) {
            $this->status = 'completado';
        } elseif ($this->total_paid > 0) {
            $this->status = 'en_proceso';
        } elseif ($this->has_overdue_payments) {
            $this->status = 'vencido';
        }
        
        $this->save();
    }
}