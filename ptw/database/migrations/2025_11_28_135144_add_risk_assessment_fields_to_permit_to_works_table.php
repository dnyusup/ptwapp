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
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->enum('risk_method_assessment', ['ya', 'tidak'])->nullable()->after('explosive_atmosphere');
            $table->enum('chemical_usage_storage', ['ya', 'tidak'])->nullable()->after('risk_method_assessment');
            $table->enum('equipment_condition', ['ya', 'tidak'])->nullable()->after('chemical_usage_storage');
            $table->enum('asbestos_presence', ['ya', 'tidak'])->nullable()->after('equipment_condition');
            $table->enum('atex_area', ['ya', 'tidak'])->nullable()->after('asbestos_presence');
            $table->enum('gas_storage_area', ['ya', 'tidak'])->nullable()->after('atex_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropColumn([
                'risk_method_assessment',
                'chemical_usage_storage',
                'equipment_condition',
                'asbestos_presence',
                'atex_area',
                'gas_storage_area'
            ]);
        });
    }
};
