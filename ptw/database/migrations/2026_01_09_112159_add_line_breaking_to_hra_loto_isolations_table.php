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
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->string('line_content_before', 255)->nullable()->after('ptw_issuer_lototo_key');
            $table->string('lb_no_residual_pressure', 10)->nullable()->after('line_content_before');
            $table->string('lb_drain_valve_open', 10)->nullable()->after('lb_no_residual_pressure');
            $table->string('lb_emergency_arrangements', 10)->nullable()->after('lb_drain_valve_open');
            $table->string('lb_line_isolated', 10)->nullable()->after('lb_emergency_arrangements');
            $table->string('lb_line_empty', 10)->nullable()->after('lb_line_isolated');
            $table->string('lb_line_clean', 10)->nullable()->after('lb_line_empty');
            $table->string('lb_no_asbestos', 10)->nullable()->after('lb_line_clean');
            $table->string('lb_pipe_no_support_needed', 10)->nullable()->after('lb_no_asbestos');
            $table->string('lb_lototo_drainage', 10)->nullable()->after('lb_pipe_no_support_needed');
            $table->boolean('lb_purged_air')->nullable()->after('lb_lototo_drainage');
            $table->boolean('lb_purged_water')->nullable()->after('lb_purged_air');
            $table->boolean('lb_purged_n2')->nullable()->after('lb_purged_water');
            $table->text('lb_additional_control')->nullable()->after('lb_purged_n2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn([
                'line_content_before',
                'lb_no_residual_pressure',
                'lb_drain_valve_open',
                'lb_emergency_arrangements',
                'lb_line_isolated',
                'lb_line_empty',
                'lb_line_clean',
                'lb_no_asbestos',
                'lb_pipe_no_support_needed',
                'lb_lototo_drainage',
                'lb_purged_air',
                'lb_purged_water',
                'lb_purged_n2',
                'lb_additional_control'
            ]);
        });
    }
};
