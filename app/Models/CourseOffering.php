<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CourseOffering extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'code',
        'is_generation',
        'generation_name',
        'location',
        'start_date',
        'end_date',
        'price',
        'duration_hours',
        'min_students',
        'max_students',
        'certificate_included',
        'status',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'is_generation' => 'boolean',
        'certificate_included' => 'boolean',
        'is_active' => 'boolean',
    ];

    // =========================================================
    // RELACIONES
    // =========================================================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relación corregida con materiales.
     * Forzamos 'course_offering_id' para evitar error de columna 'course_id' inexistente.
     */
    public function materials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'course_offering_id');
    }

    /**
     * ALIAS CRÍTICO: Define la relación 'sessions' que el controlador intenta cargar.
     */
    public function sessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CourseOfferingDate::class, 'course_offering_id')->orderBy('class_date');
    }

    public function dates()
    {
        return $this->hasMany(CourseOfferingDate::class)->orderBy('class_date');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withPivot(['enrollment_code', 'enrollment_date', 'status', 'price_paid', 'discount'])
                    ->withTimestamps();
    }

    public function courseSessions()
    {
        return $this->hasMany(CourseOfferingDate::class);
    }

    public function mealMenus()
    {
        return $this->hasMany(MealMenu::class);
    }

    public function pricingRules()
    {
        return $this->hasMany(PricingRule::class);
    }

    // =========================================================
    // SCOPES
    // =========================================================
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('end_date', '>=', today());
    }

    public function scopeInProgress($query)
    {
        return $query->where('start_date', '<=', today())
            ->where('end_date', '>=', today());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // =========================================================
    // ACCESSORS
    // =========================================================

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getCurrentEnrollmentsCountAttribute(): int
    {
        return $this->enrollments()->whereIn('status', ['inscrito', 'en_curso'])->count();
    }

    public function getAvailableSpotsAttribute(): int
    {
        return $this->max_students - $this->current_enrollments_count;
    }

    public function getFullNameAttribute(): string
    {
        $name = $this->course->name;
        if ($this->is_generation && $this->generation_name) {
            $name .= ' - ' . $this->generation_name;
        }
        return $name;
    }

    public function getStartTimeFriendlyAttribute(): string
    {
        $firstSession = $this->dates->first();
        if (!$firstSession || !$firstSession->start_time) {
            return 'Hora por definir';
        }
        $time = Carbon::parse($firstSession->start_time);
        return $time->minute > 0 ? $time->format('g:ia') : $time->format('ga');
    }

    public function getPublicScheduleLabelAttribute(): string
    {
        $firstSession = $this->dates->first();
        $dateRaw = $firstSession ? $firstSession->class_date : $this->start_date;
        $fecha = Carbon::parse($dateRaw)->translatedFormat('d M');
        $hora = $this->start_time_friendly;
        return "{$this->location} — {$fecha} a las {$hora}";
    }

    public function getGenerationNumberAttribute(): ?string
    {
        if (!$this->generation_name) return null;
        if (preg_match('/(\d+)/', $this->generation_name, $matches)) {
            return $matches[1];
        }
        return $this->generation_name;
    }

    // =========================================================
    // LÓGICA DE NEGOCIO
    // =========================================================

    public function getBestPricingRule(int $studentCount): ?PricingRule
    {
        return $this->pricingRules()
            ->where('is_active', true)
            ->where('min_students', '<=', $studentCount)
            ->where(function($q) use ($studentCount) {
                $q->whereNull('max_students')->orWhere('max_students', '>=', $studentCount);
            })
            ->orderByDesc('min_students')
            ->first();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($offering) {
            if (empty($offering->code)) {
                $offering->code = 'OFF-' . strtoupper(uniqid());
            }
        });
    }
}