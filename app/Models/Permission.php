<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'action',
        'name',
        'description',
    ];

    // Auto-generate name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($permission) {
            if (empty($permission->name)) {
                $permission->name = "{$permission->module}.{$permission->action}";
            }
        });
    }

    // Relaciones
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withTimestamps();
    }
}