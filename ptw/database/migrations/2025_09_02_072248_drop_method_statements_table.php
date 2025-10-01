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
        Schema::dropIfExists('method_statements');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We can't reverse a drop, so leave empty or recreate basic structure
    }
};
