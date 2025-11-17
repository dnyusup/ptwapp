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
            $table->text('extension_reason')->nullable();
            $table->timestamp('extended_at')->nullable();
            $table->foreignId('extended_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropForeign(['extended_by']);
            $table->dropColumn(['extension_reason', 'extended_at', 'extended_by']);
        });
    }
};
