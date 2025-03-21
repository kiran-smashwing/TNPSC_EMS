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
        Schema::create('ci_checklists', function (Blueprint $table) {
            $table->id('ci_checklist_id'); // Primary key with auto-increment
            $table->text('ci_checklist_type')->nullable();
            $table->text('ci_checklist_description')->nullable();
            $table->boolean('ci_checklist_status')->default(false);
            $table->timestamp('ci_checklist_createdat')->useCurrent();

            // Indexes for better performance on frequently queried columns
            $table->index('ci_checklist_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_checklists');

    }
};
