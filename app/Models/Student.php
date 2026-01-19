<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'phone_secondary',
        'gender',
        'birth_date',
        'identification',
        'identification_type',
        'address',
        'city',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'medical_notes',
        'emotional_notes',
        'goals',
        'status',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Accessor para nombre completo
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relaciones
    public function lead()
    {
        return $this->hasOne(Lead::class, 'converted_to_student_id');
    }

        public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    
    public function courseOfferings()
    {
        return $this->belongsToMany(CourseOffering::class, 'enrollments')
                    ->withPivot(['enrollment_code', 'enrollment_date', 'status', 'price_paid', 'discount'])
                    ->withTimestamps();
    }
    
    public function certificates(): HasMany
{
    return $this->hasMany(Certificate::class);
}
}