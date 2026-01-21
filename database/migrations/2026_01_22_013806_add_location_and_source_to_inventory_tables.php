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
        Schema::table('inventory_stocks', function (Blueprint $blueprint) {
            $blueprint->foreignId('warehouse_id')->nullable()->constrained('inventory_warehouses')->onDelete('cascade');
        });

        Schema::table('inventory_transactions', function (Blueprint $blueprint) {
            $blueprint->foreignId('warehouse_id')->nullable()->constrained('inventory_warehouses')->onDelete('set null');
            $blueprint->foreignId('sponsor_id')->nullable()->constrained('inventory_sponsors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_stocks', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['warehouse_id']);
            $blueprint->dropColumn('warehouse_id');
        });

        Schema::table('inventory_transactions', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['warehouse_id']);
            $blueprint->dropForeign(['sponsor_id']);
            $blueprint->dropColumn(['warehouse_id', 'sponsor_id']);
        });
    }
};
