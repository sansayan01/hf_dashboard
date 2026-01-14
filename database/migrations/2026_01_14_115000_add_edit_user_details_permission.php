<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = ['office_in_charge', 'dm', 'bm', 'rm', 'ro'];
        $permission = 'can_edit_user_details';

        foreach ($roles as $role) {
            $enabled = ($role === 'office_in_charge');

            DB::table('role_permissions')->updateOrInsert(
                ['role' => $role, 'permission_key' => $permission],
                ['is_enabled' => $enabled, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('role_permissions')->where('permission_key', 'can_edit_user_details')->delete();
    }
};
