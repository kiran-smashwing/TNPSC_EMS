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
        Schema::create('mobile_team', function (Blueprint $table) {
            $table->id('mobile_id'); // Primary key with auto-increment
            $table->string('mobile_district_id')->nullable(); // District ID
            $table->string('mobile_name'); // Team member name
            $table->string('mobile_designation')->nullable(); // Designation
            $table->string('mobile_phone', 15)->nullable()->unique(); // Phone number with uniqueness
            $table->string('mobile_email')->nullable()->unique(); // Email with uniqueness
            $table->string('mobile_employeeid')->nullable()->unique(); // Employee ID with uniqueness
            $table->string('mobile_password'); // Password (hashed)
            $table->string('mobile_image')->nullable(); // Profile image path
            $table->boolean('mobile_status')->default(true); // Active/inactive status
            $table->boolean('mobile_email_status')->default(false); // Email verified or not
            $table->string('remember_token')->nullable(); // Remember token for authentication
            $table->string('verification_token')->nullable(); // Email verification token
            $table->timestamp('mobile_createdat')->useCurrent();

            // Performance indexes
            $table->index('mobile_district_id'); // Index for district-related queries
            $table->index('mobile_email'); // Index for filtering verified users

            // Enhanced composite indexes for common queries
            $table->index(['mobile_district_id', 'mobile_status']);
            $table->index(['mobile_employeeid', 'mobile_status']);
            
            // Full text search capabilities 
            $table->fullText(['mobile_name', 'mobile_designation', 'mobile_email']);
                                    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_team');
    }
};
