<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address');
            }

            if (! Schema::hasColumn('users', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });

        Schema::table('waste_collectors', function (Blueprint $table) {
            if (! Schema::hasColumn('waste_collectors', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('current_lng');
            }

            if (! Schema::hasColumn('waste_collectors', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });

        Schema::table('pickups', function (Blueprint $table) {
            if (! Schema::hasColumn('pickups', 'qr_token')) {
                $table->string('qr_token')->nullable()->after('status');
            }

            if (! Schema::hasColumn('pickups', 'qr_verified_at')) {
                $table->timestamp('qr_verified_at')->nullable()->after('picked_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pickups', function (Blueprint $table) {
            if (Schema::hasColumn('pickups', 'qr_verified_at')) {
                $table->dropColumn('qr_verified_at');
            }

            if (Schema::hasColumn('pickups', 'qr_token')) {
                $table->dropColumn('qr_token');
            }
        });

        Schema::table('waste_collectors', function (Blueprint $table) {
            if (Schema::hasColumn('waste_collectors', 'longitude')) {
                $table->dropColumn('longitude');
            }

            if (Schema::hasColumn('waste_collectors', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'longitude')) {
                $table->dropColumn('longitude');
            }

            if (Schema::hasColumn('users', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
