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
        Schema::create('camp_records', function (Blueprint $table) {
            $table->id();
            $table->string('camp_name');
            $table->string('location')->nullable();
            $table->string('rm')->nullable();
            $table->date('date');
            $table->integer('patients_count')->default(0);
            $table->decimal('medicine_mrp', 10, 2)->default(0);
            $table->decimal('medicine_discount', 10, 2)->default(0);
            $table->decimal('billing_price', 10, 2)->default(0);
            $table->decimal('profit', 10, 2)->default(0);
            $table->string('doctor_name')->nullable();
            $table->string('pathologist')->nullable();
            $table->string('pharmacists_name')->nullable();
            $table->decimal('expenses', 10, 2)->default(0);
            $table->decimal('net_profit_loss', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_records');
    }
};
