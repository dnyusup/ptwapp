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
                @php $displayStatus = $hraHotWork->displayStatus(); @endphp
                @if($displayStatus === 'Draft')
                    <span class="badge bg-secondary fs-6 px-3 py-2">
                        <i class="fas fa-file-alt me-2"></i>Draft
                    </span>
                @elseif($displayStatus === 'Waiting for Approval')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        <i class="fas fa-clock me-2"></i>Waiting for Approval
                    </span>
                @elseif($displayStatus === 'Waiting Inspection')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        <i class="fas fa-clipboard-check me-2"></i>Waiting Inspection
                    </span>
                @elseif($displayStatus === 'Approved')
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check-circle me-2"></i>Approved
                    </span>
                @elseif($displayStatus === 'Rejected')
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-times-circle me-2"></i>Rejected
                    </span>
                @endif
                
                <!-- Back Button -->
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Main Permit
                </a>
                
                @php
                    $currentUser = auth()->user();
                    $approvalStatus = $hraHotWork->approval_status ?? 'draft';
                    $isDraft = $approvalStatus == 'draft';
                    $isRejected = $approvalStatus == 'rejected';
                    $isApproved = $approvalStatus == 'approved';
                    $isPending = $approvalStatus == 'pending';
                    
                    $isCreator = $currentUser && $hraHotWork->user_id == $currentUser->id;
                    $isPermitIssuer = $currentUser && $permit->permit_issuer_id == $currentUser->id;
                    $isAdmin = $currentUser && $currentUser->role == 'administrator';
                    
                    $canRequestApproval = ($isCreator || $isPermitIssuer || $isAdmin) && ($isDraft || $isRejected);
                    $canEdit = ($isCreator || $isPermitIssuer || $isAdmin) && !$isPending && !$isApproved;
                @endphp

                <!-- Download PDF Button - only visible when approved -->
                @if($isApproved)
                <a href="{{ route('hra.hot-works.download-pdf', ['permit' => $permit, 'hraHotWork' => $hraHotWork]) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
                @endif
                
                <!-- Action Buttons -->
                @if($canRequestApproval)
                    @if($isDraft)
                    <button type="button" class="btn btn-info" onclick="requestApproval()">
                        <i class="fas fa-paper-plane me-2"></i>Request Approval
                    </button>
                    @elseif($isRejected)
                    <button type="button" class="btn btn-info btn-sm" onclick="requestApproval()">
                        <i class="fas fa-redo me-2"></i>Re-request Approval
                    </button>
                    @endif
                @endif

                <!-- Edit Button - only visible when not pending or approved -->
                @if($canEdit)
                <a href="{{ route('hra.hot-works.edit', ['permit' => $permit, 'hraHotWork' => $hraHotWork]) }}" class="btn btn-warning" style="background-color: #ffc107; color: #000; border-color: #ffc107;">
                    <i class="fas fa-edit me-2"></i>Edit HRA
                </a>
                @endif
            </div>
            
            <!-- Approval Actions Section - Moved outside for better organization -->
            @if($hraHotWork->approval_status === 'pending')
                @php
                    $canApproveAsEHS = auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS' && $hraHotWork->ehs_approval === 'pending';
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
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    <!-- Main Content Layout -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">

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
                        @if($hraHotWork->work_area_photo)
                        <div class="col-12 mb-3">
                            <strong><i class="fas fa-camera me-2"></i>Foto Lokasi Area Kerja:</strong>
                            <div class="mt-2">
                                @php
                                    // Use /media/ route to serve photo (bypasses symlink requirement)
                                    $photoUrl = url('media/' . $hraHotWork->work_area_photo);
                                @endphp
                                
                                <a href="{{ $photoUrl }}" target="_blank" data-bs-toggle="tooltip" title="Klik untuk melihat ukuran penuh">
                                    <img src="{{ $photoUrl }}" alt="Work Area Photo" class="img-fluid rounded" style="max-height: 400px; cursor: pointer;" 
                                         onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'alert alert-warning\'>Foto tidak dapat dimuat. Path: {{ $hraHotWork->work_area_photo }}</div>';">
                                </a>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-info-circle me-1"></i>Klik foto untuk melihat dalam ukuran penuh
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($hraHotWork->created_via)
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-{{ $hraHotWork->created_via === 'Mobile' ? 'mobile-alt' : 'desktop' }} me-1"></i>
                            Dibuat dari: <strong>{{ $hraHotWork->created_via }}</strong>
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            @if($hraHotWork->ehs_approval === 'approved')
            @php
                $required   = $hraHotWork->requiredInspectionCount();
                $doneCount  = $hraHotWork->inspections->count();
                $inspStatus = $hraHotWork->inspection_status;
                $inspBadge  = $inspStatus === 'Complete' ? 'success'
                            : (str_contains($inspStatus, 'NOK') ? 'danger'
                            : ($inspStatus === 'Not Inspected' ? 'secondary' : 'warning'));
                $canInspect = auth()->user()->role === 'administrator'
                    || (auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS')
                    || $hraHotWork->user_id == auth()->id()
                    || $permit->permit_issuer_id == auth()->id();
            @endphp
            <!-- Inspection Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #6610f2 0%, #6f42c1 100%); color: white; border: none;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-clipboard-check me-2"></i>Inspection
                        </h5>
                        <span class="badge bg-{{ $inspBadge }}">{{ $inspStatus }} &middot; {{ $doneCount }}/{{ $required }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Wajib <strong>{{ $required }}</strong> inspeksi
                        @if($hraHotWork->additional_inspection_duration)
                            (durasi inspeksi tambahan: <strong>{{ $hraHotWork->additional_inspection_duration }}</strong>)
                        @endif.
                        Inspeksi #1 aktif sekarang; inspeksi berikutnya aktif 30 menit setelah inspeksi sebelumnya dicatat.
                    </p>

                    <div class="row g-3">
                        @for($seq = 1; $seq <= $required; $seq++)
                            @php
                                $insp     = $hraHotWork->inspections->firstWhere('sequence', $seq);
                                $prevDone = $seq === 1 || $hraHotWork->inspections->firstWhere('sequence', $seq - 1);
                                $unlocked = $hraHotWork->isInspectionSlotUnlocked($seq);
                                $unlockAt = $hraHotWork->inspectionSlotUnlockAt($seq);
                            @endphp
                            <div class="col-md-6">
                                <div class="border rounded h-100 p-3 {{ $insp ? 'bg-light' : ($unlocked ? 'border-primary' : 'bg-light') }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Inspeksi #{{ $seq }}</strong>
                                        @if($insp)
                                            <span class="badge bg-{{ $insp->finding_type === 'NOK' ? 'danger' : 'success' }}">{{ $insp->finding_type }}</span>
                                        @elseif($unlocked)
                                            <span class="badge bg-primary">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-lock me-1"></i>Terkunci</span>
                                        @endif
                                    </div>

                                    @if($insp)
                                        <div class="small">
                                            <div class="mb-1"><i class="fas fa-clock me-1 text-muted"></i>{{ $insp->inspected_at->format('d/m/Y H:i') }}</div>
                                            <div class="mb-1"><i class="fas fa-user me-1 text-muted"></i>{{ $insp->inspector_name }}
                                                <span class="text-muted">({{ $insp->inspector_email }})</span></div>
                                            <div class="mb-2 p-2 bg-white border rounded">{{ $insp->findings }}</div>
                                            @if($insp->photo_path)
                                                <a href="{{ url('media/' . $insp->photo_path) }}" target="_blank">
                                                    <img src="{{ url('media/' . $insp->photo_path) }}" alt="Foto Inspeksi #{{ $seq }}"
                                                         class="img-fluid rounded" style="max-height: 160px;">
                                                </a>
                                            @endif
                                            @if($insp->inspectedBy)
                                                <div class="text-muted mt-1">Dicatat oleh: {{ $insp->inspectedBy->name }}</div>
                                            @endif
                                        </div>
                                    @elseif($unlocked)
                                        @if($canInspect)
                                            <button type="button" class="btn btn-sm btn-primary hra-insp-open"
                                                    data-bs-toggle="modal" data-bs-target="#hraInspectionModal"
                                                    data-seq="{{ $seq }}">
                                                <i class="fas fa-plus me-1"></i>Isi Inspeksi #{{ $seq }}
                                            </button>
                                        @else
                                            <span class="text-muted small">Menunggu inspeksi oleh petugas berwenang.</span>
                                        @endif
                                    @else
                                        <div class="text-muted small">
                                            @if(!$prevDone)
                                                <i class="fas fa-lock me-1"></i>Selesaikan Inspeksi #{{ $seq - 1 }} dulu.
                                            @else
                                                <i class="fas fa-hourglass-half me-1"></i>Aktif pada
                                                <strong>{{ optional($unlockAt)->format('d/m/Y H:i') }}</strong>
                                                <span class="d-block">(30 menit setelah Inspeksi #{{ $seq - 1 }})</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            @endif

            <!-- Pre-Assessment Questions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                        <i class="fas fa-question-circle me-2"></i>Pre-Assessment Questions
                    </h5>
                </div>
                <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                    <!-- Question: Can Hot Work be avoided -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <span><strong>1.</strong> Apakah Pekerjaan Panas dapat dihindari? (Jika "Y", berhenti, tinjau metode untuk menghilangkan bahaya, misalnya pekerjaan panas)</span>
                            <span class="badge {{ $hraHotWork->hot_work_avoidable ? 'badge-yes' : 'badge-no' }}">
                                {{ $hraHotWork->hot_work_avoidable ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                    </div>

                    <!-- Question: Can Hot Work be done in designated area -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <span><strong>2.</strong> Bisakah Pekerjaan Panas dilakukan di Area Pekerjaan Panas yang telah ditentukan? (Jika "Y", hentikan dan pindahkan lokasi pekerjaan panas)</span>
                            <span class="badge {{ $hraHotWork->hot_work_designated_area ? 'badge-yes' : 'badge-no' }}">
                                {{ $hraHotWork->hot_work_designated_area ? 'Ya' : 'Tidak' }}
                            </span>
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
                    <!-- Section 1: Persyaratan dalam jarak 11m/35ft -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-ruler me-2"></i>Persyaratan dalam jarak 11m/35ft dari pekerjaan panas (termasuk di atas dan di bawah area kerja)
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>1.</strong> Semua bahan yang mudah terbakar disingkirkan atau dilindungi dengan penutup tahan api</span>
                                    <span class="badge {{ $hraHotWork->flammable_materials_removed ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->flammable_materials_removed ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>2.</strong> Cairan mudah terbakar, debu, serat, dan endapan minyak dihilangkan (debu di "dalam" dinding/atap/rongga)</span>
                                    <span class="badge {{ $hraHotWork->flammable_liquids_removed ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->flammable_liquids_removed ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>3.</strong> Lantai yang mudah terbakar dibasahi, ditutup dengan pasir basah atau penutup tahan api yang tumpang tindih</span>
                                    <span class="badge {{ $hraHotWork->flammable_floors_wetted ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->flammable_floors_wetted ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>4.</strong> Dinding/langit-langit/atap yang mudah terbakar dilindungi dengan penutup tahan api</span>
                                    <span class="badge {{ $hraHotWork->walls_ceiling_protected ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->walls_ceiling_protected ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>5.</strong> Lantai disapu bersih dari bahan yang mudah terbakar</span>
                                    <span class="badge {{ $hraHotWork->floors_swept_clean ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->floors_swept_clean ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>6.</strong> Material mudah terbakar di sisi lain dinding, langit-langit atau atap disingkirkan (perhatikan insulasinya)</span>
                                    <span class="badge {{ $hraHotWork->materials_other_side_removed ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->materials_other_side_removed ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>7.</strong> Atmosfer yang mudah meledak dihilangkan</span>
                                    <span class="badge {{ $hraHotWork->explosive_atmosphere_removed ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->explosive_atmosphere_removed ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>8.</strong> Semua bukaan dinding/lantai, termasuk saluran pembuangan, ditutup dengan penutup tahan api</span>
                                    <span class="badge {{ $hraHotWork->wall_floor_openings_covered ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->wall_floor_openings_covered ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>9.</strong> Saluran, konveyor, katup/saluran pembuangan yang terbuka secara otomatis, dll, terlindungi, terisolasi, atau keduanya</span>
                                    <span class="badge {{ $hraHotWork->ducts_conveyors_protected ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->ducts_conveyors_protected ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>10.</strong> Jika ada risiko kebakaran dari konduksi/radiasi, misalnya di sepanjang balok, tindakan pencegahan tambahan diterapkan</span>
                                    <span class="badge {{ $hraHotWork->fire_risk_prevention_applied ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->fire_risk_prevention_applied ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Peralatan tertutup -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-tools me-2"></i>Persyaratan saat bekerja pada peralatan tertutup
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>11.</strong> Peralatan dibersihkan dari semua bahan yang mudah terbakar</span>
                                    <span class="badge {{ $hraHotWork->equipment_cleaned_flammable ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->equipment_cleaned_flammable ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>12.</strong> Wadah dikosongkan, dibersihkan, dan diuji bebas dari cairan dan uap yang mudah terbakar (Lengkapi form-H)</span>
                                    <span class="badge {{ $hraHotWork->containers_emptied_cleaned ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->containers_emptied_cleaned ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Panel bangunan/material -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-building me-2"></i>Panel bangunan/material
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>13.</strong> Panel bangunan/material yang sedang dikerjakan adalah diketahui tidak mudah terbakar</span>
                                    <span class="badge {{ $hraHotWork->building_materials_non_flammable ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->building_materials_non_flammable ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>14.</strong> Jika "tidak" pada pertanyaan di atas, bahan yang mudah terbakar HARUS dipotong hingga minimal 50 cm dan dilindungi oleh bahan pelindung yang tidak mudah terbakar</span>
                                    <span class="badge {{ $hraHotWork->flammable_materials_cut_protected ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->flammable_materials_cut_protected ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Ventilasi -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-wind me-2"></i>Ventilasi
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span><strong>15.</strong> Ventilasi yang cukup di tempat kerja</span>
                                        <div class="text-muted small">
                                            Jenis: 
                                            @if($hraHotWork->ventilation_type)
                                                {{ ucfirst($hraHotWork->ventilation_type) }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge {{ $hraHotWork->ventilation_adequate ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->ventilation_adequate ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Lampu tiup dan tabung gas -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-fire me-2"></i>Lampu tiup dan tabung gas
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>16.</strong> Lampu tiup dan tabung gas hanya boleh dipasang atau diganti di area terbuka dan berventilasi baik</span>
                                    <span class="badge {{ $hraHotWork->gas_lamps_open_area ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->gas_lamps_open_area ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Peralatan dan pengelasan -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-cogs me-2"></i>Peralatan dan pengelasan
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>17.</strong> Apakah semua peralatan telah dipasang dan pengalasan dimonitor dari pengelasan dalam kondisi baik?</span>
                                    <span class="badge {{ $hraHotWork->equipment_installed_monitored ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->equipment_installed_monitored ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Pemberitahuan pekerja -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-users me-2"></i>Pemberitahuan pekerja
                        </h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span><strong>18.</strong> Semua pekerja yang ada di area tersebut diberitahu tentang pekerjaan panas yang sedang dilakukan</span>
                                    <span class="badge {{ $hraHotWork->workers_notified ? 'badge-yes' : 'badge-no' }}">
                                        {{ $hraHotWork->workers_notified ? 'Ya' : 'Tidak' }}
                                    </span>
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
                                <span>APAR Foam</span>
                                <span class="badge {{ $hraHotWork->apar_foam ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->apar_foam ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Fire Blanket</span>
                                <span class="badge {{ $hraHotWork->fire_blanket ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->fire_blanket ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Systems Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: white; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                        <i class="fas fa-exclamation-triangle me-2"></i>Emergency Systems
                    </h5>
                </div>
                <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                    <!-- Emergency Call Point -->
                    <div class="mb-4">
                        <strong>Titik panggilan darurat/alarm kebakaran terdekat:</strong>
                        <div class="mt-1 p-2 bg-light rounded">{{ $hraHotWork->emergency_call_point ?? '-' }}</div>
                    </div>

                    <!-- Sprinkler System -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <span>Apakah sistem penyiram otomatis (jika ada) tidak berfungsi?</span>
                            <span class="badge {{ $hraHotWork->sprinkler_system_disabled ? 'badge-yes' : 'badge-no' }}">
                                {{ $hraHotWork->sprinkler_system_disabled ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                    </div>

                    <!-- Fire Detection System -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <span>Apakah sistem deteksi kebakaran (jika berlaku) harus diisolasi >10 jam?</span>
                            <span class="badge {{ $hraHotWork->fire_detection_isolated ? 'badge-yes' : 'badge-no' }}">
                                {{ $hraHotWork->fire_detection_isolated ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                    </div>

                    <!-- Insurers Notification (if applicable) -->
                    @if($hraHotWork->fire_detection_isolated)
                    <div class="p-3 bg-warning bg-opacity-10 border-start border-warning border-3">
                        <h6 class="text-warning mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Insurers Notification</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Insurers notified of isolation:</strong>
                                @if($hraHotWork->isolation_notified_by || $hraHotWork->isolation_notified_when)
                                    <div class="mt-1">
                                        @if($hraHotWork->isolation_notified_by)
                                            <div class="text-muted small">Oleh: {{ $hraHotWork->isolation_notified_by }}</div>
                                        @endif
                                        @if($hraHotWork->isolation_notified_when)
                                            <div class="text-muted small">Kapan: {{ $hraHotWork->isolation_notified_when }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-1 text-muted">-</div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Insurers notified of reinstatement:</strong>
                                @if($hraHotWork->reinstatement_notified_by || $hraHotWork->reinstatement_notified_when)
                                    <div class="mt-1">
                                        @if($hraHotWork->reinstatement_notified_by)
                                            <div class="text-muted small">Oleh: {{ $hraHotWork->reinstatement_notified_by }}</div>
                                        @endif
                                        @if($hraHotWork->reinstatement_notified_when)
                                            <div class="text-muted small">Kapan: {{ $hraHotWork->reinstatement_notified_when }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-1 text-muted">-</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fire Watch Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); color: white; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                        <i class="fas fa-fire-extinguisher me-2"></i>Fire Watch
                    </h5>
                </div>
                <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                    <!-- Fire Watch Officer -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span>Petugas Fire Watch (harus dilatih dalam bahaya, risiko, dan pengendalian kebakaran. Pelatihan terverifikasi?)</span>
                                @if($hraHotWork->fire_watch_officer === 1 && $hraHotWork->fire_watch_name)
                                    <div class="text-muted small mt-2">Nama Petugas: {{ $hraHotWork->fire_watch_name }}</div>
                                @endif
                            </div>
                            <span class="badge {{ $hraHotWork->fire_watch_officer ? 'badge-yes' : 'badge-no' }}">
                                {{ $hraHotWork->fire_watch_officer ? 'YA' : 'TIDAK' }}
                            </span>
                        </div>
                    </div>

                    <!-- Monitoring Requirements -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">Membutuhkan monitoring:</h6>
                        
                        <!-- Sprinkler Protection -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <span><strong>1.</strong> Bangunan dilindungi oleh alat penyiram otomatis</span>
                                <span class="badge {{ $hraHotWork->monitoring_sprinkler ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->monitoring_sprinkler ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Combustible Materials -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span><strong>2.</strong> Tidak ada bahan yang mudah terbakar yang digunakan pada konstruksi atap/langit-langit, dinding atau lantai</span>
                                    <div class="text-muted small">Tidak memberikan penilaian jika TIDAK YAKIN</div>
                                </div>
                                <span class="badge {{ $hraHotWork->monitoring_combustible ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->monitoring_combustible ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Material Distance -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <span><strong>3.</strong> Semua bahan yang mudah terbakar/debu, serat atau endapan berminyak, berada setidaknya 11m dari area kerja</span>
                                <span class="badge {{ $hraHotWork->monitoring_distance ? 'badge-yes' : 'badge-no' }}">
                                    {{ $hraHotWork->monitoring_distance ? 'YA' : 'TIDAK' }}
                                </span>
                            </div>
                        </div>

                        <!-- Additional Inspection Duration -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span><strong>4.</strong> Berapa lama inspeksi tambahan diperlukan?</span>
                                </div>
                                <span class="badge badge-info-custom">
                                    {{ $hraHotWork->additional_inspection_duration ?? 'Tidak ditentukan' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="col-lg-4">
            <!-- Permit Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Main Permit Info
                    </h6>
                </div>
                <div class="card-body">
                    <p><strong>Permit Number:</strong><br>{{ $permit->permit_number }}</p>
                    <p><strong>Work Title:</strong><br>{{ $permit->work_title }}</p>
                    <p><strong>Location:</strong><br>{{ $permit->work_location }}</p>
                    <p><strong>Department:</strong><br>{{ $permit->department }}</p>
                    <p><strong>Date:</strong><br>{{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Approval Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Approval Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Status:</strong><br>
                        @if(($hraHotWork->approval_status ?? 'draft') === 'draft')
                            <span class="badge bg-secondary fs-6 px-3 py-2 mt-2">
                                <i class="fas fa-file-alt me-2"></i>Draft
                            </span>
                        @elseif($hraHotWork->approval_status === 'pending')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 mt-2">
                                <i class="fas fa-clock me-2"></i>Waiting for Approval
                            </span>
                        @elseif($hraHotWork->approval_status === 'approved')
                            <span class="badge bg-success fs-6 px-3 py-2 mt-2">
                                <i class="fas fa-check-circle me-2"></i>Approved
                            </span>
                        @elseif($hraHotWork->approval_status === 'rejected')
                            <span class="badge bg-danger fs-6 px-3 py-2 mt-2">
                                <i class="fas fa-times-circle me-2"></i>Rejected
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Area Owner Approval:</strong><br>
                        <span class="badge bg-{{ $hraHotWork->area_owner_approval === 'approved' ? 'success' : ($hraHotWork->area_owner_approval === 'pending' ? 'warning' : 'secondary') }} mt-1">
                            {{ ucfirst($hraHotWork->area_owner_approval ?? 'pending') }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>EHS Approval:</strong><br>
                        <span class="badge bg-{{ $hraHotWork->ehs_approval === 'approved' ? 'success' : ($hraHotWork->ehs_approval === 'pending' ? 'warning' : 'secondary') }} mt-1">
                            {{ ucfirst($hraHotWork->ehs_approval ?? 'pending') }}
                        </span>
                    </div>
                    
                    @if($hraHotWork->approved_at)
                    <div class="mb-3">
                        <strong>Approved Date:</strong><br>
                        <small class="text-muted">{{ $hraHotWork->approved_at->format('d M Y, H:i') }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Safety Guidelines -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Safety Reminders
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>All safety requirements verified</li>
                        <li><i class="fas fa-check text-success me-2"></i>Fire safety equipment confirmed</li>
                        <li><i class="fas fa-check text-success me-2"></i>Hot work area clearance checked</li>
                        <li><i class="fas fa-check text-success me-2"></i>Emergency procedures known</li>
                        <li><i class="fas fa-check text-success me-2"></i>Fire watch personnel assigned</li>
                    </ul>
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

@if($hraHotWork->ehs_approval === 'approved')
<!-- Inspection Modal -->
<div class="modal fade" id="hraInspectionModal" tabindex="-1" aria-labelledby="hraInspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="hraInspectionModalLabel">
                    <i class="fas fa-clipboard-check me-2"></i>Isi Inspeksi <span id="hraInsp_seqLabel">#1</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="hraInspectionForm" method="POST" enctype="multipart/form-data"
                  data-base="{{ url('permits/'.$permit->id.'/hra/hot-works/'.$hraHotWork->id.'/inspection') }}"
                  action="{{ url('permits/'.$permit->id.'/hra/hot-works/'.$hraHotWork->id.'/inspection/'.(old('_seq') ?: 1)) }}">
                @csrf
                <input type="hidden" name="_seq" id="hraInsp_seq" value="{{ old('_seq') ?: 1 }}">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>HRA:</strong> {{ $hraHotWork->hra_permit_number }}<br>
                        <strong>Main Permit:</strong> {{ $permit->permit_number }} &middot; {{ $permit->work_title }}
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Inspector Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="inspector_name" required
                                   value="{{ old('inspector_name', auth()->user()->name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Inspector Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="inspector_email" required
                                   value="{{ old('inspector_email', auth()->user()->email) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Finding Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="finding_type" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="OK"  {{ old('finding_type') === 'OK' ? 'selected' : '' }}>OK</option>
                                <option value="NOK" {{ old('finding_type') === 'NOK' ? 'selected' : '' }}>NOK</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Inspection Findings <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="findings" rows="5" required
                                  placeholder="Hasil temuan inspeksi, kondisi keselamatan, observasi, dan rekomendasi...">{{ old('findings') }}</textarea>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold"><i class="fas fa-camera me-1"></i>Foto Inspeksi</label>

                        {{-- Mobile: native camera capture --}}
                        <div id="hraInsp_mobileBox" class="border rounded p-3 bg-light" style="display:none;">
                            <div id="hraInsp_mobilePrompt" class="text-center">
                                <button type="button" id="hraInsp_mobileTrigger" class="btn btn-outline-primary mb-2">
                                    <i class="fas fa-camera me-2"></i>Ambil Foto
                                </button>
                                <input type="file" id="hraInsp_mobileInput" accept="image/*" capture="environment" class="d-none">
                                <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i>Klik untuk membuka kamera</p>
                            </div>
                            <div id="hraInsp_mobilePreview" class="text-center" style="display:none;">
                                <img id="hraInsp_mobileImg" src="" alt="Preview" class="img-fluid rounded mb-2" style="max-height:300px;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" id="hraInsp_mobileRetake" class="btn btn-warning"><i class="fas fa-redo me-1"></i>Ambil Ulang</button>
                                    <button type="button" id="hraInsp_mobileRemove" class="btn btn-danger"><i class="fas fa-trash me-1"></i>Hapus</button>
                                </div>
                            </div>
                        </div>

                        {{-- Desktop: live camera (getUserMedia) only, no file picking --}}
                        <div id="hraInsp_desktopBox" class="border rounded p-3 bg-light" style="display:none;">
                            <div id="hraInsp_noCam" class="text-center" style="display:none;">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                <p class="text-muted small mb-0">Kamera tidak tersedia di perangkat ini. Gunakan HP untuk mengambil foto inspeksi.</p>
                            </div>
                            <div id="hraInsp_camStart" class="text-center">
                                <button type="button" id="hraInsp_startCam" class="btn btn-outline-primary"><i class="fas fa-camera me-2"></i>Buka Kamera</button>
                                <p class="text-muted small mt-2 mb-0"><i class="fas fa-info-circle me-1"></i>Foto hanya bisa diambil langsung dari kamera</p>
                            </div>
                            <div id="hraInsp_camPreview" style="display:none;">
                                <video id="hraInsp_video" autoplay playsinline class="w-100 rounded" style="max-height:300px;background:#000;"></video>
                                <div class="d-flex gap-2 mt-2 justify-content-center">
                                    <button type="button" id="hraInsp_capture" class="btn btn-success"><i class="fas fa-camera me-1"></i>Ambil Foto</button>
                                    <button type="button" id="hraInsp_stopCam" class="btn btn-secondary"><i class="fas fa-times me-1"></i>Tutup Kamera</button>
                                </div>
                            </div>
                            <div id="hraInsp_captured" style="display:none;">
                                <img id="hraInsp_capturedImg" src="" alt="Captured" class="img-fluid rounded" style="max-height:300px;">
                                <div class="d-flex gap-2 mt-2 justify-content-center">
                                    <button type="button" id="hraInsp_retake" class="btn btn-warning"><i class="fas fa-redo me-1"></i>Ambil Ulang</button>
                                    <button type="button" id="hraInsp_removeCap" class="btn btn-danger"><i class="fas fa-trash me-1"></i>Hapus Foto</button>
                                </div>
                            </div>
                            <canvas id="hraInsp_canvas" style="display:none;"></canvas>
                        </div>

                        <input type="hidden" id="hraInsp_photoData" name="inspection_photo_data">
                        <input type="file" id="hraInsp_photoFile" name="inspection_photo" accept="image/*"
                               style="display:none;pointer-events:none;" tabindex="-1">
                        <div class="form-text">Foto wajib diambil langsung dari kamera (tidak bisa pilih dari galeri).</div>
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
@endif

@include('layouts.sidebar-scripts')

@push('scripts')
<script>
@if($hraHotWork->ehs_approval === 'approved')
/* ================= HRA Inspection (slot picker + camera-only photo) ================= */
(function () {
    const modalEl   = document.getElementById('hraInspectionModal');
    if (!modalEl) return;

    const $ = id => document.getElementById(id);
    const form = $('hraInspectionForm');

    // Point the form at the chosen slot and update the title.
    function setSlot(seq) {
        seq = parseInt(seq, 10) || 1;
        form.action = form.dataset.base + '/' + seq;
        $('hraInsp_seq').value = seq;
        $('hraInsp_seqLabel').textContent = '#' + seq;
    }

    document.querySelectorAll('.hra-insp-open').forEach(function (btn) {
        btn.addEventListener('click', function () { setSlot(this.dataset.seq); });
    });

    @if($errors->any())
    setSlot({{ (int) (old('_seq') ?: 1) }});
    new bootstrap.Modal(modalEl).show();
    @endif

    const photoData = $('hraInsp_photoData');
    const photoFile = $('hraInsp_photoFile');
    const MAX_W = 1280, MAX_H = 960;

    function isMobile() {
        const ua = navigator.userAgent;
        if (/Windows NT|Macintosh/i.test(ua)) return false;
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
    }

    function clearPhoto() {
        photoData.value = '';
        try { photoFile.value = ''; } catch (e) {}
    }

    function setPhotoFromCanvas(canvas, quality) {
        const b64 = canvas.toDataURL('image/jpeg', quality);
        photoData.value = b64;
        canvas.toBlob(function (blob) {
            try {
                const dt = new DataTransfer();
                dt.items.add(new File([blob], 'hra_inspection.jpg', { type: 'image/jpeg' }));
                photoFile.files = dt.files;
            } catch (e) { /* base64 fallback is enough */ }
        }, 'image/jpeg', quality);
    }

    function drawResized(source, sw, sh) {
        let w = sw, h = sh;
        if (w > MAX_W || h > MAX_H) {
            const r = Math.min(MAX_W / w, MAX_H / h);
            w = Math.round(w * r); h = Math.round(h * r);
        }
        const canvas = $('hraInsp_canvas');
        canvas.width = w; canvas.height = h;
        canvas.getContext('2d').drawImage(source, 0, 0, w, h);
        return canvas;
    }

    /* ---------- Mobile: native capture ---------- */
    function resetMobile() {
        $('hraInsp_mobileInput').value = '';
        $('hraInsp_mobileImg').src = '';
        $('hraInsp_mobilePrompt').style.display = 'block';
        $('hraInsp_mobilePreview').style.display = 'none';
    }

    function openMobileCamera() { $('hraInsp_mobileInput').click(); }

    $('hraInsp_mobileTrigger').addEventListener('click', openMobileCamera);
    $('hraInsp_mobileRetake').addEventListener('click', openMobileCamera);
    $('hraInsp_mobileRemove').addEventListener('click', function () { clearPhoto(); resetMobile(); });

    $('hraInsp_mobileInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        if (file.size > 15 * 1024 * 1024) { alert('Ukuran foto terlalu besar (maks 15 MB).'); this.value = ''; return; }
        const reader = new FileReader();
        reader.onload = function (e) {
            const img = new Image();
            img.onload = function () {
                const canvas = drawResized(img, img.width, img.height);
                setPhotoFromCanvas(canvas, 0.82);
                $('hraInsp_mobileImg').src = canvas.toDataURL('image/jpeg', 0.82);
                $('hraInsp_mobilePrompt').style.display = 'none';
                $('hraInsp_mobilePreview').style.display = 'block';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    /* ---------- Desktop: getUserMedia ---------- */
    let stream = null;

    function stopCamera() {
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        const v = $('hraInsp_video');
        if (v) v.srcObject = null;
    }

    function showDesktopPanel(which) {
        ['hraInsp_noCam', 'hraInsp_camStart', 'hraInsp_camPreview', 'hraInsp_captured']
            .forEach(id => $(id).style.display = (id === which ? 'block' : 'none'));
    }

    function resetDesktop() {
        stopCamera();
        showDesktopPanel('hraInsp_camStart');
        $('hraInsp_capturedImg').src = '';
    }

    async function hasCamera() {
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) return false;
            const devs = await navigator.mediaDevices.enumerateDevices();
            return devs.some(d => d.kind === 'videoinput');
        } catch (e) { return false; }
    }

    $('hraInsp_startCam').addEventListener('click', async function () {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            $('hraInsp_video').srcObject = stream;
            showDesktopPanel('hraInsp_camPreview');
        } catch (err) {
            showDesktopPanel('hraInsp_noCam');
            alert('Tidak dapat mengakses kamera. Silakan gunakan HP untuk mengambil foto inspeksi.');
        }
    });

    $('hraInsp_stopCam').addEventListener('click', function () {
        stopCamera();
        showDesktopPanel('hraInsp_camStart');
    });

    $('hraInsp_capture').addEventListener('click', function () {
        const v = $('hraInsp_video');
        const canvas = drawResized(v, v.videoWidth, v.videoHeight);
        setPhotoFromCanvas(canvas, 0.8);
        $('hraInsp_capturedImg').src = canvas.toDataURL('image/jpeg', 0.8);
        stopCamera();
        showDesktopPanel('hraInsp_captured');
    });

    $('hraInsp_retake').addEventListener('click', function () { clearPhoto(); $('hraInsp_startCam').click(); });
    $('hraInsp_removeCap').addEventListener('click', function () { clearPhoto(); resetDesktop(); });

    /* ---------- modal lifecycle ---------- */
    modalEl.addEventListener('shown.bs.modal', async function () {
        clearPhoto();
        if (isMobile()) {
            $('hraInsp_mobileBox').style.display = 'block';
            $('hraInsp_desktopBox').style.display = 'none';
            resetMobile();
        } else {
            $('hraInsp_mobileBox').style.display = 'none';
            $('hraInsp_desktopBox').style.display = 'block';
            (await hasCamera()) ? resetDesktop() : showDesktopPanel('hraInsp_noCam');
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        stopCamera();
        resetMobile();
    });
})();
@endif

function requestApproval() {
    Swal.fire({
        title: 'Request Approval',
        text: 'Are you sure you want to submit this HRA Hot Work for approval?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Request Approval',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("hra.hot-works.request-approval", [$permit, $hraHotWork]) }}';
            
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

function approveHRA() {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function rejectHRA() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endpush
@endsection
