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
            // Completion fields
            $table->enum('work_status', ['selesai', 'belum_selesai'])->nullable()->after('status');
            $table->text('work_status_detail')->nullable()->after('work_status');
            $table->enum('area_installation_status', ['siap_dioperasikan', 'belum_siap'])->nullable()->after('work_status_detail');
            $table->text('area_installation_detail')->nullable()->after('area_installation_status');
            $table->timestamp('completed_at')->nullable()->after('area_installation_detail');
            $table->unsignedBigInteger('completed_by')->nullable()->after('completed_at');
            
            // Foreign key for completed_by
            $table->foreign('completed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropForeign(['completed_by']);
            $table->dropColumn([
                'work_status',
                'work_status_detail', 
                'area_installation_status',
                'area_installation_detail',
                'completed_at',
                'completed_by'
            ]);
        });
    }
};
