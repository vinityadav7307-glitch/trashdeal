<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 15)->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('role')->default('user'); // user | collector | admin
            $table->integer('total_points')->default(0);
            $table->boolean('is_premium')->default(false);
            $table->string('premium_plan')->nullable(); // basic | pro
            $table->timestamp('premium_expires_at')->nullable();
            $table->string('address')->nullable();
            $table->string('zone')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
