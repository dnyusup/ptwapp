@extends('layouts.app')

@section('styles')
<style>
    .badge-yes {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
    }
    
    .badge-no {
        background: linear-gradient(135deg, #dc3545, #c82333) !important;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }
    
    .badge-na {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
    }
    
    .badge-yes:hover, .badge-no:hover, .badge-na:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    /* Custom button styles */
    .btn-outline-warning {
        border-color: #ffc107 !important;
        color: #ffc107 !important;
        background-color: transparent !important;
        border-width: 2px !important;
        font-weight: 600;
    }
    
    .btn-outline-warning:hover {
        background-color: #ffc107 !important;
        color: #212529 !important;
        border-color: #ffc107 !important;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3) !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .btn-outline-warning:focus {
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25) !important;
    }
    
    .badge-info-custom {
        background: linear-gradient(135deg, #17a2b8, #138496) !important;
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
    }
</style>
@endsection

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">HRA - Hot Work Details</h4>
                <p class="text-muted mb-0">
                    HRA Permit: <strong>{{ $hraHotWork->hra_permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <!-- Status Badge - Moved to the leftmost position -->
                @if(($hraHotWork->approval_status ?? 'draft') === 'draft')
                    <span class="badge bg-secondary fs-6 px-3 py-2">
                        <i class="fas fa-file-alt me-2"></i>Draft
                    </span>
                @elseif($hraHotWork->approval_status === 'pending')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        <i class="fas fa-clock me-2"></i>Waiting for Approval
                    </span>
                @elseif($hraHotWork->approval_status === 'approved')
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check-circle me-2"></i>Approved
                    </span>
                @elseif($hraHotWork->approval_status === 'rejected')
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-times-circle me-2"></i>Rejected
                    </span>
                @endif
                
                <!-- Back Button -->
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Main Permit
                </a>
                
                <!-- Download PDF Button - only visible when approved -->
                @if($hraHotWork->approval_status === 'approved')
                <a href="{{ route('hra.hot-works.download-pdf', [$permit, $hraHotWork]) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
                @endif
                
                <!-- Action Buttons -->
                @if(($hraHotWork->approval_status ?? 'draft') === 'draft')
                    @if($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator')
                    <button type="button" class="btn btn-info" onclick="requestApproval()">
                        <i class="fas fa-paper-plane me-2"></i>Request Approval
                    </button>
                    @endif
                @elseif($hraHotWork->approval_status === 'rejected')
                    @if($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator')
                    <button type="button" class="btn btn-info btn-sm" onclick="requestApproval()">
                        <i class="fas fa-redo me-2"></i>Re-request Approval
                    </button>
                    @endif
                @endif

                <!-- Edit Button - only visible when not pending or approved -->
                @if(($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator') && 
                    !in_array($hraHotWork->approval_status, ['pending', 'approved']))
                <a href="{{ route('hra.hot-works.edit', [$permit, $hraHotWork]) }}" class="btn btn-outline-warning" style="border-color: #ffc107; color: #ffc107; background-color: transparent;">
                    <i class="fas fa-edit me-2"></i>Edit HRA
                </a>
                @endif
            </div>
            
            <!-- Approval Actions Section - Moved outside for better organization -->
            @if($hraHotWork->approval_status === 'pending')
                @php
                    $canApproveAsLocationOwner = $permit->locationOwner && $permit->locationOwner->id === auth()->id() && $hraHotWork->area_owner_approval === 'pending';
                    $canApproveAsEHS = auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS' && $hraHotWork->ehs_approval === 'pending';
                @endphp
                
                @if($canApproveAsLocationOwner || $canApproveAsEHS)
                <div class="mt-3 p-3 bg-light border rounded">
                    @if($canApproveAsLocationOwner)
                    <div class="alert alert-info mb-2">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Your approval is required as Location Owner</strong>
                    </div>
                    @endif
                    
                    @if($canApproveAsEHS)
                    <div class="alert alert-info mb-2">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Your approval is required as EHS Team Member</strong>
                    </div>
                    @endif
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="approveHRA()">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                        <button type="button" class="btn btn-danger" onclick="rejectHRA()">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Rejection Information -->
    @if($hraHotWork->approval_status === 'rejected' && $hraHotWork->rejection_reason)
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-times-circle me-2"></i>HRA Hot Work Rejected
                    </h5>
                    <p class="mb-2">
                        <strong>Rejected by:</strong> {{ $hraHotWork->rejectedBy->name ?? 'System' }} 
                        <small class="text-muted">on {{ $hraHotWork->rejected_at->format('d M Y, H:i') }}</small>
                    </p>
                    <div class="rejection-reason bg-white p-3 rounded border-start border-danger border-3">
                        <strong>Reason for Rejection:</strong><br>
                        <span class="text-dark">{{ $hraHotWork->rejection_reason }}</span>
                    </div>
                    @if($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator')
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            You can edit this HRA to address the issues mentioned above and then resubmit for approval.
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Basic Information Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-info-circle me-2"></i>Basic Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nama Pekerja:</strong>
                    <div class="mt-1">{{ $hraHotWork->worker_name ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>No HP Pekerja:</strong>
                    <div class="mt-1">{{ $hraHotWork->worker_phone ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Nama Pendamping:</strong>
                    <div class="mt-1">{{ $hraHotWork->supervisor_name ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Lokasi Kerja:</strong>
                    <div class="mt-1">{{ $hraHotWork->work_location ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Tanggal & Jam Mulai:</strong>
                    <div class="mt-1">{{ $hraHotWork->start_datetime ? $hraHotWork->start_datetime->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Tanggal & Jam Selesai:</strong>
                    <div class="mt-1">{{ $hraHotWork->end_datetime ? $hraHotWork->end_datetime->format('d/m/Y H:i') : '-' }}</div>
                </div>
                <div class="col-12 mb-3">
                    <strong>Deskripsi Pekerjaan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraHotWork->work_description ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

            <!-- HRA Hot Work Assessment -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                        <i class="fas fa-clipboard-check me-2"></i>HRA Hot Work Assessment
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Questions 1-6 -->
                        <div class="col-md-6 mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>1.</strong> Apakah alternatif pengganti pekerjaan panas (Hot work) sudah dipertimbangkan</span>
                                <span class="badge {{ $hraHotWork->q1_alternative_considered ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q1_alternative_considered ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>2.</strong> Apakah peralatan diperiksa dan apakah dalam kondisi baik?</span>
                                <span class="badge {{ $hraHotWork->q2_equipment_checked ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q2_equipment_checked ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span><strong>3.</strong> Benda mudah terbakar (flammable) & dapat terbakar (combustible) dipindah?</span>
                                    <div class="text-muted small">Jarak: {{ $hraHotWork->q3_distance ?? '-' }}m (min 12m)</div>
                                </div>
                                <span class="badge {{ $hraHotWork->q3_flammable_moved ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q3_flammable_moved ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>4.</strong> Jika tidak bisa dipindah: flammable atau combustible dilindungi oleh lembar logam dan/atau cover tahan api</span>
                                <span class="badge {{ $hraHotWork->q4_protected_cover ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q4_protected_cover ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>5.</strong> Kotoran atau debu dibersihkan?</span>
                                <span class="badge {{ $hraHotWork->q5_debris_cleaned ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q5_debris_cleaned ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>6.</strong> Area sekitar termasuk tangki, pipa, dinding, dll diperiksa sebagai antisipasi jika flammable/combustible material tersembunyi?</span>
                                <span class="badge {{ $hraHotWork->q6_area_inspected ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q6_area_inspected ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Questions 7-12 -->
                        <div class="col-md-6 mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span><strong>7.</strong> Apakah dinding yang dapat terbakar, atap dan/atau struktur lainnya ada di lokasi?</span>
                                    @if($hraHotWork->q7_flammable_structures === 1 && $hraHotWork->q7_actions_taken)
                                        <div class="text-muted small mt-1">Tindakan: {{ $hraHotWork->q7_actions_taken }}</div>
                                    @endif
                                </div>
                                <span class="badge {{ $hraHotWork->q7_flammable_structures ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q7_flammable_structures ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>8.</strong> Selimut/blanket tahan api atau screen dipasang untuk membatasi bunga api?</span>
                                <span class="badge {{ $hraHotWork->q8_fire_blanket ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q8_fire_blanket ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>9.</strong> Tutup valve otomatis, saluran pembuangan (drain), cover, dll?</span>
                                <span class="badge {{ $hraHotWork->q9_valve_drain_covered ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q9_valve_drain_covered ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>10.</strong> Isolasi ducting/conveyor/exhaust yang mungkin kemasukan bunga api atau material terbakar?</span>
                                <span class="badge {{ $hraHotWork->q10_isolation_ducting ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q10_isolation_ducting ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span><strong>11.</strong> Lubang dan lubang pembuangan tertutup (sealing pada joint, chinks, bukaan, ducting, dll)?</span>
                                <span class="badge {{ $hraHotWork->q11_holes_sealed ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q11_holes_sealed ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span><strong>12.</strong> Ventilasi cukup di lokasi pekerjaan?</span>
                                    <div class="text-muted small">
                                        Jenis: 
                                        @if($hraHotWork->q12_ventilation_type)
                                            {{ ucfirst($hraHotWork->q12_ventilation_type) }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <span class="badge {{ $hraHotWork->q12_ventilation_adequate ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->q12_ventilation_adequate ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Questions 13-17 -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-shield-alt me-2"></i>Additional Safety Measures</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span><strong>13.</strong> Peralatan listrik dan kabel terlindungi?</span>
                                        <span class="badge {{ $hraHotWork->q13_electrical_protected ? 'badge-yes' : 'badge-no' }}">
                                            {{ $hraHotWork->q13_electrical_protected ? 'YA' : 'TIDAK' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span><strong>14.</strong> Peralatan/mesin disekitarnya, pipa dan material terlindungi?</span>
                                        <span class="badge {{ $hraHotWork->q14_equipment_protected ? 'badge-yes' : 'badge-no' }}">
                                            {{ $hraHotWork->q14_equipment_protected ? 'YA' : 'TIDAK' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span><strong>15.</strong> Pekerjaan panas yang berada di atas, tambahan perlindungan disediakan di bawah?</span>
                                        <span class="badge {{ $hraHotWork->q15_overhead_protection ? 'badge-yes' : 'badge-no' }}">
                                            {{ $hraHotWork->q15_overhead_protection ? 'YA' : 'TIDAK' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <span><strong>16.</strong> Lokasi kerja diberi tanda/barikade yang memadai?</span>
                                        <span class="badge {{ $hraHotWork->q16_area_marked ? 'badge-yes' : 'badge-no' }}">
                                            {{ $hraHotWork->q16_area_marked ? 'YA' : 'TIDAK' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span><strong>17.</strong> Gas monitoring untuk kemungkinan adanya gas flammable harus dilakukan sebelum pekerjaan dilakukan</span>
                                            <div class="text-muted small">Jika "Ya" formulir H-Exposures harus diisi</div>
                                        </div>
                                        <span class="badge {{ $hraHotWork->q17_gas_monitoring ? 'badge-yes' : 'badge-no' }}">
                                            {{ $hraHotWork->q17_gas_monitoring ? 'YA' : 'TIDAK' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Peralatan Pemadam Api -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                        <i class="fas fa-fire-extinguisher me-2"></i>Peralatan Pemadam Api
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- APAR Section -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-danger mb-3"><i class="fas fa-fire-extinguisher me-2"></i>APAR (Alat Pemadam Api Ringan)</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>APAR Air</span>
                                <span class="badge {{ $hraHotWork->apar_air ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->apar_air ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>APAR Powder</span>
                                <span class="badge {{ $hraHotWork->apar_powder ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->apar_powder ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>APAR CO2</span>
                                <span class="badge {{ $hraHotWork->apar_co2 ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->apar_co2 ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Fire Blanket</span>
                                <span class="badge {{ $hraHotWork->fire_blanket ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->fire_blanket ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span>Petugas Fire Watch</span>
                                    @if($hraHotWork->fire_watch_officer === 1 && $hraHotWork->fire_watch_name)
                                        <div class="text-muted small">Nama: {{ $hraHotWork->fire_watch_name }}</div>
                                    @endif
                                </div>
                                <span class="badge {{ $hraHotWork->fire_watch_officer ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->fire_watch_officer ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Monitoring Section -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-danger mb-3"><i class="fas fa-shield-alt me-2"></i>Monitoring & Safety</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Bangunan terpasang sprinkler</span>
                                <span class="badge {{ $hraHotWork->monitoring_sprinkler ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->monitoring_sprinkler ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span style="flex: 1;">Tidak ada combustible material di konstruksi</span>
                                <span class="badge {{ $hraHotWork->monitoring_combustible ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->monitoring_combustible ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span style="flex: 1;">Material combustible minimal 11m dari lokasi kerja</span>
                                <span class="badge {{ $hraHotWork->monitoring_distance ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->monitoring_distance ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Cek kondisi sistem sprinkler</span>
                                <span class="badge {{ $hraHotWork->sprinkler_check ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->sprinkler_check ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Gas monitoring selama bekerja dibutuhkan</span>
                                <span class="badge {{ $hraHotWork->gas_monitoring_required ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->gas_monitoring_required ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Emergency Info -->
                        <div class="col-12 mb-4">
                            <h6 class="text-danger mb-3"><i class="fas fa-phone me-2"></i>Emergency Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Breakglass/Emergency Call Terdekat:</strong>
                                    <div class="mt-1 p-2 bg-light rounded">{{ $hraHotWork->emergency_call ?? '-' }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><strong>Mematikan peralatan detektor api?</strong></span>
                                        <span class="badge {{ $hraHotWork->detector_shutdown ? 'badge-yes' : 'badge-no' }}">
                                            {{ $hraHotWork->detector_shutdown ? 'YA' : 'TIDAK' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detector Shutdown Details -->
                        <div class="col-12">
                            <div class="card border-start border-4 border-danger" style="background-color: #fff3cd; border-color: #dc3545 !important;">
                                <div class="card-body">
                                    <h6 class="card-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Detail Pematian Detektor</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Pemberitahuan ke SHE & Security dibutuhkan?</span>
                                            <span class="badge {{ $hraHotWork->notification_required ? 'badge-yes' : 'badge-no' }}">
                                                {{ $hraHotWork->notification_required ? 'YA' : 'TIDAK' }}
                                            </span>
                                        </div>
                                        @if($hraHotWork->notification_required === 1)
                                            <div class="mt-2">
                                                <small><strong>Telepon:</strong> {{ $hraHotWork->notification_phone ?? '-' }}</small><br>
                                                <small><strong>Nama:</strong> {{ $hraHotWork->notification_name ?? '-' }}</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Pemberitahuan ke Asuransi dibutuhkan?</span>
                                            <span class="badge {{ $hraHotWork->insurance_notification ? 'badge-yes' : 'badge-no' }}">
                                                {{ $hraHotWork->insurance_notification ? 'YA' : 'TIDAK' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><strong>Memastikan detektor sudah mati?</strong></span>
                                            <span class="badge {{ $hraHotWork->detector_confirmed_off ? 'badge-yes' : 'badge-no' }}">
                                                {{ $hraHotWork->detector_confirmed_off ? 'YA' : 'TIDAK' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>

<!-- Request Approval Modal -->
<div class="modal fade" id="requestApprovalModal" tabindex="-1" aria-labelledby="requestApprovalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestApprovalModalLabel">Request Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda akan mengirim permintaan approval untuk HRA Hot Work ini kepada:</p>
                <ul>
                    <li><strong>Pemilik Area:</strong> {{ $permit->locationOwner ? $permit->locationOwner->name : 'Belum ditentukan' }}</li>
                    <li><strong>Tim EHS</strong></li>
                </ul>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Setelah approval diminta, HRA tidak dapat diedit sampai mendapat persetujuan atau penolakan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('hra.hot-works.request-approval', [$permit, $hraHotWork]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-paper-plane me-2"></i>Send Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve HRA Hot Work</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this HRA Hot Work?</p>
                <p class="text-success">
                    <i class="fas fa-check-circle me-2"></i>
                    By approving, you confirm that all safety requirements have been reviewed and are acceptable.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('hra.hot-works.process-approval', [$permit, $hraHotWork]) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Approve
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject HRA Hot Work</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm" action="{{ route('hra.hot-works.process-approval', [$permit, $hraHotWork]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="4" required 
                                  placeholder="Please provide a detailed reason for rejecting this HRA Hot Work..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="rejectForm" class="btn btn-danger">
                    <i class="fas fa-times me-2"></i>Reject
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function requestApproval() {
    const modal = new bootstrap.Modal(document.getElementById('requestApprovalModal'));
    modal.show();
}

function approveHRA() {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function rejectHRA() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>

@include('layouts.sidebar-scripts')
@endsection
