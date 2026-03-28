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
     * Get permission for a specific role and key (legacy method, kept for compatibility)
     */
    public static function check($role, $key)
    {
        try {
            return self::where('role', $role)
                ->where('permission_key', $key)
                ->where('is_enabled', true)
                ->exists();
        } catch (\Exception $e) {
            return self::fallbackCheck($role, $key);
        }
    }

    /**
     * Check if a specific granular permission key is set for a role.
     * Returns: true/false if an explicit row exists, null if no row exists.
     */
    public static function getPermissionForRole(string $role, string $key): ?bool
    {
        try {
            $row = self::where('role', $role)
                ->where('permission_key', $key)
                ->first();

            return $row ? (bool) $row->is_enabled : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all granular permission overrides for a given role, keyed by permission_key.
     * Only returns rows that actually exist (explicit overrides).
     *
     * @return array<string, bool>
     */
    public static function getForRole(string $role): array
    {
        try {
            return self::where('role', $role)
                ->pluck('is_enabled', 'permission_key')
                ->map(fn($v) => (bool) $v)
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Bulk-set all granular permissions for a role (used by the bulk editor).
     * This replaces ALL existing rows for the role with the submitted config.
     *
     * @param string $role  The designation key (e.g. 'bm', 'rm')
     * @param array<string, bool> $permissions  key => bool map
     */
    public static function bulkSetForRole(string $role, array $permissions): void
    {
        // Determine which keys differ from PERMISSION_DEFAULTS — only store overrides
        $defaults = User::PERMISSION_DEFAULTS;
        $overrides = [];

        foreach ($permissions as $key => $value) {
            $defaultValue = $defaults[$key] ?? false;
            if ($value !== $defaultValue) {
                $overrides[$key] = $value;
            }
        }

        // Delete all existing rows for this role
        self::where('role', $role)->delete();

        // Insert only the overrides
        foreach ($overrides as $key => $value) {
            self::create([
                'role' => $role,
                'permission_key' => $key,
                'is_enabled' => $value,
            ]);
        }
    }

    /**
     * Reset all granular permissions for a role (delete all override rows).
     */
    public static function resetForRole(string $role): void
    {
        self::where('role', $role)->delete();
    }

    /**
     * Get the merged permissions for a role (PERMISSION_DEFAULTS + role overrides).
     *
     * @return array<string, bool>
     */
    public static function getMergedForRole(string $role): array
    {
        $defaults = User::PERMISSION_DEFAULTS;
        $overrides = self::getForRole($role);

        return array_merge($defaults, $overrides);
    }

    /**
     * Hardcoded permission logic as a backup
     */
    private static function fallbackCheck($role, $key)
    {
        if (in_array($role, ['office_in_charge', 'hs'])) {
            return true;
        }

        $managerPerms = ['can_create_users', 'can_view_downline', 'can_create_surveys', 'can_manage_appointments', 'can_assign_oic'];
        if (in_array($role, ['dm', 'bm', 'rm']) && in_array($key, $managerPerms)) {
            return true;
        }

        $basicPerms = ['can_create_surveys', 'can_manage_appointments'];
        if ($role === 'ro' && in_array($key, $basicPerms)) {
            return true;
        }

        return false;
    }
}
