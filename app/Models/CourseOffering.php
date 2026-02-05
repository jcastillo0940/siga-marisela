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

    // Relaciones
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function dates()
    {
        // Importante: Ordenamos por class_date para que el .first() sea siempre la Clase #1
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

    // =========================================================
    // SCOPES (NUEVO - Para el módulo de menús)
    // =========================================================
    
    /**
     * Scope para obtener solo course offerings activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para obtener course offerings próximos (que no han terminado)
     */
    public function scopeUpcoming($query)
    {
        return $query->where('end_date', '>=', today());
    }

    /**
     * Scope para obtener course offerings en progreso
     */
    public function scopeInProgress($query)
    {
        return $query->where('start_date', '<=', today())
            ->where('end_date', '>=', today());
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors Existentes
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

    // =========================================================
    // NUEVOS ACCESSORS PARA HORARIO REAL (CLASE #1)
    // =========================================================

    /**
     * Obtiene la hora de la primera clase en formato limpio (9am o 10:30am)
     */
    public function getStartTimeFriendlyAttribute(): string
    {
        $firstSession = $this->dates->first();
        
        // Si no hay clases creadas, devolvemos un texto informativo
        if (!$firstSession || !$firstSession->start_time) {
            return 'Hora por definir';
        }

        $time = Carbon::parse($firstSession->start_time);
        // Formato: si minutos son 00 muestra "9am", si no "9:30am"
        return $time->minute > 0 ? $time->format('g:ia') : $time->format('ga');
    }

    /**
     * Genera la etiqueta completa para el selector público
     * Ejemplo: Hotel Sheraton — 17 Jan a las 9am
     */
    public function getPublicScheduleLabelAttribute(): string
    {
        $firstSession = $this->dates->first();
        $dateRaw = $firstSession ? $firstSession->class_date : $this->start_date;
        
        $fecha = Carbon::parse($dateRaw)->translatedFormat('d M');
        $hora = $this->start_time_friendly;

        return "{$this->location} — {$fecha} a las {$hora}";
    }

    /**
     * Accessor para generation_number (compatibilidad con vistas)
     * Extrae el número de generación desde generation_name
     */
    public function getGenerationNumberAttribute(): ?string
    {
        if (!$this->generation_name) {
            return null;
        }
        
        // Si generation_name es "Gen 19" o "Generación 19", extrae "19"
        if (preg_match('/(\d+)/', $this->generation_name, $matches)) {
            return $matches[1];
        }
        
        return $this->generation_name;
    }

    // Boot method para generar código automático
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