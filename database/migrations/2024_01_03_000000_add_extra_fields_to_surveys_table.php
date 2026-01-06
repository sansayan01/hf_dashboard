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
            $table->string('relative_name')->nullable()->after('full_name');
            $table->string('aadhar_number')->nullable()->after('pin');
            $table->string('pan_number')->nullable()->after('aadhar_number');
            $table->string('blood_group')->nullable()->after('pan_number');
            $table->string('district')->nullable()->after('blood_group');
            $table->string('block')->nullable()->after('district');
            $table->string('gp')->nullable()->after('block');
            $table->string('landmark')->nullable()->after('gp');
            $table->text('past_diseases')->nullable()->after('landmark');
            $table->text('insurance_loan_req')->nullable()->after('past_diseases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'relative_name',
                'aadhar_number',
                'pan_number',
                'blood_group',
                'district',
                'block',
                'gp',
                'landmark',
                'past_diseases',
                'insurance_loan_req'
            ]);
        });
    }
};
