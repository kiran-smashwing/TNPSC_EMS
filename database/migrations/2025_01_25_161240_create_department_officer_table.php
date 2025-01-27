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
        Schema::create('department_officer', function (Blueprint $table) {
            $table->id('dept_off_id'); // Auto-increment primary key
            $table->text('dept_off_name'); // Officer's name
            $table->text('dept_off_designation')->nullable(); // Officer's designation
            $table->text('dept_off_phone')->nullable(); // Officer's phone
            $table->text('dept_off_role')->nullable(); // Officer's role
            $table->text('dept_off_emp_id')->nullable(); // Officer's employee ID
            $table->text('dept_off_email')->nullable()->unique(); // Officer's email
            $table->text('dept_off_password')->nullable(); // Officer's password
            $table->text('dept_off_image')->nullable(); // Officer's image
            $table->boolean('dept_off_status')->default(true); // Active status, default true
            $table->boolean('dept_off_email_status')->default(false); // Email status, default false
            $table->text('remember_token')->nullable();
            $table->text('verification_token')->nullable(); // Verification token
            $table->timestamp('dept_off_createdat')->useCurrent(); // Officer's creation timestamp

            // Index for better query performance
            $table->index('dept_off_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_officers');
    }
};
