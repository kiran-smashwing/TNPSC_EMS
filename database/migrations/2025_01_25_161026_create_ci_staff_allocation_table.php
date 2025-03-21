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
        Schema::create('ci_staff_allocations', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id');
            $table->integer('ci_id');
            $table->jsonb('invigilators')->default(json_encode([])); // JSONB field with default empty array
            $table->jsonb('assistants')->default(json_encode([])); // JSONB field with default empty array
            $table->jsonb('scribes')->default(json_encode([])); // JSONB field with default empty array
            $table->date('exam_date')->nullable(); // Nullable date for exam date
            $table->timestamps(); // Laravel's automatic created_at and updated_at

            // Indexes for better query performance
            $table->index('exam_id');
            $table->index('ci_id');
            $table->index('exam_date');

            // Add GIN indexes for JSONB columns
            $table->index('invigilators', 'idx_invigilators_gin')->algorithm('gin');
            $table->index('assistants', 'idx_assistants_gin')->algorithm('gin');
            $table->index('scribes', 'idx_scribes_gin')->algorithm('gin');
            
            // Add compound indexes for common queries
            $table->index(['exam_id', 'exam_date']);
            $table->index(['ci_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_staff_allocations');
    }
};
