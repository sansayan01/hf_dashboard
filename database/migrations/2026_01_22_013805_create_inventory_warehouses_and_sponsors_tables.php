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
        Schema::create('inventory_warehouses', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->string('location')->nullable();
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamps();
        });

        Schema::create('inventory_sponsors', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->text('description')->nullable();
            $blueprint->string('contact_person')->nullable();
            $blueprint->string('contact_email')->nullable();
            $blueprint->string('contact_phone')->nullable();
            $blueprint->timestamps();
        });

        // Create default warehouse
        \DB::table('inventory_warehouses')->insert([
            'name' => 'Main Warehouse',
            'location' => 'Headquarters',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_warehouses');
        Schema::dropIfExists('inventory_sponsors');
    }
};
