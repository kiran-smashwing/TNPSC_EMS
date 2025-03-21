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
        Schema::create('scribe', function (Blueprint $table) {
            $table->id('scribe_id'); // Primary key with auto-increment
            $table->string('scribe_district_id', 50)->index(); // Indexed for faster search
            $table->string('scribe_center_id', 50)->index(); // Indexed for search
            $table->string('scribe_venue_id', 50)->index(); // Indexed for search
            $table->string('scribe_name');
            $table->string('scribe_email')->unique(); // Ensure unique emails
            $table->string('scribe_phone')->index(); // Phone number indexed for faster queries
            $table->string('scribe_designation');
            $table->string('scribe_image')->nullable();
            $table->boolean('scribe_status')->default(true); // Default status as true
            $table->timestamp('scribe_createdat')->useCurrent(); // Default current timestamp

            // Additional indexing for performance improvement
            $table->index(['scribe_district_id', 'scribe_center_id', 'scribe_venue_id']);
            $table->index('scribe_email');

            // Enhanced composite indexes
            $table->index(['scribe_venue_id', 'scribe_status']);
            
            // Full text search capabilities
            $table->fullText(['scribe_name', 'scribe_designation','scribe_email']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scribe');
    }
};
