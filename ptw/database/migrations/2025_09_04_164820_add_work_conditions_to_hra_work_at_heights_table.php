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
            // Work Conditions fields
            $table->boolean('area_below_closed')->default(false)->after('emergency_exit_point');
            $table->boolean('work_area_disturbances')->default(false)->after('area_below_closed');
            $table->boolean('ventilation_hazards')->default(false)->after('work_area_disturbances');
            $table->boolean('equipment_protection')->default(false)->after('ventilation_hazards');
            $table->boolean('emergency_exit_available')->default(false)->after('equipment_protection');
            $table->boolean('material_handling')->default(false)->after('emergency_exit_available');
            $table->boolean('safety_personnel_needed')->default(false)->after('material_handling');
            $table->boolean('other_conditions_check')->default(false)->after('safety_personnel_needed');
            $table->text('other_conditions_text')->nullable()->after('other_conditions_check');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            $table->dropColumn([
                'area_below_closed',
                'work_area_disturbances',
                'ventilation_hazards',
                'equipment_protection',
                'emergency_exit_available',
                'material_handling',
                'safety_personnel_needed',
                'other_conditions_check',
                'other_conditions_text'
            ]);
        });
    }
};
