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
        Schema::create('venue_assigned_ci', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_consent_id');
            $table->unsignedBigInteger('ci_id');
            $table->date('exam_date');
            $table->boolean('is_confirmed')->nullable();
            $table->unsignedBigInteger('order_by_id')->nullable();
            $table->unsignedBigInteger('candidate_count')->nullable();
            $table->timestamps(); // Automatically manages created_at and updated_at

            // Add composite indexes for assignment queries
            $table->index(['venue_consent_id', 'ci_id']);
            $table->index(['venue_consent_id', 'exam_date']);
            // Add index for ordering
            $table->index(['order_by_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_assigned_ci');
    }
};
