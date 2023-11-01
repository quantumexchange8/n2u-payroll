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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('full_name');
            $table->string('ic_number')->unique();
            $table->string('address');
            $table->string('email')->nullable();
            $table->string('position_id')->nullable();
            $table->string('employee_type')->nullable();
            $table->integer('working_hour')->nullable();
            $table->integer('salary')->nullable();
            $table->string('employed_since')->nullable();
            $table->string('remarks')->nullable();
            $table->string('nation')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('bank_account')->nullable();
            $table->string('account_type')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_pic')->nullable();
            $table->string('passport_size_photo')->nullable();
            $table->string('ic_photo')->nullable();
            $table->string('offer_letter')->nullable();
            $table->string('other_image')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('member');
            $table->string('status')->default('1');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
