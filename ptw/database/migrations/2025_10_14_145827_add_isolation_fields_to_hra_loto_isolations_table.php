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
            // Machine/Tank name field
            $table->string('machine_tank_name')->nullable();
            
            // Panel Listrik isolation fields
            $table->boolean('panel_listrik_mati')->default(false);
            $table->boolean('panel_listrik_dikunci')->default(false);
            $table->boolean('panel_listrik_diperiksa')->default(false);
            $table->boolean('panel_listrik_dipasang_tag')->default(false);
            
            // Pneumatic isolation fields
            $table->boolean('pneumatic_mati')->default(false);
            $table->boolean('pneumatic_dikunci')->default(false);
            $table->boolean('pneumatic_diperiksa')->default(false);
            $table->boolean('pneumatic_dipasang_tag')->default(false);
            
            // Hydraulic isolation fields
            $table->boolean('hydraulic_mati')->default(false);
            $table->boolean('hydraulic_dikunci')->default(false);
            $table->boolean('hydraulic_diperiksa')->default(false);
            $table->boolean('hydraulic_dipasang_tag')->default(false);
            
            // Gravitasi isolation fields
            $table->boolean('gravitasi_mati')->default(false);
            $table->boolean('gravitasi_dikunci')->default(false);
            $table->boolean('gravitasi_diperiksa')->default(false);
            $table->boolean('gravitasi_dipasang_tag')->default(false);
            
            // Spring/Per isolation fields
            $table->boolean('spring_per_mati')->default(false);
            $table->boolean('spring_per_dikunci')->default(false);
            $table->boolean('spring_per_diperiksa')->default(false);
            $table->boolean('spring_per_dipasang_tag')->default(false);
            
            // Rotasi/Gerakan isolation fields
            $table->boolean('rotasi_gerakan_mati')->default(false);
            $table->boolean('rotasi_gerakan_dikunci')->default(false);
            $table->boolean('rotasi_gerakan_diperiksa')->default(false);
            $table->boolean('rotasi_gerakan_dipasang_tag')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Drop machine/tank name field
            $table->dropColumn('machine_tank_name');
            
            // Drop Panel Listrik isolation fields
            $table->dropColumn(['panel_listrik_mati', 'panel_listrik_dikunci', 'panel_listrik_diperiksa', 'panel_listrik_dipasang_tag']);
            
            // Drop Pneumatic isolation fields
            $table->dropColumn(['pneumatic_mati', 'pneumatic_dikunci', 'pneumatic_diperiksa', 'pneumatic_dipasang_tag']);
            
            // Drop Hydraulic isolation fields
            $table->dropColumn(['hydraulic_mati', 'hydraulic_dikunci', 'hydraulic_diperiksa', 'hydraulic_dipasang_tag']);
            
            // Drop Gravitasi isolation fields
            $table->dropColumn(['gravitasi_mati', 'gravitasi_dikunci', 'gravitasi_diperiksa', 'gravitasi_dipasang_tag']);
            
            // Drop Spring/Per isolation fields
            $table->dropColumn(['spring_per_mati', 'spring_per_dikunci', 'spring_per_diperiksa', 'spring_per_dipasang_tag']);
            
            // Drop Rotasi/Gerakan isolation fields
            $table->dropColumn(['rotasi_gerakan_mati', 'rotasi_gerakan_dikunci', 'rotasi_gerakan_diperiksa', 'rotasi_gerakan_dipasang_tag']);
        });
    }
};
