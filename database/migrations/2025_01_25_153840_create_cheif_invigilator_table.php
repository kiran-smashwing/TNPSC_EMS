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
        Schema::create('cheif_invigilator', function (Blueprint $table) {
            $table->id('ci_id'); // Primary key with auto-increment
            $table->string('ci_district_id', 50)->nullable();
            $table->string('ci_center_id', 50)->nullable();
            $table->string('ci_venue_id', 50)->nullable();
            $table->text('ci_name')->nullable();
            $table->text('ci_email')->nullable()->unique();
            $table->text('ci_phone')->nullable();
            $table->text('ci_alternative_phone')->nullable();
            $table->text('ci_designation')->nullable();
            $table->text('ci_password');
            $table->text('ci_image')->nullable();
            $table->boolean('ci_status')->default(true);
            $table->boolean('ci_email_status')->default(false);
            $table->text('remember_token')->nullable();
            $table->text('verification_token')->nullable();
            $table->time('ci_createdat')->useCurrent();

            // Indexes for frequently queried fields
            $table->index('ci_district_id');
            $table->index('ci_center_id');
            $table->index('ci_email');
            $table->index('ci_id');
            $table->index('ci_venue_id');

            // Timestamps for created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheif_invigilator');
    }
};
