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
        Schema::create('exam_trunkbox_scans', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->bigInteger('exam_trunkbox_id'); // Exam Trunkbox ID
            $table->timestamp('dept_off_scanned_at')->nullable(); // Department officer scanned timestamp
            $table->timestamp('hq_scanned_at')->nullable(); // HQ scanned timestamp
            $table->timestamps(0); // Automatically handles created_at and updated_at

            // Indexes for optimization
            $table->index('exam_trunkbox_id'); // Index on exam_trunkbox_id for optimized queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_trunkbox_scans');
    }
};
