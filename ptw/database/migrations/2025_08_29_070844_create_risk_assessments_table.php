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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permit_to_work_id');
            $table->text('hazard_activity');
            $table->enum('risk_level', ['high', 'medium', 'low']);
            $table->text('control_measures')->nullable();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('receiver_id');
            $table->date('author_date');
            $table->date('receiver_date');
            $table->text('detailed_control_measures')->nullable();
            $table->timestamps();
            
            $table->foreign('permit_to_work_id')->references('id')->on('permit_to_works')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
