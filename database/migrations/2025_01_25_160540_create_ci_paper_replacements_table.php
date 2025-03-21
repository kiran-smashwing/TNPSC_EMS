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
        Schema::create('ci_paper_replacements', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id');
            $table->text('center_code');
            $table->text('hall_code');
            $table->date('exam_date');
            $table->text('exam_session');
            $table->text('registration_number');
            $table->text('replacement_type');
            $table->text('old_paper_number');
            $table->text('new_paper_number');
            $table->text('replacement_reason');
            $table->text('ci_id')->nullable();
            $table->text('replacement_photo')->nullable();
            $table->text('replacement_type_paper')->nullable();
            $table->timestamps(0); // Laravel's automatic created_at and updated_at

            // Indexes for better query performance

            $table->index('exam_id');
            $table->index('center_code');
            $table->index('hall_code');
            $table->index('ci_id');
            $table->index('exam_date');
            $table->index('exam_session');
            
            // Add compound indexes for common lookups
            $table->index(['exam_id', 'exam_date', 'exam_session']);
            $table->index(['center_code', 'hall_code', 'exam_date']);
            $table->index(['registration_number', 'exam_date']);
            $table->index(['exam_id', 'center_code', 'ci_id']);
            
            // Index for tracking paper numbers
            $table->index(['old_paper_number', 'new_paper_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_paper_replacements');
    }
};
