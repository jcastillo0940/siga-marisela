<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_offering_id',
        'min_students',
        'max_students',
        'type',
        'value',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2',
    ];

    /**
     * Relación con la oferta del curso
     */
    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

    /**
     * Calcula cuánto debe pagar CADA estudiante individualmente bajo esta regla.
     * * @param float $originalPrice El precio base del curso
     * @param int $studentCount La cantidad de personas en el grupo
     */
    public function calculatePricePerStudent(float $originalPrice, int $studentCount): float
    {
        return match ($this->type) {
            // Caso 1: "Pagan $500 entre los 2" -> Cada uno paga $250
            'fixed_total_price' => $this->value / max(1, $studentCount),
            
            // Caso 2: "Descuento del 10%" -> Precio * 0.90
            'percentage' => $originalPrice * (1 - ($this->value / 100)),
            
            // Caso 3: "Descuento de $50 c/u" -> Precio - 50
            'fixed_discount' => max(0, $originalPrice - $this->value),
        };
    }
}