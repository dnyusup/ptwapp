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
        Schema::table('permit_to_works', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('permit_to_works', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('permit_to_works', 'end_time')) {
                $table->dropColumn('end_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }
};
