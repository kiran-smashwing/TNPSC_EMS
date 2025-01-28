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
        Schema::create('role', function (Blueprint $table) {
            $table->id('role_id'); // Primary key with auto-increment
            $table->string('role_department'); // Role department
            $table->string('role_name')->unique(); // Role name with uniqueness
            $table->timestamp('role_createdat')->useCurrent(); // Timestamp with default current value

            // Performance indexes
            $table->index('role_department'); // Index for department queries
            $table->index('role_name'); // Index for role name queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
