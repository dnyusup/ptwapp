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
            $table->string('emergency_call_point')->nullable()->after('additional_inspection_duration');
            $table->boolean('sprinkler_system_disabled')->nullable()->after('emergency_call_point');
            $table->boolean('fire_detection_isolated')->nullable()->after('sprinkler_system_disabled');
            $table->string('isolation_notified_by')->nullable()->after('fire_detection_isolated');
            $table->string('isolation_notified_when')->nullable()->after('isolation_notified_by');
            $table->string('reinstatement_notified_by')->nullable()->after('isolation_notified_when');
            $table->string('reinstatement_notified_when')->nullable()->after('reinstatement_notified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn([
                'emergency_call_point',
                'sprinkler_system_disabled',
                'fire_detection_isolated',
                'isolation_notified_by',
                'isolation_notified_when',
                'reinstatement_notified_by',
                'reinstatement_notified_when'
            ]);
        });
    }
};
