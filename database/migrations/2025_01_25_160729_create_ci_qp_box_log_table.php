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
        Schema::create('ci_qp_box_logs', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id');
            $table->date('exam_date')->nullable();
            $table->text('center_code');
            $table->text('hall_code');
            $table->integer('ci_id');
            $table->jsonb('qp_timing_log')->default(json_encode([])); // JSONB field with default empty array
            $table->timestamps(); // Laravel's automatic created_at and updated_at

            // Indexes for better query performance
            $table->index(['exam_id','center_code', 'hall_code', 'ci_id']);
            $table->index('ci_id');

            // Optimize JSONB column with GIN index
            $table->index('qp_timing_log', 'idx_qp_timing_log_gin')->algorithm('gin');
            
            // Add composite indexes for common queries
            $table->index(['exam_id', 'exam_date']);
            $table->index(['center_code', 'hall_code', 'exam_date']);
            $table->index(['ci_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_qp_box_logs');
    }
};
