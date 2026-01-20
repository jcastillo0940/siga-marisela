<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_menu_id',
        'name',
        'description',
        'image',
        'is_vegetarian',
        'is_vegan',
        'is_gluten_free',
        'available_quantity',
        'is_active',
    ];

    protected $casts = [
        'is_vegetarian' => 'boolean',
        'is_vegan' => 'boolean',
        'is_gluten_free' => 'boolean',
        'is_active' => 'boolean',
        'available_quantity' => 'integer',
    ];

    // Relationships
    public function mealMenu()
    {
        return $this->belongsTo(MealMenu::class);
    }

    public function selections()
    {
        return $this->hasMany(MealSelection::class);
    }

    // Accessors
    public function getDietaryLabelsAttribute(): array
    {
        $labels = [];

        if ($this->is_vegetarian) {
            $labels[] = ['label' => 'Vegetariano', 'icon' => '🥬', 'color' => 'green'];
        }

        if ($this->is_vegan) {
            $labels[] = ['label' => 'Vegano', 'icon' => '🌱', 'color' => 'green'];
        }

        if ($this->is_gluten_free) {
            $labels[] = ['label' => 'Sin Gluten', 'icon' => '🌾', 'color' => 'yellow'];
        }

        return $labels;
    }

    public function getSelectionsCountAttribute(): int
    {
        return $this->selections()->count();
    }

    public function getRemainingQuantityAttribute(): ?int
    {
        if ($this->available_quantity === null) {
            return null;
        }

        return max(0, $this->available_quantity - $this->selections_count);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Methods
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->available_quantity === null) {
            return true;
        }

        return $this->remaining_quantity > 0;
    }
}
