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
            // Check if columns exist before dropping them
            if (Schema::hasColumn('permit_to_works', 'risk_level')) {
                $table->dropColumn('risk_level');
            }
            if (Schema::hasColumn('permit_to_works', 'supervisor_name')) {
                $table->dropColumn('supervisor_name');
            }
            if (Schema::hasColumn('permit_to_works', 'supervisor_email')) {
                $table->dropColumn('supervisor_email');
            }
            if (Schema::hasColumn('permit_to_works', 'safety_requirements')) {
                $table->dropColumn('safety_requirements');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->string('risk_level')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_email')->nullable();
            $table->text('safety_requirements')->nullable();
        });
    }
};
