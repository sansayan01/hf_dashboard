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
            $table->decimal('medicines_amount', 10, 2)->default(0)->after('ta_amount');
            $table->decimal('pathology_amount', 10, 2)->default(0)->after('medicines_amount');
            $table->decimal('membership_amount', 10, 2)->default(0)->after('pathology_amount');
            $table->decimal('ots_amount', 10, 2)->default(0)->after('membership_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['medicines_amount', 'pathology_amount', 'membership_amount', 'ots_amount']);
        });
    }
};
