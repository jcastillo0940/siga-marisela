<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CourseMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_offering_id',
        'title',
        'description',
        'type',
        'file_path',
        'external_url',
        'file_size',
        'order',
        'is_active',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relationships
    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }

    // Accessors
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return number_format($size, 2) . ' ' . $units[$unitIndex];
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'pdf' => '📄',
            'video' => '🎥',
            'link' => '🔗',
            'image' => '🖼️',
            default => '📎',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'pdf' => 'PDF',
            'video' => 'Video',
            'link' => 'Enlace',
            'image' => 'Imagen',
            default => 'Otro',
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('available_from')
                  ->orWhere('available_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('available_until')
                  ->orWhere('available_until', '>=', now());
            });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    // Methods
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->available_from && $this->available_from->isFuture()) {
            return false;
        }

        if ($this->available_until && $this->available_until->isPast()) {
            return false;
        }

        return true;
    }
}
