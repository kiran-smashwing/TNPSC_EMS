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
            $table->id(); // Auto-increment primary key
            $table->text('exam_id'); // Exam ID
            $table->bigInteger('user_id'); // User ID
            $table->text('action_type'); // Action type
            $table->text('task_type'); // Task type
            $table->text('role'); // Role
            $table->text('department'); // Department
            $table->jsonb('before_state')->nullable(); // Before state in JSON format
            $table->jsonb('after_state')->nullable(); // After state in JSON format
            $table->text('description')->nullable(); // Description of the action
            $table->jsonb('metadata')->nullable(); // Metadata in JSON format

            $table->timestamps(0); // Automatically handles created_at and updated_at

            // Indexes for better query performance
            $table->index('exam_id');
            $table->index('user_id');
            $table->index('task_type');
            $table->index('role');
            $table->index('department');
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
