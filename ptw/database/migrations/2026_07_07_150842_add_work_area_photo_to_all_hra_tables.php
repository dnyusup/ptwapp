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
        $hraTables = [
            'hra_work_at_heights',
            'hra_confined_spaces',
            'hra_excavations',
            'hra_explosive_atmospheres',
            'hra_hot_works',
            'hra_line_breakings',
            'hra_loto_isolations',
        ];

        foreach ($hraTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('work_area_photo')->nullable()->after('work_description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hraTables = [
            'hra_work_at_heights',
            'hra_confined_spaces',
            'hra_excavations',
            'hra_explosive_atmospheres',
            'hra_hot_works',
            'hra_line_breakings',
            'hra_loto_isolations',
        ];

        foreach ($hraTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('work_area_photo');
            });
        }
    }
};
