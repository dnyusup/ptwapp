<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add Process Isolation fields to hra_loto_isolations table
     */
    public function up(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Process Isolation table data (JSON)
            $table->json('process_isolations')->nullable()->after('mechanical_energy_control_method');
            // Energy control method
            $table->text('process_energy_control_method')->nullable()->after('process_isolations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn([
                'process_isolations',
                'process_energy_control_method',
            ]);
        });
    }
};
