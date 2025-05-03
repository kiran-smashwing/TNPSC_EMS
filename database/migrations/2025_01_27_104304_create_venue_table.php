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
        Schema::create('venue', function (Blueprint $table) {
            $table->id('venue_id'); // Auto-increment primary key
            $table->string('venue_district_id', 50)->index(); // Indexed for better query performance
            $table->string('venue_center_id', 50)->index();
            $table->text('venue_name');
            $table->string('venue_code')->unique(); // Ensuring unique venue codes
            $table->string('venue_codeprovider')->nullable();
            $table->string('venue_email')->unique();
            $table->string('venue_phone')->index();
            $table->string('venue_alternative_phone')->nullable();
            $table->string('venue_type')->nullable();
            $table->string('venue_category')->nullable();
            $table->string('venue_website')->nullable();
            $table->text('venue_password');
            $table->text('venue_address');
            $table->text('venue_address_2')->nullable();
            $table->text('venue_pincode');
            $table->text('venue_landmark');
            $table->string('venue_distance_railway')->nullable();
            $table->string('venue_treasury_office')->nullable();
            $table->string('venue_longitude')->nullable();
            $table->string('venue_latitude')->nullable();
            $table->string('venue_bank_name')->nullable();
            $table->string('venue_account_name')->nullable();
            $table->string('venue_account_number')->nullable();
            $table->string('venue_branch_name')->nullable();
            $table->string('venue_account_type')->nullable();
            $table->string('venue_ifsc')->nullable();
            $table->boolean('venue_status')->default(true);
            $table->boolean('venue_email_status')->default(false);
            $table->string('venue_image')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamp('venue_createdat')->useCurrent(); // Default current timestamp

            // Additional indexes for performance optimization
            $table->index(['venue_code']);
            $table->index(['venue_district_id', 'venue_center_id','venue_code', 'venue_email']);
            
            // Composite indexes for common queries
            $table->index(['venue_district_id','venue_center_id','venue_status']);
            $table->index(['venue_id', 'venue_status']);
            // Full text search
            $table->fullText(['venue_name', 'venue_code', 'venue_email','venue_address', 'venue_landmark']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue');
    }
};
