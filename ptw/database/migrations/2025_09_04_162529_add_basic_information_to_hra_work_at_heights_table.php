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
            // Add basic information fields
            $table->string('worker_name')->after('permit_number');
            $table->string('worker_phone')->nullable()->after('worker_name');
            $table->string('supervisor_name')->after('worker_phone');
            $table->string('work_location')->after('supervisor_name');
            $table->datetime('start_datetime')->after('work_location');
            $table->datetime('end_datetime')->after('start_datetime');
            $table->text('work_description')->after('end_datetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            $table->dropColumn([
                'worker_name',
                'worker_phone', 
                'supervisor_name',
                'work_location',
                'start_datetime',
                'end_datetime',
                'work_description'
            ]);
        });
    }
};
