<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alert_notifications', function (Blueprint $table) {
            $table->id();
            $table->text('exam_id');
            $table->text('district_code');
            $table->text('center_code');
            $table->text('hall_code');
            $table->text('ci_id');
            $table->date('exam_date');
            $table->text('exam_session');
            $table->text('alert_type');
            $table->text('details');
            $table->text('remarks')->nullable();
            $table->timestamps(); // Automatically manages created_at and updated_at

            // Add compound indexes for filtering
            $table->index(['exam_id', 'exam_date', 'exam_session']);
            $table->index(['district_code', 'center_code']);
            $table->index(['alert_type', 'exam_date']);
            
            // Add full text search for details and remarks
            $table->fullText(['details', 'remarks']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_notifications');
    }
};
