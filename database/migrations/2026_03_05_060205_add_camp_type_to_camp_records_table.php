<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('camp_records', function (Blueprint $table) {
            $table->string('camp_type')->default('travel_allowance')->after('camp_name');
            $table->decimal('doctor_appointment_fees', 10, 2)->default(0)->after('buying_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('camp_records', function (Blueprint $table) {
            $table->dropColumn(['camp_type', 'doctor_appointment_fees']);
        });
    }
};
