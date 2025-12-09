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
            // Add overhead hazards fields at the beginning of checklist items
            $table->boolean('overhead_hazards_checked')->default(false)->after('work_description');
            $table->boolean('overhead_hazards_minimal_guards')->default(false)->after('overhead_hazards_checked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            $table->dropColumn([
                'overhead_hazards_checked',
                'overhead_hazards_minimal_guards'
            ]);
        });
    }
};
