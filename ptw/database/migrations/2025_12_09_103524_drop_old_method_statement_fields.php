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
        Schema::table('method_statements', function (Blueprint $table) {
            // Drop old unused columns
            $table->dropColumn([
                'work_access_explanation',
                'safety_equipment_explanation',
                'training_competency_explanation',
                'route_identification_explanation',
                'work_area_preparation_explanation',
                'work_sequence_explanation',
                'equipment_maintenance_explanation',
                'platform_explanation',
                'hand_washing_explanation',
                'work_area_cleanliness_explanation'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('method_statements', function (Blueprint $table) {
            // Restore old columns if rollback is needed
            $table->text('work_access_explanation')->nullable();
            $table->text('safety_equipment_explanation')->nullable();
            $table->text('training_competency_explanation')->nullable();
            $table->text('route_identification_explanation')->nullable();
            $table->text('work_area_preparation_explanation')->nullable();
            $table->text('work_sequence_explanation')->nullable();
            $table->text('equipment_maintenance_explanation')->nullable();
            $table->text('platform_explanation')->nullable();
            $table->text('hand_washing_explanation')->nullable();
            $table->text('work_area_cleanliness_explanation')->nullable();
        });
    }
};
