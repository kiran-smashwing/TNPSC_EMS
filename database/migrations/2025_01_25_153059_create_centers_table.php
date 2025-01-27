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
        Schema::create('centers', function (Blueprint $table) {
            $table->id('center_id'); // Primary key with auto-increment
            $table->string('center_district_id', 50)->nullable();
            $table->text('center_name')->nullable();
            $table->text('center_code')->nullable();
            $table->text('center_phone')->nullable();
            $table->text('center_email')->nullable()->unique();
            $table->text('center_alternate_phone')->nullable();
            $table->text('center_password');
            $table->text('center_address')->nullable();
            $table->text('center_longitude')->nullable();
            $table->text('center_latitude')->nullable();
            $table->text('center_image')->nullable();
            $table->boolean('center_status')->default(true);
            $table->boolean('center_email_status')->default(false);
            $table->text('remember_token')->nullable();
            $table->text('verification_token')->nullable();
            $table->timestamp('center_createdat')->useCurrent();


            // Indexes for faster search queries
            $table->index('center_district_id');
            $table->index('center_code');
            $table->index('center_email');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
