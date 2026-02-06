<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'level',
        'duration_hours',
        'duration_weeks',
        'price',
        'max_students',
        'min_students',
        'requirements',
        'objectives',
        'content_outline',
        'materials_included',
        'certificate_included',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'certificate_included' => 'boolean',
        'is_active' => 'boolean',
    ];

    // --- Relaciones ---
    
    /**
     * Relación con las ofertas del curso.
     */
    public function offerings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }

    /**
     * Relación con las ofertas activas.
     */
    public function activeOfferings(): HasMany
    {
        return $this->hasMany(CourseOffering::class)->where('is_active', true);
    }

    /**
     * NUEVA RELACIÓN: Acceso directo a las inscripciones.
     * Esto soluciona el error en la vista courses.show.
     */
    public function enrollments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Enrollment::class, 
            CourseOffering::class,
            'course_id',          // Llave foránea en course_offerings
            'course_offering_id', // Llave foránea en enrollments
            'id',                 // Llave local en courses
            'id'                  // Llave local en course_offerings
        );
    }

    /**
     * Relación con certificados del curso.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Relación con materiales del curso.
     * Agregar esta relación para el dashboard de estudiantes.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'course_offering_id');
    }

    // --- Scopes ---
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // --- Accessors ---
    
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getCurrentEnrollmentsCountAttribute(): int
    {
        // Usamos la nueva relación para un conteo más eficiente
        return $this->enrollments()->count();
    }

    public function getAvailableSpotsAttribute(): int
    {
        $totalCapacity = $this->offerings()->sum('max_students');
        return $totalCapacity - $this->current_enrollments_count;
    }
}