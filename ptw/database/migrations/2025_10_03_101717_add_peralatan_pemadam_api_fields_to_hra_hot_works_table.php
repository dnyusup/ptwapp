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
            // APAR fields
            $table->boolean('apar_air')->default(false)->after('q17_gas_monitoring');
            $table->boolean('apar_powder')->default(false)->after('apar_air');
            $table->boolean('apar_co2')->default(false)->after('apar_powder');
            
            // Fire safety equipment
            $table->boolean('fire_blanket')->default(false)->after('apar_co2');
            $table->boolean('fire_watch_officer')->default(false)->after('fire_blanket');
            $table->string('fire_watch_name')->nullable()->after('fire_watch_officer');
            
            // Monitoring requirements
            $table->boolean('monitoring_sprinkler')->default(false)->after('fire_watch_name');
            $table->boolean('monitoring_combustible')->default(false)->after('monitoring_sprinkler');
            $table->boolean('monitoring_distance')->default(false)->after('monitoring_combustible');
            
            // Emergency and safety checks
            $table->string('emergency_call')->nullable()->after('monitoring_distance');
            $table->boolean('sprinkler_check')->default(false)->after('emergency_call');
            $table->boolean('detector_shutdown')->default(false)->after('sprinkler_check');
            $table->boolean('notification_required')->default(false)->after('detector_shutdown');
            $table->string('notification_phone')->nullable()->after('notification_required');
            $table->string('notification_name')->nullable()->after('notification_phone');
            $table->boolean('insurance_notification')->default(false)->after('notification_name');
            $table->boolean('detector_confirmed_off')->default(false)->after('insurance_notification');
            $table->boolean('gas_monitoring_required')->default(false)->after('detector_confirmed_off');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn([
                'apar_air',
                'apar_powder',
                'apar_co2',
                'fire_blanket',
                'fire_watch_officer',
                'fire_watch_name',
                'monitoring_sprinkler',
                'monitoring_combustible',
                'monitoring_distance',
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
};
