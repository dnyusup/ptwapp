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
            // Add rejection fields
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            // Drop foreign key and rejection fields
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['rejection_reason', 'rejected_at', 'rejected_by']);
        });
    }
};