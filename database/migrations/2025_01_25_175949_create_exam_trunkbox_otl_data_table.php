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
        Schema::create('exam_trunkbox_otl_data', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('exam_id'); // Exam ID
            $table->string('district_code'); // District Code
            $table->string('center_code'); // Center Code
            $table->string('hall_code'); // Hall Code
            $table->date('exam_date'); // Exam Date
            $table->text('trunkbox_qr_code'); // Trunkbox QR Code
            $table->jsonb('otl_code'); // OTL Code (jsonb data type)
            $table->string('venue_code'); // Venue Code
            $table->string('load_order'); // Load Order
            $table->timestamps(0); // Automatically handles created_at and updated_at

            // Index for performance optimization
            $table->index(['exam_id', 'trunkbox_qr_code','district_code', 'center_code','hall_code']); // Composite index on exam_id, district_code, and center_code

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_trunkbox_otl_data');
    }
};
