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
        // Option 1: Convert to string (Most flexible)
        // Option 2: Update ENUM (Stricter)

        // We will convert to VARCHAR(50) to allow flexibility and avoid future ENUM issues
        DB::statement("ALTER TABLE users MODIFY COLUMN designation VARCHAR(50) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to ENUM if needed, but be careful of data loss if invalid values exist
        // For safety in dev environment, we might skip strict reversal or try to revert to expanded ENUM
        // DB::statement("ALTER TABLE users MODIFY COLUMN designation ENUM('super_admin', 'dm', 'bm', 'rm', 'ro')");
    }
};
