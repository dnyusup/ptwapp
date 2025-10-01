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
            // Visibility conditions
            $table->boolean('visibility_clear')->default(false);
            $table->boolean('visibility_moderate')->default(false);
            $table->boolean('visibility_limited')->default(false);
            $table->boolean('visibility_foggy')->default(false);
            
            // Rain conditions
            $table->boolean('rain_none')->default(false);
            $table->boolean('rain_light')->default(false);
            $table->boolean('rain_heavy')->default(false);
            $table->boolean('rain_storm')->default(false);
            
            // Surface conditions
            $table->boolean('surface_dry')->default(false);
            $table->boolean('surface_wet')->default(false);
            $table->boolean('surface_slippery')->default(false);
            
            // Wind conditions
            $table->boolean('wind_none')->default(false);
            $table->boolean('wind_light')->default(false);
            $table->boolean('wind_moderate')->default(false);
            $table->boolean('wind_strong')->default(false);
            
            // Chemical spill conditions
            $table->boolean('chemical_spill_yes')->default(false);
            $table->boolean('chemical_spill_no')->default(false);
            
            // Other environmental conditions
            $table->text('environment_other_conditions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            $table->dropColumn([
                'visibility_clear',
                'visibility_moderate', 
                'visibility_limited',
                'visibility_foggy',
                'rain_none',
                'rain_light',
                'rain_heavy',
                'rain_storm',
                'surface_dry',
                'surface_wet',
                'surface_slippery',
                'wind_none',
                'wind_light',
                'wind_moderate',
                'wind_strong',
                'chemical_spill_yes',
                'chemical_spill_no',
                'environment_other_conditions'
            ]);
        });
    }
};
