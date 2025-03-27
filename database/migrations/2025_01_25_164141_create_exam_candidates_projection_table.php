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
        Schema::create('exam_candidates_projection', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id'); // Exam ID as a big integer
            $table->date('exam_date'); // Exam date
            $table->string('session', 10); // Session (length 10 characters)
            $table->string('center_code', 20); // Center code (length 20 characters)
            $table->string('district_code', 20); // District code (length 20 characters)
            $table->integer('expected_candidates')->nullable(); // Expected candidates count
            $table->integer('accommodation_required')->nullable(); // Accommodation required count
            $table->bigInteger('increment_percentage')->nullable(); // Increment percentage as big integer
            $table->timestamps(); // Laravel's automatic created_at and updated_at timestamps

            // Add indexes for performance optimization
            $table->index(['exam_id', 'exam_date', 'center_code', 'district_code']); // Composite index for frequent queries

            // Add compound indexes for common queries
            $table->index(['exam_id', 'district_code']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_candidates_projection');

    }
};
