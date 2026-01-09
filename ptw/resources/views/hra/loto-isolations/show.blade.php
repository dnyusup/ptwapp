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
                <h4 class="mb-1">HRA - LOTO/Isolation Details</h4>
                <p class="text-muted mb-0">
                    HRA Permit: <strong>{{ $hraLotoIsolation->hra_permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <!-- Status Badge -->
                @if(($hraLotoIsolation->approval_status ?? 'draft') === 'draft')
                    <span class="badge bg-secondary fs-6 px-3 py-2">
                        <i class="fas fa-file-alt me-2"></i>Draft
                    </span>
                @elseif($hraLotoIsolation->approval_status === 'pending')
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        <i class="fas fa-clock me-2"></i>Waiting for Approval
                    </span>
                @elseif($hraLotoIsolation->approval_status === 'approved')
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-check-circle me-2"></i>Approved
                    </span>
                @elseif($hraLotoIsolation->approval_status === 'rejected')
                    <span class="badge bg-danger fs-6 px-3 py-2">
                        <i class="fas fa-times-circle me-2"></i>Rejected
                    </span>
                @endif
                
                <!-- Back Button -->
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Main Permit
                </a>
                
                <!-- Download PDF Button - only visible when approved -->
                @if($hraLotoIsolation->approval_status === 'approved')
                <a href="{{ route('hra.loto-isolations.download-pdf', [$permit, $hraLotoIsolation]) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
                @endif
                
                <!-- Action Buttons -->
                @if(($hraLotoIsolation->approval_status ?? 'draft') === 'draft')
                    @if($hraLotoIsolation->user_id == auth()->id() || auth()->user()->role === 'administrator')
                    <button type="button" class="btn btn-info" onclick="requestApproval()">
                        <i class="fas fa-paper-plane me-2"></i>Request Approval
                    </button>
                    @endif
                @elseif($hraLotoIsolation->approval_status === 'rejected')
                    @if($hraLotoIsolation->user_id == auth()->id() || auth()->user()->role === 'administrator')
                    <button type="button" class="btn btn-info btn-sm" onclick="requestApproval()">
                        <i class="fas fa-redo me-2"></i>Re-request Approval
                    </button>
                    @endif
                @endif

                <!-- Edit Button - only visible when not pending or approved -->
                @if(($hraLotoIsolation->user_id == auth()->id() || auth()->user()->role === 'administrator') && 
                    !in_array($hraLotoIsolation->approval_status ?? 'draft', ['pending', 'approved']))
                <a href="{{ route('hra.loto-isolations.edit', [$permit, $hraLotoIsolation]) }}" class="btn btn-outline-warning" style="border-color: #ffc107; color: #ffc107; background-color: transparent;">
                    <i class="fas fa-edit me-2"></i>Edit HRA
                </a>
                @endif
            </div>
        </div>
        
        <!-- Approval Actions Section -->
        @if($hraLotoIsolation->approval_status === 'pending')
            @php
                $canApproveAsEHS = auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS' && $hraLotoIsolation->ehs_approval === 'pending';
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
    @if($hraLotoIsolation->approval_status === 'rejected' && $hraLotoIsolation->rejection_reason)
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-times-circle me-2"></i>HRA LOTO/Isolation Rejected
                    </h5>
                    <p class="mb-2">
                        <strong>Rejected by:</strong> {{ $hraLotoIsolation->rejector->name ?? 'System' }} 
                        @if($hraLotoIsolation->rejected_at)
                        <small class="text-muted">on {{ $hraLotoIsolation->rejected_at->format('d M Y, H:i') }}</small>
                        @endif
                    </p>
                    <div class="rejection-reason bg-white p-3 rounded border-start border-danger border-3">
                        <strong>Reason for Rejection:</strong><br>
                        <span class="text-dark">{{ $hraLotoIsolation->rejection_reason }}</span>
                    </div>
                    @if($hraLotoIsolation->user_id == auth()->id() || auth()->user()->role === 'administrator')
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
                    <strong>Worker Name:</strong>
                    <div class="mt-1">{{ $hraLotoIsolation->worker_name }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Phone Number:</strong>
                    <div class="mt-1">{{ $hraLotoIsolation->worker_phone ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Supervisor Name:</strong>
                    <div class="mt-1">{{ $hraLotoIsolation->supervisor_name }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Work Location:</strong>
                    <div class="mt-1">{{ $hraLotoIsolation->work_location }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Start Time:</strong>
                    <div class="mt-1">{{ \Carbon\Carbon::parse($hraLotoIsolation->start_datetime)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>End Time:</strong>
                    <div class="mt-1">{{ \Carbon\Carbon::parse($hraLotoIsolation->end_datetime)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-12 mb-3">
                    <strong>Work Description:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->work_description }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Status:</strong>
                    <div class="mt-1">
                        @if($hraLotoIsolation->status == 'draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($hraLotoIsolation->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($hraLotoIsolation->status == 'completed')
                            <span class="badge bg-info">Completed</span>
                        @elseif($hraLotoIsolation->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Created At:</strong>
                    <div class="mt-1">{{ $hraLotoIsolation->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pre Isolation Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-clipboard-check me-2"></i>Pre Isolation
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Apakah P&ID dan/atau rencana kelistrikan yang sesuai telah ditinjau?</strong>
                        <div>
                            @if($hraLotoIsolation->pid_reviewed == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Electrical Isolation Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #fd7e14 0%, #dc6a12 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-bolt me-2"></i>Electrical Isolation
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Sedang mengerjakan instalasi HV?</strong>
                        <div>
                            @if($hraLotoIsolation->electrical_hv_installation == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Electrical Isolation Table -->
            @php
                $electricalIsolations = json_decode($hraLotoIsolation->electrical_isolations, true) ?? [];
            @endphp
            @if(count(array_filter($electricalIsolations, function($item) { return !empty($item['description']); })) > 0)
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Isolation Description</th>
                            <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                            <th style="width: 100px;" class="text-center">Lock & Tag</th>
                            <th style="width: 100px;" class="text-center">Zero Energy</th>
                            <th style="width: 100px;" class="text-center">Try Out</th>
                            <th style="width: 100px;" class="text-center">Removal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($electricalIsolations as $index => $isolation)
                            @if(!empty($isolation['description']))
                            <tr>
                                <td class="text-center"><strong>E{{ $index }}</strong></td>
                                <td>{{ $isolation['description'] }}</td>
                                <td class="text-center">
                                    @if(!empty($isolation['stop_isolate']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['lock_tag']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['zero_energy']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['try_out']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['removal']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted mb-3"><em>Tidak ada data isolasi</em></p>
            @endif

            @if($hraLotoIsolation->electrical_energy_control_method)
            <div class="row">
                <div class="col-12">
                    <strong>Metode untuk mengendalikan energi yang tersimpan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->electrical_energy_control_method }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Mechanical Isolation Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-cogs me-2"></i>Mechanical Isolation
            </h5>
        </div>
        <div class="card-body">
            <!-- Yes/No Questions in 2 columns -->
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Gravitasi</strong>
                        <div>
                            @if($hraLotoIsolation->mechanical_gravitasi == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Hidrolik</strong>
                        <div>
                            @if($hraLotoIsolation->mechanical_hidrolik == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Kelembaman</strong>
                        <div>
                            @if($hraLotoIsolation->mechanical_kelembaman == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Spring</strong>
                        <div>
                            @if($hraLotoIsolation->mechanical_spring == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Pneumatik</strong>
                        <div>
                            @if($hraLotoIsolation->mechanical_pneumatik == 'ya')
                                <span class="badge badge-yes">Ya</span>
                            @else
                                <span class="badge badge-no">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($hraLotoIsolation->mechanical_lainnya)
                <div class="col-md-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Lainnya</strong>
                        <span>{{ $hraLotoIsolation->mechanical_lainnya }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Mechanical Isolation Table -->
            @php
                $mechanicalIsolations = json_decode($hraLotoIsolation->mechanical_isolations, true) ?? [];
            @endphp
            @if(count(array_filter($mechanicalIsolations, function($item) { return !empty($item['description']); })) > 0)
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Isolation Description</th>
                            <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                            <th style="width: 100px;" class="text-center">Lock & Tag</th>
                            <th style="width: 100px;" class="text-center">Zero Energy</th>
                            <th style="width: 100px;" class="text-center">Try Out</th>
                            <th style="width: 100px;" class="text-center">Removal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mechanicalIsolations as $index => $isolation)
                            @if(!empty($isolation['description']))
                            <tr>
                                <td class="text-center"><strong>M{{ $index }}</strong></td>
                                <td>{{ $isolation['description'] }}</td>
                                <td class="text-center">
                                    @if(!empty($isolation['stop_isolate']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['lock_tag']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['zero_energy']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['try_out']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['removal']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted mb-3"><em>Tidak ada data isolasi</em></p>
            @endif

            @if($hraLotoIsolation->mechanical_energy_control_method)
            <div class="row">
                <div class="col-12">
                    <strong>Metode untuk mengendalikan energi yang tersimpan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->mechanical_energy_control_method }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Process Isolation Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #6610f2 0%, #4c0fb8 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-industry me-2"></i>Process Isolation
            </h5>
        </div>
        <div class="card-body">
            <!-- Process Isolation Table -->
            @php
                $processIsolations = json_decode($hraLotoIsolation->process_isolations, true) ?? [];
            @endphp
            @if(count(array_filter($processIsolations, function($item) { return !empty($item['description']); })) > 0)
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Isolation Description</th>
                            <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                            <th style="width: 100px;" class="text-center">Lock & Tag</th>
                            <th style="width: 100px;" class="text-center">Zero Energy</th>
                            <th style="width: 100px;" class="text-center">Try Out</th>
                            <th style="width: 100px;" class="text-center">Removal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($processIsolations as $index => $isolation)
                            @if(!empty($isolation['description']))
                            <tr>
                                <td class="text-center"><strong>P{{ $index }}</strong></td>
                                <td>{{ $isolation['description'] }}</td>
                                <td class="text-center">
                                    @if(!empty($isolation['stop_isolate']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['lock_tag']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['zero_energy']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['try_out']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['removal']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted mb-3"><em>Tidak ada data isolasi</em></p>
            @endif

            @if($hraLotoIsolation->process_energy_control_method)
            <div class="row">
                <div class="col-12">
                    <strong>Metode untuk mengendalikan energi yang tersimpan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->process_energy_control_method }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Utility Isolation Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #e83e8c 0%, #c71d6f 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-plug me-2"></i>Utility Isolation
            </h5>
        </div>
        <div class="card-body">
            <!-- Utility Isolation Table -->
            @php
                $utilityIsolations = json_decode($hraLotoIsolation->utility_isolations, true) ?? [];
            @endphp
            @if(count(array_filter($utilityIsolations, function($item) { return !empty($item['description']); })) > 0)
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Isolation Description</th>
                            <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                            <th style="width: 100px;" class="text-center">Lock & Tag</th>
                            <th style="width: 100px;" class="text-center">Zero Energy</th>
                            <th style="width: 100px;" class="text-center">Try Out</th>
                            <th style="width: 100px;" class="text-center">Removal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($utilityIsolations as $index => $isolation)
                            @if(!empty($isolation['description']))
                            <tr>
                                <td class="text-center"><strong>U{{ $index }}</strong></td>
                                <td>{{ $isolation['description'] }}</td>
                                <td class="text-center">
                                    @if(!empty($isolation['stop_isolate']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['lock_tag']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['zero_energy']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['try_out']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!empty($isolation['removal']))
                                        <i class="fas fa-check text-success"></i>
                                    @else
                                        <i class="fas fa-times text-muted"></i>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted mb-3"><em>Tidak ada data isolasi</em></p>
            @endif

            @if($hraLotoIsolation->utility_energy_control_method)
            <div class="row">
                <div class="col-12">
                    <strong>Metode untuk mengendalikan energi yang tersimpan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->utility_energy_control_method }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Verification Isolasi Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-clipboard-check me-2"></i>Verifikasi Isolasi
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Daerah yang akan terpengaruh oleh isolasi:</strong>
                </div>
                <div class="col-md-6">
                    {{ $hraLotoIsolation->affected_area ?? 'N/A' }}
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-9">
                    <p class="mb-0">Semua individu yang terkena dampak (termasuk yang tidak berada di area terdekat) diberitahu tentang isolasi, untuk tetap menjauh dan tidak mencoba mengoperasikan peralatan</p>
                </div>
                <div class="col-md-3 text-end">
                    @if($hraLotoIsolation->all_individuals_informed == 'ya')
                        <span class="badge bg-success">Ya</span>
                    @elseif($hraLotoIsolation->all_individuals_informed == 'tidak')
                        <span class="badge bg-danger">Tidak</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-9">
                    <p class="mb-0">Semua orang yang bekerja pada peralatan <strong class="text-danger">HARUS</strong> LOTOTO secara individual dengan kunci pribadi dan merupakan satu-satunya yang berwenang untuk melepasnya.</p>
                </div>
                <div class="col-md-3 text-end">
                    @if($hraLotoIsolation->individual_lototo_required == 'ya')
                        <span class="badge bg-success">Ya</span>
                    @elseif($hraLotoIsolation->individual_lototo_required == 'tidak')
                        <span class="badge bg-danger">Tidak</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-9">
                    <p class="mb-0">PtW Issuer <strong class="text-danger">HARUS</strong> memiliki kunci LOTOTO pada setiap isolasi (atau kotak LoToTo terkait).</p>
                </div>
                <div class="col-md-3 text-end">
                    @if($hraLotoIsolation->ptw_issuer_lototo_key == 'ya')
                        <span class="badge bg-success">Ya</span>
                    @elseif($hraLotoIsolation->ptw_issuer_lototo_key == 'tidak')
                        <span class="badge bg-danger">Tidak</span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Line Breaking Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #fd7e14 0%, #dc6a12 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-unlink me-2"></i>Line Breaking
            </h5>
        </div>
        <div class="card-body">
            <!-- Konten baris sebelumnya -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Konten baris sebelumnya:</strong>
                </div>
                <div class="col-md-6">
                    {{ $hraLotoIsolation->line_content_before ?? 'N/A' }}
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="row mb-2">
                        <div class="col-8">Tidak ada tekanan sisa di saluran</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_no_residual_pressure == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_no_residual_pressure == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Katup pembuangan terbuka dan tidak tersumbat</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_drain_valve_open == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_drain_valve_open == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Emergency arrangements - showers, extinguisher</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_emergency_arrangements == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_emergency_arrangements == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Garis diisolasi dengan pelat atau sekop</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_line_isolated == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_line_isolated == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Garisnya kosong</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_line_empty == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_line_empty == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Garisnya bersih</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_line_clean == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_line_clean == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="row mb-2">
                        <div class="col-8">Tidak ada serat asbes/keramik ex: gasket</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_no_asbestos == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_no_asbestos == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">Saluran/pipa tidak butuh dukungan lebih lanjut</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_pipe_no_support_needed == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_pipe_no_support_needed == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">LoToTo/ pengurasan reservoir/kontainer terkait</div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_lototo_drainage == 'ya')
                                <span class="badge bg-success">Ya</span>
                            @elseif($hraLotoIsolation->lb_lototo_drainage == 'tidak')
                                <span class="badge bg-danger">Tidak</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">
                            <strong>Line purged with:</strong>
                        </div>
                        <div class="col-4 text-end">
                            @if($hraLotoIsolation->lb_purged_air)
                                <span class="badge bg-info">Air</span>
                            @endif
                            @if($hraLotoIsolation->lb_purged_water)
                                <span class="badge bg-primary">Water</span>
                            @endif
                            @if($hraLotoIsolation->lb_purged_n2)
                                <span class="badge bg-secondary">N2</span>
                            @endif
                            @if(!$hraLotoIsolation->lb_purged_air && !$hraLotoIsolation->lb_purged_water && !$hraLotoIsolation->lb_purged_n2)
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($hraLotoIsolation->lb_additional_control)
            <div class="row mt-3">
                <div class="col-12">
                    <strong>Alasan dan kontrol tambahan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->lb_additional_control }}</div>
                </div>
            </div>
            @endif
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
                <p>Anda akan mengirim permintaan approval untuk HRA LOTO/Isolation ini kepada:</p>
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
                <form action="{{ route('hra.loto-isolations.request-approval', [$permit, $hraLotoIsolation]) }}" method="POST" style="display: inline;">
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
                <h5 class="modal-title" id="approveModalLabel">Approve HRA LOTO/Isolation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this HRA LOTO/Isolation?</p>
                <p class="text-success">
                    <i class="fas fa-check-circle me-2"></i>
                    By approving, you confirm that all safety requirements have been reviewed and are acceptable.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('hra.loto-isolations.process-approval', [$permit, $hraLotoIsolation]) }}" method="POST" style="display: inline;">
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
                <h5 class="modal-title" id="rejectModalLabel">Reject HRA LOTO/Isolation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm" action="{{ route('hra.loto-isolations.process-approval', [$permit, $hraLotoIsolation]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-3">
                        <label for="comments" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="comments" id="comments" rows="4" required 
                                  placeholder="Please provide a detailed reason for rejecting this HRA LOTO/Isolation..."></textarea>
                        <small class="text-muted">Minimum 10 characters required</small>
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

@include('layouts.sidebar-scripts')

@push('scripts')
<script>
function requestApproval() {
    Swal.fire({
        title: 'Request Approval',
        text: 'Are you sure you want to submit this HRA LOTO/Isolation for approval?',
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
            form.action = '{{ route("hra.loto-isolations.request-approval", [$permit, $hraLotoIsolation]) }}';
            
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
