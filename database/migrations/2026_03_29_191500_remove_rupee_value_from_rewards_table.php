<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('rewards', 'rupee_value')) {
            Schema::table('rewards', function (Blueprint $table) {
                $table->dropColumn('rupee_value');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('rewards', 'rupee_value')) {
            Schema::table('rewards', function (Blueprint $table) {
                $table->decimal('rupee_value', 8, 2)->nullable()->after('points_required');
            });
        }
    }
};
