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
            // Change boolean fields to string for fields that can have N/A value
            $table->string('flammable_floors_wetted', 10)->nullable()->change();
            $table->string('walls_ceiling_protected', 10)->nullable()->change();
            $table->string('wall_floor_openings_covered', 10)->nullable()->change();
            $table->string('ducts_conveyors_protected', 10)->nullable()->change();
            $table->string('fire_risk_prevention_applied', 10)->nullable()->change();
            $table->string('equipment_cleaned_flammable', 10)->nullable()->change();
            $table->string('containers_emptied_cleaned', 10)->nullable()->change();
            $table->string('building_materials_non_flammable', 10)->nullable()->change();
            $table->string('flammable_materials_cut_protected', 10)->nullable()->change();
            $table->string('gas_lamps_open_area', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            // Revert back to boolean
            $table->boolean('flammable_floors_wetted')->nullable()->change();
            $table->boolean('walls_ceiling_protected')->nullable()->change();
            $table->boolean('wall_floor_openings_covered')->nullable()->change();
            $table->boolean('ducts_conveyors_protected')->nullable()->change();
            $table->boolean('fire_risk_prevention_applied')->nullable()->change();
            $table->boolean('equipment_cleaned_flammable')->nullable()->change();
            $table->boolean('containers_emptied_cleaned')->nullable()->change();
            $table->boolean('building_materials_non_flammable')->nullable()->change();
            $table->boolean('flammable_materials_cut_protected')->nullable()->change();
            $table->boolean('gas_lamps_open_area')->nullable()->change();
        });
    }
};
