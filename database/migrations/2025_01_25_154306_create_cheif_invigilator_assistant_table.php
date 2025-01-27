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
        Schema::create('cheif_invigilator_assistant', function (Blueprint $table) {
            $table->id('cia_id'); // Primary key with auto-increment
            $table->string('cia_district_id', 50)->nullable();
            $table->string('cia_center_id', 50)->nullable();
            $table->string('cia_venue_id', 50)->nullable();
            $table->text('cia_name')->nullable();
            $table->text('cia_email')->nullable()->unique();
            $table->text('cia_phone')->nullable();
            $table->text('cia_designation')->nullable();
            $table->text('cia_image')->nullable();
            $table->boolean('cia_status')->default(true);
            $table->timestamp('cia_createdat')->useCurrent();

            // Indexes for frequently queried fields
            $table->index('cia_district_id');
            $table->index('cia_center_id');
            $table->index('cia_venue_id');
            $table->index('cia_id');
            $table->index('cia_email');

            // Timestamps for created_at and updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheif_invigilator_assistant');
    }
};
