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
        Schema::create('exam_main', function (Blueprint $table) {
            $table->id('exam_main_id'); // Auto-increment primary key
            $table->string('exam_main_no')->nullable(); // Exam main number (text)
            $table->string('exam_main_type')->nullable(); // Exam main type (text)
            $table->string('exam_main_model')->nullable(); // Exam main model (text)
            $table->string('exam_main_tiers')->nullable(); // Exam main tiers (text)
            $table->string('exam_main_service')->nullable(); // Exam main service (text)
            $table->text('exam_main_notification')->nullable(); // Exam main notification (text)
            $table->string('exam_main_notifdate')->nullable(); // Exam main notification date (text)
            $table->string('exam_main_name')->nullable(); // Exam main name (text)
            $table->string('exam_main_nametamil')->nullable(); // Exam main name in Tamil (text)
            $table->string('exam_main_postname')->nullable(); // Exam main post name (text)
            $table->string('exam_main_lastdate')->nullable(); // Exam main last date (text)
            $table->string('exam_main_startdate')->nullable(); // Exam main start date (text)
            $table->string('exam_main_flag')->nullable(); // Exam main flag (text)
            $table->string('exam_main_candidates_for_hall')->nullable(); // Exam main candidates for hall (big integer)
            $table->timestamp('exam_main_createdat')->useCurrent(); // Exam main creation date (timestamp)

            // Add indexes for performance optimization
            $table->index('exam_main_no'); // Index for exam_main_no
            $table->index('exam_main_type'); // Index for exam_main_type
            $table->index('exam_main_tiers'); // Index for exam_main_tiers
            $table->index('exam_main_notifdate'); // Index for exam_main_notifdate
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_main');
    }
};
