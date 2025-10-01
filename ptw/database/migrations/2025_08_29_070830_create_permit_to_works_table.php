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
        Schema::create('permit_to_works', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique();
            $table->string('work_location');
            $table->text('equipment_tools');
            $table->string('department');
            $table->string('responsible_person');
            $table->date('emergency_contact_date');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('tip')->nullable();
            
            // Method Statement checkboxes
            $table->boolean('work_at_heights')->default(false);
            $table->boolean('hot_work')->default(false);
            $table->boolean('loto_isolation')->default(false);
            $table->boolean('line_breaking')->default(false);
            $table->boolean('excavation')->default(false);
            $table->boolean('confined_spaces')->default(false);
            $table->boolean('explosive_atmosphere')->default(false);
            
            // Form data
            $table->string('form_y_n')->nullable();
            $table->string('form_detail')->nullable();
            
            // Validity
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date');
            $table->time('end_time');
            
            // Status and approvals
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'in_progress', 'completed', 'cancelled'])->default('draft');
            
            // User relationships
            $table->unsignedBigInteger('permit_issuer_id');
            $table->unsignedBigInteger('authorizer_id')->nullable();
            $table->unsignedBigInteger('receiver_id')->nullable();
            
            // Approval timestamps
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('authorized_at')->nullable();
            $table->timestamp('received_at')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('permit_issuer_id')->references('id')->on('users');
            $table->foreign('authorizer_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permit_to_works');
    }
};
