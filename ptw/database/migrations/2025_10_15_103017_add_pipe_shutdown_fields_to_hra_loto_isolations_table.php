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
            // Mematikan Pipa fields
            $table->string('isi_line_pipa')->nullable()->after('area_sudah_diberitahu');
            $table->enum('tidak_ada_sisa_tekanan', ['ya', 'tidak'])->nullable()->after('isi_line_pipa');
            $table->enum('drain_bleed_valves', ['ya', 'tidak'])->nullable()->after('tidak_ada_sisa_tekanan');
            $table->boolean('pipa_purged_udara')->default(false)->after('drain_bleed_valves');
            $table->boolean('pipa_purged_air')->default(false)->after('pipa_purged_udara');
            $table->boolean('pipa_purged_nitrogen')->default(false)->after('pipa_purged_air');
            $table->enum('pipa_diisolasi_plat', ['ya', 'tidak'])->nullable()->after('pipa_purged_nitrogen');
            $table->enum('pipa_kosong', ['ya', 'tidak'])->nullable()->after('pipa_diisolasi_plat');
            $table->enum('pipa_bersih', ['ya', 'tidak'])->nullable()->after('pipa_kosong');
            $table->text('alasan_deskripsi_isolasi_pipa')->nullable()->after('pipa_bersih');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hra_loto_isolations', function (Blueprint $table) {
            $table->dropColumn([
                'isi_line_pipa',
                'tidak_ada_sisa_tekanan',
                'drain_bleed_valves',
                'pipa_purged_udara',
                'pipa_purged_air',
                'pipa_purged_nitrogen',
                'pipa_diisolasi_plat',
                'pipa_kosong',
                'pipa_bersih',
                'alasan_deskripsi_isolasi_pipa'
            ]);
        });
    }
};
