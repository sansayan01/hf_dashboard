<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $fillable = ['role', 'permission_key', 'is_enabled'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get permission for a specific role and key
     */
    public static function check($role, $key)
    {
        return self::where('role', $role)
            ->where('permission_key', $key)
            ->where('is_enabled', true)
            ->exists();
    }
}
