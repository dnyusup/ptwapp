<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->string('created_via', 10)->nullable()->after('completed_by');
            $table->string('issued_via', 10)->nullable()->after('created_via');
            $table->string('authorized_via', 10)->nullable()->after('issued_via');
            $table->string('rejected_via', 10)->nullable()->after('authorized_via');
            $table->string('completed_via', 10)->nullable()->after('rejected_via');
        });
    }

    public function down(): void
    {
        Schema::table('permit_to_works', function (Blueprint $table) {
            $table->dropColumn(['created_via', 'issued_via', 'authorized_via', 'rejected_via', 'completed_via']);
        });
    }
};
