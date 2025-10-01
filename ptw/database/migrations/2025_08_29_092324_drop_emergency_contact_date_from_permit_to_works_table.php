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
            $table->dropColumn('emergency_contact_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->date('emergency_contact_date')->nullable();
        });
    }
};
