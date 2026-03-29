<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pickup_id')->nullable()->constrained('pickups')->onDelete('set null');
            $table->foreignId('reward_id')->nullable()->constrained('rewards')->onDelete('set null');
            $table->string('type');
            // type: earned_pickup | earned_scan | earned_referral | redeemed | bonus | deducted

            $table->integer('points');     // positive = earned, negative = spent
            $table->integer('balance_after'); // running balance snapshot
            $table->string('description');
            $table->string('reference_code')->nullable(); // redemption code or voucher ID
            $table->string('status')->default('completed');
            // status: pending | completed | failed | reversed
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
