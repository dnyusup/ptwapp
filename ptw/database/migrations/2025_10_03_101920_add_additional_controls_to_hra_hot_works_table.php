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
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->text('additional_controls')->nullable()->after('q17_gas_monitoring');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn('additional_controls');
        });
    }
};
