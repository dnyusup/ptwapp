<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hra_hot_work_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hra_hot_work_id')->constrained('hra_hot_works')->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence'); // 1..4
            $table->string('inspector_name');
            $table->string('inspector_email');
            $table->string('finding_type'); // OK / NOK
            $table->text('findings');
            $table->string('photo_path')->nullable();
            $table->timestamp('inspected_at');
            $table->unsignedBigInteger('inspected_by')->nullable();
            $table->timestamps();

            $table->unique(['hra_hot_work_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hra_hot_work_inspections');
    }
};
