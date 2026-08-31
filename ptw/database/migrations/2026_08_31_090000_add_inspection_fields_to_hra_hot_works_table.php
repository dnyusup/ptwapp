<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->string('inspector_name')->nullable();
            $table->string('inspector_email')->nullable();
            $table->string('inspection_category')->nullable();
            $table->string('inspection_finding_type')->nullable(); // OK / NOK
            $table->text('inspection_findings')->nullable();
            $table->string('inspection_photo_path')->nullable();
            $table->timestamp('inspected_at')->nullable();
            $table->unsignedBigInteger('inspected_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->dropColumn([
                'inspector_name',
                'inspector_email',
                'inspection_category',
                'inspection_finding_type',
                'inspection_findings',
                'inspection_photo_path',
                'inspected_at',
                'inspected_by',
            ]);
        });
    }
};
