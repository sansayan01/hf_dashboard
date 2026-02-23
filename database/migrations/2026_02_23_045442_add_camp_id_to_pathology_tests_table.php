<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pathology_tests', function (Blueprint $table) {
            $table->foreignId('camp_id')->nullable()->after('ro_id')->constrained('inventory_warehouses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pathology_tests', function (Blueprint $table) {
            $table->dropForeign(['camp_id']);
            $table->dropColumn('camp_id');
        });
    }
};
