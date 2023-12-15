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
        Schema::create('punch_record_logs', function (Blueprint $table) {
            $table->id();
            $table->string('punch_record_id');
            $table->string('employee_id');
            $table->string('record_date');
            $table->string('actual_clock_in_time')->nullable();
            $table->string('new_clock_in_time')->nullable();
            $table->string('actual_clock_out_time')->nullable();
            $table->string('new_clock_out_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punch_record_logs');
    }
};
