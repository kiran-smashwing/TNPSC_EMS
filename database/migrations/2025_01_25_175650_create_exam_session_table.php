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
        Schema::create('exam_session', function (Blueprint $table) {
            $table->id('exam_session_id'); // Auto-increment primary key
            $table->text('exam_sess_mainid'); // Exam Session Main ID
            $table->text('exam_sess_date')->nullable(); // Exam Session Date
            $table->text('exam_sess_session')->nullable(); // Exam Session Name
            $table->text('exam_sess_time')->nullable(); // Exam Session Time
            $table->text('exam_sess_duration')->nullable(); // Exam Duration
            $table->text('exam_sess_subject')->nullable(); // Exam Subject
            $table->text('exam_sess_flag')->nullable(); // Exam Flag
            $table->text('exam_sess_type')->nullable(); // Exam Session Type
            $table->text('exam_sess_created_at')->nullable(); // Exam Session Created At
            // Index for performance optimization
            $table->index('exam_sess_mainid'); // Index on exam_sess_mainid for faster lookups
            $table->index('exam_sess_date'); // Index on exam_sess_date for optimized queries by date
            
            // Enhanced indexes
            $table->index(['exam_sess_mainid', 'exam_sess_date', 'exam_sess_session']);
            $table->index(['exam_sess_date', 'exam_sess_session']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_session');
    }
};
