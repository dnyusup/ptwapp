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

@php
// Helper function to check if value should be checked for "Ya" (value=1)
function isCheckedYes($value) {
    return $value === true || $value === 1 || $value === '1';
}
// Helper function to check if value should be checked for "Tidak" (value=0)
function isCheckedNo($value) {
    return $value === false || $value === 0 || $value === '0';
}
@endphp

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Edit HRA - Work at Heights</h4>
                <p class="text-muted mb-0">
                    HRA Permit: <strong>{{ $hraWorkAtHeight->hra_permit_number }}</strong> | Main Permit: <strong>{{ $permit->permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to HRA Details
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
    <form method="POST" action="{{ route('hra.work-at-heights.update', [$permit, $hraWorkAtHeight]) }}">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                
                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);"">
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
                                                {{ old('worker_name', $hraWorkAtHeight->worker_name) == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih dari user perusahaan: {{ $permit->receiver_company_name ?? 'Tidak ada perusahaan' }}</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="worker_phone" class="form-label">No HP Pekerja</label>
                                <input type="text" class="form-control" id="worker_phone" name="worker_phone" 
                                       value="{{ old('worker_phone', $hraWorkAtHeight->worker_phone) }}" readonly>
                                <small class="text-muted">Otomatis terisi berdasarkan nama pekerja</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="supervisor_name" class="form-label">Nama Pendamping</label>
                                <select class="form-select searchable-select" id="supervisor_name" name="supervisor_name" required>
                                    <option value="">Pilih Pendamping...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" 
                                                {{ old('supervisor_name', $hraWorkAtHeight->supervisor_name) == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="work_location" class="form-label">Lokasi Kerja</label>
                                <input type="text" class="form-control" id="work_location" name="work_location" 
                                       value="{{ old('work_location', $hraWorkAtHeight->work_location) }}" readonly>
                                <small class="text-muted">Otomatis dari data permit</small>
                            </div>
                            @php
                                $startDate = $hraWorkAtHeight->start_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->start_datetime)->format('Y-m-d') : date('Y-m-d');
                                $startTime = $hraWorkAtHeight->start_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->start_datetime)->format('H:i') : '08:00';
                                $endDate = $hraWorkAtHeight->end_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->end_datetime)->format('Y-m-d') : date('Y-m-d');
                                $endTime = $hraWorkAtHeight->end_datetime ? \Carbon\Carbon::parse($hraWorkAtHeight->end_datetime)->format('H:i') : '17:00';
                            @endphp
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ old('start_date', $startDate) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" required onchange="updateEndDate()">
                                <small class="text-muted">Default: hari ini</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="{{ old('start_time', $startTime) }}" required onchange="updateEndTime()">
                                <small class="text-muted">Default: 08:00</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ old('end_date', $endDate) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" readonly required>
                                <small class="text-muted">Otomatis sama dengan tanggal mulai</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="{{ old('end_time', $endTime) }}" required>
                                <small class="text-muted">Otomatis +9 jam dari jam mulai</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" placeholder="Jelaskan detail pekerjaan yang akan dilakukan..." required>{{ old('work_description', $hraWorkAtHeight->work_description) }}</textarea>
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
                    
                    <!-- Layanan overhead/bahaya? -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Layanan overhead/bahaya?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="overhead_hazards_checked" value="1" {{ isCheckedYes(old('overhead_hazards_checked', $hraWorkAtHeight->overhead_hazards_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('overhead_hazards_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="overhead_hazards_checked" value="0" {{ isCheckedNo(old('overhead_hazards_checked', $hraWorkAtHeight->overhead_hazards_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('overhead_hazards_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="overhead_hazards_sub" class="sub-questions {{ isCheckedYes(old('overhead_hazards_checked', $hraWorkAtHeight->overhead_hazards_checked)) ? 'show' : '' }}">
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Tutup benturan, minimal, digunakan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="overhead_hazards_minimal_guards" value="1" {{ isCheckedYes(old('overhead_hazards_minimal_guards', $hraWorkAtHeight->overhead_hazards_minimal_guards)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="overhead_hazards_minimal_guards" value="0" {{ isCheckedNo(old('overhead_hazards_minimal_guards', $hraWorkAtHeight->overhead_hazards_minimal_guards)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fixed Scaffolding -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Fixed Scaffolding</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_checked" value="1" {{ isCheckedYes(old('fixed_scaffolding_checked', $hraWorkAtHeight->fixed_scaffolding_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('fixed_scaffolding_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="fixed_scaffolding_checked" value="0" {{ isCheckedNo(old('fixed_scaffolding_checked', $hraWorkAtHeight->fixed_scaffolding_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('fixed_scaffolding_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="fixed_scaffolding_sub" class="sub-questions {{ isCheckedYes(old('fixed_scaffolding_checked', $hraWorkAtHeight->fixed_scaffolding_checked)) ? 'show' : '' }}">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="1" {{ isCheckedYes(old('fixed_scaffolding_approved_by_she', $hraWorkAtHeight->fixed_scaffolding_approved_by_she)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="0" {{ isCheckedNo(old('fixed_scaffolding_approved_by_she', $hraWorkAtHeight->fixed_scaffolding_approved_by_she)) ? 'checked' : '' }}> Tidak</label>
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
                                <label><input type="radio" name="mobile_scaffolding_checked" value="1" {{ isCheckedYes(old('mobile_scaffolding_checked', $hraWorkAtHeight->mobile_scaffolding_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_scaffolding_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="mobile_scaffolding_checked" value="0" {{ isCheckedNo(old('mobile_scaffolding_checked', $hraWorkAtHeight->mobile_scaffolding_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_scaffolding_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="mobile_scaffolding_sub" class="sub-questions {{ isCheckedYes(old('mobile_scaffolding_checked', $hraWorkAtHeight->mobile_scaffolding_checked)) ? 'show' : '' }}">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="1" {{ isCheckedYes(old('mobile_scaffolding_approved_by_she', $hraWorkAtHeight->mobile_scaffolding_approved_by_she)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="0" {{ isCheckedNo(old('mobile_scaffolding_approved_by_she', $hraWorkAtHeight->mobile_scaffolding_approved_by_she)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Elevated Working Platform (MEWP) -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Mobile Elevated Working Platform (MEWP)</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_elevation_checked" value="1" {{ isCheckedYes(old('mobile_elevation_checked', $hraWorkAtHeight->mobile_elevation_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_elevation_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="mobile_elevation_checked" value="0" {{ isCheckedNo(old('mobile_elevation_checked', $hraWorkAtHeight->mobile_elevation_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('mobile_elevation_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="mobile_elevation_sub" class="sub-questions {{ isCheckedYes(old('mobile_elevation_checked', $hraWorkAtHeight->mobile_elevation_checked)) ? 'show' : '' }}">
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Operator terlatih</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_operator_trained" value="1" {{ isCheckedYes(old('mobile_elevation_operator_trained', $hraWorkAtHeight->mobile_elevation_operator_trained)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_operator_trained" value="0" {{ isCheckedNo(old('mobile_elevation_operator_trained', $hraWorkAtHeight->mobile_elevation_operator_trained)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Orang yang berkompeten untuk melakukan penyelamatan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_rescue_person" value="1" {{ isCheckedYes(old('mobile_elevation_rescue_person', $hraWorkAtHeight->mobile_elevation_rescue_person)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_rescue_person" value="0" {{ isCheckedNo(old('mobile_elevation_rescue_person', $hraWorkAtHeight->mobile_elevation_rescue_person)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pemantau di tempat untuk semua pergerakan MEWP</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_monitor_in_place" value="1" {{ isCheckedYes(old('mobile_elevation_monitor_in_place', $hraWorkAtHeight->mobile_elevation_monitor_in_place)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_monitor_in_place" value="0" {{ isCheckedNo(old('mobile_elevation_monitor_in_place', $hraWorkAtHeight->mobile_elevation_monitor_in_place)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Catatan pemeriksaan hukum valid</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_legal_inspection" value="1" {{ isCheckedYes(old('mobile_elevation_legal_inspection', $hraWorkAtHeight->mobile_elevation_legal_inspection)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_legal_inspection" value="0" {{ isCheckedNo(old('mobile_elevation_legal_inspection', $hraWorkAtHeight->mobile_elevation_legal_inspection)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pemeriksaan pra-penggunaan yang terdokumentasi telah selesai</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_pre_use_inspection" value="1" {{ isCheckedYes(old('mobile_elevation_pre_use_inspection', $hraWorkAtHeight->mobile_elevation_pre_use_inspection)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_pre_use_inspection" value="0" {{ isCheckedNo(old('mobile_elevation_pre_use_inspection', $hraWorkAtHeight->mobile_elevation_pre_use_inspection)) ? 'checked' : '' }}> Tidak</label>
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
                                <label><input type="radio" name="ladder_checked" value="1" {{ isCheckedYes(old('ladder_checked', $hraWorkAtHeight->ladder_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('ladder_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="ladder_checked" value="0" {{ isCheckedNo(old('ladder_checked', $hraWorkAtHeight->ladder_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('ladder_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="ladder_sub" class="sub-questions {{ isCheckedYes(old('ladder_checked', $hraWorkAtHeight->ladder_checked)) ? 'show' : '' }}">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Tidak ada alat lain yang bisa dipakai?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_no_other_tools" value="1" {{ isCheckedYes(old('mobile_elevation_no_other_tools', $hraWorkAtHeight->mobile_elevation_no_other_tools)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_no_other_tools" value="0" {{ isCheckedNo(old('mobile_elevation_no_other_tools', $hraWorkAtHeight->mobile_elevation_no_other_tools)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_activities_short" value="1" {{ isCheckedYes(old('mobile_elevation_activities_short', $hraWorkAtHeight->mobile_elevation_activities_short)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_activities_short" value="0" {{ isCheckedNo(old('mobile_elevation_activities_short', $hraWorkAtHeight->mobile_elevation_activities_short)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Diperiksa dan di-tag</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="ladder_area_barriers" value="1" {{ isCheckedYes(old('ladder_area_barriers', $hraWorkAtHeight->ladder_area_barriers)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="ladder_area_barriers" value="0" {{ isCheckedNo(old('ladder_area_barriers', $hraWorkAtHeight->ladder_area_barriers)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pekerja dilatih menggunakannya</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="safety_personnel_required" value="1" {{ isCheckedYes(old('safety_personnel_required', $hraWorkAtHeight->safety_personnel_required)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="safety_personnel_required" value="0" {{ isCheckedNo(old('safety_personnel_required', $hraWorkAtHeight->safety_personnel_required)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fall Arrest -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">APD WAH diperlukan? (peralatan penangkap dan penahan jatuh)</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fall_arrest_used" value="1" {{ isCheckedYes(old('fall_arrest_used', $hraWorkAtHeight->fall_arrest_used)) ? 'checked' : '' }} onchange="toggleSubQuestions('fall_arrest_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="fall_arrest_used" value="0" {{ isCheckedNo(old('fall_arrest_used', $hraWorkAtHeight->fall_arrest_used)) ? 'checked' : '' }} onchange="toggleSubQuestions('fall_arrest_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="fall_arrest_sub" class="sub-questions {{ isCheckedYes(old('fall_arrest_used', $hraWorkAtHeight->fall_arrest_used)) ? 'show' : '' }}">
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pekerja yang terlatih dalam penggunaan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fall_arrest_worker_trained" value="1" {{ isCheckedYes(old('fall_arrest_worker_trained', $hraWorkAtHeight->fall_arrest_worker_trained)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fall_arrest_worker_trained" value="0" {{ isCheckedNo(old('fall_arrest_worker_trained', $hraWorkAtHeight->fall_arrest_worker_trained)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Catatan pemeriksaan hukum valid</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fall_arrest_legal_inspection" value="1" {{ isCheckedYes(old('fall_arrest_legal_inspection', $hraWorkAtHeight->fall_arrest_legal_inspection)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fall_arrest_legal_inspection" value="0" {{ isCheckedNo(old('fall_arrest_legal_inspection', $hraWorkAtHeight->fall_arrest_legal_inspection)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pemeriksaan pra-penggunaan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fall_arrest_pre_use_inspection" value="1" {{ isCheckedYes(old('fall_arrest_pre_use_inspection', $hraWorkAtHeight->fall_arrest_pre_use_inspection)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fall_arrest_pre_use_inspection" value="0" {{ isCheckedNo(old('fall_arrest_pre_use_inspection', $hraWorkAtHeight->fall_arrest_pre_use_inspection)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sarana pengikatan yang ditentukan oleh personel yang berkualifikasi</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fall_arrest_qualified_personnel" value="1" {{ isCheckedYes(old('fall_arrest_qualified_personnel', $hraWorkAtHeight->fall_arrest_qualified_personnel)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fall_arrest_qualified_personnel" value="0" {{ isCheckedNo(old('fall_arrest_qualified_personnel', $hraWorkAtHeight->fall_arrest_qualified_personnel)) ? 'checked' : '' }}> Tidak</label>
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
                                <label><input type="radio" name="roof_work_checked" value="1" {{ isCheckedYes(old('roof_work_checked', $hraWorkAtHeight->roof_work_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('roof_work_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="roof_work_checked" value="0" {{ isCheckedNo(old('roof_work_checked', $hraWorkAtHeight->roof_work_checked)) ? 'checked' : '' }} onchange="toggleSubQuestions('roof_work_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="roof_work_sub" class="sub-questions {{ isCheckedYes(old('roof_work_checked', $hraWorkAtHeight->roof_work_checked)) ? 'show' : '' }}">
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Kapasitas menahan beban atap cukup</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_load_capacity_adequate" value="1" {{ isCheckedYes(old('roof_load_capacity_adequate', $hraWorkAtHeight->roof_load_capacity_adequate)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="roof_load_capacity_adequate" value="0" {{ isCheckedNo(old('roof_load_capacity_adequate', $hraWorkAtHeight->roof_load_capacity_adequate)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Penggunaan perlindungan tepi</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_edge_protection" value="1" {{ isCheckedYes(old('roof_edge_protection', $hraWorkAtHeight->roof_edge_protection)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="roof_edge_protection" value="0" {{ isCheckedNo(old('roof_edge_protection', $hraWorkAtHeight->roof_edge_protection)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Penggunaan sistem perlindungan jatuh/ WaH PPE</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_fall_protection_system" value="1" {{ isCheckedYes(old('roof_fall_protection_system', $hraWorkAtHeight->roof_fall_protection_system)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="roof_fall_protection_system" value="0" {{ isCheckedNo(old('roof_fall_protection_system', $hraWorkAtHeight->roof_fall_protection_system)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-question-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Metode komunikasi yang disepakati</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="roof_communication_method" value="1" {{ isCheckedYes(old('roof_communication_method', $hraWorkAtHeight->roof_communication_method)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="roof_communication_method" value="0" {{ isCheckedNo(old('roof_communication_method', $hraWorkAtHeight->roof_communication_method)) ? 'checked' : '' }}> Tidak</label>
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
                            
                            <!-- Apakah mereka yang terlibat memiliki bukti Pelatihan Bekerja di Ketinggian -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah mereka yang terlibat memiliki bukti Pelatihan Bekerja di Ketinggian (termasuk penggunaan tangga bila diperlukan)?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="workers_have_training_proof" value="1" {{ isCheckedYes(old('workers_have_training_proof', $hraWorkAtHeight->workers_have_training_proof)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="workers_have_training_proof" value="0" {{ isCheckedNo(old('workers_have_training_proof', $hraWorkAtHeight->workers_have_training_proof)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah area di bawah tempat kerja telah diblokir untuk kendaraan/lalu lintas/pejalan kaki -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah area di bawah tempat kerja telah diblokir untuk kendaraan/lalu lintas/pejalan kaki?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="area_below_blocked" value="1" {{ isCheckedYes(old('area_below_blocked', $hraWorkAtHeight->area_below_blocked)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="area_below_blocked" value="0" {{ isCheckedNo(old('area_below_blocked', $hraWorkAtHeight->area_below_blocked)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah ada yang bekerja di bawah mereka yang bekerja di ketinggian -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah ada yang bekerja di bawah mereka yang bekerja di ketinggian? Jika ada, helm pengaman wajib dipakai.</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="workers_below_present" value="1" {{ isCheckedYes(old('workers_below_present', $hraWorkAtHeight->workers_below_present)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="workers_below_present" value="0" {{ isCheckedNo(old('workers_below_present', $hraWorkAtHeight->workers_below_present)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah lantai/tanah cocok untuk digunakannya peralatan akses -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah lantai/tanah cocok untuk digunakannya peralatan akses?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="floor_suitable_for_access_equipment" value="1" {{ isCheckedYes(old('floor_suitable_for_access_equipment', $hraWorkAtHeight->floor_suitable_for_access_equipment)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="floor_suitable_for_access_equipment" value="0" {{ isCheckedNo(old('floor_suitable_for_access_equipment', $hraWorkAtHeight->floor_suitable_for_access_equipment)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah ada kendala di atau dekat lokasi kerja -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah ada kendala di atau dekat lokasi kerja (saluran kabel, kabel tunggal, pipa, dll.)?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="obstacles_near_work_location" value="1" {{ isCheckedYes(old('obstacles_near_work_location', $hraWorkAtHeight->obstacles_near_work_location)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="obstacles_near_work_location" value="0" {{ isCheckedNo(old('obstacles_near_work_location', $hraWorkAtHeight->obstacles_near_work_location)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah ada ventilasi, cerobong asapyang dapat mengeluarkan media panas/berbau/berbahaya -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah ada ventilasi, cerobong asapyang dapat mengeluarkan media panas/berbau/berbahaya?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="ventilation_hazardous_emissions" value="1" {{ isCheckedYes(old('ventilation_hazardous_emissions', $hraWorkAtHeight->ventilation_hazardous_emissions)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="ventilation_hazardous_emissions" value="0" {{ isCheckedNo(old('ventilation_hazardous_emissions', $hraWorkAtHeight->ventilation_hazardous_emissions)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah perlindungan dibutuhkan untuk peralatan akses WaH dan/atau peralatan proses/pabrik di lokasi -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah perlindungan dibutuhkan untuk peralatan akses WaH dan/atau peralatan proses/pabrik di lokasi?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="protection_needed_for_equipment" value="1" {{ isCheckedYes(old('protection_needed_for_equipment', $hraWorkAtHeight->protection_needed_for_equipment)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="protection_needed_for_equipment" value="0" {{ isCheckedNo(old('protection_needed_for_equipment', $hraWorkAtHeight->protection_needed_for_equipment)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah ada metode akses & keluar yang aman -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah ada metode akses & keluar yang aman? (Tangga akses harus diperpanjang 1 m di atas tempat pendaratan)</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="safe_access_exit_method" value="1" {{ isCheckedYes(old('safe_access_exit_method', $hraWorkAtHeight->safe_access_exit_method)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="safe_access_exit_method" value="0" {{ isCheckedNo(old('safe_access_exit_method', $hraWorkAtHeight->safe_access_exit_method)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah cara yang aman untuk menaik turunkan material dan peralatan telah ditentukan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah cara yang aman untuk menaik turunkan material dan peralatan telah ditentukan?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="safe_material_handling_method" value="1" {{ isCheckedYes(old('safe_material_handling_method', $hraWorkAtHeight->safe_material_handling_method)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="safe_material_handling_method" value="0" {{ isCheckedNo(old('safe_material_handling_method', $hraWorkAtHeight->safe_material_handling_method)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apakah diperlukan rencana darurat & pelarian -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah diperlukan rencana darurat & pelarian?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="emergency_escape_plan_needed" value="1" {{ isCheckedYes(old('emergency_escape_plan_needed', $hraWorkAtHeight->emergency_escape_plan_needed)) ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="emergency_escape_plan_needed" value="0" {{ isCheckedNo(old('emergency_escape_plan_needed', $hraWorkAtHeight->emergency_escape_plan_needed)) ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Lainnya/Others -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Lainnya (Others)</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="other_conditions_check" value="1" {{ isCheckedYes(old('other_conditions_check', $hraWorkAtHeight->other_conditions_check)) ? 'checked' : '' }} onchange="toggleOtherInput(this.value == '1')"> Ya</label>
                                        <label><input type="radio" name="other_conditions_check" value="0" {{ isCheckedNo(old('other_conditions_check', $hraWorkAtHeight->other_conditions_check)) ? 'checked' : '' }} onchange="toggleOtherInput(this.value == '1')"> Tidak</label>
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
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="terang" {{ old('visibility_condition', $hraWorkAtHeight->visibility_condition) == 'terang' ? 'checked' : '' }}> terang</label>
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="remang-remang" {{ old('visibility_condition', $hraWorkAtHeight->visibility_condition) == 'remang-remang' ? 'checked' : '' }}> remang-remang</label>
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="gelap" {{ old('visibility_condition', $hraWorkAtHeight->visibility_condition) == 'gelap' ? 'checked' : '' }}> gelap</label>
                                            <label><input type="radio" name="visibility_condition" value="berkabut" {{ old('visibility_condition', $hraWorkAtHeight->visibility_condition) == 'berkabut' ? 'checked' : '' }}> berkabut</label>
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
                                            <label class="me-2"><input type="radio" name="rain_condition" value="tidak" {{ old('rain_condition', $hraWorkAtHeight->rain_condition) == 'tidak' ? 'checked' : '' }}> tidak</label>
                                            <label class="me-2"><input type="radio" name="rain_condition" value="rintik" {{ old('rain_condition', $hraWorkAtHeight->rain_condition) == 'rintik' ? 'checked' : '' }}> rintik</label>
                                            <label class="me-2"><input type="radio" name="rain_condition" value="gerimis" {{ old('rain_condition', $hraWorkAtHeight->rain_condition) == 'gerimis' ? 'checked' : '' }}> gerimis</label>
                                            <label><input type="radio" name="rain_condition" value="deras" {{ old('rain_condition', $hraWorkAtHeight->rain_condition) == 'deras' ? 'checked' : '' }}> deras</label>
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
                                            <label class="me-2"><input type="radio" name="surface_condition" value="kering" {{ old('surface_condition', $hraWorkAtHeight->surface_condition) == 'kering' ? 'checked' : '' }}> kering</label>
                                            <label class="me-2"><input type="radio" name="surface_condition" value="basah" {{ old('surface_condition', $hraWorkAtHeight->surface_condition) == 'basah' ? 'checked' : '' }}> basah</label>
                                            <label><input type="radio" name="surface_condition" value="licin" {{ old('surface_condition', $hraWorkAtHeight->surface_condition) == 'licin' ? 'checked' : '' }}> licin</label>
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
                                            <label class="me-2"><input type="radio" name="wind_condition" value="tidak" {{ old('wind_condition', $hraWorkAtHeight->wind_condition) == 'tidak' ? 'checked' : '' }}> tidak</label>
                                            <label class="me-2"><input type="radio" name="wind_condition" value="sedang" {{ old('wind_condition', $hraWorkAtHeight->wind_condition) == 'sedang' ? 'checked' : '' }}> sedang</label>
                                            <label><input type="radio" name="wind_condition" value="kencang" {{ old('wind_condition', $hraWorkAtHeight->wind_condition) == 'kencang' ? 'checked' : '' }}> kencang</label>
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
                                            <label class="me-2"><input type="radio" name="chemical_spill_condition" value="Ya" {{ old('chemical_spill_condition', $hraWorkAtHeight->chemical_spill_condition) == 'Ya' ? 'checked' : '' }}> Ya</label>
                                            <label><input type="radio" name="chemical_spill_condition" value="Tidak" {{ old('chemical_spill_condition', $hraWorkAtHeight->chemical_spill_condition) == 'Tidak' ? 'checked' : '' }}> Tidak</label>
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
                                            @php
                                                $hasOtherConditions = !empty(old('environment_other_conditions', $hraWorkAtHeight->environment_other_conditions));
                                            @endphp
                                            <label class="me-2"><input type="radio" name="environment_other" value="1" {{ $hasOtherConditions ? 'checked' : '' }} onchange="toggleEnvironmentOtherInput(this.value == '1')"> Ya</label>
                                            <label><input type="radio" name="environment_other" value="0" {{ !$hasOtherConditions ? 'checked' : '' }} onchange="toggleEnvironmentOtherInput(this.value == '1')"> Tidak</label>
                                        </div>
                                    </div>
                                    
                                    <div id="environment_other_input" style="display: {{ $hasOtherConditions ? 'block' : 'none' }}; margin-top: 10px;">
                                        <textarea name="environment_other_conditions" class="form-control" rows="3" placeholder="Jelaskan kondisi lingkungan lainnya...">{{ old('environment_other_conditions', $hraWorkAtHeight->environment_other_conditions) }}</textarea>
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
    
    // Initialize end date and time on page load
    updateEndDate();
    updateEndTime();

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
            'workers_have_training_proof',
            'area_below_blocked',
            'workers_below_present',
            'floor_suitable_for_access_equipment',
            'obstacles_near_work_location',
            'ventilation_hazardous_emissions',
            'protection_needed_for_equipment',
            'safe_access_exit_method',
            'safe_material_handling_method',
            'emergency_escape_plan_needed',
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
            'overhead_hazards_checked': [
                'overhead_hazards_minimal_guards'
            ],
            'fixed_scaffolding_checked': [
                'fixed_scaffolding_approved_by_she'
            ],
            'mobile_scaffolding_checked': [
                'mobile_scaffolding_approved_by_she'
            ],
            'mobile_elevation_checked': [
                'mobile_elevation_operator_trained',
                'mobile_elevation_rescue_person',
                'mobile_elevation_monitor_in_place',
                'mobile_elevation_legal_inspection',
                'mobile_elevation_pre_use_inspection'
            ],
            'ladder_checked': [
                'mobile_elevation_no_other_tools',
                'mobile_elevation_activities_short',
                'ladder_area_barriers',
                'safety_personnel_required'
            ],
            'fall_arrest_used': [
                'fall_arrest_worker_trained',
                'fall_arrest_legal_inspection',
                'fall_arrest_pre_use_inspection',
                'fall_arrest_qualified_personnel'
            ],
            'roof_work_checked': [
                'roof_load_capacity_adequate',
                'roof_edge_protection',
                'roof_fall_protection_system',
                'roof_communication_method'
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

function updateEndDate() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date');
    
    if (startDate) {
        endDate.value = startDate;
    }
}

function updateEndTime() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time');
    
    if (startTime) {
        // Parse start time and add 9 hours
        const [hours, minutes] = startTime.split(':');
        const startHour = parseInt(hours);
        const endHour = (startHour + 9) % 24; // Handle overflow to next day
        
        // Format end time
        const formattedEndTime = String(endHour).padStart(2, '0') + ':' + minutes;
        endTime.value = formattedEndTime;
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


