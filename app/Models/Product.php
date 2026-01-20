<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'price',
        'stock',
        'min_stock',
        'track_inventory',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'track_inventory' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Accessors
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'product' ? 'Producto' : 'Servicio';
    }

    public function getIsLowStockAttribute(): bool
    {
        if (!$this->track_inventory) {
            return false;
        }
        return $this->stock <= $this->min_stock;
    }

    public function getStockStatusAttribute(): string
    {
        if (!$this->track_inventory) {
            return 'No aplica';
        }
        if ($this->stock <= 0) {
            return 'Sin stock';
        }
        if ($this->is_low_stock) {
            return 'Stock bajo';
        }
        return 'Disponible';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->where('track_inventory', false)
                  ->orWhere('stock', '>', 0);
            });
    }

    // Methods
    public function decreaseStock(int $quantity): void
    {
        if ($this->track_inventory) {
            $this->decrement('stock', $quantity);
        }
    }

    public function increaseStock(int $quantity): void
    {
        if ($this->track_inventory) {
            $this->increment('stock', $quantity);
        }
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->code)) {
                $product->code = 'PROD-' . strtoupper(uniqid());
            }
        });
    }
}
