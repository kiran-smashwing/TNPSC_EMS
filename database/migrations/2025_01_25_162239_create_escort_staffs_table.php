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
        Schema::create('escort_staffs', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->bigInteger('charted_vehicle_id')->nullable();
            $table->string('district_code'); // District code
            $table->string('tnpsc_staff_id'); // TNPSC staff ID
            $table->json('si_details')->nullable(); // SI details in JSON format
            $table->json('revenue_staff_details')->nullable(); // Revenue staff details in JSON format
            $table->timestamps(0); // Laravel's automatic created_at and updated_at timestamps

            // Index for better query performance
            $table->index('district_code');
            $table->index('charted_vehicle_id');
            $table->index('tnpsc_staff_id');

            // Add JSON indexes
            $table->index('si_details', 'idx_si_details_gin')->algorithm('gin');
            $table->index('revenue_staff_details', 'idx_revenue_staff_gin')->algorithm('gin');
            
            // Add compound indexes
            $table->index(['charted_vehicle_id', 'district_code']);
            $table->index(['charted_vehicle_id','tnpsc_staff_id', 'district_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escort_staffs');
    }
};
