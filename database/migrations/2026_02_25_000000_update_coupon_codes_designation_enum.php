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
        // Using raw SQL as updating Enum columns via Blueprint can be problematic
        DB::statement("ALTER TABLE coupon_codes MODIFY COLUMN designation ENUM('dm', 'bm', 'rm', 'ro', 'membership') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE coupon_codes MODIFY COLUMN designation ENUM('dm', 'bm', 'rm', 'ro') DEFAULT NULL");
    }
};
