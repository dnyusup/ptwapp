<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration removes all form fields except Basic Information
     * as part of the HRA LOTO form revision.
     */
    public function up(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Drop Isolasi Mesin/Tangki columns
            $columns = [
                'machine_tank_name',
                
                // Panel Listrik
                'panel_listrik_mati',
                'panel_listrik_dikunci',
                'panel_listrik_diperiksa',
                'panel_listrik_dipasang_tag',
                
                // Pneumatic
                'pneumatic_mati',
                'pneumatic_dikunci',
                'pneumatic_diperiksa',
                'pneumatic_dipasang_tag',
                
                // Hydraulic
                'hydraulic_mati',
                'hydraulic_dikunci',
                'hydraulic_diperiksa',
                'hydraulic_dipasang_tag',
                
                // Gravitasi
                'gravitasi_mati',
                'gravitasi_dikunci',
                'gravitasi_diperiksa',
                'gravitasi_dipasang_tag',
                
                // Spring/Per
                'spring_per_mati',
                'spring_per_dikunci',
                'spring_per_diperiksa',
                'spring_per_dipasang_tag',
                
                // Rotasi/Gerakan
                'rotasi_gerakan_mati',
                'rotasi_gerakan_dikunci',
                'rotasi_gerakan_diperiksa',
                'rotasi_gerakan_dipasang_tag',
                
                // Isolasi Listrik - Panel Listrik
                'bekerja_panel_listrik',
                'referensi_manual_panel',
                'saklar_diposisi_off',
                'tag_dipasang_panel',
                'sekring_cb_dimatikan',
                'panel_off_panel',
                
                // Isolasi Listrik - Sistem Mekanis
                'bekerja_sistem_mekanis',
                'referensi_manual_sistem',
                'safety_switch_off',
                'tag_dipasang_sistem',
                'sekring_cb_sistem_dimatikan',
                'sudah_dicoba_dinyalakan',
                
                // Tes Listrik
                'membutuhkan_tes_listrik_on',
                'safety_barrier',
                'full_face_protection',
                'insulated_gloves',
                'insulated_mat',
                'full_length_sleeves',
                'tool_insulation_satisfactory',
                'maximum_voltage',
                'alasan_live_test',
                
                // Isolasi Utility
                'utility_listrik_off',
                'utility_listrik_secured',
                'utility_listrik_checked',
                'utility_listrik_tagged',
                'utility_cooling_water_off',
                'utility_cooling_water_secured',
                'utility_cooling_water_checked',
                'utility_cooling_water_tagged',
                'utility_oil_hidrolik_off',
                'utility_oil_hidrolik_secured',
                'utility_oil_hidrolik_checked',
                'utility_oil_hidrolik_tagged',
                'utility_kompresor_off',
                'utility_kompresor_secured',
                'utility_kompresor_checked',
                'utility_kompresor_tagged',
                'utility_vacuum_off',
                'utility_vacuum_secured',
                'utility_vacuum_checked',
                'utility_vacuum_tagged',
                'utility_gas_off',
                'utility_gas_secured',
                'utility_gas_checked',
                'utility_gas_tagged',
                'utility_lainnya_nama',
                'utility_lainnya_off',
                'utility_lainnya_secured',
                'utility_lainnya_checked',
                'utility_lainnya_tagged',
                'line_diisolasi_plat',
                'alasan_deskripsi_isolasi',
                'area_terdampak_isolasi',
                'area_sudah_diberitahu',
                
                // Mematikan Pipa
                'isi_line_pipa',
                'tidak_ada_sisa_tekanan',
                'drain_bleed_valves',
                'pipa_purged_udara',
                'pipa_purged_air',
                'pipa_purged_nitrogen',
                'pipa_diisolasi_plat',
                'pipa_kosong',
                'pipa_bersih',
                'alasan_deskripsi_isolasi_pipa',
            ];
            
            // Drop columns that exist
            foreach ($columns as $column) {
                if (Schema::hasColumn('hra_loto_isolations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Isolasi Mesin/Tangki
            $table->string('machine_tank_name')->nullable();
            
            // Panel Listrik
            $table->boolean('panel_listrik_mati')->default(false);
            $table->boolean('panel_listrik_dikunci')->default(false);
            $table->boolean('panel_listrik_diperiksa')->default(false);
            $table->boolean('panel_listrik_dipasang_tag')->default(false);
            
            // Pneumatic
            $table->boolean('pneumatic_mati')->default(false);
            $table->boolean('pneumatic_dikunci')->default(false);
            $table->boolean('pneumatic_diperiksa')->default(false);
            $table->boolean('pneumatic_dipasang_tag')->default(false);
            
            // Hydraulic
            $table->boolean('hydraulic_mati')->default(false);
            $table->boolean('hydraulic_dikunci')->default(false);
            $table->boolean('hydraulic_diperiksa')->default(false);
            $table->boolean('hydraulic_dipasang_tag')->default(false);
            
            // Gravitasi
            $table->boolean('gravitasi_mati')->default(false);
            $table->boolean('gravitasi_dikunci')->default(false);
            $table->boolean('gravitasi_diperiksa')->default(false);
            $table->boolean('gravitasi_dipasang_tag')->default(false);
            
            // Spring/Per
            $table->boolean('spring_per_mati')->default(false);
            $table->boolean('spring_per_dikunci')->default(false);
            $table->boolean('spring_per_diperiksa')->default(false);
            $table->boolean('spring_per_dipasang_tag')->default(false);
            
            // Rotasi/Gerakan
            $table->boolean('rotasi_gerakan_mati')->default(false);
            $table->boolean('rotasi_gerakan_dikunci')->default(false);
            $table->boolean('rotasi_gerakan_diperiksa')->default(false);
            $table->boolean('rotasi_gerakan_dipasang_tag')->default(false);
            
            // Isolasi Listrik - Panel Listrik
            $table->string('bekerja_panel_listrik')->nullable();
            $table->string('referensi_manual_panel')->nullable();
            $table->string('saklar_diposisi_off')->nullable();
            $table->string('tag_dipasang_panel')->nullable();
            $table->string('sekring_cb_dimatikan')->nullable();
            $table->string('panel_off_panel')->nullable();
            
            // Isolasi Listrik - Sistem Mekanis
            $table->string('bekerja_sistem_mekanis')->nullable();
            $table->string('referensi_manual_sistem')->nullable();
            $table->string('safety_switch_off')->nullable();
            $table->string('tag_dipasang_sistem')->nullable();
            $table->string('sekring_cb_sistem_dimatikan')->nullable();
            $table->string('sudah_dicoba_dinyalakan')->nullable();
            
            // Tes Listrik
            $table->string('membutuhkan_tes_listrik_on')->nullable();
            $table->string('safety_barrier')->nullable();
            $table->string('full_face_protection')->nullable();
            $table->string('insulated_gloves')->nullable();
            $table->string('insulated_mat')->nullable();
            $table->string('full_length_sleeves')->nullable();
            $table->string('tool_insulation_satisfactory')->nullable();
            $table->integer('maximum_voltage')->nullable();
            $table->text('alasan_live_test')->nullable();
            
            // Isolasi Utility
            $table->boolean('utility_listrik_off')->default(false);
            $table->boolean('utility_listrik_secured')->default(false);
            $table->boolean('utility_listrik_checked')->default(false);
            $table->boolean('utility_listrik_tagged')->default(false);
            $table->boolean('utility_cooling_water_off')->default(false);
            $table->boolean('utility_cooling_water_secured')->default(false);
            $table->boolean('utility_cooling_water_checked')->default(false);
            $table->boolean('utility_cooling_water_tagged')->default(false);
            $table->boolean('utility_oil_hidrolik_off')->default(false);
            $table->boolean('utility_oil_hidrolik_secured')->default(false);
            $table->boolean('utility_oil_hidrolik_checked')->default(false);
            $table->boolean('utility_oil_hidrolik_tagged')->default(false);
            $table->boolean('utility_kompresor_off')->default(false);
            $table->boolean('utility_kompresor_secured')->default(false);
            $table->boolean('utility_kompresor_checked')->default(false);
            $table->boolean('utility_kompresor_tagged')->default(false);
            $table->boolean('utility_vacuum_off')->default(false);
            $table->boolean('utility_vacuum_secured')->default(false);
            $table->boolean('utility_vacuum_checked')->default(false);
            $table->boolean('utility_vacuum_tagged')->default(false);
            $table->boolean('utility_gas_off')->default(false);
            $table->boolean('utility_gas_secured')->default(false);
            $table->boolean('utility_gas_checked')->default(false);
            $table->boolean('utility_gas_tagged')->default(false);
            $table->string('utility_lainnya_nama')->nullable();
            $table->boolean('utility_lainnya_off')->default(false);
            $table->boolean('utility_lainnya_secured')->default(false);
            $table->boolean('utility_lainnya_checked')->default(false);
            $table->boolean('utility_lainnya_tagged')->default(false);
            $table->string('line_diisolasi_plat')->nullable();
            $table->text('alasan_deskripsi_isolasi')->nullable();
            $table->string('area_terdampak_isolasi')->nullable();
            $table->string('area_sudah_diberitahu')->nullable();
            
            // Mematikan Pipa
            $table->string('isi_line_pipa')->nullable();
            $table->string('tidak_ada_sisa_tekanan')->nullable();
            $table->string('drain_bleed_valves')->nullable();
            $table->boolean('pipa_purged_udara')->default(false);
            $table->boolean('pipa_purged_air')->default(false);
            $table->boolean('pipa_purged_nitrogen')->default(false);
            $table->string('pipa_diisolasi_plat')->nullable();
            $table->string('pipa_kosong')->nullable();
            $table->string('pipa_bersih')->nullable();
            $table->text('alasan_deskripsi_isolasi_pipa')->nullable();
        });
    }
};
