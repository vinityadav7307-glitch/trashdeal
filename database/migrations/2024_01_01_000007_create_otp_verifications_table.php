<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15);
            $table->string('otp_code', 6);
            $table->string('purpose'); // login | register | password_reset | pickup_confirm
            $table->boolean('is_used')->default(false);
            $table->integer('attempts')->default(0); // prevent brute force
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['phone', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
