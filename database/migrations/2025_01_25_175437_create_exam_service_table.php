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
        Schema::create('exam_service', function (Blueprint $table) {
            $table->id('examservice_id'); // Auto-increment primary key
            $table->string('examservice_name')->nullable(); // Exam Service Name
            $table->string('examservice_code')->nullable(); // Exam Service Code
            $table->boolean('examservice_status')->default(true); // Exam Service Status (default true)
            $table->timestamp('examservice_createdat')->useCurrent(); // Creation timestamp
            // Add index for performance optimization
            $table->index('examservice_code'); // Index on examservice_code for faster lookups
            $table->fullText('examservice_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_service');
    }
};
