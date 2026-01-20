<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'meal_menu_id',
        'meal_option_id',
        'notes',
    ];

    // Relationships
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function mealMenu()
    {
        return $this->belongsTo(MealMenu::class);
    }

    public function mealOption()
    {
        return $this->belongsTo(MealOption::class);
    }
}
