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
        Schema::create('ci_meeting_qrcodes', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('exam_id');
            $table->text('district_code');
            $table->text('qrcode');
            $table->timestamp('meeting_date_time');
            $table->timestamps(); // Laravel's automatic created_at and updated_at

            // Indexes for better query performance
            $table->index('exam_id');
            $table->index('district_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ci_meeting_qrcodes');
    }
};
