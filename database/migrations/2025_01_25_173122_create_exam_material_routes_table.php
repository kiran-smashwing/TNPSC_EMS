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
        Schema::create('exam_material_routes', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('exam_id'); // Exam ID (text)
            $table->date('exam_date'); // Exam date (date)
            $table->string('route_no'); // Route number (text)
            $table->string('driver_name'); // Driver name (text)
            $table->string('driver_license'); // Driver license number (text)
            $table->string('driver_phone'); // Driver phone (text)
            $table->string('vehicle_no'); // Vehicle number (text)
            $table->string('mobile_team_staff'); // Mobile team staff (text)
            $table->string('center_code'); // Center code (text)
            $table->jsonb('hall_code'); // Hall code (jsonb)
            $table->string('district_code')->nullable(); // District code (text)

            // Using timestamps() to automatically handle created_at and updated_at
            $table->timestamps(0); // Automatically creates created_at and updated_at columns

            // Add composite index for performance optimization
            $table->index(['exam_id', 'route_no', 'center_code', 'district_code', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_material_routes');
    }
};
