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
        Schema::table('medicines', function (Blueprint $table) {
            $table->decimal('market_price', 10, 2)->nullable()->after('description');
            $table->integer('market_price_unit_count')->nullable()->after('market_price'); // e.g., per 10 tablets
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['market_price', 'market_price_unit_count']);
        });
    }
};
