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
        Schema::table('surveys', function (Blueprint $table) {
            $table->decimal('membership_fee', 10, 2)->default(0)->after('is_member');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('membership_fee');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage');
            $table->decimal('final_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('final_amount');
            $table->decimal('due_amount', 10, 2)->default(0)->after('amount_paid');
            $table->string('payment_method')->nullable()->after('due_amount');
            $table->string('payment_screenshot')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'membership_fee',
                'discount_percentage',
                'discount_amount',
                'final_amount',
                'amount_paid',
                'due_amount',
                'payment_method',
                'payment_screenshot'
            ]);
        });
    }
};
