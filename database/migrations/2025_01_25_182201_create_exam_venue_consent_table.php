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
        Schema::create('exam_venue_consent', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id'); // Exam ID
            $table->text('venue_id'); // Venue ID
            $table->string('center_code', 50); // Center code
            $table->string('district_code', 50); // District code
            $table->text('consent_status')->default('pending'); // Consent status
            $table->boolean('email_sent_status')->default(false); // Email sent status
            $table->bigInteger('expected_candidates_count')->default(0); // Expected candidates count
            $table->timestamps(0); // Handles created_at and updated_at

            // Indexes for optimization
            $table->index('exam_id'); // Index on exam_id for performance on queries involving exam ID
            $table->index('venue_id'); // Index on venue_id for performance on queries involving venue ID
            $table->index('center_code'); // Index on center_code for filtering by center
            $table->index('district_code'); // Index on district_code for filtering by district
            $table->index('consent_status'); // Index on consent_status for filtering based on consent

            // Enhanced composite indexes
            $table->index(['exam_id', 'district_code', 'consent_status']);
            $table->index(['exam_id', 'center_code', 'consent_status']); 
            $table->index(['exam_id', 'venue_id', 'consent_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_venue_consent');
    }
};
