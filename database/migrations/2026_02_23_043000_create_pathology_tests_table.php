<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pathology_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('surveys')->onDelete('cascade');
            $table->string('test_name');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_percentage', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('payment_method')->default('cash');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('ro_id')->constrained('users'); // Relationship Officer getting the incentive
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathology_tests');
    }
};
