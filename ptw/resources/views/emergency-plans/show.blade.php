@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-shield-alt me-2 text-danger"></i>Emergency & Escape Plan</h4>
                <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
            </div>
            <div class="d-flex gap-2">
                @php
                    $canEdit = false;
                    $currentUser = auth()->user();
                    
                    // Administrator can always edit
                    if ($currentUser->role === 'administrator') {
                        $canEdit = true;
                    }
                    // Bekaert EHS department can edit
                    elseif ($currentUser->role === 'bekaert' && $currentUser->department === 'EHS') {
                        $canEdit = true;
                    }
                    // User as responsible person in permit
                    elseif ($currentUser->id === $permit->permit_issuer_id || $currentUser->id === $permit->receiver_id) {
                        $canEdit = true;
                    }
                @endphp
                
                @if($canEdit)
                <a href="{{ route('emergency-plans.edit', $permit->permit_number) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @endif
                
                <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Emergency & Escape Plan Status</h6>
                        <span class="badge bg-{{ $emergencyPlan->status === 'completed' ? 'success' : ($emergencyPlan->status === 'approved' ? 'primary' : 'secondary') }} fs-6">
                            {{ ucfirst($emergencyPlan->status) }}
                        </span>
                        <p class="mb-0 mt-2 text-muted">
                            Created: {{ $emergencyPlan->created_at->format('d M Y H:i') }} by {{ $emergencyPlan->creator->name ?? 'Unknown' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency & Escape Plan Form -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Emergency & Escape Plan</h5>
        </div>
        <div class="card-body">
            <!-- Kemungkinan Kontaminasi -->
            <div class="row mb-4">
                <label class="col-sm-4 col-form-label fw-bold">Kemungkinan kontaminasi:</label>
                <div class="col-sm-8">
                    @if($emergencyPlan->kontaminasi_keadaan)
                        <p class="form-control-plaintext">{{ $emergencyPlan->kontaminasi_keadaan }}</p>
                    @else
                        <p class="form-control-plaintext text-muted">Tidak ada keterangan</p>
                    @endif
                </div>
            </div>

            <!-- Checklist Keselamatan Darurat -->
            <h6 class="fw-bold text-primary mb-3">Keadaan darurat Perencanaan harus mencakup:</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="60%">Pertanyaan</th>
                            <th class="text-center" width="20%">Ya</th>
                            <th class="text-center" width="20%">Tidak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Petugas tanggap darurat tersedia</td>
                            <td class="text-center">
                                @if($emergencyPlan->petugas_tanggap_darurat)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->petugas_tanggap_darurat)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Cara meminta bantuan diketahui</td>
                            <td class="text-center">
                                @if($emergencyPlan->cara_meminta_bantuan)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->cara_meminta_bantuan)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Sarana akses aman tersedia</td>
                            <td class="text-center">
                                @if($emergencyPlan->sarana_akses_aman)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->sarana_akses_aman)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Orang yang diselamatkan dalam keadaan aman</td>
                            <td class="text-center">
                                @if($emergencyPlan->orang_diselamatkan_aman)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->orang_diselamatkan_aman)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Tata graha dalam keadaan baik</td>
                            <td class="text-center">
                                @if($emergencyPlan->tata_graha_keadaan_baik)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->tata_graha_keadaan_baik)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi titik panggilan diketahui</td>
                            <td class="text-center">
                                @if($emergencyPlan->lokasi_titik_panggilan)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->lokasi_titik_panggilan)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Ketersediaan petugas pertolongan</td>
                            <td class="text-center">
                                @if($emergencyPlan->ketersediaan_petugas_pertolongan)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->ketersediaan_petugas_pertolongan)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Ketersediaan defibrilator</td>
                            <td class="text-center">
                                @if($emergencyPlan->ketersediaan_defibrilator)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->ketersediaan_defibrilator)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Ketersediaan media pemadam</td>
                            <td class="text-center">
                                @if($emergencyPlan->ketersediaan_media_pemadam)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->ketersediaan_media_pemadam)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Kebutuhan alat pernapasan</td>
                            <td class="text-center">
                                @if($emergencyPlan->kebutuhan_alat_pernapasan)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->kebutuhan_alat_pernapasan)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>APD khusus lainnya</td>
                            <td class="text-center">
                                @if($emergencyPlan->apd_khusus_lainnya)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->apd_khusus_lainnya)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        @if($emergencyPlan->apd_khusus_lainnya && $emergencyPlan->sebutkan_apd)
                        <tr>
                            <td colspan="3">
                                <div class="bg-light p-2 rounded">
                                    <small class="text-muted">APD khusus yang diperlukan:</small><br>
                                    {{ $emergencyPlan->sebutkan_apd }}
                                </div>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td>Alat ukur gas dikalibrasi</td>
                            <td class="text-center">
                                @if($emergencyPlan->alat_ukur_gas_dikalibrasi)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->alat_ukur_gas_dikalibrasi)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Peralatan keselamatan khusus</td>
                            <td class="text-center">
                                @if($emergencyPlan->peralatan_keselamatan_khusus)
                                    <i class="fas fa-check-circle text-success fa-lg"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$emergencyPlan->peralatan_keselamatan_khusus)
                                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Field Tambahan -->
            @if($emergencyPlan->alat_keselamatan_digunakan)
            <div class="mt-4">
                <label class="form-label fw-bold">Alat yang akan digunakan (mempertimbangkan persyaratan keselamatan):</label>
                <p class="form-control-plaintext">{{ $emergencyPlan->alat_keselamatan_digunakan }}</p>
            </div>
            @endif
            
            @if($emergencyPlan->deskripsi_rencana_penyelamatan)
            <div class="mt-4">
                <label class="form-label fw-bold">Deskripsi rencana penyelamatan dan penanggung jawab:</label>
                <p class="form-control-plaintext">{{ $emergencyPlan->deskripsi_rencana_penyelamatan }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection