<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_plans', function (Blueprint $table) {
            $table->string('created_via', 10)->nullable()->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_plans', function (Blueprint $table) {
            $table->dropColumn('created_via');
        });
    }
};
