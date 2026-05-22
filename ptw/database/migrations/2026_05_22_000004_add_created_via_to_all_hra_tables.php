<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'hra_work_at_heights',
            'hra_hot_works',
            'hra_loto_isolations',
            'hra_line_breakings',
            'hra_excavations',
            'hra_confined_spaces',
            'hra_explosive_atmospheres',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('created_via', 10)->nullable()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'hra_work_at_heights',
            'hra_hot_works',
            'hra_loto_isolations',
            'hra_line_breakings',
            'hra_excavations',
            'hra_confined_spaces',
            'hra_explosive_atmospheres',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('created_via');
            });
        }
    }
};
