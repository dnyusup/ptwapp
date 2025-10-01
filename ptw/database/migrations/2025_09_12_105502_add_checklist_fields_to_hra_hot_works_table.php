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
            // Hot Work Safety Checklist Fields
            $table->enum('q1_alternative_considered', ['ya', 'tidak'])->nullable()->after('work_description');
            $table->enum('q2_equipment_checked', ['ya', 'tidak'])->nullable()->after('q1_alternative_considered');
            $table->enum('q3_flammable_moved', ['ya', 'tidak'])->nullable()->after('q2_equipment_checked');
            $table->integer('q3_distance')->nullable()->after('q3_flammable_moved');
            $table->enum('q4_protected_cover', ['ya', 'tidak'])->nullable()->after('q3_distance');
            $table->enum('q5_debris_cleaned', ['ya', 'tidak'])->nullable()->after('q4_protected_cover');
            $table->enum('q6_area_inspected', ['ya', 'tidak'])->nullable()->after('q5_debris_cleaned');
            $table->enum('q7_flammable_structures', ['ya', 'tidak'])->nullable()->after('q6_area_inspected');
            $table->text('q7_actions_taken')->nullable()->after('q7_flammable_structures');
            $table->enum('q8_fire_blanket', ['ya', 'tidak'])->nullable()->after('q7_actions_taken');
            $table->enum('q9_valve_drain_covered', ['ya', 'tidak'])->nullable()->after('q8_fire_blanket');
            $table->enum('q10_isolation_ducting', ['ya', 'tidak'])->nullable()->after('q9_valve_drain_covered');
            $table->enum('q11_holes_sealed', ['ya', 'tidak'])->nullable()->after('q10_isolation_ducting');
            $table->boolean('q12_natural_ventilation')->default(false)->after('q11_holes_sealed');
            $table->boolean('q12_artificial_ventilation')->default(false)->after('q12_natural_ventilation');
            $table->enum('q12_ventilation_adequate', ['ya', 'tidak'])->nullable()->after('q12_artificial_ventilation');
            $table->enum('q13_electrical_protected', ['ya', 'tidak'])->nullable()->after('q12_ventilation_adequate');
            $table->enum('q14_equipment_protected', ['ya', 'tidak'])->nullable()->after('q13_electrical_protected');
            $table->enum('q15_overhead_protection', ['ya', 'tidak'])->nullable()->after('q14_equipment_protected');
            $table->enum('q16_area_marked', ['ya', 'tidak'])->nullable()->after('q15_overhead_protection');
            $table->enum('q17_gas_monitoring', ['ya', 'tidak'])->nullable()->after('q16_area_marked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn([
                'q1_alternative_considered',
                'q2_equipment_checked', 
                'q3_flammable_moved',
                'q3_distance',
                'q4_protected_cover',
                'q5_debris_cleaned',
                'q6_area_inspected',
                'q7_flammable_structures',
                'q7_actions_taken',
                'q8_fire_blanket',
                'q9_valve_drain_covered',
                'q10_isolation_ducting',
                'q11_holes_sealed',
                'q12_natural_ventilation',
                'q12_artificial_ventilation',
                'q12_ventilation_adequate',
                'q13_electrical_protected',
                'q14_equipment_protected',
                'q15_overhead_protection',
                'q16_area_marked',
                'q17_gas_monitoring'
            ]);
        });
    }
};
