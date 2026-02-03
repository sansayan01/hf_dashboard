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
        try {
            return self::where('role', $role)
                ->where('permission_key', $key)
                ->where('is_enabled', true)
                ->exists();
        } catch (\Exception $e) {
            // 500 Error Prevention:
            // If the table is missing on live server, fall back to hardcoded logic
            // so the user can at least login and the site doesn't crash.
            return self::fallbackCheck($role, $key);
        }
    }

    /**
     * Hardcoded permission logic as a backup
     */
    private static function fallbackCheck($role, $key)
    {
        // Admins have full access
        if (in_array($role, ['office_in_charge', 'hs'])) {
            return true;
        }

        // Common permissions for managers
        $managerPerms = ['can_create_users', 'can_view_downline', 'can_create_surveys', 'can_manage_appointments', 'can_assign_oic'];
        if (in_array($role, ['dm', 'bm', 'rm']) && in_array($key, $managerPerms)) {
            return true;
        }

        // Basic permissions
        $basicPerms = ['can_create_surveys', 'can_manage_appointments'];
        if ($role === 'ro' && in_array($key, $basicPerms)) {
            return true;
        }

        return false;
    }
}
