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
            // Add new MEWP fields
            $table->boolean('mobile_elevation_operator_trained')->default(false);
            $table->boolean('mobile_elevation_rescue_person')->default(false);
            $table->boolean('mobile_elevation_monitor_in_place')->default(false);
            $table->boolean('mobile_elevation_legal_inspection')->default(false);
            $table->boolean('mobile_elevation_pre_use_inspection')->default(false);
            
            // Drop old fields
            $table->dropColumn([
                'mobile_elevation_used_before',
                'mobile_elevation_training_provided'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Drop new MEWP fields
            $table->dropColumn([
                'mobile_elevation_operator_trained',
                'mobile_elevation_rescue_person',
                'mobile_elevation_monitor_in_place',
                'mobile_elevation_legal_inspection',
                'mobile_elevation_pre_use_inspection'
            ]);
            
            // Add back old fields
            $table->boolean('mobile_elevation_used_before')->default(false);
            $table->boolean('mobile_elevation_training_provided')->default(false);
        });
    }
};
