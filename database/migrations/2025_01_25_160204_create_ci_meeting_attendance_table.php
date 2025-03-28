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
        Schema::create('ci_meeting_attendance', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id');
            $table->text('district_code');
            $table->text('center_code');
            $table->text('hall_code');
            $table->text('ci_id');
            $table->jsonb('adequacy_check'); // JSON field for adequacy check
            $table->timestamps(); // Laravel's automatic created_at and updated_at

            // Indexes for improved query performance
            $table->index(['exam_id', 'district_code', 'center_code', 'hall_code', 'ci_id']);

            // Add compound indexes for common queries
            $table->index(['exam_id', 'district_code']);
            
            // Add GIN index for adequacy check JSON
            $table->index('adequacy_check', 'idx_adequacy_check_gin')->algorithm('gin');
            
            // Add index for CI attendance queries
            $table->index(['ci_id', 'district_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_meeting_attendance');
    }
};
