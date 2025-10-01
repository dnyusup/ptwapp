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
            // Convert enum fields to boolean for checkbox implementation
            $table->dropColumn([
                'q1_alternative_considered',
                'q2_equipment_checked',
                'q3_flammable_moved',
                'q4_protected_cover',
                'q5_debris_cleaned',
                'q6_area_inspected',
                'q7_flammable_structures',
                'q8_fire_blanket',
                'q9_valve_drain_covered',
                'q10_isolation_ducting',
                'q11_holes_sealed',
                'q12_ventilation_adequate',
                'q13_electrical_protected',
                'q14_equipment_protected',
                'q15_overhead_protection',
                'q16_area_marked',
                'q17_gas_monitoring'
            ]);
            
            // Add boolean fields for checkboxes
            $table->boolean('q1_alternative_considered')->default(false)->after('work_description');
            $table->boolean('q2_equipment_checked')->default(false)->after('q1_alternative_considered');
            $table->boolean('q3_flammable_moved')->default(false)->after('q2_equipment_checked');
            $table->boolean('q4_protected_cover')->default(false)->after('q3_distance');
            $table->boolean('q5_debris_cleaned')->default(false)->after('q4_protected_cover');
            $table->boolean('q6_area_inspected')->default(false)->after('q5_debris_cleaned');
            $table->boolean('q7_flammable_structures')->default(false)->after('q6_area_inspected');
            $table->boolean('q8_fire_blanket')->default(false)->after('q7_actions_taken');
            $table->boolean('q9_valve_drain_covered')->default(false)->after('q8_fire_blanket');
            $table->boolean('q10_isolation_ducting')->default(false)->after('q9_valve_drain_covered');
            $table->boolean('q11_holes_sealed')->default(false)->after('q10_isolation_ducting');
            $table->boolean('q12_ventilation_adequate')->default(false)->after('q12_artificial_ventilation');
            $table->boolean('q13_electrical_protected')->default(false)->after('q12_ventilation_adequate');
            $table->boolean('q14_equipment_protected')->default(false)->after('q13_electrical_protected');
            $table->boolean('q15_overhead_protection')->default(false)->after('q14_equipment_protected');
            $table->boolean('q16_area_marked')->default(false)->after('q15_overhead_protection');
            $table->boolean('q17_gas_monitoring')->default(false)->after('q16_area_marked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            // Revert to enum fields
            $table->dropColumn([
                'q1_alternative_considered',
                'q2_equipment_checked',
                'q3_flammable_moved',
                'q4_protected_cover',
                'q5_debris_cleaned',
                'q6_area_inspected',
                'q7_flammable_structures',
                'q8_fire_blanket',
                'q9_valve_drain_covered',
                'q10_isolation_ducting',
                'q11_holes_sealed',
                'q12_ventilation_adequate',
                'q13_electrical_protected',
                'q14_equipment_protected',
                'q15_overhead_protection',
                'q16_area_marked',
                'q17_gas_monitoring'
            ]);
            
            // Add back enum fields
            $table->enum('q1_alternative_considered', ['ya', 'tidak'])->nullable()->after('work_description');
            $table->enum('q2_equipment_checked', ['ya', 'tidak'])->nullable()->after('q1_alternative_considered');
            $table->enum('q3_flammable_moved', ['ya', 'tidak'])->nullable()->after('q2_equipment_checked');
            $table->enum('q4_protected_cover', ['ya', 'tidak'])->nullable()->after('q3_distance');
            $table->enum('q5_debris_cleaned', ['ya', 'tidak'])->nullable()->after('q4_protected_cover');
            $table->enum('q6_area_inspected', ['ya', 'tidak'])->nullable()->after('q5_debris_cleaned');
            $table->enum('q7_flammable_structures', ['ya', 'tidak'])->nullable()->after('q6_area_inspected');
            $table->enum('q8_fire_blanket', ['ya', 'tidak'])->nullable()->after('q7_actions_taken');
            $table->enum('q9_valve_drain_covered', ['ya', 'tidak'])->nullable()->after('q8_fire_blanket');
            $table->enum('q10_isolation_ducting', ['ya', 'tidak'])->nullable()->after('q9_valve_drain_covered');
            $table->enum('q11_holes_sealed', ['ya', 'tidak'])->nullable()->after('q10_isolation_ducting');
            $table->enum('q12_ventilation_adequate', ['ya', 'tidak'])->nullable()->after('q12_artificial_ventilation');
            $table->enum('q13_electrical_protected', ['ya', 'tidak'])->nullable()->after('q12_ventilation_adequate');
            $table->enum('q14_equipment_protected', ['ya', 'tidak'])->nullable()->after('q13_electrical_protected');
            $table->enum('q15_overhead_protection', ['ya', 'tidak'])->nullable()->after('q14_equipment_protected');
            $table->enum('q16_area_marked', ['ya', 'tidak'])->nullable()->after('q15_overhead_protection');
            $table->enum('q17_gas_monitoring', ['ya', 'tidak'])->nullable()->after('q16_area_marked');
        });
    }
};
