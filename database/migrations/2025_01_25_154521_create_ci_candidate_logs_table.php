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
        Schema::create('ci_candidate_logs', function (Blueprint $table) {
            $table->id(); // Primary key with auto-increment
            $table->text('exam_id');
            $table->text('center_code');
            $table->text('hall_code');
            $table->date('exam_date')->nullable();
            $table->jsonb('additional_details')->default(json_encode([]));
            $table->jsonb('candidate_remarks')->default(json_encode([]));
            $table->jsonb('omr_remarks')->nullable();
            $table->text('ci_id')->nullable();
            $table->jsonb('candidate_attendance')->nullable();
            // Timestamps for created_at and updated_at
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['exam_id', 'center_code', 'hall_code', 'ci_id']);

            // JSON field indexes
            $table->index('additional_details', 'idx_additional_details_gin')->algorithm('gin');
            $table->index('candidate_remarks', 'idx_candidate_remarks_gin')->algorithm('gin');
            
            // Composite indexes
            $table->index(['exam_id', 'exam_date']);
            $table->index(['exam_id', 'center_code', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_candidate_logs');
    }
};
