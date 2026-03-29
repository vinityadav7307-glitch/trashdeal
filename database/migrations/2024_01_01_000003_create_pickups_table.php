<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('collector_id')->nullable()->constrained('waste_collectors')->onDelete('set null');
            $table->string('address');
            $table->decimal('pickup_lat', 10, 8)->nullable();
            $table->decimal('pickup_lng', 11, 8)->nullable();
            $table->string('waste_type');   // organic | recyclable | e-waste | inert | mixed
            $table->string('status')->default('pending');
            // status flow: pending -> assigned -> picked_up -> weighed -> verified -> completed | cancelled

            $table->decimal('estimated_weight_kg', 8, 2)->nullable();
            $table->decimal('actual_weight_kg', 8, 2)->nullable();
            $table->integer('points_earned')->default(0);
            $table->string('notes')->nullable(); // user notes
            $table->string('pickup_photo')->nullable();   // photo taken at pickup
            $table->string('delivery_photo')->nullable(); // photo at DDC drop-off
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['collector_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickups');
    }
};
