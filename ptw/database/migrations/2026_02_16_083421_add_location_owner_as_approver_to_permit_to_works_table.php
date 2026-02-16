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
            $table->boolean('location_owner_as_approver')->default(true)->after('location_owner_id');
            $table->string('location_owner_approval_status')->nullable()->after('location_owner_as_approver');
            $table->timestamp('location_owner_approved_at')->nullable()->after('location_owner_approval_status');
            $table->string('ehs_approval_status')->nullable()->after('location_owner_approved_at');
            $table->timestamp('ehs_approved_at')->nullable()->after('ehs_approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropColumn([
                'location_owner_as_approver', 
                'location_owner_approval_status', 
                'location_owner_approved_at',
                'ehs_approval_status',
                'ehs_approved_at'
            ]);
        });
    }
};
