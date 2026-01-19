<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'kommo_id',
    'kommo_contact_id',
    'last_synced_at',
    'first_name',
    'last_name',
    'email',
    'phone',
    'phone_secondary',
    'source',
    'source_detail',
    'status',
    'notes',
    'interests',
    'follow_up_date',
    'assigned_to',
    'converted_to_student_id',
    'converted_at',
    
    // --- Nuevos campos del CSV ---
    'student_photo',
    'who_fills_form',
    'age',
    'birth_date_text',
    'address_full',
    'parent_phone',
    'occupation',
    'parent_occupation',
    'has_previous_experience',
    'previous_experience_detail',
    'motivation',
    'social_media_handle',
    'medical_notes_lead',
    
    // --- Campos de Pago ---
    'payment_receipt_path',
    'payment_status',
    'course_offering_id',
];

    protected $casts = [
        'follow_up_date' => 'date',
        'converted_at' => 'datetime',
    ];

    // Accessor para nombre completo
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relaciones
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'converted_to_student_id');
    }

    // Verificar si fue convertido
    public function isConverted(): bool
    {
        return !is_null($this->converted_to_student_id);
    }
    public function courseOffering()
{
    return $this->belongsTo(CourseOffering::class);
}

// Helper para saber si adjuntó pago
public function hasPaymentReceipt(): bool
{
    return !is_null($this->payment_receipt_path);
}
}