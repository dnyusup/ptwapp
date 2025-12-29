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
            $table->dropColumn([
                'emergency_call',
                'sprinkler_check',
                'detector_shutdown',
                'notification_required',
                'notification_phone',
                'notification_name',
                'insurance_notification',
                'detector_confirmed_off',
                'gas_monitoring_required'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->string('emergency_call')->nullable();
            $table->boolean('sprinkler_check')->nullable();
            $table->boolean('detector_shutdown')->nullable();
            $table->boolean('notification_required')->nullable();
            $table->string('notification_phone', 20)->nullable();
            $table->string('notification_name')->nullable();
            $table->boolean('insurance_notification')->nullable();
            $table->boolean('detector_confirmed_off')->nullable();
            $table->boolean('gas_monitoring_required')->nullable();
        });
    }
};
