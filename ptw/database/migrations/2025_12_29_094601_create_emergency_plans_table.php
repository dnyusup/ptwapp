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
        Schema::create('emergency_plans', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number');
            $table->text('kontaminasi_keadaan')->nullable();
            
            // Checkbox fields for emergency planning
            $table->boolean('petugas_tanggap_darurat')->default(false);
            $table->boolean('cara_meminta_bantuan')->default(false);
            $table->boolean('sarana_akses_aman')->default(false);
            $table->boolean('orang_diselamatkan_aman')->default(false);
            $table->boolean('tata_graha_keadaan_baik')->default(false);
            $table->boolean('lokasi_titik_panggilan')->default(false);
            $table->boolean('ketersediaan_petugas_pertolongan')->default(false);
            $table->boolean('ketersediaan_defibrilator')->default(false);
            $table->boolean('ketersediaan_media_pemadam')->default(false);
            $table->boolean('kebutuhan_alat_pernapasan')->default(false);
            $table->boolean('apd_khusus_lainnya')->default(false);
            
            $table->text('sebutkan_apd')->nullable();
            
            // Additional emergency questions
            $table->boolean('alat_ukur_gas_dikalibrasi')->default(false);
            $table->boolean('peralatan_keselamatan_khusus')->default(false);
            
            $table->text('alat_keselamatan_digunakan')->nullable();
            $table->text('deskripsi_rencana_penyelamatan')->nullable();
            
            $table->enum('status', ['draft', 'completed', 'approved'])->default('draft');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users');
            $table->index('permit_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_plans');
    }
};
