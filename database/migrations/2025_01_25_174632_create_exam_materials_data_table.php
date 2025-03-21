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
        Schema::create('exam_materials_data', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id')->nullable(); // Exam ID
            $table->text('district_code')->nullable(); // District Code
            $table->text('center_code')->nullable(); // Center Code
            $table->text('hall_code')->nullable(); // Hall Code
            $table->date('exam_date')->nullable(); // Exam Date
            $table->text('exam_session')->nullable(); // Exam Session
            $table->text('qr_code'); // QR Code, not nullable
            $table->text('category')->nullable(); // Category
            $table->text('mobile_team_id')->nullable(); // Mobile Team ID
            $table->text('ci_id')->nullable(); // CI ID
            $table->text('venue_code')->nullable(); // Venue Code with a length of 255 characters

            // Using timestamps() for created_at and updated_at
            $table->timestamps(0); // Automatically handles created_at and updated_at

            // Create a composite index for multiple columns
            $table->index(['exam_id', 'qr_code','district_code', 'center_code','mobile_team_id', 'ci_id']); // Composite index on exam_id, district_code, and center_code
            // Added index on exam_date for filtering on date
            $table->index('exam_date');

            // Enhanced indexes for scanning/tracking
            $table->index(['exam_id', 'qr_code']);
            $table->index(['qr_code']);
            $table->index(['district_code', 'center_code', 'exam_date']);
            $table->index(['mobile_team_id', 'exam_date']);
            $table->index(['ci_id', 'exam_date']);

            // Enhanced indexes for material tracking
            $table->index(['exam_id', 'qr_code', 'mobile_team_id']);
            $table->index(['exam_id', 'qr_code', 'ci_id']);
            $table->index(['exam_id', 'qr_code', 'district_code']);
            $table->index(['exam_id', 'qr_code', 'center_code']);
            
            // QR code specific indexes
            $table->index(['qr_code', 'category']);
            $table->index(['qr_code', 'exam_date', 'exam_session']);
            
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_materials_data');
    }
};
