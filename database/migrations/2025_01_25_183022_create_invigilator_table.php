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
        Schema::create('invigilator', function (Blueprint $table) {
            $table->id('invigilator_id'); // Auto-increment primary key
            $table->string('invigilator_district_id', 50)->nullable(); // District ID
            $table->string('invigilator_center_id', 50)->nullable(); // Center ID
            $table->text('invigilator_venue_id')->nullable(); // Venue ID
            $table->string('invigilator_name'); // Invigilator name
            $table->string('invigilator_email')->nullable()->unique(); // Invigilator email
            $table->string('invigilator_phone')->nullable(); // Invigilator phone number
            $table->string('invigilator_designation')->nullable(); // Invigilator designation
            $table->boolean('invigilator_status')->default(true); // Invigilator status (active/inactive)
            $table->string('invigilator_image')->nullable(); // Invigilator image
            $table->timestamp('invigilator_createdat')->useCurrent(); // Creation timestamp
            // Indexes for optimization
            $table->index('invigilator_district_id'); // Index on district_id for performance on queries involving district
            $table->index('invigilator_center_id'); // Index on center_id for performance on queries involving center
            $table->index('invigilator_venue_id'); // Index on venue_id for performance on queries involving venue
            $table->index('invigilator_status'); // Index on invigilator_status for filtering active/inactive invigilators
            $table->index('invigilator_email'); // Index on email for faster lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invigilator');
    }
};
