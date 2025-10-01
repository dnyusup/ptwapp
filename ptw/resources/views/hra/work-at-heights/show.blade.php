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
            <div class="d-flex gap-2">
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Main Permit
                </a>
                <a href="{{ route('hra.work-at-heights.download-pdf', [$permit, $hraWorkAtHeight]) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Download PDF
                </a>
                @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                <a href="{{ route('hra.work-at-heights.edit', [$permit, $hraWorkAtHeight]) }}" class="btn btn-warning">
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

                <!-- Mobile Elevation Platform -->
                <div class="col-md-6 mb-4">
                    <h6 class="text-primary mb-3"><i class="fas fa-arrow-up me-2"></i>Mobile Elevation Platform</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Mobile elevation platform</strong>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_checked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_checked ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->mobile_elevation_checked)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Operator terlatih?</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_training_provided ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_training_provided ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Penggunaannya tertulis?</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_used_before ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_used_before ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Menggunakan Alat Pelindung Jatuh?</span>
                        <span class="badge {{ $hraWorkAtHeight->mobile_elevation_location_marked ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->mobile_elevation_location_marked ? 'YES' : 'NO' }}
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
                    <h6 class="text-primary mb-3"><i class="fas fa-life-ring me-2"></i>Fall Arrest</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Fall arrest seperti FBH digunakan?</strong>
                        <span class="badge {{ $hraWorkAtHeight->fall_arrest_used ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->fall_arrest_used ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    @if($hraWorkAtHeight->fall_arrest_used)
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Diperiksa sebelum digunakan</span>
                        <span class="badge {{ $hraWorkAtHeight->area_closed_from_below ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->area_closed_from_below ? 'YES' : 'NO' }}
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
                        <span>Apakah ada atap yang rawan?</span>
                        <span class="badge {{ $hraWorkAtHeight->roof_fragile_areas ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_fragile_areas ? 'YES' : 'NO' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pelindung jatuh/pelindung disisi tersedia?</span>
                        <span class="badge {{ $hraWorkAtHeight->roof_fall_protection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->roof_fall_protection ? 'YES' : 'NO' }}
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
                        <span class="fw-semibold">Area di bawah pekerjaan berlangsung ditutup dari lalu lintas/pejalan kaki</span>
                        <span class="badge {{ $hraWorkAtHeight->area_below_closed ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->area_below_closed ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Gangguan pada atau sekitar lokasi pekerjaan</span>
                        <span class="badge {{ $hraWorkAtHeight->work_area_disturbances ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->work_area_disturbances ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Ventilasi, cerobong, bukaan yang mengeluarkan udara/air yang panas/berbau/berbahaya</span>
                        <span class="badge {{ $hraWorkAtHeight->ventilation_hazards ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->ventilation_hazards ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Bagian dari mesin/peralatan harus dilindungi</span>
                        <span class="badge {{ $hraWorkAtHeight->equipment_protection ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->equipment_protection ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Terdapat titik untuk keluar dalam kondisi darurat</span>
                        <span class="badge {{ $hraWorkAtHeight->emergency_exit_available ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->emergency_exit_available ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Material/alat yang perlu dinaik/turunkan</span>
                        <span class="badge {{ $hraWorkAtHeight->material_handling ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->material_handling ? 'YES' : 'NO' }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Personnel Safety atau Petugas lain yang diperlukan</span>
                        <span class="badge {{ $hraWorkAtHeight->safety_personnel_needed ? 'badge-yes' : 'badge-no' }}">
                            {{ $hraWorkAtHeight->safety_personnel_needed ? 'YES' : 'NO' }}
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

@include('layouts.sidebar-scripts')
@endsection
