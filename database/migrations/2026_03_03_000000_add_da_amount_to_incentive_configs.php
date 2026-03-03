<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incentive_configs', function (Blueprint $table) {
            $table->decimal('da_amount', 10, 2)->default(0)->after('ta_amount');
        });
    }

    public function down(): void
    {
        Schema::table('incentive_configs', function (Blueprint $table) {
            $table->dropColumn('da_amount');
        });
    }
};
