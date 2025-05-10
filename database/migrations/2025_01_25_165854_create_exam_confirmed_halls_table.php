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
        Schema::create('exam_confirmed_halls', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('district_code'); // District code (text)
            $table->string('center_code'); // Center code (text)
            $table->string('venue_code'); // Venue code (text)
            $table->text('exam_id'); // Exam ID (big integer)
            $table->string('hall_code'); // Hall code (text)
            $table->date('exam_date'); // Exam date (date)
            $table->string('exam_session'); // Exam session (text)
            $table->bigInteger('ci_id'); // Chief invigilator ID (big integer)
            $table->boolean('is_apd_uploaded')->default(false); // Whether APD is uploaded
            $table->text('alloted_count')->nullable(); // Alloted count (nullable)
            $table->text('addl_cand_count')->nullable(); // Additional candidate count (nullable)
            $table->timestamps(); // Timestamps for created_at and updated_at with precision

            // Add indexes for performance optimization
            $table->index('district_code'); // Index for district_code
            $table->index('center_code'); // Index for center_code
            $table->index('venue_code'); // Index for venue_code

            // Composite index for frequent queries on district_code, center_code, and exam_id
            $table->index(['district_code', 'center_code', 'hall_code','exam_id','exam_date']);

            // Enhanced composite indexes 
            $table->index(['exam_id', 'district_code', 'center_code', 'venue_code']);
            $table->index(['exam_id', 'exam_date', 'exam_session', 'district_code']);
            $table->index(['exam_id', 'ci_id']);
            $table->index(['exam_id', 'ci_id', 'exam_date', 'exam_session']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_confirmed_halls');
    }
};
