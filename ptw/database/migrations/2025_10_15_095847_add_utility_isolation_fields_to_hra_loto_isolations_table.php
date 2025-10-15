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
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Utility isolation matrix fields - Listrik
            $table->boolean('utility_listrik_off')->default(false);
            $table->boolean('utility_listrik_secured')->default(false);
            $table->boolean('utility_listrik_checked')->default(false);
            $table->boolean('utility_listrik_tagged')->default(false);
            
            // Cooling water
            $table->boolean('utility_cooling_water_off')->default(false);
            $table->boolean('utility_cooling_water_secured')->default(false);
            $table->boolean('utility_cooling_water_checked')->default(false);
            $table->boolean('utility_cooling_water_tagged')->default(false);
            
            // Oil Hidrolik
            $table->boolean('utility_oil_hidrolik_off')->default(false);
            $table->boolean('utility_oil_hidrolik_secured')->default(false);
            $table->boolean('utility_oil_hidrolik_checked')->default(false);
            $table->boolean('utility_oil_hidrolik_tagged')->default(false);
            
            // Kompresor
            $table->boolean('utility_kompresor_off')->default(false);
            $table->boolean('utility_kompresor_secured')->default(false);
            $table->boolean('utility_kompresor_checked')->default(false);
            $table->boolean('utility_kompresor_tagged')->default(false);
            
            // Vacuum
            $table->boolean('utility_vacuum_off')->default(false);
            $table->boolean('utility_vacuum_secured')->default(false);
            $table->boolean('utility_vacuum_checked')->default(false);
            $table->boolean('utility_vacuum_tagged')->default(false);
            
            // Gas
            $table->boolean('utility_gas_off')->default(false);
            $table->boolean('utility_gas_secured')->default(false);
            $table->boolean('utility_gas_checked')->default(false);
            $table->boolean('utility_gas_tagged')->default(false);
            
            // Lainnya
            $table->string('utility_lainnya_nama')->nullable();
            $table->boolean('utility_lainnya_off')->default(false);
            $table->boolean('utility_lainnya_secured')->default(false);
            $table->boolean('utility_lainnya_checked')->default(false);
            $table->boolean('utility_lainnya_tagged')->default(false);
            
            // Additional fields
            $table->string('line_diisolasi_plat')->nullable();
            $table->text('alasan_deskripsi_isolasi')->nullable();
            $table->string('area_terdampak_isolasi')->nullable();
            $table->string('area_sudah_diberitahu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Drop utility isolation fields
            $table->dropColumn([
                // Listrik
                'utility_listrik_off',
                'utility_listrik_secured',
                'utility_listrik_checked',
                'utility_listrik_tagged',
                
                // Cooling water
                'utility_cooling_water_off',
                'utility_cooling_water_secured',
                'utility_cooling_water_checked',
                'utility_cooling_water_tagged',
                
                // Oil Hidrolik
                'utility_oil_hidrolik_off',
                'utility_oil_hidrolik_secured',
                'utility_oil_hidrolik_checked',
                'utility_oil_hidrolik_tagged',
                
                // Kompresor
                'utility_kompresor_off',
                'utility_kompresor_secured',
                'utility_kompresor_checked',
                'utility_kompresor_tagged',
                
                // Vacuum
                'utility_vacuum_off',
                'utility_vacuum_secured',
                'utility_vacuum_checked',
                'utility_vacuum_tagged',
                
                // Gas
                'utility_gas_off',
                'utility_gas_secured',
                'utility_gas_checked',
                'utility_gas_tagged',
                
                // Lainnya
                'utility_lainnya_nama',
                'utility_lainnya_off',
                'utility_lainnya_secured',
                'utility_lainnya_checked',
                'utility_lainnya_tagged',
                
                // Additional fields
                'line_diisolasi_plat',
                'alasan_deskripsi_isolasi',
                'area_terdampak_isolasi',
                'area_sudah_diberitahu'
            ]);
        });
    }
};
