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
            $table->string('permit_number')->unique();
            $table->foreign('permit_number')->references('permit_number')->on('permit_to_works')->onDelete('cascade');
            
            // Basic Information
            $table->string('responsible_person_name')->nullable();
            $table->date('method_statement_date')->nullable();
            $table->string('permit_receiver_name')->nullable();
            $table->string('permit_issuer_name')->nullable();
            
            // Method Statement Details
            $table->text('work_method_description')->nullable();
            
            // Risk Assessment checkboxes
            $table->boolean('risk_identified')->default(false);
            $table->boolean('documentation_reviewed')->default(false);
            $table->boolean('risk_assessment_different_format')->default(false);
            $table->boolean('risk_assessment_attached')->default(false);
            
            // Responsible persons (numbered list 1-6)
            $table->text('responsible_person_1')->nullable();
            $table->text('responsible_person_2')->nullable();
            $table->text('responsible_person_3')->nullable();
            $table->text('responsible_person_4')->nullable();
            $table->text('responsible_person_5')->nullable();
            $table->text('responsible_person_6')->nullable();
            
            // Work method explanations
            $table->text('work_access_explanation')->nullable();
            $table->text('safety_equipment_explanation')->nullable();
            $table->text('training_competency_explanation')->nullable();
            $table->text('route_identification_explanation')->nullable();
            $table->text('work_area_preparation_explanation')->nullable();
            $table->text('work_sequence_explanation')->nullable();
            $table->text('equipment_maintenance_explanation')->nullable();
            $table->text('platform_explanation')->nullable();
            $table->text('hand_washing_explanation')->nullable();
            $table->text('work_area_cleanliness_explanation')->nullable();
            
            // Risk Assessment Table
            $table->json('risk_activities')->nullable(); // Store array of activities
            $table->json('risk_levels')->nullable(); // Store array of risk levels (High/Medium/Low)
            $table->json('control_measures')->nullable(); // Store array of control measures
            
            // Additional risk control details
            $table->text('medium_high_risk_details')->nullable();
            
            // Signatures
            $table->string('author_name')->nullable();
            $table->string('author_signature')->nullable();
            $table->date('author_date')->nullable();
            
            $table->string('receiver_name')->nullable();
            $table->string('receiver_signature')->nullable();
            $table->date('receiver_date')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'completed', 'approved'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
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
