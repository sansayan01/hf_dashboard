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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('incentive_amount', 10, 2)->after('status')->default(0);
            $table->decimal('ta_amount', 10, 2)->after('incentive_amount')->default(0);
            $table->decimal('total_amount', 10, 2)->after('ta_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['incentive_amount', 'ta_amount', 'total_amount']);
        });
    }
};
