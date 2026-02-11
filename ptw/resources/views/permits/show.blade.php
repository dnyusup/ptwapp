@extends('layouts.app')

@section('styles')
<style>
.icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.info-item {
    transition: all 0.3s ease;
    padding: 10px 0;
}

.info-item:hover {
    transform: translateY(-2px);
}

.description-box {
    border: 1px solid #e9ecef;
    min-height: 60px;
}

.text-purple {
    color: #6f42c1 !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

.text-brown {
    color: #8B4513 !important;
}

.bg-brown {
    background-color: #8B4513 !important;
}

.border-brown {
    border-color: #8B4513 !important;
}

/* Excavation card specific styles for better contrast */
.bg-brown.bg-opacity-10 {
    background-color: rgba(139, 69, 19, 0.15) !important;
}

.bg-brown.bg-opacity-20 {
    background-color: rgba(139, 69, 19, 0.25) !important;
}

.text-brown {
    color: #654321 !important; /* Darker brown for better contrast */
}

.work-type-selected {
    transition: all 0.3s ease;
}

.work-type-selected:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    color: #000;
}

.fs-7 {
    font-size: 0.875rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
}

.card-header.bg-gradient-primary {
    border-bottom: none;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-radius: 15px 15px 0 0;
}

.badge.rounded-pill {
    padding: 8px 16px;
    font-weight: 600;
}

/* Completion card styles */
.completion-info .status-box {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.completion-info .status-box:hover {
    border-color: #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-box {
    background: #f8f9fa;
    font-size: 0.9rem;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
}
    letter-spacing: 0.5px;
}

/* Enhanced button styles for better visibility */
.btn-enhanced {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 25px;
    padding: 0.6rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.btn-enhanced-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border-color: #dc3545;
}

.btn-enhanced-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
    color: white;
    border-color: #c82333;
}

/* HRA Action buttons specific styling */
.hra-action-buttons .btn {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 20px;
    padding: 0.5rem 1.2rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    border-width: 2px;
}

.hra-action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.hra-action-buttons .btn-outline-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    color: #000;
    border-color: #ffc107;
}

.hra-action-buttons .btn-outline-warning:hover {
    background: linear-gradient(135deg, #ff8c00 0%, #ffc107 100%);
    color: #000;
    border-color: #ff8c00;
    transform: translateY(-2px) scale(1.02);
}

.hra-action-buttons .btn-warning {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border-color: #17a2b8;
}

.hra-action-buttons .btn-warning:hover {
    background: linear-gradient(135deg, #138496 0%, #17a2b8 100%);
    color: white;
    border-color: #138496;
    transform: translateY(-2px) scale(1.02);
}

/* PDF Button enhanced styling */
.btn-enhanced-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border-color: #dc3545;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.btn-enhanced-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
    color: white;
    border-color: #c82333;
    transform: translateY(-3px);
}

.btn-enhanced-danger:active {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

@media (max-width: 768px) {
    .icon-box {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }
    
    .info-item {
        padding: 8px 0;
    }
    
    .btn-enhanced {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
    
    .hra-action-buttons .btn {
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
    }
    
    .d-flex.justify-content-between.align-items-center {
        flex-direction: column;
        align-items: stretch !important;
        gap: 1rem;
    }
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
                <h4 class="mb-1">Permit Details</h4>
                <p class="text-muted mb-0">View permit to work information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('permits.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permits
                </a>
                
                @php
                    $currentUser = auth()->user();
                    $isEHS = $currentUser && $currentUser->role === 'bekaert' && $currentUser->department === 'EHS';
                    
                    // Simplified conditions for Request Approval button
                    $hasMethodStatement = $permit->methodStatement ? true : false;
                    $hasEmergencyPlan = $permit->emergencyPlan ? true : false;
                    $msCompleted = $hasMethodStatement && $permit->methodStatement->status === 'completed';
                    $epCompleted = $hasEmergencyPlan && $permit->emergencyPlan->status === 'completed';
                    $isCreatorOrAdmin = ($permit->permit_issuer_id == auth()->id()) || ($currentUser && $currentUser->role === 'administrator');
                    $isDraft = $permit->status === 'draft';
                    
                    $canRequestApproval = $msCompleted && $epCompleted && $isCreatorOrAdmin && $isDraft;
                @endphp

                {{-- Request Approval Button --}}
                @if($canRequestApproval)
                    <form id="request-approval-form" method="POST" action="{{ route('permits.request-approval', $permit) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-paper-plane me-2"></i>Request Approval
                        </button>
                    </form>
                @elseif(!$msCompleted)
                    <button type="button" class="btn btn-outline-secondary" disabled title="Method Statement must be completed first">
                        <i class="fas fa-lock me-2"></i>Request Approval (Method Statement Required)
                    </button>
                @elseif(!$epCompleted)
                    <button type="button" class="btn btn-outline-secondary" disabled title="Emergency & Escape Plan must be completed first">
                        <i class="fas fa-lock me-2"></i>Request Approval (Emergency Plan Required)
                    </button>
                @elseif($msCompleted && $epCompleted && !$isCreatorOrAdmin && $isDraft)
                    <button type="button" class="btn btn-outline-secondary" disabled title="Only permit creator can request approval">
                        <i class="fas fa-lock me-2"></i>Request Approval (Access Denied)
                    </button>
                @endif

                {{-- Single Approval Button for Both Permit and Method Statement --}}
                @if($isEHS && in_array($permit->status, ['pending_approval', 'resubmitted']))
                    <form method="POST" action="{{ route('permits.approve', $permit) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('Are you sure you want to approve this permit{{ $permit->methodStatement ? ' and method statement' : '' }}?')">
                            <i class="fas fa-check me-2"></i>Approve{{ $permit->methodStatement ? ' All' : '' }}
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-2"></i>Reject
                    </button>
                @endif

                {{-- Extension Approval Buttons for EHS --}}
                @if($isEHS && $permit->status === 'pending_extension_approval')
                    <form method="POST" action="{{ route('permits.approve-extension', $permit) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('Are you sure you want to approve this permit extension?')">
                            <i class="fas fa-check me-2"></i>Approve Extension
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectExtensionModal">
                        <i class="fas fa-times me-2"></i>Reject Extension
                    </button>
                @endif

                @if(($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator') && 
                    in_array($permit->status, ['draft', 'rejected']))
                    <a href="{{ route('permits.edit', $permit) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Permit Information -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Status Header Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">{{ $permit->permit_number ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0 small">Permit to Work</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                @switch($permit->status)
                                    @case('pending')
                                    @case('pending_approval')
                                        <span class="badge bg-warning text-dark px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-clock me-1"></i>Pending Approval
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i>Approved
                                        </span>
                                        @break
                                    @case('active')
                                        <span class="badge bg-success px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-play-circle me-1"></i>Active
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                        @break
                                    @case('resubmitted')
                                        <span class="badge bg-info px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-redo me-1"></i>Resubmitted
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-info px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-flag-checkered me-1"></i>Completed
                                        </span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-danger px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Expired
                                        </span>
                                        @break
                                    @case('pending_extension_approval')
                                        <span class="badge bg-warning px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-clock me-1"></i>Pending Extension Approval
                                        </span>
                                        @break
                                    @case('draft')
                                        <span class="badge bg-secondary px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-edit me-1"></i>Draft
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary px-3 py-2 fs-7 rounded-pill">
                                            <i class="fas fa-question-circle me-1"></i>{{ ucfirst($permit->status) }}
                                        </span>
                                @endswitch
                            </div>
                            @if(in_array($permit->status, ['active', 'completed']))
                            <a href="{{ route('permits.download-pdf', $permit) }}" class="btn btn-enhanced btn-enhanced-danger" title="Download PDF">
                                <i class="fas fa-file-pdf me-2"></i>Download PDF
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Information Card -->
            @if($permit->status === 'completed' && $permit->completed_at)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-check-circle me-2"></i>Pekerjaan Selesai
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="completion-info">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-clipboard-check me-2"></i>Status Pekerjaan
                                </h6>
                                <div class="status-box p-3 rounded-3 bg-light border">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-{{ $permit->work_status === 'selesai' ? 'success' : 'warning' }} me-2">
                                            <i class="fas fa-{{ $permit->work_status === 'selesai' ? 'check' : 'clock' }} me-1"></i>
                                            {{ $permit->work_status === 'selesai' ? 'Selesai' : 'Belum Selesai' }}
                                        </span>
                                    </div>
                                    @if($permit->work_status_detail)
                                        <div class="detail-box bg-white p-2 rounded border">
                                            <small class="text-muted d-block mb-1">Detail:</small>
                                            <span class="text-dark">{{ $permit->work_status_detail }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="completion-info">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="fas fa-cogs me-2"></i>Status Area/Instalasi/Peralatan
                                </h6>
                                <div class="status-box p-3 rounded-3 bg-light border">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-{{ $permit->area_installation_status === 'siap_dioperasikan' ? 'success' : 'warning' }} me-2">
                                            <i class="fas fa-{{ $permit->area_installation_status === 'siap_dioperasikan' ? 'check' : 'exclamation-triangle' }} me-1"></i>
                                            {{ $permit->area_installation_status === 'siap_dioperasikan' ? 'Siap Dioperasikan' : 'Belum Siap' }}
                                        </span>
                                    </div>
                                    @if($permit->area_installation_detail)
                                        <div class="detail-box bg-white p-2 rounded border">
                                            <small class="text-muted d-block mb-1">Detail:</small>
                                            <span class="text-dark">{{ $permit->area_installation_detail }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completion Meta Info -->
                    <div class="row g-4 mt-3 pt-3 border-top">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Diselesaikan oleh:</small>
                                    <span class="fw-semibold">{{ $permit->completedBy->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Tanggal Selesai:</small>
                                    <span class="fw-semibold">{{ $permit->completed_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-clipboard-list me-2"></i>Informasi Pekerjaan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Work Title & Department -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Work Title</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->work_title ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Department</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Description -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Work Description</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->work_description ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Work Location</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->work_location ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-user-cog"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Location Owner</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">
                                        @if($permit->locationOwner)
                                            {{ $permit->locationOwner->name }}<br>
                                            <span class="text-muted small">{{ $permit->locationOwner->email }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Equipment & Tools</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->equipment_tools ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Area Photo -->
                    @if($permit->work_area_photo)
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="info-item border-start border-info border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-info bg-opacity-10 text-info me-3 rounded-3">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Foto Area Kerja</label>
                                </div>
                                <div class="ms-5">
                                    @php
                                        $photoUrl = url('media/' . $permit->work_area_photo);
                                    @endphp
                                    <a href="{{ $photoUrl }}" target="_blank" data-bs-toggle="tooltip" title="Klik untuk melihat ukuran penuh">
                                        <img src="{{ $photoUrl }}" alt="Work Area Photo" class="img-fluid rounded shadow-sm" style="max-height: 300px; cursor: pointer;" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-muted\'>Foto tidak dapat dimuat</span>';">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Dates -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">Start Date</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->start_date ? $permit->start_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item border-start border-secondary border-3 ps-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3 rounded-3">
                                        <i class="fas fa-calendar-minus"></i>
                                    </div>
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">End Date</label>
                                </div>
                                <div class="ms-5">
                                    <p class="mb-0 text-dark fw-medium">{{ $permit->end_date ? $permit->end_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personnel Information -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-dark bg-opacity-10 text-dark me-3">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <label class="form-label fw-semibold text-dark mb-0">Responsible Person</label>
                                </div>
                                @if($permit->responsible_person)
                                    <p class="ms-5 mb-1 text-muted">{{ $permit->responsible_person }}</p>
                                    @if($permit->responsible_person_email)
                                        <p class="ms-5 mb-0 text-muted small">
                                            <i class="fas fa-envelope me-1"></i>{{ $permit->responsible_person_email }}
                                        </p>
                                    @endif
                                @else
                                    <p class="ms-5 mb-0 text-muted">N/A</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                        <i class="fas fa-hard-hat"></i>
                                    </div>
                                    <label class="form-label fw-semibold text-dark mb-0">Receiver/Pelaksana</label>
                                </div>
                                @if($permit->receiver_name)
                                    <p class="ms-5 mb-1 text-muted">{{ $permit->receiver_name }}</p>
                                    <p class="ms-5 mb-1 text-muted small">
                                        <i class="fas fa-envelope me-1"></i>{{ $permit->receiver_email }}
                                    </p>
                                    @if($permit->receiver_company_name)
                                        <p class="ms-5 mb-0 text-muted small">
                                            <i class="fas fa-building me-1"></i>{{ $permit->receiver_company_name }}
                                        </p>
                                    @endif
                                @else
                                    <p class="ms-5 mb-0 text-muted">Not assigned</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Creation Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-muted">
                        <i class="fas fa-info-circle me-2"></i>Creation Information
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <label class="form-label fw-semibold text-dark mb-0 small">Created By</label>
                                </div>
                                <p class="ms-5 mb-0 text-muted small">{{ $permit->permitIssuer->name ?? 'N/A' }}</p>
                                <span class="ms-5 badge bg-secondary bg-opacity-20 text-dark small">{{ ucfirst($permit->permitIssuer->role ?? 'unknown') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box bg-info bg-opacity-10 text-info me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <label class="form-label fw-semibold text-dark mb-0 small">Created Date</label>
                                </div>
                                <p class="ms-5 mb-0 text-muted small">{{ $permit->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspection Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-clipboard-check me-2"></i>Inspection
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($permit->inspections && $permit->inspections->count() > 0)
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Inspection Completed</h6>
                                    <p class="mb-0 text-muted small">
                                        Total Inspections: <span class="badge bg-primary">{{ $permit->inspections->count() }}</span>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        Last Inspection: {{ $permit->inspections->last()->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('inspections.index', $permit->permit_number) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                @if($permit->status === 'active')
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inspectionModal">
                                    <i class="fas fa-plus me-1"></i>Add Inspection
                                </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">No Inspection Yet</h6>
                                    <p class="mb-0 text-muted small">
                                        Belum ada inspection untuk permit ini.
                                    </p>
                                </div>
                            </div>
                            @if($permit->status === 'active')
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inspectionModal">
                                <i class="fas fa-plus me-1"></i>Create Inspection
                            </button>
                            @else
                            <button type="button" class="btn btn-secondary btn-sm" disabled title="Inspection can only be created when permit status is Active">
                                <i class="fas fa-lock me-1"></i>Create Inspection
                            </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Method Statement Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-info text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-file-alt me-2"></i>Method Statement
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($permit->methodStatement)
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Method Statement Completed</h6>
                                    <p class="mb-0 text-muted small">
                                        Status: <span class="badge bg-{{ $permit->methodStatement->status === 'completed' ? 'success' : ($permit->methodStatement->status === 'approved' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($permit->methodStatement->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        Created: {{ $permit->methodStatement->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('method-statements.show', $permit->permit_number) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                @if($permit->methodStatement->status === 'draft' || auth()->user()->role === 'administrator')
                                <a href="{{ route('method-statements.edit', $permit->permit_number) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Method Statement Required</h6>
                                    <p class="mb-0 text-muted small">
                                        Method Statement belum dibuat untuk permit ini.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('method-statements.create', $permit->permit_number) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Create Method Statement
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Emergency & Escape Plan Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-danger text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-shield-alt me-2"></i>Emergency & Escape Plan
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($permit->emergencyPlan)
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Emergency & Escape Plan Completed</h6>
                                    <p class="mb-0 text-muted small">
                                        Status: <span class="badge bg-{{ $permit->emergencyPlan->status === 'completed' ? 'success' : ($permit->emergencyPlan->status === 'approved' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($permit->emergencyPlan->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        Created: {{ $permit->emergencyPlan->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('emergency-plans.show', $permit->permit_number) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                @if($permit->emergencyPlan->status === 'draft' || auth()->user()->role === 'administrator')
                                <a href="{{ route('emergency-plans.edit', $permit->permit_number) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Emergency & Escape Plan Required</h6>
                                    <p class="mb-0 text-muted small">
                                        Emergency & Escape Plan belum dibuat untuk permit ini.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('emergency-plans.create', $permit->permit_number) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>Create Emergency Plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Work Types Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient-warning text-dark">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-clipboard-check me-2"></i>Izin Kerja (PTW) HRA yang Diperlukan
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $workTypes = [
                            'work_at_heights' => ['label' => 'Bekerja di Ketinggian (Work at Heights)', 'icon' => 'fas fa-building', 'color' => 'warning'],
                            'hot_work' => ['label' => 'Pekerjaan Panas (Hot Work)', 'icon' => 'fas fa-fire', 'color' => 'danger'],
                            'loto_isolation' => ['label' => 'LOTOTO - Isolasi/Menghilangkan Energi', 'icon' => 'fas fa-lock', 'color' => 'info'],
                            'line_breaking' => ['label' => 'Mematikan Line (Line breaking)', 'icon' => 'fas fa-cut', 'color' => 'secondary'],
                            'excavation' => ['label' => 'Penggalian (Excavation)', 'icon' => 'fas fa-shovel', 'color' => 'brown'],
                            'confined_spaces' => ['label' => 'Memasuki Ruang Terbatas (Entering Confined spaces)', 'icon' => 'fas fa-cube', 'color' => 'dark'],
                            'explosive_atmosphere' => ['label' => 'Atmosfer berbahaya (Explosive atmosphere)', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'danger']
                        ];
                        
                        $hasWorkTypes = false;
                        foreach($workTypes as $key => $type) {
                            if($permit->$key) {
                                $hasWorkTypes = true;
                                break;
                            }
                        }
                    @endphp

                    @if($hasWorkTypes)
                        <div class="row g-3">
                            @foreach($workTypes as $key => $type)
                                @if($permit->$key)
                                <div class="col-12">
                                    <div class="work-type-selected p-3 rounded-3 border border-{{ $type['color'] }} bg-{{ $type['color'] }} bg-opacity-10">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box {{ $type['color'] === 'brown' ? 'bg-brown text-white' : 'bg-' . $type['color'] . ' bg-opacity-20 text-' . $type['color'] }} me-3">
                                                    <i class="{{ $type['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold text-{{ $type['color'] === 'brown' ? 'brown' : $type['color'] }}">
                                                        {{ $type['label'] }}
                                                    </span>
                                                    <div class="small text-muted">Diperlukan untuk pekerjaan ini</div>
                                                    
                                                    @if($key === 'work_at_heights')
                                                        @php
                                                            $hraCount = $permit->hraWorkAtHeights->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @elseif($key === 'hot_work')
                                                        @php
                                                            $hraCount = $permit->hraHotWorks->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @elseif($key === 'loto_isolation')
                                                        @php
                                                            $hraCount = $permit->hraLotoIsolations->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @elseif($key === 'line_breaking')
                                                        @php
                                                            $hraCount = $permit->hraLineBreakings->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @elseif($key === 'excavation')
                                                        @php
                                                            $hraCount = $permit->hraExcavations->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @elseif($key === 'confined_spaces')
                                                        @php
                                                            $hraCount = $permit->hraConfinedSpaces->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @elseif($key === 'explosive_atmosphere')
                                                        @php
                                                            $hraCount = $permit->hraExplosiveAtmospheres->count();
                                                        @endphp
                                                        @if($hraCount > 0)
                                                            <div class="small text-success mt-1">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                {{ $hraCount }} HRA Permit{{ $hraCount > 1 ? 's' : '' }} created
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($key === 'work_at_heights')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.work-at-heights.create', $permit->id) }}" 
                                                           class="btn btn-outline-warning d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraWorkAtHeights->count() > 0)
                                                        <a href="{{ route('hra.work-at-heights.index', $permit->id) }}" 
                                                           class="btn btn-warning d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($key === 'hot_work')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.hot-works.create', $permit->id) }}" 
                                                           class="btn btn-outline-danger d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraHotWorks->count() > 0)
                                                        <a href="{{ route('hra.hot-works.index', $permit->id) }}" 
                                                           class="btn btn-danger d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($key === 'loto_isolation')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.loto-isolations.create', $permit->id) }}" 
                                                           class="btn btn-outline-info d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraLotoIsolations->count() > 0)
                                                        <a href="{{ route('hra.loto-isolations.index', $permit->id) }}" 
                                                           class="btn btn-info d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($key === 'line_breaking')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.line-breakings.create', $permit->id) }}" 
                                                           class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraLineBreakings->count() > 0)
                                                        <a href="{{ route('hra.line-breakings.index', $permit->id) }}" 
                                                           class="btn btn-secondary d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($key === 'excavation')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.excavations.create', $permit->id) }}" 
                                                           class="btn btn-outline-brown d-flex align-items-center justify-content-center" style="--bs-btn-color: #8B4513; --bs-btn-border-color: #8B4513; --bs-btn-hover-bg: #8B4513; --bs-btn-hover-color: #fff;">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraExcavations->count() > 0)
                                                        <a href="{{ route('hra.excavations.index', $permit->id) }}" 
                                                           class="btn d-flex align-items-center justify-content-center" style="background-color: #8B4513; color: white;">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($key === 'confined_spaces')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.confined-spaces.create', $permit->id) }}" 
                                                           class="btn btn-outline-dark d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraConfinedSpaces->count() > 0)
                                                        <a href="{{ route('hra.confined-spaces.index', $permit->id) }}" 
                                                           class="btn btn-dark d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($key === 'explosive_atmosphere')
                                                <div class="d-flex flex-column gap-2 hra-action-buttons">
                                                    @if($permit->status === 'active')
                                                        <a href="{{ route('hra.explosive-atmospheres.create', $permit->id) }}" 
                                                           class="btn btn-outline-danger d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-plus me-2"></i>
                                                            <span>Create HRA</span>
                                                        </a>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-secondary d-flex align-items-center justify-content-center" 
                                                                disabled 
                                                                title="HRA can only be created when permit status is Active">
                                                            <i class="fas fa-lock me-2"></i>
                                                            <span>Create HRA</span>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($permit->hraExplosiveAtmospheres->count() > 0)
                                                        <a href="{{ route('hra.explosive-atmospheres.index', $permit->id) }}" 
                                                           class="btn btn-danger d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-eye me-2"></i>
                                                            <span>View HRA</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-muted fa-2x mb-3"></i>
                            <p class="text-muted mb-0">Tidak ada izin kerja khusus yang diperlukan untuk pekerjaan ini</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Risk Assessment & Method Statement Section -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-shield-alt me-2"></i>Penilaian Risiko & Pernyataan Metode
                    </h5>
                    <small class="text-white-50">Status evaluasi keamanan dan risiko pekerjaan</small>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Risk Method Assessment -->
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 border-2 {{ $permit->risk_method_assessment === 'ya' ? 'border-success' : 'border-danger' }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $permit->risk_method_assessment === 'ya' ? 'bg-success text-white' : 'bg-danger text-white' }} me-3">
                                        <i class="fas {{ $permit->risk_method_assessment === 'ya' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">
                                            Penilaian Risiko & Pernyataan Metode
                                        </div>
                                        <div class="small text-secondary mb-1">Ditinjau dan Disetujui?</div>
                                        <div class="fw-bold {{ $permit->risk_method_assessment === 'ya' ? 'text-success' : 'text-danger' }}">
                                            {{ strtoupper($permit->risk_method_assessment) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chemical Usage Storage -->
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 border-2 {{ $permit->chemical_usage_storage === 'ya' ? 'border-success' : 'border-danger' }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $permit->chemical_usage_storage === 'ya' ? 'bg-success text-white' : 'bg-danger text-white' }} me-3">
                                        <i class="fas {{ $permit->chemical_usage_storage === 'ya' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">
                                            Penggunaan & Penyimpanan Bahan Kimia
                                        </div>
                                        <div class="small text-secondary mb-1">Ditinjau dan Disetujui?</div>
                                        <div class="fw-bold {{ $permit->chemical_usage_storage === 'ya' ? 'text-success' : 'text-danger' }}">
                                            {{ strtoupper($permit->chemical_usage_storage) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Condition -->
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 border-2 {{ $permit->equipment_condition === 'ya' ? 'border-success' : 'border-danger' }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $permit->equipment_condition === 'ya' ? 'bg-success text-white' : 'bg-danger text-white' }} me-3">
                                        <i class="fas {{ $permit->equipment_condition === 'ya' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">
                                            Kondisi Peralatan
                                        </div>
                                        <div class="small text-secondary mb-1">Ditinjau dan Disetujui?</div>
                                        <div class="fw-bold {{ $permit->equipment_condition === 'ya' ? 'text-success' : 'text-danger' }}">
                                            {{ strtoupper($permit->equipment_condition) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Asbestos Presence -->
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 border-2 {{ $permit->asbestos_presence === 'tidak' ? 'border-success' : 'border-warning' }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $permit->asbestos_presence === 'tidak' ? 'bg-success text-white' : 'bg-warning text-dark' }} me-3">
                                        <i class="fas {{ $permit->asbestos_presence === 'tidak' ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">
                                            Keberadaan Asbes
                                        </div>
                                        <div class="small text-secondary mb-1">Apakah Asbes Ada di Area/Peralatan/Infrastruktur?</div>
                                        <div class="fw-bold {{ $permit->asbestos_presence === 'tidak' ? 'text-success' : 'text-warning' }}">
                                            {{ strtoupper($permit->asbestos_presence) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ATEX Area -->
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 border-2 {{ $permit->atex_area === 'tidak' ? 'border-success' : 'border-warning' }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $permit->atex_area === 'tidak' ? 'bg-success text-white' : 'bg-warning text-dark' }} me-3">
                                        <i class="fas {{ $permit->atex_area === 'tidak' ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">
                                            Area ATEX
                                        </div>
                                        <div class="small text-secondary mb-1">Pekerjaan dalam area ATEX? (Mandatory Authoriser)</div>
                                        <div class="fw-bold {{ $permit->atex_area === 'tidak' ? 'text-success' : 'text-warning' }}">
                                            {{ strtoupper($permit->atex_area) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gas Storage Area -->
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3 border-2 {{ $permit->gas_storage_area === 'tidak' ? 'border-success' : 'border-warning' }}">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $permit->gas_storage_area === 'tidak' ? 'bg-success text-white' : 'bg-warning text-dark' }} me-3">
                                        <i class="fas {{ $permit->gas_storage_area === 'tidak' ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">
                                            Area Penyimpanan Gas
                                        </div>
                                        <div class="small text-secondary mb-1">Pekerjaan di area penyimpanan gas/cairan mudah terbakar? (Mandatory Authoriser)</div>
                                        <div class="fw-bold {{ $permit->gas_storage_area === 'tidak' ? 'text-success' : 'text-warning' }}">
                                            {{ strtoupper($permit->gas_storage_area) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Rejection Information -->
            @if($permit->status === 'rejected' && $permit->rejection_reason)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Rejection Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Rejection:</label>
                        <p class="text-muted mb-2">{{ $permit->rejection_reason }}</p>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">
                                <strong>Rejected by:</strong><br>
                                {{ $permit->rejectedBy->name ?? 'N/A' }}
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">
                                <strong>Rejected at:</strong><br>
                                {{ $permit->rejected_at ? $permit->rejected_at->format('d M Y, H:i') : 'N/A' }}
                            </small>
                        </div>
                    </div>
                    @if($permit->permit_issuer_id == auth()->id())
                    <hr>
                    <div class="text-center">
                        <p class="text-muted mb-3"><small>You can edit your permit and resubmit for approval</small></p>
                        <a href="{{ route('permits.edit', $permit) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit Permit
                        </a>
                        <form method="POST" action="{{ route('permits.resubmit', $permit) }}">
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Are you sure you want to resubmit this permit for approval?')">
                                    <i class="fas fa-redo me-2"></i>Resubmit for Approval
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Work Completion and Extension -->
            @if(($permit->status === 'active' || $permit->status === 'expired' || $permit->status === 'pending_extension_approval') && ($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator'))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        @if($permit->status === 'expired')
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>Expired Permit Actions
                        @elseif($permit->status === 'pending_extension_approval')
                            <i class="fas fa-clock text-warning me-2"></i>Extension Pending Approval
                        @else
                            Work Completion
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Mark as Complete -->
                    <div class="d-grid mb-3">
                        <button type="button" class="btn btn-info text-white fw-bold" 
                                style="background-color: #0dcaf0; border-color: #0dcaf0; color: #fff !important;"
                                data-bs-toggle="modal" data-bs-target="#completePermitModal">
                            <i class="fas fa-check-circle me-2"></i>Mark as Completed
                        </button>
                    </div>

                    @if($permit->status === 'expired')
                    <!-- Extend Permit -->
                    <div class="d-grid">
                        <button type="button" class="btn btn-warning text-dark fw-bold" 
                                style="background-color: #ffc107; border-color: #ffc107; color: #000 !important;"
                                data-bs-toggle="modal" data-bs-target="#extendPermitModal">
                            <i class="fas fa-calendar-plus me-2"></i>Extend Permit
                        </button>
                    </div>
                    @elseif($permit->status === 'pending_extension_approval')
                    <!-- Extension Status Info -->
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Extension request submitted. Waiting for EHS approval.
                        @if($permit->extension_reason)
                            <br><strong>Reason:</strong> {{ $permit->extension_reason }}
                        @endif
                        @if($permit->end_date)
                            <br><strong>Requested End Date:</strong> {{ $permit->end_date->format('d M Y') }}
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Permit Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $timelineEvents = collect();
                            
                            // Permit Draft
                            $timelineEvents->push([
                                'timestamp' => $permit->created_at,
                                'title' => 'Permit Draft',
                                'by' => $permit->permitIssuer->name ?? 'N/A',
                                'color' => 'bg-secondary'
                            ]);
                            
                            // Method Statement Created
                            if ($permit->methodStatement) {
                                $timelineEvents->push([
                                    'timestamp' => $permit->methodStatement->created_at,
                                    'title' => 'Method Statement Created',
                                    'by' => $permit->methodStatement->creator->name ?? 'N/A',
                                    'color' => 'bg-warning'
                                ]);
                            }
                            
                            // Emergency Plan Created
                            if ($permit->emergencyPlan) {
                                $timelineEvents->push([
                                    'timestamp' => $permit->emergencyPlan->created_at,
                                    'title' => 'Emergency & Escape Plan Created',
                                    'by' => $permit->emergencyPlan->creator->name ?? 'N/A',
                                    'color' => 'bg-danger'
                                ]);
                            }
                            
                            // Final Status
                            if ($permit->status === 'pending_approval') {
                                $timelineEvents->push([
                                    'timestamp' => $permit->updated_at,
                                    'title' => 'Pending Approval',
                                    'by' => null,
                                    'color' => 'bg-warning'
                                ]);
                            } elseif ($permit->status === 'approved') {
                                $timelineEvents->push([
                                    'timestamp' => $permit->updated_at,
                                    'title' => 'Permit Approved',
                                    'by' => null,
                                    'color' => 'bg-success'
                                ]);
                            } elseif ($permit->status === 'active') {
                                $timelineEvents->push([
                                    'timestamp' => $permit->updated_at,
                                    'title' => 'Permit Activated',
                                    'by' => $permit->authorizer ? $permit->authorizer->name : null,
                                    'color' => 'bg-success'
                                ]);
                            } elseif ($permit->status === 'rejected') {
                                $timelineEvents->push([
                                    'timestamp' => $permit->updated_at,
                                    'title' => 'Permit Rejected',
                                    'by' => null,
                                    'color' => 'bg-danger'
                                ]);
                            } elseif ($permit->status === 'completed') {
                                $timelineEvents->push([
                                    'timestamp' => $permit->updated_at,
                                    'title' => 'Work Completed',
                                    'by' => null,
                                    'color' => 'bg-info'
                                ]);
                            }
                            
                            // Sort by timestamp DESC (newest first)
                            $timelineEvents = $timelineEvents->sortByDesc('timestamp');
                        @endphp
                        
                        @foreach($timelineEvents as $event)
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $event['color'] }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $event['title'] }}</h6>
                                <small class="text-muted">{{ $event['timestamp']->format('d M Y H:i') }}</small>
                                @if($event['by'])
                                    <p class="small mb-0">By {{ $event['by'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Safety Guidelines Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Safety Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <h6>Safety Reminders:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-hard-hat text-warning me-2"></i>Wear appropriate PPE</li>
                            <li><i class="fas fa-exclamation-triangle text-danger me-2"></i>Follow safety procedures</li>
                            <li class="fas fa-phone text-info me-2"></i>Emergency contact ready</li>
                            <li><i class="fas fa-users text-success me-2"></i>Supervisor supervision</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('permits.reject', $permit) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle me-2"></i>Reject Permit
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This permit will be rejected and returned to the creator for revision.
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-bold">
                            <span class="text-danger">*</span> Reason for Rejection:
                        </label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" 
                                  placeholder="Please provide a detailed reason for rejecting this permit..." 
                                  required minlength="10" maxlength="1000"></textarea>
                        <div class="form-text">Minimum 10 characters, maximum 1000 characters</div>
                    </div>
                    <div class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        The permit creator will be able to see this reason and edit their permit before resubmitting.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i>Reject Permit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Extension Modal -->
<div class="modal fade" id="rejectExtensionModal" tabindex="-1" aria-labelledby="rejectExtensionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('permits.reject-extension', $permit) }}">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectExtensionModalLabel">
                        <i class="fas fa-times-circle me-2"></i>Reject Extension Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Are you sure you want to reject this extension request? The permit will remain expired.
                    </div>
                    <div class="mb-3">
                        <label for="extension_rejection_reason" class="form-label">
                            <i class="fas fa-comment me-2"></i>Reason for Rejection <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="extension_rejection_reason" name="rejection_reason" 
                                rows="3" placeholder="Please provide a reason for rejecting this extension request..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i>Reject Extension
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Inspection Modal -->
<div class="modal fade" id="inspectionModal" tabindex="-1" aria-labelledby="inspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="inspectionModalLabel">
                    <i class="fas fa-clipboard-check me-2"></i>Create Inspection
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inspectionForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Permit Number:</strong> {{ $permit->permit_number }}<br>
                        <strong>Work Title:</strong> {{ $permit->work_title }}
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inspector_name" class="form-label fw-semibold">Inspector Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inspector_name" name="inspector_name" 
                                   value="{{ auth()->user()->name }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="inspector_email" class="form-label fw-semibold">Inspector Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="inspector_email" name="inspector_email" 
                                   value="{{ auth()->user()->email }}" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="findings" class="form-label fw-semibold">Inspection Findings <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="findings" name="findings" rows="6" 
                                  placeholder="Masukkan hasil temuan inspeksi, kondisi keselamatan, observasi, dan rekomendasi..." required></textarea>
                        <div class="form-text">
                            Jelaskan hasil inspeksi secara detail termasuk kondisi keselamatan yang ditemukan, 
                            kepatuhan terhadap prosedur, dan rekomendasi perbaikan jika ada.
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Inspection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// Inspection form submission
document.getElementById('inspectionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Clear previous errors
    form.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    
    fetch('{{ route("inspections.store", $permit->permit_number) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        // Get response as text first to see what we're receiving
        return response.text().then(text => {
            console.log('Raw response:', text.substring(0, 200));
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 100)}`);
            }
            
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response was:', text);
                throw new Error('Invalid JSON response received');
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'toast position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="toast-header bg-success text-white">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${data.message}
                </div>
            `;
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Hide modal and reload page
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('inspectionModal')).hide();
                location.reload();
            }, 2000);
        } else {
            throw new Error(data.message || 'Something went wrong');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error message with detailed info
        const toast = document.createElement('div');
        toast.className = 'toast position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${error.message || 'Failed to save inspection. Please try again.'}
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    })
    .finally(() => {
        // Restore button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Reset form when modal is shown
document.getElementById('inspectionModal').addEventListener('show.bs.modal', function() {
    const form = document.getElementById('inspectionForm');
    form.reset();
    
    // Set default values
    document.getElementById('inspector_name').value = '{{ auth()->user()->name }}';
    document.getElementById('inspector_email').value = '{{ auth()->user()->email }}';
    
    // Clear validation states
    form.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
});

// Extend Permit Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    @if($permit->status === 'expired' && ($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator'))
    // Calculate max extend date (5 days after current end date)
    const endDate = new Date('{{ $permit->end_date->format('Y-m-d') }}');
    const maxDate = new Date(endDate);
    maxDate.setDate(maxDate.getDate() + 5);
    
    // Set min date to current end date + 1 day
    const minDate = new Date(endDate);
    minDate.setDate(minDate.getDate() + 1);
    
    const extendDateInput = document.getElementById('extend_end_date');
    if (extendDateInput) {
        extendDateInput.min = minDate.toISOString().split('T')[0];
        extendDateInput.max = maxDate.toISOString().split('T')[0];
        
        // Set default value to max date
        extendDateInput.value = maxDate.toISOString().split('T')[0];
    }
    @endif
    
    // Completion form validation
    const completionForm = document.getElementById('completionForm');
    if (completionForm) {
        completionForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous validation states
            this.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            // Validate work status detail
            const workStatusDetail = document.getElementById('work_status_detail');
            if (workStatusDetail && workStatusDetail.value.trim().length < 10) {
                workStatusDetail.classList.add('is-invalid');
                workStatusDetail.nextElementSibling.textContent = 'Detail status pekerjaan minimal 10 karakter.';
                isValid = false;
            }
            
            // Validate area installation detail
            const areaDetail = document.getElementById('area_installation_detail');
            if (areaDetail && areaDetail.value.trim().length < 10) {
                areaDetail.classList.add('is-invalid');
                areaDetail.nextElementSibling.textContent = 'Detail status area/instalasi/peralatan minimal 10 karakter.';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Show error toast
                const toast = document.createElement('div');
                toast.className = 'toast position-fixed top-0 end-0 m-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <div class="toast-header bg-danger text-white">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong class="me-auto">Validation Error</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        Mohon lengkapi semua field yang wajib diisi dengan minimal 10 karakter.
                    </div>
                `;
                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        });
    }
});
</script>

<!-- Complete Permit Modal -->
@if(($permit->status === 'active' || $permit->status === 'expired') && ($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator'))
<div class="modal fade" id="completePermitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Mark Permit as Completed
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="completionForm" method="POST" action="{{ route('permits.complete', $permit) }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Permit:</strong> {{ $permit->permit_number }}<br>
                        <strong>Work:</strong> {{ $permit->work_title }}
                    </div>
                    
                    <div class="row">
                        <!-- Status Pekerjaan -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i class="fas fa-clipboard-check me-2"></i>Status Pekerjaan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="work_status" 
                                               id="work_selesai" value="selesai" required>
                                        <label class="form-check-label fw-semibold text-success" for="work_selesai">
                                            <i class="fas fa-check-circle me-1"></i>Selesai
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="work_status" 
                                               id="work_belum_selesai" value="belum_selesai" required>
                                        <label class="form-check-label fw-semibold text-warning" for="work_belum_selesai">
                                            <i class="fas fa-clock me-1"></i>Belum Selesai
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="work_status_detail" class="form-label small">
                                            Detail <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control form-control-sm" id="work_status_detail" 
                                                  name="work_status_detail" rows="3" 
                                                  placeholder="Berikan detail tentang status pekerjaan..."
                                                  required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Area/Instalasi/Peralatan -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold text-info">
                                        <i class="fas fa-cogs me-2"></i>Status Area/Instalasi/Peralatan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="area_installation_status" 
                                               id="area_siap" value="siap_dioperasikan" required>
                                        <label class="form-check-label fw-semibold text-success" for="area_siap">
                                            <i class="fas fa-check-circle me-1"></i>Siap Dioperasikan
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="area_installation_status" 
                                               id="area_belum_siap" value="belum_siap" required>
                                        <label class="form-check-label fw-semibold text-warning" for="area_belum_siap">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Belum Siap
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="area_installation_detail" class="form-label small">
                                            Detail <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control form-control-sm" id="area_installation_detail" 
                                                  name="area_installation_detail" rows="3" 
                                                  placeholder="Berikan detail tentang status area/instalasi/peralatan..."
                                                  required></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i>Mark as Completed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Extend Permit Modal -->
@if($permit->status === 'expired' && ($permit->permit_issuer_id == auth()->id() || auth()->user()->role === 'administrator'))
<div class="modal fade" id="extendPermitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-plus me-2"></i>Extend Permit
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('permits.extend', $permit) }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Current End Date:</strong> {{ $permit->end_date->format('d M Y') }}<br>
                        <small>You can extend this permit up to <strong>5 days</strong> from the original end date.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="extend_end_date" class="form-label fw-bold">New End Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="extend_end_date" 
                               name="end_date" 
                               required>
                        <div class="form-text">
                            Maximum date: {{ $permit->end_date->addDays(5)->format('d M Y') }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="extension_reason" class="form-label fw-bold">Reason for Extension</label>
                        <textarea class="form-control" 
                                  id="extension_reason" 
                                  name="extension_reason" 
                                  rows="3" 
                                  placeholder="Please provide reason for extending this permit..."
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold" 
                            style="background-color: #ffc107; border-color: #ffc107; color: #000 !important;">
                        <i class="fas fa-calendar-plus me-2"></i>Extend Permit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@include('layouts.sidebar-scripts')
@endsection
