<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'certificate_number',
        'verification_code',
        'enrollment_id',
        'certificate_template_id',
        'student_id',
        'course_id',
        'student_full_name',
        'student_document',
        'course_name',
        'course_start_date',
        'course_end_date',
        'total_sessions',
        'attended_sessions',
        'attendance_percentage',
        'final_grade',
        'pdf_path',
        'pdf_filename',
        'file_size',
        'issued_at',
        'downloaded_at',
        'download_count',
        'issued_by',
        'status',
        'revocation_reason',
        'revoked_at',
    ];

    protected $casts = [
        'course_start_date' => 'date',
        'course_end_date' => 'date',
        'attendance_percentage' => 'decimal:2',
        'final_grade' => 'decimal:2',
        'issued_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    // Relaciones
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // Scopes
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // Accessors
    public function getIsValidAttribute(): bool
    {
        return $this->status === 'issued' && !$this->revoked_at;
    }

    public function getHasBeenDownloadedAttribute(): bool
    {
        return $this->download_count > 0;
    }

    public function getPublicVerificationUrlAttribute(): string
    {
        return route('certificates.verify', ['code' => $this->verification_code]);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('certificates.download', ['certificate' => $this->id]);
    }

    // Métodos estáticos para generación de números
    public static function generateCertificateNumber(): string
    {
        $year = now()->year;
        $prefix = 'AA'; // Academia Auténtica
        
        // Obtener el último número del año actual
        $lastCertificate = self::whereYear('issued_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastCertificate 
            ? ((int) substr($lastCertificate->certificate_number, -6)) + 1 
            : 1;

        return sprintf('%s-%d-%06d', $prefix, $year, $nextNumber);
    }

    public static function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('verification_code', $code)->exists());

        return $code;
    }

    // Métodos de instancia
    public function recordDownload(): void
    {
        $this->increment('download_count');
        
        if (!$this->downloaded_at) {
            $this->update(['downloaded_at' => now()]);
        }
    }

    public function revoke(string $reason, ?int $userId = null): void
    {
        $this->update([
            'status' => 'revoked',
            'revocation_reason' => $reason,
            'revoked_at' => now(),
            'issued_by' => $userId,
        ]);
    }

    public function reissue(?int $userId = null): void
    {
        $this->update([
            'status' => 'issued',
            'revocation_reason' => null,
            'revoked_at' => null,
            'issued_by' => $userId,
        ]);
    }

    // Método para obtener datos para el template
    public function getTemplateData(): array
    {
        return [
            'student_name' => $this->student_full_name,
            'student_document' => $this->student_document,
            'course_name' => $this->course_name,
            'course_duration' => $this->course_start_date->diffInDays($this->course_end_date) . ' días',
            'course_start_date' => $this->course_start_date->format('d/m/Y'),
            'course_end_date' => $this->course_end_date->format('d/m/Y'),
            'attendance_percentage' => number_format($this->attendance_percentage, 1),
            'total_hours' => $this->total_sessions * 4, // asumiendo 4 horas por sesión
            'total_sessions' => $this->total_sessions,
            'attended_sessions' => $this->attended_sessions,
            'issue_date' => $this->issued_at->format('d/m/Y'),
            'certificate_number' => $this->certificate_number,
            'verification_code' => $this->verification_code,
            'final_grade' => $this->final_grade ? number_format($this->final_grade, 1) : 'N/A',
        ];
    }

    // Verificación pública
    public static function verifyByCode(string $code): ?self
    {
        return self::where('verification_code', $code)
            ->where('status', 'issued')
            ->first();
    }
}
