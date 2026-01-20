<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_code',
        'sale_date',
        'customer_name',
        'customer_document',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_method',
        'reference_number',
        'cash_register_id',
        'sold_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(SalePaymentMethod::class);
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function soldBy()
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    // Accessors
    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::getPaymentMethodLabel($this->payment_method);
    }

    public static function getPaymentMethodLabel(string $method): string
    {
        $labels = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta de Crédito',
            'tarjeta_debito' => 'Tarjeta de Débito',
            'yappy' => 'Yappy',
            'otro' => 'Otro',
            'multiple' => 'Múltiples Métodos'
        ];

        return $labels[$method] ?? 'N/A';
    }

    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'completado' => 'Completado',
            'pendiente' => 'Pendiente',
            'cancelado' => 'Cancelado',
            'reembolsado' => 'Reembolsado',
        ];

        return $labels[$this->status] ?? 'N/A';
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->sale_code)) {
                $sale->sale_code = 'SALE-' . strtoupper(uniqid());
            }
        });
    }
}
