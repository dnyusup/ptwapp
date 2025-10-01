@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
/* Custom styling for checklist items */
.checklist-item {
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.checklist-item:last-child {
    border-bottom: none;
}

.condition-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 15px;
    align-items: center;
}

.checkbox-group {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.radio-group {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

/* Responsive design for mobile */
@media (max-width: 768px) {
    .condition-grid {
        grid-template-columns: 1fr;
        gap: 10px;
        align-items: stretch;
    }
    
    .checkbox-group {
        margin-top: 10px;
        gap: 10px;
    }
    
    .radio-group {
        margin-top: 10px;
        gap: 10px;
    }
    
    .checkbox-group label,
    .radio-group label {
        font-size: 13px;
        white-space: nowrap;
    }
}

.checkbox-group label,
.radio-group label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    background: #ffffff;
    transition: all 0.2s ease;
}

.checkbox-group label:hover,
.radio-group label:hover {
    background: #f8f9fa;
    border-color: #007bff;
}

.checkbox-group input[type="checkbox"],
.checkbox-group input[type="radio"],
.radio-group input[type="radio"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* Validation error styling */
input[type="radio"].is-invalid {
    border: 2px solid #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    outline: none !important;
}

.checklist-item.error {
    border-left: 4px solid #dc3545 !important;
    background-color: #fff5f5 !important;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.sub-questions {
    margin-left: 20px;
    margin-top: 15px;
    padding: 20px;
    background: #ffffff;
    border-left: 4px solid #0d6efd;
    border-radius: 0 8px 8px 0;
    display: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.sub-questions.show {
    display: block;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.parent-question {
    font-weight: 700;
    color: #212529;
    font-size: 15px;
}

.sub-question {
    font-weight: 500;
    color: #495057;
    font-size: 14px;
    margin-top: 0;
}

.sub-questions .checklist-item {
    padding: 12px 0;
    border-bottom: 1px solid #f1f3f4;
}

.sub-questions .checklist-item:last-child {
    border-bottom: none;
}

/* Custom Select2 styling */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-top: 6px;
    padding-bottom: 6px;
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
                <h4 class="mb-1">Create HRA - Work at Heights</h4>
                <p class="text-muted mb-0">
                    Main Permit: <strong>{{ $permit->permit_number }}</strong> - {{ $permit->work_title }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
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

    <!-- HRA Form -->
    <form method="POST" action="{{ route('hra.work-at-heights.store', $permit) }}">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                
                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="worker_name" class="form-label">Nama Pekerja</label>
                                <select class="form-select searchable-select" id="worker_name" name="worker_name" required onchange="updateWorkerPhone()">
                                    <option value="">Pilih Pekerja...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" 
                                                data-phone="{{ $user->phone ?? '' }}"
                                                {{ old('worker_name') == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih dari user perusahaan: {{ $permit->receiver_company_name ?? 'Tidak ada perusahaan' }}</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="worker_phone" class="form-label">No HP Pekerja</label>
                                <input type="text" class="form-control" id="worker_phone" name="worker_phone" 
                                       value="{{ old('worker_phone') }}" readonly>
                                <small class="text-muted">Otomatis terisi berdasarkan nama pekerja</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="supervisor_name" class="form-label">Nama Pendamping</label>
                                <select class="form-select searchable-select" id="supervisor_name" name="supervisor_name" required>
                                    <option value="">Pilih Pendamping...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" 
                                                {{ old('supervisor_name') == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="work_location" class="form-label">Lokasi Kerja</label>
                                <input type="text" class="form-control" id="work_location" name="work_location" 
                                       value="{{ old('work_location', $permit->work_location) }}" readonly>
                                <small class="text-muted">Otomatis dari data permit</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="start_datetime" class="form-label">Tanggal & Jam Mulai</label>
                                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                       value="{{ old('start_datetime') }}" 
                                       min="{{ $permit->start_date->format('Y-m-d\TH:i') }}"
                                       max="{{ $permit->end_date->format('Y-m-d\T23:59') }}" required>
                                <small class="text-muted">Harus dalam rentang: {{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_datetime" class="form-label">Tanggal & Jam Selesai</label>
                                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                       value="{{ old('end_datetime') }}" 
                                       min="{{ $permit->start_date->format('Y-m-d\TH:i') }}"
                                       max="{{ $permit->end_date->format('Y-m-d\T23:59') }}" required>
                                <small class="text-muted">Harus dalam rentang: {{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" placeholder="Jelaskan detail pekerjaan yang akan dilakukan..." required>{{ old('work_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HRA Assessment Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-clipboard-check me-2"></i>HRA Work at Heights Assessment
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                    
                    <!-- Fixed Scaffolding -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Fixed Scaffolding</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_checked" value="1" {{ old('fixed_scaffolding_checked') == '1' ? 'checked' : '' }} onchange="toggleSubQuestions('fixed_scaffolding_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="fixed_scaffolding_checked" value="0" {{ old('fixed_scaffolding_checked') == '0' ? 'checked' : '' }} onchange="toggleSubQuestions('fixed_scaffolding_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="fixed_scaffolding_sub" class="sub-questions {{ old('fixed_scaffolding_checked') == '1' ? 'show' : '' }}">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="1" {{ old('fixed_scaffolding_approved_by_she') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="0" {{ old('fixed_scaffolding_approved_by_she') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Scaffolding -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Mobile scaffolding</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_scaffolding_checked" value="1" {{ old('mobile_scaffolding_checked') == '1' ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_scaffolding_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="mobile_scaffolding_checked" value="0" {{ old('mobile_scaffolding_checked') == '0' ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_scaffolding_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="mobile_scaffolding_sub" class="sub-questions {{ old('mobile_scaffolding_checked') == '1' ? 'show' : '' }}">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="1" {{ old('mobile_scaffolding_approved_by_she') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="0" {{ old('mobile_scaffolding_approved_by_she') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Elevation Platform -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Mobile elevation platform</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_elevation_checked" value="1" {{ old('mobile_elevation_checked') == '1' ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_elevation_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="mobile_elevation_checked" value="0" {{ old('mobile_elevation_checked') == '0' ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_elevation_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="mobile_elevation_sub" class="sub-questions {{ old('mobile_elevation_checked') == '1' ? 'show' : '' }}">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Operator terlatih?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_training_provided" value="1" {{ old('mobile_elevation_training_provided') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_training_provided" value="0" {{ old('mobile_elevation_training_provided') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Penggunaannya tertulis?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_used_before" value="1" {{ old('mobile_elevation_used_before') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_used_before" value="0" {{ old('mobile_elevation_used_before') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Menggunakan Alat Pelindung Jatuh?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_location_marked" value="1" {{ old('mobile_elevation_location_marked') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_location_marked" value="0" {{ old('mobile_elevation_location_marked') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tangga -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Tangga</span>
                            <div class="radio-group">
                                <label><input type="radio" name="ladder_checked" value="1" onchange="toggleSubQuestions('ladder_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="ladder_checked" value="0" onchange="toggleSubQuestions('ladder_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="ladder_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Tidak ada alat lain yang bisa dipakai?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_no_other_tools" value="1"> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_no_other_tools" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_activities_short" value="1"> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_activities_short" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Diperiksa dan di-tag</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="ladder_area_barriers" value="1"> Ya</label>
                                        <label><input type="radio" name="ladder_area_barriers" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pekerja dilatih menggunakannya</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="safety_personnel_required" value="1"> Ya</label>
                                        <label><input type="radio" name="safety_personnel_required" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fall Arrest -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Fall arrest seperti FBH digunakan?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fall_arrest_used" value="1" onchange="toggleSubQuestions('fall_arrest_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="fall_arrest_used" value="0" onchange="toggleSubQuestions('fall_arrest_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="fall_arrest_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Diperiksa sebelum digunakan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="area_closed_from_below" value="1"> Ya</label>
                                        <label><input type="radio" name="area_closed_from_below" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Tim pat cantolan ditunjuk oleh SHE PTB atau orang lain dengan kualifikasi</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="materials_secured" value="1"> Ya</label>
                                        <label><input type="radio" name="materials_secured" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roof Work -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Pekerjaan di Atap (Roof Work)</span>
                            <div class="radio-group">
                                <label><input type="radio" name="roof_work_checked" value="1" onchange="toggleSubQuestions('roof_work_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="roof_work_checked" value="0" onchange="toggleSubQuestions('roof_work_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="roof_work_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Atap kuat menahan beban?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_load_capacity" value="1"> Ya</label>
                                        <label><input type="radio" name="roof_load_capacity" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Apakah ada atap yang rawan?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_fragile_areas" value="1"> Ya</label>
                                        <label><input type="radio" name="roof_fragile_areas" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pelindung jatuh/pelindung disisi tersedia?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_fall_protection" value="1"> Ya</label>
                                        <label><input type="radio" name="roof_fall_protection" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Area dibarikade pada sisi bawah</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="emergency_exit_point" value="1"> Ya</label>
                                        <label><input type="radio" name="emergency_exit_point" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    </div>
                </div>

                <!-- Work Conditions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
                        <h5 class="card-title mb-0" style="color: white; font-weight: 600;">
                            <i class="fas fa-exclamation-triangle me-2"></i>Kondisi Pekerjaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="checklist-container">
                            
                            <!-- Area di bawah pekerjaan berlangsung ditutup -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Area di bawah pekerjaan berlangsung ditutup dari lalu lintas/pejalan kaki</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="area_below_closed" value="1" {{ old('area_below_closed') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="area_below_closed" value="0" {{ old('area_below_closed') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Gangguan pada atau sekitar lokasi pekerjaan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Gangguan pada atau sekitar lokasi pekerjaan (cable ducts/tray, single cables, pipa, dll)</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="work_area_disturbances" value="1" {{ old('work_area_disturbances') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="work_area_disturbances" value="0" {{ old('work_area_disturbances') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Ventilasi, cerobong, bukaan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Ventilasi, cerobong, bukaan yang mengeluarkan udara/air yang panas/berbau/berbahaya</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="ventilation_hazards" value="1" {{ old('ventilation_hazards') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="ventilation_hazards" value="0" {{ old('ventilation_hazards') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian dari mesin/peralatan harus dilindungi -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Bagian dari mesin/peralatan harus dilindungi</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="equipment_protection" value="1" {{ old('equipment_protection') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="equipment_protection" value="0" {{ old('equipment_protection') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Terdapat titik untuk keluar dalam kondisi darurat -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Terdapat titik untuk keluar dalam kondisi darurat</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="emergency_exit_available" value="1" {{ old('emergency_exit_available') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="emergency_exit_available" value="0" {{ old('emergency_exit_available') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Material/alat yang perlu diamankan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Material/alat yang perlu diamankan/diturunkan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="material_handling" value="1" {{ old('material_handling') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="material_handling" value="0" {{ old('material_handling') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Personnel Safety atau Petugas lain -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Personnel Safety atau Petugas lain yang diperlukan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="safety_personnel_needed" value="1" {{ old('safety_personnel_needed') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="safety_personnel_needed" value="0" {{ old('safety_personnel_needed') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Lainnya/Others -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Lainnya (Others)</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="other_conditions_check" value="1" {{ old('other_conditions_check') == '1' ? 'checked' : '' }} onchange="toggleOtherInput(this.value == '1')"> Ya</label>
                                        <label><input type="radio" name="other_conditions_check" value="0" {{ old('other_conditions_check') == '0' ? 'checked' : '' }} onchange="toggleOtherInput(this.value == '1')"> Tidak</label>
                                    </div>
                                </div>
                                
                                <div id="other_conditions_input" style="display: {{ old('other_conditions_check') == '1' ? 'block' : 'none' }}; margin-top: 10px;">
                                    <textarea name="other_conditions_text" class="form-control" rows="3" placeholder="Jelaskan kondisi kerja lainnya...">{{ old('other_conditions_text') }}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Environmental Conditions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-cloud-rain me-2"></i>Kondisi Lingkungan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            
                            <!-- Jarak pandang umum -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Jarak pandang umum</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="terang" {{ old('visibility_condition') == 'terang' ? 'checked' : '' }}> terang</label>
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="remang-remang" {{ old('visibility_condition') == 'remang-remang' ? 'checked' : '' }}> remang-remang</label>
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="gelap" {{ old('visibility_condition') == 'gelap' ? 'checked' : '' }}> gelap</label>
                                            <label><input type="radio" name="visibility_condition" value="berkabut" {{ old('visibility_condition') == 'berkabut' ? 'checked' : '' }}> berkabut</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hujan -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Hujan</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="rain_condition" value="tidak" {{ old('rain_condition') == 'tidak' ? 'checked' : '' }}> tidak</label>
                                            <label class="me-2"><input type="radio" name="rain_condition" value="rintik" {{ old('rain_condition') == 'rintik' ? 'checked' : '' }}> rintik</label>
                                            <label class="me-2"><input type="radio" name="rain_condition" value="gerimis" {{ old('rain_condition') == 'gerimis' ? 'checked' : '' }}> gerimis</label>
                                            <label><input type="radio" name="rain_condition" value="deras" {{ old('rain_condition') == 'deras' ? 'checked' : '' }}> deras</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kondisi permukaan tanah/lantai -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Kondisi permukaan tanah/lantai</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="surface_condition" value="kering" {{ old('surface_condition') == 'kering' ? 'checked' : '' }}> kering</label>
                                            <label class="me-2"><input type="radio" name="surface_condition" value="basah" {{ old('surface_condition') == 'basah' ? 'checked' : '' }}> basah</label>
                                            <label><input type="radio" name="surface_condition" value="licin" {{ old('surface_condition') == 'licin' ? 'checked' : '' }}> licin</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Angin -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Angin</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="wind_condition" value="tidak" {{ old('wind_condition') == 'tidak' ? 'checked' : '' }}> tidak</label>
                                            <label class="me-2"><input type="radio" name="wind_condition" value="sedang" {{ old('wind_condition') == 'sedang' ? 'checked' : '' }}> sedang</label>
                                            <label><input type="radio" name="wind_condition" value="kencang" {{ old('wind_condition') == 'kencang' ? 'checked' : '' }}> kencang</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permukaan licin -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Permukaan licin dari tumpahan oli atau bahan kimia?</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="chemical_spill_condition" value="Ya" {{ old('chemical_spill_condition') == 'Ya' ? 'checked' : '' }}> Ya</label>
                                            <label><input type="radio" name="chemical_spill_condition" value="Tidak" {{ old('chemical_spill_condition') == 'Tidak' ? 'checked' : '' }}> Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lainnya -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Lainnya</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="environment_other" value="1" {{ old('environment_other') == '1' ? 'checked' : '' }} onchange="toggleEnvironmentOtherInput(this.value == '1')"> Ya</label>
                                            <label><input type="radio" name="environment_other" value="0" {{ old('environment_other') == '0' ? 'checked' : '' }} onchange="toggleEnvironmentOtherInput(this.value == '1')"> Tidak</label>
                                        </div>
                                    </div>
                                    
                                    <div id="environment_other_input" style="display: {{ old('environment_other') == '1' ? 'block' : 'none' }}; margin-top: 10px;">
                                        <textarea name="environment_other_conditions" class="form-control" rows="3" placeholder="Jelaskan kondisi lingkungan lainnya...">{{ old('environment_other_conditions') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Save HRA Assessment
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Main Permit Info -->
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

                <!-- Safety Guidelines -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Safety Reminders
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>Review all safety requirements</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ensure proper training for operators</li>
                            <li><i class="fas fa-check text-success me-2"></i>Check equipment condition</li>
                            <li><i class="fas fa-check text-success me-2"></i>Verify fall protection measures</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('layouts.sidebar-scripts')

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for searchable selects
    $('.searchable-select').select2({
        theme: 'bootstrap-5',
        placeholder: function() {
            return $(this).data('placeholder') || 'Pilih...';
        },
        allowClear: true,
        width: '100%'
    });
    
    // Handle change event for worker name to update phone
    $('#worker_name').on('select2:select', function(e) {
        updateWorkerPhone();
    });
    
    $('#worker_name').on('select2:clear', function(e) {
        $('#worker_phone').val('');
    });

    // Handle datetime validation
    $('#start_datetime').on('change', function() {
        const startDateTime = this.value;
        const endDateTimeInput = document.getElementById('end_datetime');
        
        if (startDateTime) {
            // Set minimum end datetime to be same as start datetime
            endDateTimeInput.min = startDateTime;
            
            // If end datetime is already set and is earlier than start, clear it
            if (endDateTimeInput.value && endDateTimeInput.value < startDateTime) {
                endDateTimeInput.value = '';
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai. Silakan pilih ulang tanggal selesai.');
            }
        }
    });

    $('#end_datetime').on('change', function() {
        const endDateTime = this.value;
        const startDateTime = document.getElementById('start_datetime').value;
        
        if (startDateTime && endDateTime && endDateTime < startDateTime) {
            alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
            this.value = '';
        }
    });

    // Form validation for environmental conditions, work conditions, and HRA assessment
    $('form').on('submit', function(e) {
        let isValid = true;
        let missingFields = [];

        // Environmental Conditions - all required
        const environmentalFields = [
            'visibility_condition',
            'rain_condition', 
            'surface_condition',
            'wind_condition',
            'chemical_spill_condition',
            'environment_other'
        ];

        environmentalFields.forEach(fieldName => {
            const radios = document.querySelectorAll(`input[name="${fieldName}"]`);
            let isChecked = false;
            
            radios.forEach(radio => {
                if (radio.checked) {
                    isChecked = true;
                }
            });
            
            if (!isChecked) {
                isValid = false;
                missingFields.push('Environmental: ' + fieldName.replace('_', ' '));
            }
        });

        // Work Conditions - all required
        const workConditionFields = [
            'area_below_closed',
            'work_area_disturbances',
            'ventilation_hazards',
            'equipment_protection',
            'emergency_exit_available',
            'material_handling',
            'safety_personnel_needed',
            'other_conditions_check'
        ];

        workConditionFields.forEach(fieldName => {
            const radios = document.querySelectorAll(`input[name="${fieldName}"]`);
            let isChecked = false;
            
            radios.forEach(radio => {
                if (radio.checked) {
                    isChecked = true;
                }
            });
            
            if (!isChecked) {
                isValid = false;
                missingFields.push('Work Condition: ' + fieldName.replace('_', ' '));
            }
        });

        // HRA Assessment - Parent questions (all required)
        const parentQuestions = [
            'fixed_scaffolding_checked',
            'mobile_scaffolding_checked',
            'mobile_elevation_checked',
            'ladder_checked',
            'fall_arrest_used',
            'roof_work_checked'
        ];

        parentQuestions.forEach(fieldName => {
            const radios = document.querySelectorAll(`input[name="${fieldName}"]`);
            let isChecked = false;
            let selectedValue = null;
            
            radios.forEach(radio => {
                if (radio.checked) {
                    isChecked = true;
                    selectedValue = radio.value;
                }
            });
            
            if (!isChecked) {
                isValid = false;
                missingFields.push('HRA Assessment: ' + fieldName.replace('_checked', '').replace('_', ' '));
            }
            
            // Check sub-questions if parent is "Ya" (value="1")
            if (selectedValue === '1') {
                const subQuestionFields = getSubQuestionFields(fieldName);
                subQuestionFields.forEach(subField => {
                    const subRadios = document.querySelectorAll(`input[name="${subField}"]`);
                    let subIsChecked = false;
                    
                    subRadios.forEach(radio => {
                        if (radio.checked) {
                            subIsChecked = true;
                        }
                    });
                    
                    if (!subIsChecked) {
                        isValid = false;
                        missingFields.push('Sub-question: ' + subField.replace('_', ' '));
                    }
                });
            }
        });

        if (!isValid) {
            e.preventDefault();
            
            // Add visual indicators for missing fields
            $('input[type="radio"]').removeClass('is-invalid');
            
            // Highlight missing fields
            [...environmentalFields, ...workConditionFields, ...parentQuestions].forEach(fieldName => {
                const radios = document.querySelectorAll(`input[name="${fieldName}"]`);
                let isChecked = false;
                
                radios.forEach(radio => {
                    if (radio.checked) {
                        isChecked = true;
                    }
                });
                
                if (!isChecked) {
                    radios.forEach(radio => {
                        radio.classList.add('is-invalid');
                        // Add red border to parent container
                        const container = radio.closest('.checklist-item');
                        if (container) {
                            container.style.borderLeft = '4px solid #dc3545';
                            container.style.backgroundColor = '#fff5f5';
                        }
                    });
                }
            });
            
            alert('❌ FORM BELUM LENGKAP!\n\n' + 
                  'Mohon lengkapi semua pertanyaan berikut:\n' + 
                  '• ENVIRONMENTAL CONDITIONS (semua harus diisi)\n' + 
                  '• WORK CONDITIONS (semua harus diisi)\n' + 
                  '• HRA ASSESSMENT (parent questions harus diisi)\n' + 
                  '• SUB-QUESTIONS (wajib diisi jika parent = "Ya")\n\n' +
                  'Field yang belum diisi:\n' + missingFields.slice(0, 10).join('\n') + 
                  (missingFields.length > 10 ? '\n... dan ' + (missingFields.length - 10) + ' lainnya' : '') +
                  '\n\nSilakan periksa kembali form Anda! ⚠️');
            
            // Scroll to first missing field
            const firstMissing = missingFields[0];
            if (firstMissing) {
                const searchTerm = firstMissing.split(': ')[1] || firstMissing;
                const fieldElement = document.querySelector(`input[name*="${searchTerm.replace(' ', '_')}"]`);
                if (fieldElement) {
                    fieldElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Highlight the field
                    setTimeout(() => {
                        fieldElement.focus();
                    }, 500);
                }
            }
            
            return false;
        }
        
        // Remove any previous error indicators
        $('input[type="radio"]').removeClass('is-invalid');
        $('.checklist-item').each(function() {
            this.style.borderLeft = '';
            this.style.backgroundColor = '';
        });
        
        // Show success message if all fields are valid
        const submitButton = e.target.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitButton.disabled = true;
        }
    });

    // Function to get sub-question fields for each parent
    function getSubQuestionFields(parentFieldName) {
        const subQuestionMap = {
            'fixed_scaffolding_checked': [
                'fixed_scaffolding_approved_by_she'
            ],
            'mobile_scaffolding_checked': [
                'mobile_scaffolding_approved_by_she'
            ],
            'mobile_elevation_checked': [
                'mobile_elevation_training_provided',
                'mobile_elevation_used_before',
                'mobile_elevation_location_marked'
            ],
            'ladder_checked': [
                'mobile_elevation_no_other_tools',
                'mobile_elevation_activities_short',
                'ladder_area_barriers',
                'safety_personnel_required'
            ],
            'fall_arrest_used': [
                'area_closed_from_below',
                'materials_secured'
            ],
            'roof_work_checked': [
                'roof_load_capacity',
                'roof_fragile_areas'
            ]
        };
        
        return subQuestionMap[parentFieldName] || [];
    }
});

function toggleSubQuestions(containerId, isChecked) {
    const container = document.getElementById(containerId);
    if (isChecked) {
        container.classList.add('show');
    } else {
        container.classList.remove('show');
        // Reset all checkboxes and radio buttons in this container
        const checkboxes = container.querySelectorAll('input[type="checkbox"]');
        const radioButtons = container.querySelectorAll('input[type="radio"]');
        
        checkboxes.forEach(checkbox => checkbox.checked = false);
        radioButtons.forEach(radio => radio.checked = false);
    }
}

function updateWorkerPhone() {
    const selectElement = document.getElementById('worker_name');
    const phoneInput = document.getElementById('worker_phone');
    
    if (selectElement.value) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const phone = selectedOption.getAttribute('data-phone');
        phoneInput.value = phone || '';
    } else {
        phoneInput.value = '';
    }
}

function toggleOtherInput(isChecked) {
    const otherInput = document.getElementById('other_conditions_input');
    const textarea = otherInput.querySelector('textarea');
    
    if (isChecked) {
        otherInput.style.display = 'block';
    } else {
        otherInput.style.display = 'none';
        textarea.value = '';
    }
}

function toggleEnvironmentOtherInput(isChecked) {
    const otherInput = document.getElementById('environment_other_input');
    const textarea = otherInput.querySelector('textarea');
    
    if (isChecked) {
        otherInput.style.display = 'block';
    } else {
        otherInput.style.display = 'none';
        textarea.value = '';
    }
}

// Add event listeners for validation feedback
$(document).ready(function() {
    // Remove error indicators when user makes a selection
    $('input[type="radio"]').on('change', function() {
        const container = this.closest('.checklist-item');
        if (container) {
            container.style.borderLeft = '';
            container.style.backgroundColor = '';
            container.classList.remove('error');
        }
        this.classList.remove('is-invalid');
    });

    // Show validation feedback on page load if there are old values
    console.log('HRA Work at Heights form validation loaded successfully ✅');
});
</script>
@endpush

@endsection