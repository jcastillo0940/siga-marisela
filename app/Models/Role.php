<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    // Relaciones
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('assigned_at');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
            ->withTimestamps();
    }

    // Métodos Helper
    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->syncWithoutDetaching($permission);
    }

    public function revokePermissionTo(Permission $permission)
    {
        return $this->permissions()->detach($permission);
    }
}