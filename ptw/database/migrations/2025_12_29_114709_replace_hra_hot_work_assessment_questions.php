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
            // Drop all old assessment fields
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
                'q12_ventilation_type',
                'q12_natural_ventilation',
                'q12_artificial_ventilation',
                'q12_ventilation_adequate',
                'q13_electrical_protected',
                'q14_equipment_protected',
                'q15_overhead_protection',
                'q16_area_marked',
                'q17_gas_monitoring'
            ]);
            
            // Add new assessment fields based on new questions
            // Section 1: Persyaratan dalam jarak 11m/35ft
            $table->boolean('flammable_materials_removed')->nullable()->comment('Semua bahan yang mudah terbakar disingkirkan atau dilindungi');
            $table->boolean('flammable_liquids_removed')->nullable()->comment('Cairan mudah terbakar, debu, serat, dan endapan minyak dihilangkan');
            $table->boolean('flammable_floors_wetted')->nullable()->comment('Lantai yang mudah terbakar dibasahi, ditutup dengan pasir basah');
            $table->boolean('walls_ceiling_protected')->nullable()->comment('Dinding/langit-langit/atap yang mudah terbakar dilindungi');
            $table->boolean('floors_swept_clean')->nullable()->comment('Lantai disapu bersih dari bahan yang mudah terbakar');
            $table->boolean('materials_other_side_removed')->nullable()->comment('Material mudah terbakar di sisi lain dinding/langit-langit/atap disingkirkan');
            $table->boolean('explosive_atmosphere_removed')->nullable()->comment('Atmosfer yang mudah meledak dihilangkan');
            $table->boolean('wall_floor_openings_covered')->nullable()->comment('Semua bukaan dinding/lantai ditutup dengan penutup tahan api');
            $table->boolean('ducts_conveyors_protected')->nullable()->comment('Saluran, konveyor, katup/saluran pembuangan yang terbuka dilindungi');
            $table->boolean('fire_risk_prevention_applied')->nullable()->comment('Jika ada risiko kebakaran dari konduksi/radiasi, tindakan pencegahan tambahan diterapkan');
            
            // Section 2: Persyaratan saat bekerja pada peralatan tertutup
            $table->boolean('equipment_cleaned_flammable')->nullable()->comment('Peralatan dibersihkan dari semua bahan yang mudah terbakar');
            $table->boolean('containers_emptied_cleaned')->nullable()->comment('Wadah dikosongkan, dibersihkan, dan diuji bebas dari cairan dan uap mudah terbakar');
            
            // Section 3: Panel bangunan/material
            $table->boolean('building_materials_non_flammable')->nullable()->comment('Panel bangunan/material yang sedang dikerjakan diketahui tidak mudah terbakar');
            $table->boolean('flammable_materials_cut_protected')->nullable()->comment('Bahan mudah terbakar dipotong minimal 50 cm dan dilindungi');
            
            // Section 4: Ventilasi
            $table->string('ventilation_type')->nullable()->comment('Ventilasi yang cukup (alami/buatan)');
            $table->boolean('ventilation_adequate')->nullable()->comment('Ventilasi yang cukup di tempat kerja');
            
            // Section 5: Lampu tiup dan tabung gas
            $table->boolean('gas_lamps_open_area')->nullable()->comment('Lampu tiup dan tabung gas dipasang di area terbuka dan berventilasi baik');
            
            // Section 6: Peralatan dan pengelasan
            $table->boolean('equipment_installed_monitored')->nullable()->comment('Semua peralatan telah dipasang dan pengalasan dimonitor dalam kondisi baik');
            
            // Section 7: Pemberitahuan pekerja
            $table->boolean('workers_notified')->nullable()->comment('Semua pekerja diberitahu tentang pekerjaan panas yang sedang dilakukan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn([
                'flammable_materials_removed',
                'flammable_liquids_removed', 
                'flammable_floors_wetted',
                'walls_ceiling_protected',
                'floors_swept_clean',
                'materials_other_side_removed',
                'explosive_atmosphere_removed',
                'wall_floor_openings_covered',
                'ducts_conveyors_protected',
                'fire_risk_prevention_applied',
                'equipment_cleaned_flammable',
                'containers_emptied_cleaned',
                'building_materials_non_flammable',
                'flammable_materials_cut_protected',
                'ventilation_type',
                'ventilation_adequate',
                'gas_lamps_open_area',
                'equipment_installed_monitored',
                'workers_notified'
            ]);
            
            // Restore old fields (simplified version for rollback)
            $table->boolean('q1_alternative_considered')->nullable();
            $table->boolean('q2_equipment_checked')->nullable();
            $table->boolean('q3_flammable_moved')->nullable();
            $table->integer('q3_distance')->nullable();
            $table->boolean('q4_protected_cover')->nullable();
            $table->boolean('q5_debris_cleaned')->nullable();
            $table->boolean('q6_area_inspected')->nullable();
            $table->boolean('q7_flammable_structures')->nullable();
            $table->text('q7_actions_taken')->nullable();
            $table->boolean('q8_fire_blanket')->nullable();
            $table->boolean('q9_valve_drain_covered')->nullable();
            $table->boolean('q10_isolation_ducting')->nullable();
            $table->boolean('q11_holes_sealed')->nullable();
            $table->string('q12_ventilation_type')->nullable();
            $table->boolean('q12_natural_ventilation')->nullable();
            $table->boolean('q12_artificial_ventilation')->nullable();
            $table->boolean('q12_ventilation_adequate')->nullable();
            $table->boolean('q13_electrical_protected')->nullable();
            $table->boolean('q14_equipment_protected')->nullable();
            $table->boolean('q15_overhead_protection')->nullable();
            $table->boolean('q16_area_marked')->nullable();
            $table->boolean('q17_gas_monitoring')->nullable();
        });
    }
};
