<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // e.g., 'dm', 'bm', etc.
            $table->string('permission_key'); // e.g., 'can_create_users'
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();

            $table->unique(['role', 'permission_key']);
        });

        // Seed default permissions
        $roles = ['office_in_charge', 'dm', 'bm', 'rm', 'ro'];
        $permissions = [
            'can_create_users',
            'can_approve_users',
            'can_view_downline',
            'can_delete_users',
            'can_create_surveys',
            'can_view_reports',
            'can_manage_appointments'
        ];

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                // Default logic (can be adjusted by admin later)
                $enabled = false;

                // Define some sane defaults
                if ($role === 'office_in_charge') {
                    $enabled = true;
                } elseif ($role === 'dm' && in_array($permission, ['can_create_users', 'can_view_downline', 'can_create_surveys', 'can_view_reports', 'can_manage_appointments'])) {
                    $enabled = true;
                } elseif ($role === 'bm' && in_array($permission, ['can_create_users', 'can_view_downline', 'can_create_surveys', 'can_manage_appointments'])) {
                    $enabled = true;
                } elseif ($role === 'rm' && in_array($permission, ['can_create_users', 'can_view_downline', 'can_create_surveys', 'can_manage_appointments'])) {
                    $enabled = true;
                } elseif ($role === 'ro' && in_array($permission, ['can_create_surveys', 'can_manage_appointments'])) {
                    $enabled = true;
                }

                \DB::table('role_permissions')->insert([
                    'role' => $role,
                    'permission_key' => $permission,
                    'is_enabled' => $enabled,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
