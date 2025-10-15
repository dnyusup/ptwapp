<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HraLotoIsolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'hra_permit_number',
        'permit_to_work_id',
        'permit_number',
        'user_id',
        // Basic Information
        'worker_name',
        'worker_phone',
        'supervisor_name',
        'work_location',
        'start_datetime',
        'end_datetime',
        'work_description',
        'status',
        
        // Isolasi Mesin/Tangki
        'machine_tank_name',
        
        // Panel Listrik
        'panel_listrik_mati',
        'panel_listrik_dikunci',
        'panel_listrik_diperiksa',
        'panel_listrik_dipasang_tag',
        
        // Pneumatic
        'pneumatic_mati',
        'pneumatic_dikunci',
        'pneumatic_diperiksa',
        'pneumatic_dipasang_tag',
        
        // Hydraulic
        'hydraulic_mati',
        'hydraulic_dikunci',
        'hydraulic_diperiksa',
        'hydraulic_dipasang_tag',
        
        // Gravitasi
        'gravitasi_mati',
        'gravitasi_dikunci',
        'gravitasi_diperiksa',
        'gravitasi_dipasang_tag',
        
        // Spring/Per
        'spring_per_mati',
        'spring_per_dikunci',
        'spring_per_diperiksa',
        'spring_per_dipasang_tag',
        
        // Rotasi/Gerakan
        'rotasi_gerakan_mati',
        'rotasi_gerakan_dikunci',
        'rotasi_gerakan_diperiksa',
        'rotasi_gerakan_dipasang_tag',
        
        // Isolasi Listrik - Panel Listrik
        'bekerja_panel_listrik',
        'referensi_manual_panel',
        'saklar_diposisi_off',
        'tag_dipasang_panel',
        'sekring_cb_dimatikan',
        'panel_off_panel',
        
        // Isolasi Listrik - Sistem Mekanis
        'bekerja_sistem_mekanis',
        'referensi_manual_sistem',
        'safety_switch_off',
        'tag_dipasang_sistem',
        'sekring_cb_sistem_dimatikan',
        'sudah_dicoba_dinyalakan',
        
        // Tes Listrik
        'membutuhkan_tes_listrik_on',
        'safety_barrier',
        'full_face_protection',
        'insulated_gloves',
        'insulated_mat',
        'full_length_sleeves',
        'tool_insulation_satisfactory',
        'maximum_voltage',
        'alasan_live_test',
        
        // Isolasi Utility
        'utility_listrik_off',
        'utility_listrik_secured',
        'utility_listrik_checked',
        'utility_listrik_tagged',
        'utility_cooling_water_off',
        'utility_cooling_water_secured',
        'utility_cooling_water_checked',
        'utility_cooling_water_tagged',
        'utility_oil_hidrolik_off',
        'utility_oil_hidrolik_secured',
        'utility_oil_hidrolik_checked',
        'utility_oil_hidrolik_tagged',
        'utility_kompresor_off',
        'utility_kompresor_secured',
        'utility_kompresor_checked',
        'utility_kompresor_tagged',
        'utility_vacuum_off',
        'utility_vacuum_secured',
        'utility_vacuum_checked',
        'utility_vacuum_tagged',
        'utility_gas_off',
        'utility_gas_secured',
        'utility_gas_checked',
        'utility_gas_tagged',
        'utility_lainnya_nama',
        'utility_lainnya_off',
        'utility_lainnya_secured',
        'utility_lainnya_checked',
        'utility_lainnya_tagged',
        'line_diisolasi_plat',
        'alasan_deskripsi_isolasi',
        'area_terdampak_isolasi',
        'area_sudah_diberitahu',
        
        // Mematikan Pipa
        'isi_line_pipa',
        'tidak_ada_sisa_tekanan',
        'drain_bleed_valves',
        'pipa_purged_udara',
        'pipa_purged_air',
        'pipa_purged_nitrogen',
        'pipa_diisolasi_plat',
        'pipa_kosong',
        'pipa_bersih',
        'alasan_deskripsi_isolasi_pipa',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Generate unique HRA permit number
     */
    public static function generateHraPermitNumber($permitNumber)
    {
        // Get count of existing HRA LOTO/Isolation for this permit
        $count = static::where('permit_number', $permitNumber)->count();
        
        // Format: General Permit Number + HRA Type + Sequence
        return $permitNumber . '-LOTO-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship with PermitToWork
     */
    public function permitToWork()
    {
        return $this->belongsTo(PermitToWork::class, 'permit_to_work_id');
    }

    /**
     * Relationship with User (creator)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
