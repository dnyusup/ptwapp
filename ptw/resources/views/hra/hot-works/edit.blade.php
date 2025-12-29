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

.checklist-item:last-child {                        <!-- Fire Blanket -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Fire blanket</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_blanket" value="1" {{ old('fire_blanket', $hraHotWork->fire_blanket) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_blanket" value="0" {{ old('fire_blanket', $hraHotWork->fire_blanket) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>r-bottom: none;
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
    align-items: center;
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
    
    .checkbox-group label {
        font-size: 13px;
        white-space: nowrap;
    }
}

.checkbox-group label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
    margin-bottom: 0;
}

.checkbox-group input[type="checkbox"],
.checkbox-group input[type="radio"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.checkbox-group input[type="number"] {
    width: 80px;
    height: 32px;
    margin-right: 8px;
    text-align: center;
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
                <h4 class="mb-1">Edit HRA - Hot Works</h4>
                <p class="text-muted mb-0">
                    HRA Number: <strong>{{ $hraHotWork->hra_permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to HRA
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
    <form method="POST" action="{{ route('hra.hot-works.update', [$permit, $hraHotWork]) }}">
        @csrf
        @method('PUT')
        
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
                                <label for="worker_name" class="form-label">Nama Pekerja</label>
                                <select class="form-select searchable-select" id="worker_name" name="worker_name" required onchange="updateWorkerPhone()">
                                    <option value="">Pilih Pekerja...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" 
                                                data-phone="{{ $user->phone ?? '' }}"
                                                {{ old('worker_name', $hraHotWork->worker_name) == $user->name ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih dari user perusahaan: {{ $permit->receiver_company_name ?? 'Tidak ada perusahaan' }}</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="worker_phone" class="form-label">No HP Pekerja</label>
                                <input type="text" class="form-control" id="worker_phone" name="worker_phone" 
                                       value="{{ old('worker_phone', $hraHotWork->worker_phone) }}" readonly>
                                <small class="text-muted">Otomatis terisi berdasarkan nama pekerja</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="supervisor_name" class="form-label">Nama Pendamping</label>
                                <select class="form-select searchable-select" id="supervisor_name" name="supervisor_name" required>
                                    <option value="">Pilih Pendamping...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->name }}" 
                                                {{ old('supervisor_name', $hraHotWork->supervisor_name) == $user->name ? 'selected' : '' }}>
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
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ old('start_date', $hraHotWork->start_datetime ? $hraHotWork->start_datetime->format('Y-m-d') : date('Y-m-d')) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" required onchange="updateEndDate()">
                                <small class="text-muted">Default: hari ini</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="{{ old('start_time', $hraHotWork->start_datetime ? $hraHotWork->start_datetime->format('H:i') : '08:00') }}" required onchange="updateEndTime()">
                                <small class="text-muted">Default: 08:00</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ old('end_date', $hraHotWork->end_datetime ? $hraHotWork->end_datetime->format('Y-m-d') : date('Y-m-d')) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" readonly required>
                                <small class="text-muted">Otomatis sama dengan tanggal mulai</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="{{ old('end_time', $hraHotWork->end_datetime ? $hraHotWork->end_datetime->format('H:i') : '17:00') }}" required>
                                <small class="text-muted">Otomatis +9 jam dari jam mulai</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" placeholder="Jelaskan detail pekerjaan yang akan dilakukan..." required>{{ old('work_description', $hraHotWork->work_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- HRA Hot Work Safety Checklist -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-clipboard-check me-2"></i>HRA Hot Work Assessment
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                    
                        <!-- Section 1: Persyaratan dalam jarak 11m/35ft dari pekerjaan panas -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 1: Persyaratan dalam jarak 11m/35ft dari pekerjaan panas (termasuk di atas dan di bawah area kerja):</h6>
                        
                            <!-- Question 1 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Semua bahan yang mudah terbakar disingkirkan atau dilindungi dengan penutup tahan api</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="flammable_materials_removed" value="1" {{ old('flammable_materials_removed', $hraHotWork->flammable_materials_removed) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="flammable_materials_removed" value="0" {{ old('flammable_materials_removed', $hraHotWork->flammable_materials_removed) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Cairan mudah terbakar, debu, serat, dan endapan minyak dihilangkan (debu di "dalam" dinding/atap/rongga)</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="flammable_liquids_removed" value="1" {{ old('flammable_liquids_removed', $hraHotWork->flammable_liquids_removed) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="flammable_liquids_removed" value="0" {{ old('flammable_liquids_removed', $hraHotWork->flammable_liquids_removed) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Lantai yang mudah terbakar dibasahi, ditutup dengan pasir basah atau penutup tahan api yang tumpang tindih</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="flammable_floors_wetted" value="1" {{ old('flammable_floors_wetted', $hraHotWork->flammable_floors_wetted) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="flammable_floors_wetted" value="0" {{ old('flammable_floors_wetted', $hraHotWork->flammable_floors_wetted) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="flammable_floors_wetted" value="N/A" {{ old('flammable_floors_wetted', $hraHotWork->flammable_floors_wetted) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 4 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Dinding/langit-langit/atap yang mudah terbakar dilindungi dengan penutup tahan api</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="walls_ceiling_protected" value="1" {{ old('walls_ceiling_protected', $hraHotWork->walls_ceiling_protected) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="walls_ceiling_protected" value="0" {{ old('walls_ceiling_protected', $hraHotWork->walls_ceiling_protected) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="walls_ceiling_protected" value="N/A" {{ old('walls_ceiling_protected', $hraHotWork->walls_ceiling_protected) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 5 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Lantai disapu bersih dari bahan yang mudah terbakar</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="floors_swept_clean" value="1" {{ old('floors_swept_clean', $hraHotWork->floors_swept_clean) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="floors_swept_clean" value="0" {{ old('floors_swept_clean', $hraHotWork->floors_swept_clean) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 6 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Material mudah terbakar di sisi lain dinding, langit-langit atau atap disingkirkan (perhatikan insulasinya)</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="materials_other_side_removed" value="1" {{ old('materials_other_side_removed', $hraHotWork->materials_other_side_removed) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="materials_other_side_removed" value="0" {{ old('materials_other_side_removed', $hraHotWork->materials_other_side_removed) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 7 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Atmosfer yang mudah meledak dihilangkan</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="explosive_atmosphere_removed" value="1" {{ old('explosive_atmosphere_removed', $hraHotWork->explosive_atmosphere_removed) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="explosive_atmosphere_removed" value="0" {{ old('explosive_atmosphere_removed', $hraHotWork->explosive_atmosphere_removed) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 8 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Semua bukaan dinding/lantai, termasuk saluran pembuangan, ditutup dengan penutup tahan api</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="wall_floor_openings_covered" value="1" {{ old('wall_floor_openings_covered', $hraHotWork->wall_floor_openings_covered) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="wall_floor_openings_covered" value="0" {{ old('wall_floor_openings_covered', $hraHotWork->wall_floor_openings_covered) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="wall_floor_openings_covered" value="N/A" {{ old('wall_floor_openings_covered', $hraHotWork->wall_floor_openings_covered) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 9 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Saluran, konveyor, katup/saluran pembuangan yang terbuka secara otomatis, dll, terlindungi, terisolasi, atau keduanya</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="ducts_conveyors_protected" value="1" {{ old('ducts_conveyors_protected', $hraHotWork->ducts_conveyors_protected) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="ducts_conveyors_protected" value="0" {{ old('ducts_conveyors_protected', $hraHotWork->ducts_conveyors_protected) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="ducts_conveyors_protected" value="N/A" {{ old('ducts_conveyors_protected', $hraHotWork->ducts_conveyors_protected) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 10 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Jika ada risiko kebakaran dari konduksi/radiasi, misalnya di sepanjang balok, tindakan pencegahan tambahan diterapkan</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="fire_risk_prevention_applied" value="1" {{ old('fire_risk_prevention_applied', $hraHotWork->fire_risk_prevention_applied) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="fire_risk_prevention_applied" value="0" {{ old('fire_risk_prevention_applied', $hraHotWork->fire_risk_prevention_applied) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="fire_risk_prevention_applied" value="N/A" {{ old('fire_risk_prevention_applied', $hraHotWork->fire_risk_prevention_applied) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Persyaratan saat bekerja pada peralatan tertutup -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 2: Persyaratan saat bekerja pada peralatan tertutup:</h6>
                        
                            <!-- Question 11 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Peralatan dibersihkan dari semua bahan yang mudah terbakar</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="equipment_cleaned_flammable" value="1" {{ old('equipment_cleaned_flammable', $hraHotWork->equipment_cleaned_flammable) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="equipment_cleaned_flammable" value="0" {{ old('equipment_cleaned_flammable', $hraHotWork->equipment_cleaned_flammable) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="equipment_cleaned_flammable" value="N/A" {{ old('equipment_cleaned_flammable', $hraHotWork->equipment_cleaned_flammable) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 12 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Wadah dikosongkan, dibersihkan, dan diuji bebas dari cairan dan uap yang mudah terbakar (Lengkapi form-H)</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="containers_emptied_cleaned" value="1" {{ old('containers_emptied_cleaned', $hraHotWork->containers_emptied_cleaned) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="containers_emptied_cleaned" value="0" {{ old('containers_emptied_cleaned', $hraHotWork->containers_emptied_cleaned) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="containers_emptied_cleaned" value="N/A" {{ old('containers_emptied_cleaned', $hraHotWork->containers_emptied_cleaned) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Panel bangunan/material -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 3: Panel bangunan/material:</h6>
                        
                            <!-- Question 13 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Panel bangunan/material yang sedang dikerjakan adalah diketahui tidak mudah terbakar</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="building_materials_non_flammable" value="1" {{ old('building_materials_non_flammable', $hraHotWork->building_materials_non_flammable) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="building_materials_non_flammable" value="0" {{ old('building_materials_non_flammable', $hraHotWork->building_materials_non_flammable) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="building_materials_non_flammable" value="N/A" {{ old('building_materials_non_flammable', $hraHotWork->building_materials_non_flammable) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Question 14 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Jika "tidak" pada pertanyaan di atas, bahan yang mudah terbakar HARUS dipotong hingga minimal 50 cm dan dilindungi oleh bahan pelindung yang tidak mudah terbakar</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="flammable_materials_cut_protected" value="1" {{ old('flammable_materials_cut_protected', $hraHotWork->flammable_materials_cut_protected) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="flammable_materials_cut_protected" value="0" {{ old('flammable_materials_cut_protected', $hraHotWork->flammable_materials_cut_protected) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="flammable_materials_cut_protected" value="N/A" {{ old('flammable_materials_cut_protected', $hraHotWork->flammable_materials_cut_protected) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Ventilasi -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 4: Ventilasi:</h6>
                        
                            <!-- Question 15 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Ventilasi yang cukup di tempat kerja</span>
                                    <div class="checkbox-group">
                                        <span class="small">(</span>
                                        <label><input type="radio" name="ventilation_type" value="alami" {{ old('ventilation_type', $hraHotWork->ventilation_type) == 'alami' ? 'checked' : '' }} onchange="toggleVentilationCheckbox()"> alami</label>
                                        <label><input type="radio" name="ventilation_type" value="buatan" {{ old('ventilation_type', $hraHotWork->ventilation_type) == 'buatan' ? 'checked' : '' }} onchange="toggleVentilationCheckbox()"> buatan</label>
                                        <span class="small">)</span>
                                        <label><input type="radio" id="ventilation_adequate_ya" name="ventilation_adequate" value="1" {{ old('ventilation_adequate', $hraHotWork->ventilation_adequate) == '1' ? 'checked' : '' }} disabled> Ya</label>
                                        <label><input type="radio" id="ventilation_adequate_tidak" name="ventilation_adequate" value="0" {{ old('ventilation_adequate', $hraHotWork->ventilation_adequate) == '0' ? 'checked' : '' }} disabled> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5: Lampu tiup dan tabung gas -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 5: Lampu tiup dan tabung gas:</h6>
                        
                            <!-- Question 16 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Lampu tiup dan tabung gas hanya boleh dipasang atau diganti di area terbuka dan berventilasi baik</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="gas_lamps_open_area" value="1" {{ old('gas_lamps_open_area', $hraHotWork->gas_lamps_open_area) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="gas_lamps_open_area" value="0" {{ old('gas_lamps_open_area', $hraHotWork->gas_lamps_open_area) == '0' ? 'checked' : '' }}> Tidak</label>
                                        <label><input type="radio" name="gas_lamps_open_area" value="N/A" {{ old('gas_lamps_open_area', $hraHotWork->gas_lamps_open_area) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Peralatan dan pengelasan -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 6: Peralatan dan pengelasan:</h6>
                        
                            <!-- Question 17 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Apakah semua peralatan telah dipasang dan pengalasan dimonitor dari pengelasan dalam kondisi baik?</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="equipment_installed_monitored" value="1" {{ old('equipment_installed_monitored', $hraHotWork->equipment_installed_monitored) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="equipment_installed_monitored" value="0" {{ old('equipment_installed_monitored', $hraHotWork->equipment_installed_monitored) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7: Pemberitahuan pekerja -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Section 7: Pemberitahuan pekerja:</h6>
                        
                            <!-- Question 18 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Semua pekerja yang ada di area tersebut diberitahu tentang pekerjaan panas yang sedang dilakukan</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="workers_notified" value="1" {{ old('workers_notified', $hraHotWork->workers_notified) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="workers_notified" value="0" {{ old('workers_notified', $hraHotWork->workers_notified) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Peralatan Pemadam Api Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-fire-extinguisher me-2"></i>Peralatan Pemadam Api
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        
                        <!-- APAR Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">APAR</h6>
                            
                            <!-- APAR Air -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Air</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_air" value="1" {{ old('apar_air', $hraHotWork->apar_air) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_air" value="0" {{ old('apar_air', $hraHotWork->apar_air) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- APAR Powder -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Powder</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_powder" value="1" {{ old('apar_powder', $hraHotWork->apar_powder) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_powder" value="0" {{ old('apar_powder', $hraHotWork->apar_powder) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- APAR CO2 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">CO2</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_co2" value="1" {{ old('apar_co2', $hraHotWork->apar_co2) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_co2" value="0" {{ old('apar_co2', $hraHotWork->apar_co2) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fire Blanket -->
                        <div class="mb-4">
                            <div class="condition-grid">
                                <span class="sub-question fw-bold">Fire blanket</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_blanket" value="1" {{ old('fire_blanket', $hraHotWork->fire_blanket) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_blanket" value="0" {{ old('fire_blanket', $hraHotWork->fire_blanket) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Petugas Fire Watch -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Petugas Fire Watch</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_watch_officer" value="1" {{ old('fire_watch_officer', $hraHotWork->fire_watch_officer) == '1' ? 'checked' : '' }} onchange="toggleFireWatchField(this.checked)"> Ya</label>
                                    <label><input type="radio" name="fire_watch_officer" value="0" {{ old('fire_watch_officer', $hraHotWork->fire_watch_officer) == '0' ? 'checked' : '' }} onchange="toggleFireWatchField(false)"> Tidak</label>
                                </div>
                            </div>
                            <div id="fire_watch_name_field" class="mt-3" style="display: {{ old('fire_watch_officer') == '1' ? 'block' : 'none' }};">
                                <label for="fire_watch_name" class="form-label small text-muted">Nama Petugas (jika Ya):</label>
                                <input type="text" class="form-control" id="fire_watch_name" name="fire_watch_name" value="{{ old('fire_watch_name', $hraHotWork->fire_watch_name) }}" placeholder="Masukkan nama petugas fire watch">
                            </div>
                        </div>

                        <!-- Monitoring Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">Membutuhkan monitoring:</h6>
                            
                            <!-- Sprinkler -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">1. Bangunan terpasang sprinkler</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="monitoring_sprinkler" value="1" {{ old('monitoring_sprinkler', $hraHotWork->monitoring_sprinkler) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="monitoring_sprinkler" value="0" {{ old('monitoring_sprinkler', $hraHotWork->monitoring_sprinkler) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Combustible Material -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <div>
                                        <span class="sub-question">2. Tidak ada combustible material (kayu, plastik, oli, dll) digunakan di atap dinding atau lantai konstruksi, termasuk insulasi.</span>
                                        <br><small class="text-muted">Tidak memberikan penilaian jika TIDAK YAKIN</small>
                                    </div>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="monitoring_combustible" value="1" {{ old('monitoring_combustible', $hraHotWork->monitoring_combustible) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="monitoring_combustible" value="0" {{ old('monitoring_combustible', $hraHotWork->monitoring_combustible) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Material Distance -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">3. Semua combustible material, termasuk cairan flammable, debu combustible, debu mengandung oli, minimal sejauh 11 m dari lokasi kerja.</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="monitoring_distance" value="1" {{ old('monitoring_distance', $hraHotWork->monitoring_distance) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="monitoring_distance" value="0" {{ old('monitoring_distance', $hraHotWork->monitoring_distance) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Inspection Duration -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">4. Berapa lama inspeksi tambahan diperlukan?</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="additional_inspection_duration" value="30min" {{ old('additional_inspection_duration', $hraHotWork->additional_inspection_duration) == '30min' ? 'checked' : '' }}> 30min</label>
                                        <label><input type="radio" name="additional_inspection_duration" value="60min" {{ old('additional_inspection_duration', $hraHotWork->additional_inspection_duration) == '60min' ? 'checked' : '' }}> 60min</label>
                                        <label><input type="radio" name="additional_inspection_duration" value="90min" {{ old('additional_inspection_duration', $hraHotWork->additional_inspection_duration) == '90min' ? 'checked' : '' }}> 90min</label>
                                        <label><input type="radio" name="additional_inspection_duration" value="120min" {{ old('additional_inspection_duration', $hraHotWork->additional_inspection_duration) == '120min' ? 'checked' : '' }}> 120min</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Call Location -->
                        <div class="checklist-item">
                            <div class="row">
                                <div class="col-12">
                                    <label for="emergency_call" class="form-label fw-bold">Breakglass/emergency call terdekat</label>
                                    <input type="text" class="form-control" id="emergency_call" name="emergency_call" value="{{ old('emergency_call', $hraHotWork->emergency_call) }}" placeholder="Lokasi emergency call terdekat">
                                    <small class="text-muted">Sebutkan lokasi emergency call terdekat dari area kerja</small>
                                </div>
                            </div>
                        </div>

                        <!-- Sprinkler Check -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Cek kondisi sistem sprinkler (jika tersedia)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="sprinkler_check" value="1" {{ old('sprinkler_check', $hraHotWork->sprinkler_check) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="sprinkler_check" value="0" {{ old('sprinkler_check', $hraHotWork->sprinkler_check) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Detector Shutdown -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Mematikan peralatan detektor api?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="detector_shutdown" value="1" {{ old('detector_shutdown', $hraHotWork->detector_shutdown) == '1' ? 'checked' : '' }} onchange="toggleDetectorShutdownFields(this.checked)"> Ya</label>
                                    <label><input type="radio" name="detector_shutdown" value="0" {{ old('detector_shutdown', $hraHotWork->detector_shutdown) == '0' ? 'checked' : '' }} onchange="toggleDetectorShutdownFields(false)"> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Conditional Fields - Only show if detector_shutdown = Ya -->
                        <div id="detector_shutdown_fields" style="display: {{ old('detector_shutdown') == '1' ? 'block' : 'none' }};">
                            <!-- Notification Required -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Pemberitahuan ke SHE & Security dibutuhkan?</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="notification_required" value="1" {{ old('notification_required', $hraHotWork->notification_required) == '1' ? 'checked' : '' }} onchange="toggleNotificationFields(this.checked)"> Ya</label>
                                        <label><input type="radio" name="notification_required" value="0" {{ old('notification_required', $hraHotWork->notification_required) == '0' ? 'checked' : '' }} onchange="toggleNotificationFields(false)"> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Contact Details - Only show if notification_required = Ya -->
                            <div id="notification_contact_fields" class="checklist-item" style="display: {{ old('notification_required') == '1' ? 'block' : 'none' }};">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="notification_phone" class="form-label">Telepon (jika diperlukan notifikasi)</label>
                                        <input type="text" class="form-control" id="notification_phone" name="notification_phone" value="{{ old('notification_phone', $hraHotWork->notification_phone) }}" placeholder="Nomor telepon">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="notification_name" class="form-label">Nama (jika diperlukan notifikasi)</label>
                                        <input type="text" class="form-control" id="notification_name" name="notification_name" value="{{ old('notification_name', $hraHotWork->notification_name) }}" placeholder="Nama penanggung jawab">
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance Notification -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Pemberitahuan ke Asuransi dibutuhkan?</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="insurance_notification" value="1" {{ old('insurance_notification', $hraHotWork->insurance_notification) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="insurance_notification" value="0" {{ old('insurance_notification', $hraHotWork->insurance_notification) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Detector Confirmation -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Memastikan detektor sudah mati?</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="detector_confirmed_off" value="1" {{ old('detector_confirmed_off', $hraHotWork->detector_confirmed_off) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="detector_confirmed_off" value="0" {{ old('detector_confirmed_off', $hraHotWork->detector_confirmed_off) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gas Monitoring -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Gas monitoring untuk kemungkinan gas flammable dibutuhkan selama bekerja?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="gas_monitoring_required" value="1" {{ old('gas_monitoring_required', $hraHotWork->gas_monitoring_required) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="gas_monitoring_required" value="0" {{ old('gas_monitoring_required', $hraHotWork->gas_monitoring_required) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-danger btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Update HRA Hot Work
                        </button>
                        <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="btn btn-secondary btn-lg px-5 ms-3">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
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
                            <li><i class="fas fa-check text-success me-2"></i>Ensure proper PPE is available</li>
                            <li><i class="fas fa-check text-success me-2"></i>Check fire safety equipment</li>
                            <li><i class="fas fa-check text-success me-2"></i>Verify hot work area clearance</li>
                            <li><i class="fas fa-check text-success me-2"></i>Emergency contacts are known</li>
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
    
    // Initialize end date on page load (for edit page, time should remain as saved)
    updateEndDate();
});

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

function toggleActionField(fieldId, isChecked) {
    const field = document.getElementById(fieldId);
    if (isChecked) {
        field.style.display = 'block';
    } else {
        field.style.display = 'none';
        // Clear the textarea when hiding
        const textarea = field.querySelector('textarea');
        if (textarea) {
            textarea.value = '';
        }
    }
}

function toggleFireWatchField(isChecked) {
    const field = document.getElementById('fire_watch_name_field');
    const input = field.querySelector('input');
    
    if (isChecked) {
        field.style.display = 'block';
    } else {
        field.style.display = 'none';
        if (input) {
            input.value = '';
        }
    }
}

function toggleDetectorShutdownFields(isChecked) {
    const fieldsContainer = document.getElementById('detector_shutdown_fields');
    
    if (isChecked) {
        fieldsContainer.style.display = 'block';
    } else {
        fieldsContainer.style.display = 'none';
        
        // Clear all fields in this section
        const radios = fieldsContainer.querySelectorAll('input[type="radio"]');
        const textInputs = fieldsContainer.querySelectorAll('input[type="text"]');
        
        radios.forEach(radio => {
            radio.checked = false;
        });
        
        textInputs.forEach(input => {
            input.value = '';
        });
        
        // Hide notification contact fields as well
        const notificationFields = document.getElementById('notification_contact_fields');
        notificationFields.style.display = 'none';
    }
}

function toggleNotificationFields(isChecked) {
    const fieldsContainer = document.getElementById('notification_contact_fields');
    
    if (isChecked) {
        fieldsContainer.style.display = 'block';
    } else {
        fieldsContainer.style.display = 'none';
        
        // Clear the text inputs
        const phoneInput = document.getElementById('notification_phone');
        const nameInput = document.getElementById('notification_name');
        
        if (phoneInput) phoneInput.value = '';
        if (nameInput) nameInput.value = '';
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

function toggleVentilationCheckbox() {
    const radioButtons = document.querySelectorAll('input[name="ventilation_type"]');
    const radioYa = document.getElementById('ventilation_adequate_ya');
    const radioTidak = document.getElementById('ventilation_adequate_tidak');
    let isRadioSelected = false;
    
    // Check if any radio button is selected
    radioButtons.forEach(radio => {
        if (radio.checked) {
            isRadioSelected = true;
        }
    });
    
    if (isRadioSelected) {
        radioYa.disabled = false;
        radioTidak.disabled = false;
        radioYa.parentElement.style.opacity = '1';
        radioTidak.parentElement.style.opacity = '1';
    } else {
        radioYa.disabled = true;
        radioTidak.disabled = true;
        radioYa.checked = false;
        radioTidak.checked = false;
        radioYa.parentElement.style.opacity = '0.5';
        radioTidak.parentElement.style.opacity = '0.5';
    }
    }
}

function initializeConditionalFields() {
    // Initialize Q7 actions field
    const q7RadioYa = document.querySelector('input[name="q7_flammable_structures"][value="1"]');
    if (q7RadioYa && q7RadioYa.checked) {
        toggleActionField('q7_actions_field', true);
    }
    
    // Initialize fire watch name field
    const fireWatchRadioYa = document.querySelector('input[name="fire_watch_officer"][value="1"]');
    if (fireWatchRadioYa && fireWatchRadioYa.checked) {
        toggleFireWatchField(true);
    }
    
    // Initialize detector shutdown fields
    const detectorShutdownRadioYa = document.querySelector('input[name="detector_shutdown"][value="1"]');
    if (detectorShutdownRadioYa && detectorShutdownRadioYa.checked) {
        toggleDetectorShutdownFields(true);
        
        // Check if notification is required and show contact fields
        const notificationRadioYa = document.querySelector('input[name="notification_required"][value="1"]');
        if (notificationRadioYa && notificationRadioYa.checked) {
            toggleNotificationFields(true);
        }
    }
}

// Initialize on page load
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
    
    // Initialize ventilation checkbox state
    toggleVentilationCheckbox();
    
    // Initialize conditional fields based on existing data
    initializeConditionalFields();

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

    // Add form validation for radio buttons
    $('form').on('submit', function(e) {
        const radioGroups = [
            'flammable_materials_removed',
            'flammable_liquids_removed',
            'flammable_floors_wetted',
            'walls_ceiling_protected',
            'floors_swept_clean',
            'materials_other_side_removed',
            'explosive_atmosphere_removed',
            'wall_floor_openings_covered',
            'ducts_conveyors_protected',
            'fire_risk_prevention_applied',
            'equipment_cleaned_flammable',
            'containers_emptied_cleaned',
            'building_materials_non_flammable',
            'flammable_materials_cut_protected',
            'ventilation_adequate',
            'gas_lamps_open_area',
            'equipment_installed_monitored',
            'workers_notified',
            // Form Terlampir radio groups
            'apar_air',
            'apar_powder',
            'apar_co2',
            'fire_blanket',
            'fire_watch_officer',
            'monitoring_sprinkler',
            'monitoring_combustible',
            'monitoring_distance',
            'sprinkler_check',
            'detector_shutdown',
            'notification_required',
            'insurance_notification',
            'detector_confirmed_off',
            'gas_monitoring_required'
        ];

        let isValid = true;
        let missingAnswers = [];

        radioGroups.forEach(groupName => {
            const radios = document.querySelectorAll(`input[name="${groupName}"]`);
            let isChecked = false;
            
            // Check if the field is visible (not in a hidden container)
            let isVisible = true;
            if (radios.length > 0) {
                const container = radios[0].closest('#detector_shutdown_fields, #notification_contact_fields');
                if (container && container.style.display === 'none') {
                    isVisible = false;
                }
            }
            
            // Only validate visible fields
            if (isVisible) {
                radios.forEach(radio => {
                    if (radio.checked) {
                        isChecked = true;
                    }
                });
                
                if (!isChecked) {
                    isValid = false;
                    missingAnswers.push(groupName);
                }
            }
        });

        // Check conditional radio groups
        const ventilationRadios = document.querySelectorAll('input[name="ventilation_type"]');
        let ventilationTypeChecked = false;
        ventilationRadios.forEach(radio => {
            if (radio.checked) ventilationTypeChecked = true;
        });
        
        if (ventilationTypeChecked) {
            const ventilationAdequateRadios = document.querySelectorAll('input[name="ventilation_adequate"]');
            let ventilationAdequateChecked = false;
            ventilationAdequateRadios.forEach(radio => {
                if (radio.checked) ventilationAdequateChecked = true;
            });
            if (!ventilationAdequateChecked) {
                isValid = false;
                missingAnswers.push('ventilation_adequate');
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Mohon jawab semua pertanyaan checklist sebelum melanjutkan.');
            return false;
        }
    });
});
</script>
@endpush
@endsection
