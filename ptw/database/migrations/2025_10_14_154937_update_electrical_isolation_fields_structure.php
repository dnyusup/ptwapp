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
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Drop the separate "ya" boolean fields that are no longer needed
            $table->dropColumn([
                'saklar_diposisi_off_ya',
                'tag_dipasang_ya', 
                'sekring_cb_ya',
                'panel_off_ya',
                'safety_switch_ya',
                'tag_sistem_ya',
                'sekring_sistem_ya',
                'dicoba_nyala_ya'
            ]);
            
            // Update existing boolean fields to string fields for ya/tidak values
            $table->string('saklar_diposisi_off')->nullable()->change();
            $table->string('tag_dipasang_panel')->nullable()->change();
            $table->string('sekring_cb_dimatikan')->nullable()->change();
            $table->string('panel_off_panel')->nullable()->change();
            $table->string('safety_switch_off')->nullable()->change();
            $table->string('tag_dipasang_sistem')->nullable()->change();
            $table->string('sekring_cb_sistem_dimatikan')->nullable()->change();
            $table->string('sudah_dicoba_dinyalakan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Revert string fields back to boolean
            $table->boolean('saklar_diposisi_off')->default(false)->change();
            $table->boolean('tag_dipasang_panel')->default(false)->change();
            $table->boolean('sekring_cb_dimatikan')->default(false)->change();
            $table->boolean('panel_off_panel')->default(false)->change();
            $table->boolean('safety_switch_off')->default(false)->change();
            $table->boolean('tag_dipasang_sistem')->default(false)->change();
            $table->boolean('sekring_cb_sistem_dimatikan')->default(false)->change();
            $table->boolean('sudah_dicoba_dinyalakan')->default(false)->change();
            
            // Add back the "ya" boolean fields
            $table->boolean('saklar_diposisi_off_ya')->default(false);
            $table->boolean('tag_dipasang_ya')->default(false);
            $table->boolean('sekring_cb_ya')->default(false);
            $table->boolean('panel_off_ya')->default(false);
            $table->boolean('safety_switch_ya')->default(false);
            $table->boolean('tag_sistem_ya')->default(false);
            $table->boolean('sekring_sistem_ya')->default(false);
            $table->boolean('dicoba_nyala_ya')->default(false);
        });
    }
};
