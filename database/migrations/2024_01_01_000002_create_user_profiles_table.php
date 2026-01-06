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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('full_name');
            $table->string('profile_picture')->nullable();
            $table->string('phone_number', 15);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();

            // Identity Documents
            $table->string('aadhaar_number', 12)->nullable();
            $table->string('pan_number', 10)->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('block')->nullable();
            $table->string('gram_panchayat')->nullable();
            $table->string('pin_code', 6)->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
