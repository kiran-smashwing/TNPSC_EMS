<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district', function (Blueprint $table) {
            $table->id('district_id');
            $table->string('district_code')->nullable();
            $table->string('district_phone')->nullable();
            $table->string('district_email')->nullable();
            $table->string('district_name')->nullable();
            $table->string('district_alternate_phone')->nullable();
            $table->string('district_password')->nullable();
            $table->string('district_website')->nullable();
            $table->text('district_address')->nullable();
            $table->string('district_longitude')->nullable();
            $table->string('district_latitude')->nullable();
            $table->timestamp('district_createdat')->useCurrent();
            $table->string('district_image')->nullable();
            $table->boolean('district_status')->default(true);
            $table->boolean('district_email_status')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district');
    }
};