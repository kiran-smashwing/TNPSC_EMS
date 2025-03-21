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
        Schema::create('ci_checklist_answers', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id');
            $table->text('center_code');
            $table->text('venue_code')->nullable();
            $table->text('hall_code');
            $table->text('ci_id');
            $table->jsonb('preliminary_answer')->default(json_encode([]));
            $table->jsonb('session_answer')->default(json_encode([]));
            $table->jsonb('utility_answer')->default(json_encode([]));
            $table->jsonb('consolidate_answer')->default(json_encode([]));
            $table->jsonb('videography_answer')->nullable();

            // Standard Laravel timestamps for created_at and updated_at
            $table->timestamps();

            // Indexes for better performance
            $table->index(['exam_id', 'center_code', 'hall_code', 'venue_code', 'ci_id']);

            // Add composite indexes
            $table->index(['exam_id', 'ci_id', 'exam_date']);
            // Add GIN indexes for JSONB fields
            $table->index('preliminary_answer', 'idx_preliminary_gin')->algorithm('gin');
            $table->index('session_answer', 'idx_session_gin')->algorithm('gin');
            $table->index('utility_answer', 'idx_utility_gin')->algorithm('gin');
            $table->index('consolidate_answer', 'idx_consolidate_gin')->algorithm('gin');
            $table->index('videography_answer', 'idx_videography_gin')->algorithm('gin');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_checklist_answers');

    }
};
