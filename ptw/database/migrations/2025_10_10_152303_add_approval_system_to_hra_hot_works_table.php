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
            // Approval system fields
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('gas_monitoring_required');
            $table->timestamp('approval_requested_at')->nullable()->after('approval_status');
            
            // Area Owner Approval
            $table->enum('area_owner_approval', ['pending', 'approved', 'rejected'])->nullable()->after('approval_requested_at');
            $table->timestamp('area_owner_approved_at')->nullable()->after('area_owner_approval');
            $table->text('area_owner_comments')->nullable()->after('area_owner_approved_at');
            
            // EHS Approval  
            $table->enum('ehs_approval', ['pending', 'approved', 'rejected'])->nullable()->after('area_owner_comments');
            $table->timestamp('ehs_approved_at')->nullable()->after('ehs_approval');
            $table->text('ehs_comments')->nullable()->after('ehs_approved_at');
            
            // Final approval timestamp
            $table->timestamp('final_approved_at')->nullable()->after('ehs_comments');
            
            // Approval notification tracking
            $table->boolean('area_owner_notified')->default(false)->after('final_approved_at');
            $table->boolean('ehs_notified')->default(false)->after('area_owner_notified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn([
                'approval_status',
                'approval_requested_at',
                'area_owner_approval',
                'area_owner_approved_at', 
                'area_owner_comments',
                'ehs_approval',
                'ehs_approved_at',
                'ehs_comments',
                'final_approved_at',
                'area_owner_notified',
                'ehs_notified'
            ]);
        });
    }
};
