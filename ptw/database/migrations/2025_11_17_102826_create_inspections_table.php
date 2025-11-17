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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number');
            $table->string('inspector_name');
            $table->string('inspector_email');
            $table->text('findings');
            $table->timestamps();

            $table->foreign('permit_number')->references('permit_number')->on('permit_to_works')->onDelete('cascade');
            $table->index(['permit_number', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
