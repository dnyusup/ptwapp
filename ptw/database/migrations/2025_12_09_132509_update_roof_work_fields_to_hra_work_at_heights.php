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
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Add new roof work fields
            $table->boolean('roof_load_capacity_adequate')->default(false)->after('roof_work_checked');
            $table->boolean('roof_edge_protection')->default(false)->after('roof_load_capacity_adequate');
            $table->boolean('roof_fall_protection_system')->default(false)->after('roof_edge_protection');
            $table->boolean('roof_communication_method')->default(false)->after('roof_fall_protection_system');
            
            // Drop old fields that are no longer used
            $table->dropColumn([
                'roof_load_capacity',
                'roof_fragile_areas', 
                'roof_fall_protection'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            // Restore old fields
            $table->boolean('roof_load_capacity')->default(false)->after('roof_work_checked');
            $table->boolean('roof_fragile_areas')->default(false)->after('roof_load_capacity');
            $table->boolean('roof_fall_protection')->default(false)->after('roof_fragile_areas');
            
            // Drop new fields
            $table->dropColumn([
                'roof_load_capacity_adequate',
                'roof_edge_protection',
                'roof_fall_protection_system', 
                'roof_communication_method'
            ]);
        });
    }
};
