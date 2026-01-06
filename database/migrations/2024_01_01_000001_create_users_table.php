<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('email')->unique();
            $table->string('password');
            
            // Hierarchy
            $table->enum('designation', ['super_admin', 'dm', 'bm', 'rm', 'ro']);
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Status
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->boolean('is_office_in_charge')->default(false);
            $table->foreignId('office_in_charge_creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('office_in_charge_type', ['temporary', 'permanent'])->nullable();
            $table->date('office_in_charge_end_date')->nullable();
            
            // Soft delete
            $table->softDeletes();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes
            $table->index('parent_id');
            $table->index('designation');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
