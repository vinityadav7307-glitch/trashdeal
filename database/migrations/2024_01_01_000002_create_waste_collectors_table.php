<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_collectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('phone', 15)->unique();
            $table->string('qr_code')->unique(); // unique QR for each collector
            $table->string('zone');              // area they cover
            $table->string('vehicle_type')->nullable(); // bike | rickshaw | truck
            $table->string('vehicle_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(false); // currently free to pick up?
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();
            $table->integer('total_pickups')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();

            $table->index('zone');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_collectors');
    }
};
