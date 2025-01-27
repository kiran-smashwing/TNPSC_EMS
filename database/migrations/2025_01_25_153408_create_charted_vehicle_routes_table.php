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
        Schema::create('charted_vehicle_routes', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->text('route_no');
            $table->jsonb('exam_id');
            $table->text('charted_vehicle_no');
            $table->jsonb('driver_details');
            $table->jsonb('gps_locks');
            $table->jsonb('pc_details');
            $table->jsonb('escort_vehicle_details')->nullable();
            $table->jsonb('otl_locks')->nullable();
            $table->jsonb('handover_verification_details')->nullable();
            $table->timestamps(); // Automatically manages created_at and updated_at

            // Indexes for optimized performance
            $table->index('route_no');
            $table->index('exam_id');
            $table->index('charted_vehicle_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charted_vehicle_routes');
    }
};
