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
            // Overall approval status
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('status');
            $table->timestamp('approval_requested_at')->nullable()->after('approval_status');

            // EHS Approval (only EHS approval required, Location Owner is CCed)
            $table->enum('ehs_approval', ['pending', 'approved', 'rejected'])->nullable()->after('approval_requested_at');
            $table->timestamp('ehs_approved_at')->nullable()->after('ehs_approval');
            $table->unsignedBigInteger('ehs_approved_by')->nullable()->after('ehs_approved_at');
            $table->text('ehs_comments')->nullable()->after('ehs_approved_by');

            // Final Approval
            $table->timestamp('final_approved_at')->nullable()->after('ehs_comments');
            $table->boolean('ehs_notified')->default(false)->after('final_approved_at');

            // Rejection Fields
            $table->text('rejection_reason')->nullable()->after('ehs_notified');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');

            // Foreign keys
            $table->foreign('ehs_approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_work_at_heights', function (Blueprint $table) {
            $table->dropForeign(['ehs_approved_by']);
            $table->dropForeign(['rejected_by']);

            $table->dropColumn([
                'approval_status',
                'approval_requested_at',
                'ehs_approval',
                'ehs_approved_at',
                'ehs_approved_by',
                'ehs_comments',
                'final_approved_at',
                'ehs_notified',
                'rejection_reason',
                'rejected_at',
                'rejected_by',
            ]);
        });
    }
};
