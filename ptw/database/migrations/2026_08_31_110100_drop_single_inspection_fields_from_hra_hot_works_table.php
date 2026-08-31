<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The single-inspection columns added earlier are replaced by the
     * hra_hot_work_inspections child table (up to 4 inspections per HRA).
     */
    private array $columns = [
        'inspector_name',
        'inspector_email',
        'inspection_finding_type',
        'inspection_findings',
        'inspection_photo_path',
        'inspected_at',
        'inspected_by',
    ];

    public function up(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            foreach ($this->columns as $col) {
                if (Schema::hasColumn('hra_hot_works', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('hra_hot_works', function (Blueprint $table) {
            $table->string('inspector_name')->nullable();
            $table->string('inspector_email')->nullable();
            $table->string('inspection_finding_type')->nullable();
            $table->text('inspection_findings')->nullable();
            $table->string('inspection_photo_path')->nullable();
            $table->timestamp('inspected_at')->nullable();
            $table->unsignedBigInteger('inspected_by')->nullable();
        });
    }
};
