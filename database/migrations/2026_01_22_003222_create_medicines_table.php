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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->foreignId('category_id')->constrained('medicine_categories')->onDelete('cascade');
            $table->string('unit'); // e.g., Strip, Tablet, Bottle
            $table->string('dosage')->nullable(); // e.g., 500mg
            $table->text('description')->nullable();
            $table->integer('min_stock_level')->default(10);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
