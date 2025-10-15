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
            // Tes Listrik fields
            $table->string('membutuhkan_tes_listrik_on')->nullable();
            $table->string('safety_barrier')->nullable();
            $table->string('full_face_protection')->nullable();
            $table->string('insulated_gloves')->nullable();
            $table->string('insulated_mat')->nullable();
            $table->string('full_length_sleeves')->nullable();
            $table->string('tool_insulation_satisfactory')->nullable();
            $table->integer('maximum_voltage')->nullable();
            $table->text('alasan_live_test')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Drop Tes Listrik fields
            $table->dropColumn([
                'membutuhkan_tes_listrik_on',
                'safety_barrier',
                'full_face_protection',
                'insulated_gloves',
                'insulated_mat',
                'full_length_sleeves',
                'tool_insulation_satisfactory',
                'maximum_voltage',
                'alasan_live_test'
            ]);
        });
    }
};
