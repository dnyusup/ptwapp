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
        Schema::create('hra_work_at_heights', function (Blueprint $table) {
            $table->id();
            $table->string('hra_permit_number')->unique();
            $table->foreignId('permit_to_work_id')->constrained()->onDelete('cascade');
            $table->string('permit_number'); // Reference to main permit number
            
            // Checklist items based on the form
            // Fixed Scaffolding
            $table->boolean('fixed_scaffolding_checked')->default(false);
            $table->boolean('fixed_scaffolding_approved_by_she')->default(false);
            $table->boolean('fixed_scaffolding_operator_trained')->default(false);
            $table->boolean('fixed_scaffolding_usage_correct')->default(false);
            $table->boolean('fixed_scaffolding_fall_protection')->default(false);
            
            // Mobile scaffolding  
            $table->boolean('mobile_scaffolding_checked')->default(false);
            $table->boolean('mobile_scaffolding_approved_by_she')->default(false);
            $table->boolean('mobile_scaffolding_operator_trained')->default(false);
            $table->boolean('mobile_scaffolding_usage_correct')->default(false);
            $table->boolean('mobile_scaffolding_fall_protection')->default(false);
            
            // Mobile elevation platform
            $table->boolean('mobile_elevation_checked')->default(false);
            $table->boolean('mobile_elevation_no_other_tools')->default(false);
            $table->boolean('mobile_elevation_activities_short')->default(false);
            $table->boolean('mobile_elevation_used_before')->default(false);
            $table->boolean('mobile_elevation_training_provided')->default(false);
            $table->boolean('mobile_elevation_location_marked')->default(false);
            
            // Tangga (Ladder)
            $table->boolean('ladder_checked')->default(false);
            $table->boolean('ladder_area_barriers')->default(false);
            
            // Fall arrest equipment
            $table->boolean('fall_arrest_used')->default(false);
            
            // Roof Work
            $table->boolean('roof_work_checked')->default(false);
            $table->boolean('roof_load_capacity')->default(false);
            $table->boolean('roof_fragile_areas')->default(false);
            $table->boolean('roof_fall_protection')->default(false);
            $table->text('roof_additional_controls')->nullable();
            
            // Work area conditions
            $table->boolean('area_closed_from_below')->default(false);
            $table->boolean('cable_ducts_disruption')->default(false);
            $table->boolean('ventilation_openings')->default(false);
            $table->boolean('machine_equipment_protected')->default(false);
            $table->boolean('emergency_exit_point')->default(false);
            $table->boolean('materials_secured')->default(false);
            $table->boolean('safety_personnel_required')->default(false);
            $table->text('other_conditions')->nullable();
            
            // Environmental conditions when permit issued
            $table->enum('visibility', ['terang', 'remang-remang', 'gelap', 'berkabut'])->nullable();
            $table->enum('rain', ['tidak', 'rintik', 'gerimis', 'deras'])->nullable();
            $table->enum('surface_condition', ['kering', 'basah', 'licin'])->nullable();
            $table->enum('wind', ['none', 'slight', 'moderate', 'strong'])->nullable();
            $table->boolean('oil_spill_risk')->default(false);
            
            // Additional controls
            $table->text('additional_controls')->nullable();
            
            // Signatures
            $table->string('permit_issuer_name')->nullable();
            $table->text('permit_issuer_signature')->nullable();
            $table->string('receiver_name')->nullable();
            $table->text('receiver_signature')->nullable();
            $table->date('issue_date')->nullable();
            $table->time('issue_time')->nullable();
            
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hra_work_at_heights');
    }
};
