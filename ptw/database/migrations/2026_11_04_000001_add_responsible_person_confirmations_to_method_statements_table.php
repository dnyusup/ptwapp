<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('method_statements', function (Blueprint $table) {
            $table->json('responsible_person_confirmations')->nullable()->after('responsible_persons');
        });
    }

    public function down(): void
    {
        Schema::table('method_statements', function (Blueprint $table) {
            $table->dropColumn('responsible_person_confirmations');
        });
    }
};
