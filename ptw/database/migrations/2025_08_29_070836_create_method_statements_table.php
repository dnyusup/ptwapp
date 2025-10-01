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
        Schema::create('method_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permit_to_work_id');
            $table->string('responsible_person');
            $table->date('method_statement_date');
            $table->string('permit_receiver');
            $table->string('method_statement_author')->nullable();
            
            // Checklist questions
            $table->text('document_guidance')->nullable();
            $table->text('hazard_identification')->nullable();
            $table->text('risk_evaluation')->nullable();
            $table->boolean('risk_document_different')->default(false);
            $table->text('persons_responsibility')->nullable();
            $table->text('work_sequence_1')->nullable();
            $table->text('work_sequence_2')->nullable();
            $table->text('work_sequence_3')->nullable();
            $table->text('work_sequence_4')->nullable();
            $table->text('work_sequence_5')->nullable();
            $table->text('work_sequence_6')->nullable();
            $table->text('tools_access_method')->nullable();
            $table->text('safety_equipment_ppe')->nullable();
            $table->text('training_competence')->nullable();
            $table->text('route_identification')->nullable();
            $table->text('off_job_equipment_storage')->nullable();
            $table->text('work_sequence_order')->nullable();
            $table->text('equipment_inspection')->nullable();
            $table->text('temporary_platform')->nullable();
            $table->text('weather_influence')->nullable();
            $table->text('cleanliness_housekeeping')->nullable();
            
            $table->timestamps();
            
            $table->foreign('permit_to_work_id')->references('id')->on('permit_to_works')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('method_statements');
    }
};
