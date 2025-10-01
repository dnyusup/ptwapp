<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update status enum to include 'rejected' and 'resubmitted' values
        DB::statement("ALTER TABLE permit_to_works MODIFY COLUMN status ENUM('draft', 'pending_approval', 'approved', 'active', 'rejected', 'resubmitted', 'in_progress', 'completed', 'cancelled') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status enum to original values (remove 'rejected' and 'resubmitted')
        DB::statement("ALTER TABLE permit_to_works MODIFY COLUMN status ENUM('draft', 'pending_approval', 'approved', 'active', 'in_progress', 'completed', 'cancelled') DEFAULT 'draft'");
    }
};
