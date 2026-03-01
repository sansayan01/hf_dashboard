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
        Schema::table('camp_records', function (Blueprint $table) {
            $table->decimal('total_discount', 10, 2)->default(0)->after('medicine_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('camp_records', function (Blueprint $table) {
            $table->dropColumn('total_discount');
        });
    }
};
