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
        Schema::create('hra_hot_works', function (Blueprint $table) {
            $table->id();
            
            // HRA Identification
            $table->string('hra_permit_number')->unique();
            $table->foreignId('permit_to_work_id')->constrained('permit_to_works')->onDelete('cascade');
            $table->string('permit_number');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Basic Information
            $table->string('worker_name');
            $table->string('worker_phone')->nullable();
            $table->string('supervisor_name');
            $table->string('work_location');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->text('work_description');
            
            // Status
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['permit_to_work_id', 'status']);
            $table->index('hra_permit_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hra_hot_works');
    }
};
