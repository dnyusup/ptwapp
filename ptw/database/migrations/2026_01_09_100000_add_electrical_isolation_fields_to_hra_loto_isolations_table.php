<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add Electrical Isolation fields to hra_loto_isolations table
     */
    public function up(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->string('electrical_hv_installation')->nullable()->after('pid_reviewed');
            $table->json('electrical_isolations')->nullable()->after('electrical_hv_installation');
            $table->text('electrical_energy_control_method')->nullable()->after('electrical_isolations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn([
                'electrical_hv_installation',
                'electrical_isolations',
                'electrical_energy_control_method',
            ]);
        });
    }
};
