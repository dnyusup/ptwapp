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
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->boolean('hot_work_avoidable')->nullable()->after('work_description');
            $table->boolean('hot_work_designated_area')->nullable()->after('hot_work_avoidable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn(['hot_work_avoidable', 'hot_work_designated_area']);
        });
    }
};
