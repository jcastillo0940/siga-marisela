<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseOfferingDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_offering_id',
        'class_date',
        'start_time',
        'end_time',
        'notes',
        'is_cancelled',
    ];

    protected $casts = [
        'class_date' => 'date',
        'is_cancelled' => 'boolean',
    ];

    // Relaciones
    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }
    public function attendances(): HasMany
{
    return $this->hasMany(Attendance::class, 'course_session_id');
}

    // Accessors
    public function getFormattedDateAttribute(): string
    {
        return $this->class_date->format('d/m/Y');
    }

    public function getFormattedTimeAttribute(): ?string
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time . ' - ' . $this->end_time;
        }
        return null;
    }
}