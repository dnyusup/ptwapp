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
            $table->unsignedBigInteger('location_owner_id')->nullable()->after('work_location');
            $table->foreign('location_owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropForeign(['location_owner_id']);
            $table->dropColumn('location_owner_id');
        });
    }
};
