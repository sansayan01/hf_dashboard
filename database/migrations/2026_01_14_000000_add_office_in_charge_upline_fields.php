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
        Schema::table('users', function (Blueprint $table) {
            // Add upline_id to track which user this Office In-Charge is representing
            $table->foreignId('upline_id')->nullable()->after('parent_id')->constrained('users')->onDelete('cascade');

            // Add upline_designation to track the designation of the upline
            $table->string('upline_designation')->nullable()->after('upline_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['upline_id']);
            $table->dropColumn(['upline_id', 'upline_designation']);
        });
    }
};
