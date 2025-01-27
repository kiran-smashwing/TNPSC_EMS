<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('district', function (Blueprint $table) {
            $table->id('district_id');
            $table->text('district_code')->nullable()->unique(); // District code
            $table->text('district_phone')->nullable(); // District phone number
            $table->text('district_email')->nullable()->unique(); // District email
            $table->text('district_name')->nullable(); // District name
            $table->text('district_alternate_phone')->nullable(); // District alternate phone number
            $table->text('district_password')->nullable(); // District password
            $table->text('district_website')->nullable(); // District website URL
            $table->text('district_address')->nullable(); // District address
            $table->text('district_longitude')->nullable(); // Longitude of district
            $table->text('district_latitude')->nullable(); // Latitude of district
            $table->text('district_image')->nullable(); // District image URL
            $table->boolean('district_status')->default(true); // Active status, default true
            $table->boolean('district_email_status')->default(false); // Email verification status, default false
            $table->text('remember_token')->nullable();
            $table->text('verification_token')->nullable(); // Verification token for email confirmation
            $table->timestamp('district_createdat')->useCurrent(); // Creation timestamp with time zone

            // Index for better query performance
            $table->index('district_email');
            $table->index('district_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};
