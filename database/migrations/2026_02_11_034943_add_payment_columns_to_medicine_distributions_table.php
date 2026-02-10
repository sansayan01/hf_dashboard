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
        Schema::table('medicine_distributions', function (Blueprint $table) {
            $table->decimal('amount_paid', 10, 2)->default(0)->after('final_amount');
            $table->decimal('due_amount', 10, 2)->default(0)->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_distributions', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'due_amount']);
        });
    }
};
