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
            // Panel Listrik fields
            $table->string('bekerja_panel_listrik')->nullable();
            $table->string('referensi_manual_panel')->nullable();
            $table->boolean('saklar_diposisi_off')->default(false);
            $table->boolean('saklar_diposisi_off_ya')->default(false);
            $table->boolean('tag_dipasang_panel')->default(false);
            $table->boolean('tag_dipasang_ya')->default(false);
            $table->boolean('sekring_cb_dimatikan')->default(false);
            $table->boolean('sekring_cb_ya')->default(false);
            $table->boolean('panel_off_panel')->default(false);
            $table->boolean('panel_off_ya')->default(false);
            
            // Sistem Mekanis fields
            $table->string('bekerja_sistem_mekanis')->nullable();
            $table->string('referensi_manual_sistem')->nullable();
            $table->boolean('safety_switch_off')->default(false);
            $table->boolean('safety_switch_ya')->default(false);
            $table->boolean('tag_dipasang_sistem')->default(false);
            $table->boolean('tag_sistem_ya')->default(false);
            $table->boolean('sekring_cb_sistem_dimatikan')->default(false);
            $table->boolean('sekring_sistem_ya')->default(false);
            $table->boolean('sudah_dicoba_dinyalakan')->default(false);
            $table->boolean('dicoba_nyala_ya')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            // Drop Panel Listrik fields
            $table->dropColumn([
                'bekerja_panel_listrik',
                'referensi_manual_panel',
                'saklar_diposisi_off',
                'saklar_diposisi_off_ya',
                'tag_dipasang_panel',
                'tag_dipasang_ya',
                'sekring_cb_dimatikan',
                'sekring_cb_ya',
                'panel_off_panel',
                'panel_off_ya'
            ]);
            
            // Drop Sistem Mekanis fields
            $table->dropColumn([
                'bekerja_sistem_mekanis',
                'referensi_manual_sistem',
                'safety_switch_off',
                'safety_switch_ya',
                'tag_dipasang_sistem',
                'tag_sistem_ya',
                'sekring_cb_sistem_dimatikan',
                'sekring_sistem_ya',
                'sudah_dicoba_dinyalakan',
                'dicoba_nyala_ya'
            ]);
        });
    }
};
