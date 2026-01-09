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
            // Overall approval status
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('status');
            $table->timestamp('approval_requested_at')->nullable()->after('approval_status');

            // Area Owner Approval
            $table->enum('area_owner_approval', ['pending', 'approved', 'rejected'])->nullable()->after('approval_requested_at');
            $table->timestamp('area_owner_approved_at')->nullable()->after('area_owner_approval');
            $table->unsignedBigInteger('area_owner_approved_by')->nullable()->after('area_owner_approved_at');
            $table->text('area_owner_comments')->nullable()->after('area_owner_approved_by');

            // EHS Approval
            $table->enum('ehs_approval', ['pending', 'approved', 'rejected'])->nullable()->after('area_owner_comments');
            $table->timestamp('ehs_approved_at')->nullable()->after('ehs_approval');
            $table->unsignedBigInteger('ehs_approved_by')->nullable()->after('ehs_approved_at');
            $table->text('ehs_comments')->nullable()->after('ehs_approved_by');

            // Final Approval
            $table->timestamp('final_approved_at')->nullable()->after('ehs_comments');
            $table->boolean('area_owner_notified')->default(false)->after('final_approved_at');
            $table->boolean('ehs_notified')->default(false)->after('area_owner_notified');

            // Rejection Fields
            $table->text('rejection_reason')->nullable()->after('ehs_notified');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');

            // Foreign keys
            $table->foreign('area_owner_approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('ehs_approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropForeign(['area_owner_approved_by']);
            $table->dropForeign(['ehs_approved_by']);
            $table->dropForeign(['rejected_by']);

            $table->dropColumn([
                'approval_status',
                'approval_requested_at',
                'area_owner_approval',
                'area_owner_approved_at',
                'area_owner_approved_by',
                'area_owner_comments',
                'ehs_approval',
                'ehs_approved_at',
                'ehs_approved_by',
                'ehs_comments',
                'final_approved_at',
                'area_owner_notified',
                'ehs_notified',
                'rejection_reason',
                'rejected_at',
                'rejected_by',
            ]);
        });
    }
};
