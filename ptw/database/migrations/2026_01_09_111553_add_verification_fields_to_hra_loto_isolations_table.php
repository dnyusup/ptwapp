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
            $table->string('affected_area', 255)->nullable()->after('utility_energy_control_method');
            $table->string('all_individuals_informed', 10)->nullable()->after('affected_area');
            $table->string('individual_lototo_required', 10)->nullable()->after('all_individuals_informed');
            $table->string('ptw_issuer_lototo_key', 10)->nullable()->after('individual_lototo_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn([
                'affected_area',
                'all_individuals_informed',
                'individual_lototo_required',
                'ptw_issuer_lototo_key'
            ]);
        });
    }
};
