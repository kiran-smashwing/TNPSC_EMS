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
            $table->jsonb('used_otl_locks')->nullable();
            $table->jsonb('used_gps_lock')->nullable();
            $table->jsonb('charted_vehicle_verification')->nullable();
            $table->timestamps(); // Automatically manages created_at and updated_at

            // Indexes for optimized performance
            $table->index('route_no');
            $table->index('exam_id');
            $table->index('charted_vehicle_no');

            // Enhanced indexes for vehicle tracking
            $table->index(['exam_id', 'route_no']); 
            $table->index(['charted_vehicle_no', 'route_no']);
            
            // Add GIN indexes for JSON fields
            $table->index('driver_details', 'idx_driver_details_gin')->algorithm('gin');
            $table->index('gps_locks', 'idx_gps_locks_gin')->algorithm('gin');
            $table->index('pc_details', 'idx_pc_details_gin')->algorithm('gin');
            $table->index('escort_vehicle_details', 'idx_escort_vehicle_gin')->algorithm('gin');
            $table->index('otl_locks', 'idx_otl_locks_gin')->algorithm('gin');
            $table->index('used_otl_locks', 'idx_used_otl_locks_gin')->algorithm('gin');
            
        
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
