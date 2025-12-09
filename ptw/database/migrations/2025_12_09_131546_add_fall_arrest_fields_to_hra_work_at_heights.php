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
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Add new fall arrest fields
            $table->boolean('fall_arrest_worker_trained')->default(false)->after('fall_arrest_used');
            $table->boolean('fall_arrest_legal_inspection')->default(false)->after('fall_arrest_worker_trained');
            $table->boolean('fall_arrest_pre_use_inspection')->default(false)->after('fall_arrest_legal_inspection');
            $table->boolean('fall_arrest_qualified_personnel')->default(false)->after('fall_arrest_pre_use_inspection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Drop the added fields
            $table->dropColumn([
                'fall_arrest_worker_trained',
                'fall_arrest_legal_inspection', 
                'fall_arrest_pre_use_inspection',
                'fall_arrest_qualified_personnel'
            ]);
        });
    }
};
