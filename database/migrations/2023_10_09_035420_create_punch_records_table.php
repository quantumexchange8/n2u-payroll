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
        Schema::create('punch_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('in')->nullable();
            $table->string('out')->nullable();
            $table->string('status')->nullable();
            $table->string('status_clock')->nullable();
            $table->string('ot_approval')->nullable();
            $table->string('ot_hours')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punch_records');
    }
};
