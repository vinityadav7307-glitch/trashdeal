<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pickup_id')->nullable()->constrained('pickups')->onDelete('set null');
            $table->string('scan_type');
            // scan_type: waste_identification | collector_qr | weight_verification

            $table->string('image_path')->nullable(); // path to uploaded waste image
            $table->string('qr_data')->nullable();    // raw QR code string (for collector scans)
            $table->string('detected_waste')->nullable();   // e.g. "Plastic Bottle"
            $table->string('waste_category')->nullable();   // organic | recyclable | e-waste | inert
            $table->decimal('confidence', 5, 2)->nullable(); // ML confidence score (0-100)
            $table->integer('points_awarded')->default(0);
            $table->boolean('is_verified')->default(false); // verified by DDC staff?
            $table->string('verified_by')->nullable();      // DDC staff name or ID
            $table->json('ml_raw_response')->nullable();    // full ML API response saved
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'scan_type']);
            $table->index('pickup_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_scans');
    }
};
