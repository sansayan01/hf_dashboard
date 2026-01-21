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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('inventory_stocks')->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'dispense', 'expired', 'damaged']);
            $table->integer('quantity');
            $table->foreignId('user_id')->constrained('users'); // Who performed the transaction
            $table->unsignedBigInteger('patient_id')->nullable(); // If dispense, link to survey/patient
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('surveys')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
