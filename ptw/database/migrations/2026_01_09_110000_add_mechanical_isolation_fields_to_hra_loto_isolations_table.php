<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add Mechanical Isolation fields to hra_loto_isolations table
     */
    public function up(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Mechanical Isolation yes/no questions
            $table->string('mechanical_gravitasi')->nullable()->after('electrical_energy_control_method');
            $table->string('mechanical_hidrolik')->nullable()->after('mechanical_gravitasi');
            $table->string('mechanical_kelembaman')->nullable()->after('mechanical_hidrolik');
            $table->string('mechanical_spring')->nullable()->after('mechanical_kelembaman');
            $table->string('mechanical_pneumatik')->nullable()->after('mechanical_spring');
            $table->string('mechanical_lainnya')->nullable()->after('mechanical_pneumatik');
            // Mechanical Isolation table data (JSON)
            $table->json('mechanical_isolations')->nullable()->after('mechanical_lainnya');
            // Energy control method
            $table->text('mechanical_energy_control_method')->nullable()->after('mechanical_isolations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn([
                'mechanical_gravitasi',
                'mechanical_hidrolik',
                'mechanical_kelembaman',
                'mechanical_spring',
                'mechanical_pneumatik',
                'mechanical_lainnya',
                'mechanical_isolations',
                'mechanical_energy_control_method',
            ]);
        });
    }
};
