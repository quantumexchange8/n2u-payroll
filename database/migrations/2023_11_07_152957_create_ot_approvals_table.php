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
        Schema::create('ot_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('date');
            $table->string('shift_start');
            $table->string('shift_end');
            $table->string('clock_out_time');
            $table->string('ot_hour');
            $table->string('status');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_approvals');
    }
};
