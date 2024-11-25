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
        Schema::create('exam_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id');
            $table->foreignId('user_id');
            $table->string('action_type');  // e.g., 'created', 'updated', 'uploaded'
            $table->string('task_type');    // e.g., 'exam_metadata', 'candidate_csv', 'candidate_count'
            $table->string('role');         // e.g., 'Section Officer'
            $table->string('department');   // e.g., 'RND', 'APD', 'ID'
            $table->json('before_state')->nullable();
            $table->json('after_state')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_audit_logs');
    }
};
