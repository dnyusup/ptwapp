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
            // Add new work conditions fields
            $table->boolean('workers_have_training_proof')->default(false);
            $table->boolean('area_below_blocked')->default(false);
            $table->boolean('workers_below_present')->default(false);
            $table->boolean('floor_suitable_for_access_equipment')->default(false);
            $table->boolean('obstacles_near_work_location')->default(false);
            $table->boolean('ventilation_hazardous_emissions')->default(false);
            $table->boolean('protection_needed_for_equipment')->default(false);
            $table->boolean('safe_access_exit_method')->default(false);
            $table->boolean('safe_material_handling_method')->default(false);
            $table->boolean('emergency_escape_plan_needed')->default(false);
            
            // Drop old work conditions fields
            $table->dropColumn([
                'area_below_closed',
                'work_area_disturbances',
                'ventilation_hazards',
                'equipment_protection',
                'emergency_exit_available',
                'material_handling',
                'safety_personnel_needed'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Restore old fields
            $table->boolean('area_below_closed')->default(false);
            $table->boolean('work_area_disturbances')->default(false);
            $table->boolean('ventilation_hazards')->default(false);
            $table->boolean('equipment_protection')->default(false);
            $table->boolean('emergency_exit_available')->default(false);
            $table->boolean('material_handling')->default(false);
            $table->boolean('safety_personnel_needed')->default(false);
            
            // Drop new fields
            $table->dropColumn([
                'workers_have_training_proof',
                'area_below_blocked',
                'workers_below_present',
                'floor_suitable_for_access_equipment',
                'obstacles_near_work_location',
                'ventilation_hazardous_emissions',
                'protection_needed_for_equipment',
                'safe_access_exit_method',
                'safe_material_handling_method',
                'emergency_escape_plan_needed'
            ]);
        });
    }
};
