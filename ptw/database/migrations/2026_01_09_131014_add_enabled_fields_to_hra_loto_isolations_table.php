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
            $table->boolean('electrical_enabled')->nullable()->default(false)->after('pid_reviewed');
            $table->boolean('mechanical_enabled')->nullable()->default(false)->after('electrical_energy_control_method');
            $table->boolean('process_enabled')->nullable()->default(false)->after('mechanical_energy_control_method');
            $table->boolean('utility_enabled')->nullable()->default(false)->after('process_energy_control_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn(['electrical_enabled', 'mechanical_enabled', 'process_enabled', 'utility_enabled']);
        });
    }
};
