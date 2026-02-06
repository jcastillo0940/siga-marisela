<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'password',
        'phone',
        'position',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    // Relaciones
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps()
            ->withPivot('assigned_at');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // Métodos Helper
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('slug', $roleName);
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roles->flatMap->permissions
            ->contains('name', $permissionName);
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->whereIn('slug', $roles)->isNotEmpty();
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // Accessor para saludo con género
    public function getGreetingAttribute(): string
    {
        return match($this->gender) {
            'female' => '¡Bienvenida',
            'male' => '¡Bienvenido',
            'other' => '¡Bienvenid@',
            default => '¡Bienvenido'
        };
    }
}