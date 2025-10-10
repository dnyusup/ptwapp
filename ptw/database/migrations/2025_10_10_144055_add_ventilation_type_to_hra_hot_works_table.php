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
            $table->enum('q12_ventilation_type', ['alami', 'buatan'])->nullable()->after('q11_holes_sealed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn('q12_ventilation_type');
        });
    }
};
