<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'kontaminasi_keadaan',
        'petugas_tanggap_darurat',
        'cara_meminta_bantuan',
        'sarana_akses_aman',
        'orang_diselamatkan_aman',
        'tata_graha_keadaan_baik',
        'lokasi_titik_panggilan',
        'ketersediaan_petugas_pertolongan',
        'ketersediaan_defibrilator',
        'ketersediaan_media_pemadam',
        'kebutuhan_alat_pernapasan',
        'apd_khusus_lainnya',
        'sebutkan_apd',
        'alat_ukur_gas_dikalibrasi',
        'peralatan_keselamatan_khusus',
        'alat_keselamatan_digunakan',
        'deskripsi_rencana_penyelamatan',
        'status',
        'created_by'
    ];

    protected $casts = [
        'petugas_tanggap_darurat' => 'boolean',
        'cara_meminta_bantuan' => 'boolean',
        'sarana_akses_aman' => 'boolean',
        'orang_diselamatkan_aman' => 'boolean',
        'tata_graha_keadaan_baik' => 'boolean',
        'lokasi_titik_panggilan' => 'boolean',
        'ketersediaan_petugas_pertolongan' => 'boolean',
        'ketersediaan_defibrilator' => 'boolean',
        'ketersediaan_media_pemadam' => 'boolean',
        'kebutuhan_alat_pernapasan' => 'boolean',
        'apd_khusus_lainnya' => 'boolean',
        'alat_ukur_gas_dikalibrasi' => 'boolean',
        'peralatan_keselamatan_khusus' => 'boolean',
    ];

    // Relationship dengan PermitToWork
    public function permit()
    {
        return $this->belongsTo(PermitToWork::class, 'permit_number', 'permit_number');
    }

    // Relationship dengan User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
