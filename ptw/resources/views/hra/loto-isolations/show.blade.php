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
            <div class="d-flex gap-2">
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Main Permit
                </a>
                <a href="{{ route('hra.loto-isolations.download-pdf', [$permit, $hraLotoIsolation]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Download PDF
                </a>
                @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                <a href="{{ route('hra.loto-isolations.edit', [$permit, $hraLotoIsolation]) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit HRA
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
            </div>
        </div>
    </div>

    <!-- Isolasi Mesin/Tangki Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-cogs me-2"></i>Isolasi Mesin/Tangki
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <strong>Machine/Tank Name:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->machine_tank_name ?? '-' }}</div>
                </div>
            </div>
            
            <!-- Equipment Isolation Matrix -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="background: #f8f9fa; font-weight: 600;">Equipment</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Mati</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Dikunci</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Diperiksa</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Dipasang Tag</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Panel Listrik</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->panel_listrik_mati)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->panel_listrik_dikunci)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->panel_listrik_diperiksa)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->panel_listrik_dipasang_tag)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Pneumatic</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->pneumatic_mati)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->pneumatic_dikunci)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->pneumatic_diperiksa)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->pneumatic_dipasang_tag)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Hydraulic</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->hydraulic_mati)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->hydraulic_dikunci)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->hydraulic_diperiksa)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->hydraulic_dipasang_tag)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Gravitasi</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->gravitasi_mati)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->gravitasi_dikunci)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->gravitasi_diperiksa)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->gravitasi_dipasang_tag)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Spring/Per</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->spring_per_mati)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->spring_per_dikunci)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->spring_per_diperiksa)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->spring_per_dipasang_tag)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Rotasi/Gerakan</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->rotasi_gerakan_mati)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->rotasi_gerakan_dikunci)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->rotasi_gerakan_diperiksa)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->rotasi_gerakan_dipasang_tag)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Isolasi Listrik Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-bolt me-2"></i>Isolasi Listrik
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Panel Listrik Section -->
                <div class="col-md-6">
                    <h6 class="text-primary mb-3"><i class="fas fa-cube me-2"></i>Panel Listrik</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Bekerja Pada Panel Listrik</span>
                        @if($hraLotoIsolation->bekerja_panel_listrik == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Referensi Manual</span>
                        @if($hraLotoIsolation->referensi_manual_panel == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Saklar di Posisi OFF</span>
                        @if($hraLotoIsolation->saklar_diposisi_off == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tag Dipasang</span>
                        @if($hraLotoIsolation->tag_dipasang_panel == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Sekring/CB Dimatikan</span>
                        @if($hraLotoIsolation->sekring_cb_dimatikan == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Panel OFF</span>
                        @if($hraLotoIsolation->panel_off_panel == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>

                <!-- Sistem Mekanis Section -->
                <div class="col-md-6">
                    <h6 class="text-primary mb-3"><i class="fas fa-cog me-2"></i>Sistem Mekanis</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Bekerja Pada Sistem Mekanis</span>
                        @if($hraLotoIsolation->bekerja_sistem_mekanis == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Referensi Manual</span>
                        @if($hraLotoIsolation->referensi_manual_sistem == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Safety Switch OFF</span>
                        @if($hraLotoIsolation->safety_switch_off == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tag Dipasang</span>
                        @if($hraLotoIsolation->tag_dipasang_sistem == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Sekring/CB Dimatikan</span>
                        @if($hraLotoIsolation->sekring_cb_sistem_dimatikan == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Sudah Dicoba Dinyalakan</span>
                        @if($hraLotoIsolation->sudah_dicoba_dinyalakan == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tes Listrik Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-zap me-2"></i>Tes Listrik
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Membutuhkan tes dengan listrik ON?</strong>
                        @if($hraLotoIsolation->membutuhkan_tes_listrik_on == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>

                @if($hraLotoIsolation->membutuhkan_tes_listrik_on == 'ya')
                <!-- Safety Equipment -->
                <div class="col-12 mb-3">
                    <h6 class="text-primary mb-3">Safety Equipment</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Safety Barrier</span>
                                @if($hraLotoIsolation->safety_barrier == 'ya')
                                    <span class="badge badge-yes">Ya</span>
                                @else
                                    <span class="badge badge-no">Tidak</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Full Face Protection</span>
                                @if($hraLotoIsolation->full_face_protection == 'ya')
                                    <span class="badge badge-yes">Ya</span>
                                @else
                                    <span class="badge badge-no">Tidak</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Insulated Gloves</span>
                                @if($hraLotoIsolation->insulated_gloves == 'ya')
                                    <span class="badge badge-yes">Ya</span>
                                @else
                                    <span class="badge badge-no">Tidak</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Insulated Mat</span>
                                @if($hraLotoIsolation->insulated_mat == 'ya')
                                    <span class="badge badge-yes">Ya</span>
                                @else
                                    <span class="badge badge-no">Tidak</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Full Length Sleeves</span>
                                @if($hraLotoIsolation->full_length_sleeves == 'ya')
                                    <span class="badge badge-yes">Ya</span>
                                @else
                                    <span class="badge badge-no">Tidak</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>Tool Insulation Satisfactory</span>
                                @if($hraLotoIsolation->tool_insulation_satisfactory == 'ya')
                                    <span class="badge badge-yes">Ya</span>
                                @else
                                    <span class="badge badge-no">Tidak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voltage and Reason -->
                <div class="col-md-6 mb-3">
                    <strong>Maximum Voltage (V):</strong>
                    <div class="mt-1">{{ $hraLotoIsolation->maximum_voltage ?? '-' }}</div>
                </div>
                <div class="col-12 mb-3">
                    <strong>Alasan Live Test:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->alasan_live_test ?? '-' }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Isolasi Utility Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-tools me-2"></i>Isolasi Utility
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="background: #f8f9fa; font-weight: 600;">Medium</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Off</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Secured/Locked</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Checked</th>
                            <th class="text-center" style="background: #f8f9fa; font-weight: 600;">Tagged</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Listrik</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_listrik_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_listrik_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_listrik_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_listrik_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Cooling water</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_cooling_water_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_cooling_water_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_cooling_water_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_cooling_water_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Oil Hidrolik</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_oil_hidrolik_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_oil_hidrolik_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_oil_hidrolik_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_oil_hidrolik_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Kompresor</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_kompresor_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_kompresor_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_kompresor_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_kompresor_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Vacuum</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_vacuum_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_vacuum_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_vacuum_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_vacuum_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Gas</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_gas_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_gas_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_gas_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_gas_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ $hraLotoIsolation->utility_lainnya_nama ?: 'Lainnya' }}</strong></td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_lainnya_off)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_lainnya_secured)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_lainnya_checked)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($hraLotoIsolation->utility_lainnya_tagged)
                                    <span class="badge badge-yes">✓</span>
                                @else
                                    <span class="badge badge-no">✗</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Additional Questions -->
            <div class="row mt-4">
                <div class="col-md-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Line di isolasi dengan plat:</strong>
                        @if($hraLotoIsolation->line_diisolasi_plat == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <strong>Jika "Tidak" berikan alasan dan deskripsikan bagaimana isolasi dilakukan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->alasan_deskripsi_isolasi ?? '-' }}</div>
                </div>
                <div class="col-12 mb-3">
                    <strong>Area yang terdampak isolasi:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->area_terdampak_isolasi ?? '-' }}</div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Area sudah diberitahu:</strong>
                        @if($hraLotoIsolation->area_sudah_diberitahu == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mematikan Pipa Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                <i class="fas fa-exclamation-triangle me-2"></i>Mematikan Pipa
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <strong>Isi dari line/pipa:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->isi_line_pipa ?? '-' }}</div>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tidak ada sisa tekanan dalam pipa</span>
                        @if($hraLotoIsolation->tidak_ada_sisa_tekanan == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Drain/bleed valves terbuka dan tidak terblok</span>
                        @if($hraLotoIsolation->drain_bleed_valves == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Pipa di-purged dengan:</strong>
                        <div class="mt-2">
                            @if($hraLotoIsolation->pipa_purged_udara)
                                <span class="badge badge-info-custom me-2">Udara</span>
                            @endif
                            @if($hraLotoIsolation->pipa_purged_air)
                                <span class="badge badge-info-custom me-2">Air</span>
                            @endif
                            @if($hraLotoIsolation->pipa_purged_nitrogen)
                                <span class="badge badge-info-custom me-2">Nitrogen</span>
                            @endif
                            @if(!$hraLotoIsolation->pipa_purged_udara && !$hraLotoIsolation->pipa_purged_air && !$hraLotoIsolation->pipa_purged_nitrogen)
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pipa diisolasi dengan plat</span>
                        @if($hraLotoIsolation->pipa_diisolasi_plat == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pipa Kosong</span>
                        @if($hraLotoIsolation->pipa_kosong == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Pipa Bersih</span>
                        @if($hraLotoIsolation->pipa_bersih == 'ya')
                            <span class="badge badge-yes">Ya</span>
                        @else
                            <span class="badge badge-no">Tidak</span>
                        @endif
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <strong>Jika "Tidak" berikan alasan dan deskripsikan bagaimana isolasi dilakukan:</strong>
                    <div class="mt-2 p-3 bg-light rounded">{{ $hraLotoIsolation->alasan_deskripsi_isolasi_pipa ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection