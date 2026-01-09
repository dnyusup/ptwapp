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
    
    .badge-yes:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
        transition: all 0.2s ease;
    }
    
    .badge-no:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
        transition: all 0.2s ease;
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
    
    .badge-info-custom:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
        transition: all 0.2s ease;
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
                <h4 class="mb-1">HRA - Work at Heights Details</h4>
                <p class="text-muted mb-0">
                    HRA Permit: <strong>{{ $hraWorkAtHeight->hra_permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <!-- Status Badge -->
                @if(($hraWorkAtHeight->approval_status ?? 'draft') === 'draft')
                    <span class="badge bg-secondary fs-6 px-3 py-2">
                        <i class="fas fa-file-alt me-2"></i>Draft
                    </span>
                @elseif($hraWorkAtHeight->approval_status === 'pending')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        <i class="fas fa-clock me-2"></i>Waiting for Approval
                    </span>
                @elseif($hraWorkAtHeight->approval_status === 'approved')
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check-circle me-2"></i>Approved
                    </span>
                @elseif($hraWorkAtHeight->approval_status === 'rejected')
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-times-circle me-2"></i>Rejected
                    </span>
                @endif
                
                <!-- Back Button -->
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Main Permit
                </a>
                
                <!-- Download PDF Button - only visible when approved -->
                @if($hraWorkAtHeight->approval_status === 'approved')
                <a href="{{ route('hra.work-at-heights.download-pdf', [$permit, $hraWorkAtHeight]) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
                @endif
                
                <!-- Action Buttons -->
                @if(($hraWorkAtHeight->approval_status ?? 'draft') === 'draft')
                    @if($hraWorkAtHeight->user_id == auth()->id() || auth()->user()->role === 'administrator')
                    <button type="button" class="btn btn-info" onclick="requestApproval()">
                        <i class="fas fa-paper-plane me-2"></i>Request Approval
                    </button>
                    @endif
                @elseif($hraWorkAtHeight->approval_status === 'rejected')
                    @if($hraWorkAtHeight->user_id == auth()->id() || auth()->user()->role === 'administrator')
                    <button type="button" class="btn btn-info btn-sm" onclick="requestApproval()">
                        <i class="fas fa-redo me-2"></i>Re-request Approval
                    </button>
                    @endif
                @endif

                <!-- Edit Button - only visible when not pending or approved -->
                @if((auth()->user()->role === 'administrator' || auth()->user()->id === $hraWorkAtHeight->user_id) && 
                    !in_array($hraWorkAtHeight->approval_status ?? 'draft', ['pending', 'approved']))
                <a href="{{ route('hra.work-at-heights.edit', [$permit, $hraWorkAtHeight]) }}" class="btn btn-outline-warning" style="border-color: #ffc107; color: #ffc107; background-color: transparent;">
                    <i class="fas fa-edit me-2"></i>Edit HRA
                </a>
                @endif
            </div>
        </div>
        
        <!-- Approval Actions Section -->
        @if($hraWorkAtHeight->approval_status === 'pending')
            @php
                $canApproveAsEHS = auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS' && $hraWorkAtHeight->ehs_approval === 'pending';
            @endphp
            
            @if($canApproveAsEHS)
            <div class="mt-3 p-3 bg-light border rounded">
                <div class="alert alert-info mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Your approval is required as EHS Team Member</strong>
                </div>
                
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Rejection Information -->
    @if($hraWorkAtHeight->approval_status === 'rejected' && $hraWorkAtHeight->rejection_reason)
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-times-circle me-2"></i>HRA Work at Height Rejected
                    </h5>
                    <p class="mb-2">
                        <strong>Rejected by:</strong> {{ $hraWorkAtHeight->rejector->name ?? 'System' }} 
                        @if($hraWorkAtHeight->rejected_at)
                        <small class="text-muted">on {{ $hraWorkAtHeight->rejected_at->format('d M Y, H:i') }}</small>
                        @endif
                    </p>
                    <div class="rejection-reason bg-white p-3 rounded border-start border-danger border-3">
                        <strong>Reason for Rejection:</strong><br>
                        <span class="text-dark">{{ $hraWorkAtHeight->rejection_reason }}</span>
                    </div>
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
                    <strong>Worker Name:</strong>
                    <div class="mt-1">{{ $hraWorkAtHeight->worker_name }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Phone Number:</strong>
                    <div class="mt-1">{{ $hraWorkAtHeight->worker_phone ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Supervisor Name:</strong>
                    <div class="mt-1">{{ $hraWorkAtHeight->supervisor_name }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Work Location:</strong>
                    <div class="mt-1">{{ $hraWorkAtHeight->work_location }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Start Time:</strong>
                    <div class="mt-1">{{ \Carbon\Carbon::parse($hraWorkAtHeight->start_datetime)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>End Time:</strong>
                    <div class="mt-1">{{ \Carbon\Carbon::parse($hraWorkAtHeight->end_datetime)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-12 mb-3">
                    <strong>Work Description:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraWorkAtHeight->work_description }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- HRA Assessment Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-clipboard-check me-2"></i>HRA Work at Heights Assessment
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Overhead Hazards -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Layanan overhead/bahaya?</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Layanan overhead/bahaya?</strong>
                        <span class="badge {{ $hraWorkAtHeight->overhead_hazards_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->overhead_hazards_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->overhead_hazards_checked)
                        <div class="sub-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Tutup benturan, minimal, digunakan</span>
                                <span class="badge {{ $hraWorkAtHeight->overhead_hazards_minimal_guards ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraWorkAtHeight->overhead_hazards_minimal_guards ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Fixed Scaffolding -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-building me-2"></i>Fixed Scaffolding</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Fixed Scaffolding</strong>
                        <span class="badge {{ $hraWorkAtHeight->fixed_scaffolding_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fixed_scaffolding_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->fixed_scaffolding_checked)
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Sudah disetujui oleh SHE PTBI?</span>
                        <span class="badge {{ $hraWorkAtHeight->fixed_scaffolding_approved_by_she ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fixed_scaffolding_approved_by_she ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Mobile Scaffolding -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-dolly me-2"></i>Mobile Scaffolding</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Mobile scaffolding</strong>
                        <span class="badge {{ $hraWorkAtHeight->mobile_scaffolding_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_scaffolding_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->mobile_scaffolding_checked)
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Sudah disetujui oleh SHE PTBI?</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_scaffolding_approved_by_she ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_scaffolding_approved_by_she ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Mobile Elevated Working Platform (MEWP) -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-arrow-up me-2"></i>Mobile Elevated Working Platform (MEWP)</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Mobile Elevated Working Platform (MEWP)</strong>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->mobile_elevation_checked)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Operator terlatih</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_operator_trained ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_operator_trained ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Orang yang berkompeten untuk melakukan penyelamatan</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_rescue_person ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_rescue_person ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pemantau di tempat untuk semua pergerakan MEWP</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_monitor_in_place ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_monitor_in_place ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Catatan pemeriksaan hukum valid</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_legal_inspection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_legal_inspection ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pemeriksaan pra-penggunaan yang terdokumentasi telah selesai</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_pre_use_inspection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_pre_use_inspection ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Tangga -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-stairs me-2"></i>Tangga</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Tangga</strong>
                        <span class="badge {{ $hraWorkAtHeight->ladder_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->ladder_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->ladder_checked)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_activities_short ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_activities_short ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Diperiksa dan di-tag</span>
                        <span class="badge {{ $hraWorkAtHeight->ladder_area_barriers ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->ladder_area_barriers ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Fall Arrest -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-life-ring me-2"></i>APD WAH</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>APD WAH diperlukan? (peralatan penangkap dan penahan jatuh)</strong>
                        <span class="badge {{ $hraWorkAtHeight->fall_arrest_used ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fall_arrest_used ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->fall_arrest_used)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pekerja yang terlatih dalam penggunaan</span>
                        <span class="badge {{ $hraWorkAtHeight->fall_arrest_worker_trained ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fall_arrest_worker_trained ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Catatan pemeriksaan hukum valid</span>
                        <span class="badge {{ $hraWorkAtHeight->fall_arrest_legal_inspection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fall_arrest_legal_inspection ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pemeriksaan pra-penggunaan</span>
                        <span class="badge {{ $hraWorkAtHeight->fall_arrest_pre_use_inspection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fall_arrest_pre_use_inspection ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Sarana pengikatan yang ditentukan oleh personel yang berkualifikasi</span>
                        <span class="badge {{ $hraWorkAtHeight->fall_arrest_qualified_personnel ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fall_arrest_qualified_personnel ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Roof Work -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-home me-2"></i>Roof Work</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Pekerjaan di Atap (Roof Work)</strong>
                        <span class="badge {{ $hraWorkAtHeight->roof_work_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_work_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->roof_work_checked)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Kapasitas menahan beban atap cukup</span>
                        <span class="badge {{ $hraWorkAtHeight->roof_load_capacity_adequate ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_load_capacity_adequate ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Penggunaan perlindungan tepi</span>
                        <span class="badge {{ $hraWorkAtHeight->roof_edge_protection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_edge_protection ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Penggunaan sistem perlindungan jatuh/ WaH PPE</span>
                        <span class="badge {{ $hraWorkAtHeight->roof_fall_protection_system ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_fall_protection_system ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Metode komunikasi yang disepakati</span>
                        <span class="badge {{ $hraWorkAtHeight->roof_communication_method ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_communication_method ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Work Conditions Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
            <h5 class="card-title mb-0" style="color: white; font-weight: 600;">
                <i class="fas fa-exclamation-triangle me-2"></i>Work Conditions
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah mereka yang terlibat memiliki bukti Pelatihan Bekerja di Ketinggian</span>
                        <span class="badge {{ $hraWorkAtHeight->workers_have_training_proof ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->workers_have_training_proof ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah area di bawah tempat kerja telah diblokir</span>
                        <span class="badge {{ $hraWorkAtHeight->area_below_blocked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->area_below_blocked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah ada yang bekerja di bawah mereka yang bekerja di ketinggian</span>
                        <span class="badge {{ $hraWorkAtHeight->workers_below_present ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->workers_below_present ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah lantai/tanah cocok untuk digunakannya peralatan akses</span>
                        <span class="badge {{ $hraWorkAtHeight->floor_suitable_for_access_equipment ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->floor_suitable_for_access_equipment ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah ada kendala di atau dekat lokasi kerja</span>
                        <span class="badge {{ $hraWorkAtHeight->obstacles_near_work_location ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->obstacles_near_work_location ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah ada ventilasi, cerobong asapyang dapat mengeluarkan media panas/berbau/berbahaya</span>
                        <span class="badge {{ $hraWorkAtHeight->ventilation_hazardous_emissions ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->ventilation_hazardous_emissions ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah perlindungan dibutuhkan untuk peralatan akses WaH</span>
                        <span class="badge {{ $hraWorkAtHeight->protection_needed_for_equipment ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->protection_needed_for_equipment ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah ada metode akses & keluar yang aman</span>
                        <span class="badge {{ $hraWorkAtHeight->safe_access_exit_method ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->safe_access_exit_method ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah cara yang aman untuk menaik turunkan material dan peralatan telah ditentukan</span>
                        <span class="badge {{ $hraWorkAtHeight->safe_material_handling_method ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->safe_material_handling_method ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Apakah diperlukan rencana darurat & pelarian</span>
                        <span class="badge {{ $hraWorkAtHeight->emergency_escape_plan_needed ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->emergency_escape_plan_needed ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Lainnya</span>
                        <span class="badge {{ $hraWorkAtHeight->other_conditions_check ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->other_conditions_check ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                @if($hraWorkAtHeight->other_conditions_check && $hraWorkAtHeight->other_conditions_text)
                <div class="col-12 mb-3">
                    <div class="d-flex flex-column">
                        <span class="fw-semibold mb-2">Keterangan kondisi lain:</span>
                        <div class="p-3 bg-light rounded">{{ $hraWorkAtHeight->other_conditions_text }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Environmental Conditions Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-cloud-rain me-2"></i>Environmental Conditions
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Jarak pandang umum</span>
                        <span class="badge badge-info-custom">{{ $hraWorkAtHeight->visibility_condition ?? 'Not Set' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Hujan</span>
                        <span class="badge badge-info-custom">{{ $hraWorkAtHeight->rain_condition ?? 'Not Set' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Kondisi permukaan tanah/lantai</span>
                        <span class="badge badge-info-custom">{{ $hraWorkAtHeight->surface_condition ?? 'Not Set' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Angin</span>
                        <span class="badge badge-info-custom">{{ $hraWorkAtHeight->wind_condition ?? 'Not Set' }}</span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Permukaan licin dari tumpahan oli atau bahan kimia?</span>
                        <span class="badge badge-info-custom">{{ $hraWorkAtHeight->chemical_spill_condition ?? 'Not Set' }}</span>
                    </div>
                </div>
                @if($hraWorkAtHeight->environment_other_conditions)
                <div class="col-12 mb-3">
                    <div class="d-flex flex-column">
                        <span class="fw-semibold mb-2">Lainnya:</span>
                        <div class="p-3 bg-light rounded">{{ $hraWorkAtHeight->environment_other_conditions }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pengendalian Tambahan Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);">
            <h5 class="mb-0">
                <i class="fas fa-shield-alt me-2"></i>Pengendalian Tambahan
            </h5>
        </div>
        <div class="card-body">
            @if($hraWorkAtHeight->additional_controls)
                <div class="p-3 bg-light rounded">
                    {{ $hraWorkAtHeight->additional_controls }}
                </div>
            @else
                <p class="text-muted mb-0">No additional controls specified.</p>
            @endif
        </div>
    </div>

</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST" action="{{ route('hra.work-at-heights.process', [$permit, $hraWorkAtHeight]) }}">
                @csrf
                <input type="hidden" name="action" value="approve">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Approve HRA Work at Height</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to approve HRA Work at Height <strong>{{ $hraWorkAtHeight->hra_permit_number }}</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label">Comments (Optional)</label>
                        <textarea name="comments" class="form-control" rows="3" placeholder="Add any comments..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST" action="{{ route('hra.work-at-heights.process', [$permit, $hraWorkAtHeight]) }}">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Reject HRA Work at Height</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to reject HRA Work at Height <strong>{{ $hraWorkAtHeight->hra_permit_number }}</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')

@push('scripts')
<script>
// Request Approval
function requestApproval() {
    Swal.fire({
        title: 'Request Approval',
        text: 'Are you sure you want to submit this HRA Work at Height for approval?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Request Approval',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("hra.work-at-heights.request-approval", [$permit, $hraWorkAtHeight]) }}';
            
            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Approve HRA
function approveHRA() {
    let modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    modal.show();
}

// Reject HRA
function rejectHRA() {
    let modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endpush
@endsection
