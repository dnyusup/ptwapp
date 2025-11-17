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
        // Add new status to enum
        \DB::statement("ALTER TABLE permit_to_works MODIFY COLUMN status ENUM('draft', 'pending_approval', 'approved', 'active', 'rejected', 'completed', 'expired', 'pending_extension_approval') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the status from enum (revert back)
        \DB::statement("ALTER TABLE permit_to_works MODIFY COLUMN status ENUM('draft', 'pending_approval', 'approved', 'active', 'rejected', 'completed', 'expired') NOT NULL DEFAULT 'draft'");
    }
};
