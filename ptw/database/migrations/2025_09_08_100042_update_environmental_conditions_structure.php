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
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Add single select columns that don't exist yet
            $table->string('wind_condition')->nullable();
            $table->string('chemical_spill_condition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Drop single select columns
            $table->dropColumn([
                'wind_condition',
                'chemical_spill_condition'
            ]);
        });
    }
};
