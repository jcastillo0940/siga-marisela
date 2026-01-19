<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enrollment_id',
        'course_session_id',
        'status',
        'checked_in_at',
        'checked_out_at',
        'check_in_method',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    // Relaciones
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function courseSession(): BelongsTo
    {
        return $this->belongsTo(CourseOfferingDate::class, 'course_session_id');
    }

    public function student(): BelongsTo
    {
        return $this->enrollment->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeExcused($query)
    {
        return $query->where('status', 'excused');
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->whereHas('enrollment', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        });
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('course_session_id', $sessionId);
    }

    // Accessors
    public function getIsLateAttribute(): bool
    {
        return $this->status === 'late';
    }

    public function getIsPresentAttribute(): bool
    {
        return in_array($this->status, ['present', 'late']);
    }

    public function getDurationInMinutesAttribute(): ?int
    {
        if (!$this->checked_in_at || !$this->checked_out_at) {
            return null;
        }

        return $this->checked_in_at->diffInMinutes($this->checked_out_at);
    }

    // Métodos de instancia
    public function markAsPresent(string $method = 'manual', ?int $userId = null): void
    {
        $this->update([
            'status' => 'present',
            'checked_in_at' => now(),
            'check_in_method' => $method,
            'recorded_by' => $userId,
        ]);
    }

    public function markAsAbsent(?int $userId = null): void
    {
        $this->update([
            'status' => 'absent',
            'recorded_by' => $userId,
        ]);
    }

    public function markAsLate(string $method = 'manual', ?int $userId = null): void
    {
        $this->update([
            'status' => 'late',
            'checked_in_at' => now(),
            'check_in_method' => $method,
            'recorded_by' => $userId,
        ]);
    }

    public function markAsExcused(string $notes, ?int $userId = null): void
    {
        $this->update([
            'status' => 'excused',
            'notes' => $notes,
            'recorded_by' => $userId,
        ]);
    }

    public function checkOut(): void
    {
        $this->update([
            'checked_out_at' => now(),
        ]);
    }
}
