<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'course_offering_id',
        'enrollment_code',
        'enrollment_date',
        'status',
        'price_paid',
        'discount',
        'notes',
        'certificate_issued',
        'certificate_issued_at',
        'requires_approval',
        'management_approved',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'certificate_issued_at' => 'date',
        'price_paid' => 'decimal:2',
        'discount' => 'decimal:2',
        'certificate_issued' => 'boolean',
        'requires_approval' => 'boolean',
        'management_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // Relaciones
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }
    
    public function paymentPlan(): HasOne
    {
        return $this->hasOne(PaymentPlan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // NUEVAS RELACIONES - ASISTENCIA Y CERTIFICADOS
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessor para saber si tiene plan de pagos
    public function getHasPaymentPlanAttribute(): bool
    {
        return $this->paymentPlan()->exists();
    }

    // Accessor para obtener balance de pagos
    public function getPaymentBalanceAttribute(): float
    {
        if (!$this->has_payment_plan) {
            return 0;
        }
        return $this->paymentPlan->balance;
    }

    // Accessor para acceder al curso base
    public function getCourseAttribute()
    {
        return $this->courseOffering->course;
    }

    // Accessors
    public function getFinalPriceAttribute(): float
    {
        return $this->price_paid - $this->discount;
    }

    public function getIsActiveAttribute(): bool
    {
        return in_array($this->status, ['inscrito', 'en_curso']);
    }

    // NUEVOS ACCESSORS - ASISTENCIA
    public function getAttendancePercentageAttribute(): float
    {
        $totalSessions = $this->courseOffering->courseSessions()->count();
        
        if ($totalSessions === 0) {
            return 0;
        }
        
        $attendedSessions = $this->attendances()
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        return round(($attendedSessions / $totalSessions) * 100, 2);
    }

    public function getAttendedSessionsAttribute(): int
    {
        return $this->attendances()
            ->whereIn('status', ['present', 'late'])
            ->count();
    }

    public function getTotalSessionsAttribute(): int
    {
        return $this->courseOffering->courseSessions()->count();
    }

    public function getIsFullyPaidAttribute(): bool
    {
        if (!$this->has_payment_plan) {
            return true;
        }
        return $this->paymentPlan->balance <= 0;
    }

    public function getBalanceAttribute(): float
    {
        return $this->payment_balance;
    }

    // ACCESSORS PARA APROBACIÓN
    public function getApprovalStatusAttribute(): string
    {
        if (!$this->requires_approval) {
            return 'No requiere';
        }

        if ($this->management_approved === null) {
            return 'Pendiente';
        }

        return $this->management_approved ? 'Aprobada' : 'Rechazada';
    }

    public function getApprovalStatusLabelAttribute(): string
    {
        $labels = [
            'No requiere' => 'bg-gray-100 text-gray-800',
            'Pendiente' => 'bg-yellow-100 text-yellow-800',
            'Aprobada' => 'bg-green-100 text-green-800',
            'Rechazada' => 'bg-red-100 text-red-800',
        ];

        return $labels[$this->approval_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getIsPendingApprovalAttribute(): bool
    {
        return $this->requires_approval && $this->management_approved === null;
    }

    // Boot method para generar código automático
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (empty($enrollment->enrollment_code)) {
                $enrollment->enrollment_code = 'ENR-' . strtoupper(uniqid());
            }
        });
    }
}