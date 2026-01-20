<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealMenu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_offering_id',
        'meal_date',
        'meal_type',
        'menu_description',
        'menu_image',
        'max_selections',
        'is_active',
    ];

    protected $casts = [
        'meal_date' => 'date',
        'is_active' => 'boolean',
        'max_selections' => 'integer',
    ];

    // Relationships
    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function options()
    {
        return $this->hasMany(MealOption::class);
    }

    public function selections()
    {
        return $this->hasMany(MealSelection::class);
    }

    // Accessors
    public function getMealTypeLabelAttribute(): string
    {
        return match($this->meal_type) {
            'breakfast' => 'Desayuno',
            'lunch' => 'Almuerzo',
            'dinner' => 'Cena',
            'snack' => 'Merienda',
            default => ucfirst($this->meal_type),
        };
    }

    public function getMealTypeIconAttribute(): string
    {
        return match($this->meal_type) {
            'breakfast' => '🍳',
            'lunch' => '🍽️',
            'dinner' => '🌙',
            'snack' => '🍪',
            default => '🍴',
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('meal_date', '>=', today());
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('meal_date', $date);
    }

    // Methods
    public function hasStudentSelected(int $enrollmentId): bool
    {
        return $this->selections()
            ->where('enrollment_id', $enrollmentId)
            ->exists();
    }

    public function getStudentSelection(int $enrollmentId): ?MealSelection
    {
        return $this->selections()
            ->where('enrollment_id', $enrollmentId)
            ->first();
    }

    public function canSelect(): bool
    {
        return $this->is_active && $this->meal_date >= today();
    }
}
