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
        Schema::table('method_statements', function (Blueprint $table) {
            // Add new JSON column for responsible persons
            $table->json('responsible_persons')->nullable()->after('permit_issuer_name');
            
            // Drop old individual columns
            $table->dropColumn([
                'responsible_person_1',
                'responsible_person_2',
                'responsible_person_3',
                'responsible_person_4',
                'responsible_person_5',
                'responsible_person_6'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('method_statements', function (Blueprint $table) {
            // Drop JSON column
            $table->dropColumn('responsible_persons');
            
            // Add back old individual columns
            $table->text('responsible_person_1')->nullable()->after('permit_issuer_name');
            $table->text('responsible_person_2')->nullable()->after('responsible_person_1');
            $table->text('responsible_person_3')->nullable()->after('responsible_person_2');
            $table->text('responsible_person_4')->nullable()->after('responsible_person_3');
            $table->text('responsible_person_5')->nullable()->after('responsible_person_4');
            $table->text('responsible_person_6')->nullable()->after('responsible_person_5');
        });
    }
};
