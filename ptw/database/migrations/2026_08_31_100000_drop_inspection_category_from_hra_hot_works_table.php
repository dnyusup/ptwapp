<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            if (Schema::hasColumn('hra_hot_works', 'inspection_category')) {
                $table->dropColumn('inspection_category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->string('inspection_category')->nullable();
        });
    }
};
