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
            // Add missing fields that are used in the form
            $table->string('work_title')->nullable()->after('permit_number');
            $table->string('location')->nullable()->after('work_title');
            $table->text('work_description')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropColumn(['work_title', 'location', 'work_description']);
        });
    }
};
