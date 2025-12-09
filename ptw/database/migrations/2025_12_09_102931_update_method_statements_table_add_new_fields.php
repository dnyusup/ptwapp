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
        Schema::table('method_statements', function (Blueprint $table) {
            // Add new fields replacing the old ones
            $table->text('safe_access_explanation')->nullable();
            $table->text('ppe_safety_equipment_explanation')->nullable();
            $table->text('qualifications_training_explanation')->nullable();
            $table->text('safe_routes_identification_explanation')->nullable();
            $table->text('storage_security_explanation')->nullable();
            $table->text('equipment_checklist_explanation')->nullable();
            $table->text('work_order_explanation')->nullable();
            $table->text('temporary_work_explanation')->nullable();
            $table->text('weather_conditions_explanation')->nullable();
            $table->text('area_maintenance_explanation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('method_statements', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'safe_access_explanation',
                'ppe_safety_equipment_explanation',
                'qualifications_training_explanation',
                'safe_routes_identification_explanation',
                'storage_security_explanation',
                'equipment_checklist_explanation',
                'work_order_explanation',
                'temporary_work_explanation',
                'weather_conditions_explanation',
                'area_maintenance_explanation'
            ]);
        });
    }
};
