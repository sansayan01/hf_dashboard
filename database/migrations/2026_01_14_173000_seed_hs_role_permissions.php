<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\RolePermission;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add HS permissions
        $role = 'hs';
        $permissions = [
            'can_create_users',
            'can_approve_users',
            'can_view_downline',
            'can_delete_users',
            'can_create_surveys',
            'can_view_reports',
            'can_manage_appointments',
            'can_edit_user_details',
        ];

        foreach ($permissions as $permission) {
            RolePermission::firstOrCreate(
                ['role' => $role, 'permission' => $permission],
                [
                    'is_enabled' => true // Default to enabled for HS
                ]
            );
        }
    }

    public function down(): void
    {
        RolePermission::where('role', 'hs')->delete();
    }
};
