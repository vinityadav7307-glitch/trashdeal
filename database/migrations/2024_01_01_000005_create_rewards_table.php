<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('category');
            // category: grocery | amazon_voucher | fuel | bus_pass | eco_donation
            $table->string('brand')->nullable();          // e.g. "Amazon", "BigBazaar"
            $table->integer('points_required');
            $table->integer('stock')->default(-1);        // -1 = unlimited
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium_only')->default(false);
            $table->string('image_path')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'category']);
            $table->index('points_required');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
