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
        Schema::create('exam_materials_scans', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->bigInteger('exam_material_id'); // Exam material ID
            $table->timestamp('district_scanned_at')->nullable(); // Timestamp for district scan
            $table->timestamp('center_scanned_at')->nullable(); // Timestamp for center scan
            $table->timestamp('mobile_team_scanned_at')->nullable(); // Timestamp for mobile team scan
            $table->timestamp('ci_scanned_at')->nullable(); // Timestamp for CI scan
            // Using timestamps() to automatically handle created_at and updated_at
            $table->timestamps(0); // Automatically creates created_at and updated_at columns

            // Add index for better performance
            $table->index('exam_material_id'); // Index for exam_material_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_materials_scans');
    }
};
