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
        Schema::create('treasury_officer', function (Blueprint $table) {
            $table->id('tre_off_id'); // Primary key with auto-increment
            $table->string('tre_off_district_id', 50)->index(); // Indexed for performance
            $table->string('tre_off_name');
            $table->string('tre_off_designation');
            $table->string('tre_off_phone')->index(); // Indexed for faster lookups
            $table->string('tre_off_email')->unique(); // Ensuring unique emails
            $table->string('tre_off_employeeid')->unique(); // Unique employee ID
            $table->string('tre_off_password');
            $table->string('tre_off_image')->nullable();
            $table->boolean('tre_off_email_status')->default(false);
            $table->boolean('tre_off_status')->default(true);
            $table->string('remember_token')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamp('tre_off_createdat')->useCurrent(); // Default current timestamp with timezone

            // Additional indexing for better query performance
            $table->index(['tre_off_district_id']);
            $table->index('tre_off_email');

            // Enhanced composite indexes
            $table->index(['tre_off_district_id', 'tre_off_status']);
            $table->index(['tre_off_employeeid', 'tre_off_status']);
            
            // Full text search
            $table->fullText(['tre_off_name', 'tre_off_designation', 'tre_off_email']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treasury_officer');
    }
};
