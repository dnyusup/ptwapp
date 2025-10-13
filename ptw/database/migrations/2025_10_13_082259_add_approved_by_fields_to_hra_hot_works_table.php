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
            $table->unsignedBigInteger('area_owner_approved_by')->nullable()->after('area_owner_approved_at');
            $table->unsignedBigInteger('ehs_approved_by')->nullable()->after('ehs_approved_at');
            
            $table->foreign('area_owner_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ehs_approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropForeign(['area_owner_approved_by']);
            $table->dropForeign(['ehs_approved_by']);
            $table->dropColumn(['area_owner_approved_by', 'ehs_approved_by']);
        });
    }
};
