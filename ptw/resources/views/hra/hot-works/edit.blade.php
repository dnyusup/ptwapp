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
                    Main Permit: <strong>{{ $permit->permit_number }}</strong> - {{ $permit->work_title }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hra.hot-works.show', ['permit' => $permit, 'hraHotWork' => $hraHotWork]) }}" class="btn btn-secondary">
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
    <form method="POST" action="{{ route('hra.hot-works.update', ['permit' => $permit, 'hraHotWork' => $hraHotWork]) }}">
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
                                       value="{{ old('work_location', $hraHotWork->work_location) }}" readonly>
                                <small class="text-muted">Otomatis dari data permit</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ old('start_date', $hraHotWork->start_date ?? '') }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" required onchange="updateEndDate()">
                                <small class="text-muted">Harus dalam rentang: {{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="{{ old('start_time', $hraHotWork->start_time ?? '') }}" required onchange="updateEndTime()">
                                <small class="text-muted">Default: 08:00</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ old('end_date', $hraHotWork->end_date ?? '') }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" readonly required>
                                <small class="text-muted">Otomatis sama dengan tanggal mulai</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="{{ old('end_time', $hraHotWork->end_time ?? '') }}" required>
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
                
                <!-- Pre-Assessment Questions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        <!-- Question: Can Hot Work be avoided -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah Pekerjaan Panas dapat dihindari? (Jika "Y", berhenti, tinjau metode untuk menghilangkan bahaya, misalnya pekerjaan panas)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="hot_work_avoidable" value="1" {{ old('hot_work_avoidable', $hraHotWork->hot_work_avoidable) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="hot_work_avoidable" value="0" {{ old('hot_work_avoidable', $hraHotWork->hot_work_avoidable) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question: Can Hot Work be done in designated area -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Bisakah Pekerjaan Panas dilakukan di Area Pekerjaan Panas yang telah ditentukan? (Jika "Y", hentikan dan pindahkan lokasi pekerjaan panas)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="hot_work_designated_area" value="1" {{ old('hot_work_designated_area', $hraHotWork->hot_work_designated_area) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="hot_work_designated_area" value="0" {{ old('hot_work_designated_area', $hraHotWork->hot_work_designated_area) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
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
                        <h6 class="fw-bold text-primary mb-3">Persyaratan dalam jarak 11m/35ft dari pekerjaan panas (termasuk di atas dan di bawah area kerja):</h6>

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

                        <!-- Question 7 - Atmosfer yang mudah meledak -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Atmosfer yang mudah meledak dihilangkan</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="explosive_atmosphere_removed" value="1" {{ old('explosive_atmosphere_removed', $hraHotWork->explosive_atmosphere_removed) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="explosive_atmosphere_removed" value="0" {{ old('explosive_atmosphere_removed', $hraHotWork->explosive_atmosphere_removed) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 7 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Drainase air hujan, parit, dan sistem drainase lainnya disegel jika berada dalam radius 11m/35ft</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="drainage_sealed" value="1" {{ old('drainage_sealed', $hraHotWork->drainage_sealed) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="drainage_sealed" value="0" {{ old('drainage_sealed', $hraHotWork->drainage_sealed) == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="drainage_sealed" value="N/A" {{ old('drainage_sealed', $hraHotWork->drainage_sealed) == 'N/A' ? 'checked' : '' }}> N/A</label>
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

                        <!-- Section 2: Persyaratan saat bekerja pada peralatan tertutup -->
                        <h6 class="fw-bold text-primary mb-3 mt-4">Persyaratan saat bekerja pada peralatan tertutup:</h6>

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

                        <!-- Section 3: Panel bangunan/material -->
                        <h6 class="fw-bold text-primary mb-3 mt-4">Panel bangunan/material:</h6>

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

                        <!-- Question 15 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Ventilasi yang cukup di tempat kerja (alami atau buatan)</span>
                                <div class="checkbox-group">
                                    <select class="form-select me-3" name="ventilation_type" id="ventilation_type" style="width: 180px; min-width: 180px;">
                                        <option value="">Pilih...</option>
                                        <option value="alami" {{ old('ventilation_type', $hraHotWork->ventilation_type) == 'alami' ? 'selected' : '' }}>Alami</option>
                                        <option value="buatan" {{ old('ventilation_type', $hraHotWork->ventilation_type) == 'buatan' ? 'selected' : '' }}>Buatan</option>
                                    </select>
                                    <label><input type="radio" name="ventilation_adequate" value="1" {{ old('ventilation_adequate', $hraHotWork->ventilation_adequate) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="ventilation_adequate" value="0" {{ old('ventilation_adequate', $hraHotWork->ventilation_adequate) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 16 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Lampu tiup dan tabung gas hanya boleh dipasang atau diganti di area terbuka dan berventilasi baik</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="gas_lamps_open_area" value="1" {{ old('gas_lamps_open_area', $hraHotWork->gas_lamps_open_area) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="gas_lamps_open_area" value="0" {{ old('gas_lamps_open_area', $hraHotWork->gas_lamps_open_area) == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="gas_lamps_open_area" value="N/A" {{ old('gas_lamps_open_area', $hraHotWork->gas_lamps_open_area) == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 17 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Apakah semua peralatan telah dipasang dan pengalasan dimonitor dari pengelasan dalam kondisi baik?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="equipment_installed_monitored" value="1" {{ old('equipment_installed_monitored', $hraHotWork->equipment_installed_monitored) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="equipment_installed_monitored" value="0" {{ old('equipment_installed_monitored', $hraHotWork->equipment_installed_monitored) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 18 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Semua pekerja yang ada di area tersebut diberitahu tentang pekerjaan panas yang sedang dilakukan</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="workers_notified" value="1" {{ old('workers_notified', $hraHotWork->workers_notified) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="workers_notified" value="0" {{ old('workers_notified', $hraHotWork->workers_notified) == '0' ? 'checked' : '' }}> Tidak</label>
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
                            <h6 class="fw-bold text-dark mb-3">APAR setidaknya tersedia 2 di area pekerjaan panas</h6>
                            
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

                            <!-- APAR Foam -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Foam</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_foam" value="1" {{ old('apar_foam', $hraHotWork->apar_foam) == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_foam" value="0" {{ old('apar_foam', $hraHotWork->apar_foam) == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fire Blanket -->
                        <div class="mb-4">
                            <div class="condition-grid">
                                <span class="sub-question">Fire blanket</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_blanket" value="1" {{ old('fire_blanket', $hraHotWork->fire_blanket) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_blanket" value="0" {{ old('fire_blanket', $hraHotWork->fire_blanket) == '0' ? 'checked' : '' }}> Tidak</label>
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

                        <!-- Titik panggilan darurat -->
                        <div class="checklist-item">
                            <div class="row">
                                <div class="col-12">
                                    <label for="emergency_call_point" class="form-label fw-bold">Titik panggilan darurat/alarm kebakaran terdekat</label>
                                    <input type="text" class="form-control" id="emergency_call_point" name="emergency_call_point" value="{{ old('emergency_call_point', $hraHotWork->emergency_call_point) }}" placeholder="Sebutkan lokasi titik panggilan darurat/alarm terdekat">
                                </div>
                            </div>
                        </div>

                        <!-- Sistem penyiram otomatis -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah sistem penyiram otomatis (jika ada) tidak berfungsi?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="sprinkler_system_disabled" value="1" {{ old('sprinkler_system_disabled', $hraHotWork->sprinkler_system_disabled) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="sprinkler_system_disabled" value="0" {{ old('sprinkler_system_disabled', $hraHotWork->sprinkler_system_disabled) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Sistem deteksi kebakaran -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah sistem deteksi kebakaran (jika berlaku) harus diisolasi >10 jam?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_detection_isolated" value="1" {{ old('fire_detection_isolated', $hraHotWork->fire_detection_isolated) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_detection_isolated" value="0" {{ old('fire_detection_isolated', $hraHotWork->fire_detection_isolated) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Insurers notification section -->
                        <div class="mb-4">
                            <!-- Insurers notified of isolation -->
                            <div class="checklist-item">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label class="form-label fw-bold">Insurers notified of isolation</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="isolation_notified_by" class="form-label small text-muted">Oleh:</label>
                                        <input type="text" class="form-control" id="isolation_notified_by" name="isolation_notified_by" value="{{ old('isolation_notified_by', $hraHotWork->isolation_notified_by) }}" placeholder="Nama yang memberitahu">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="isolation_notified_when" class="form-label small text-muted">Kapan:</label>
                                        <input type="text" class="form-control" id="isolation_notified_when" name="isolation_notified_when" value="{{ old('isolation_notified_when', $hraHotWork->isolation_notified_when) }}" placeholder="Tanggal/waktu">
                                    </div>
                                </div>
                            </div>

                            <!-- Insurers notified of reinstatement -->
                            <div class="checklist-item">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label class="form-label fw-bold">Insurers notified of reinstatement</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reinstatement_notified_by" class="form-label small text-muted">Oleh:</label>
                                        <input type="text" class="form-control" id="reinstatement_notified_by" name="reinstatement_notified_by" value="{{ old('reinstatement_notified_by', $hraHotWork->reinstatement_notified_by) }}" placeholder="Nama yang memberitahu">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reinstatement_notified_when" class="form-label small text-muted">Kapan:</label>
                                        <input type="text" class="form-control" id="reinstatement_notified_when" name="reinstatement_notified_when" value="{{ old('reinstatement_notified_when', $hraHotWork->reinstatement_notified_when) }}" placeholder="Tanggal/waktu">
                                    </div>
                                </div>
                            </div>
                        </div>

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

                        <!-- Petugas Fire Watch -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Petugas Fire Watch (harus dilatih dalam bahaya, risiko, dan pengendalian kebakaran. Pelatihan terverifikasi?)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_watch_officer" value="1" {{ old('fire_watch_officer', $hraHotWork->fire_watch_officer) == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_watch_officer" value="0" {{ old('fire_watch_officer', $hraHotWork->fire_watch_officer) == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                            <div class="mt-3">
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
                                    <span class="sub-question">1. Bangunan dilindungi oleh alat penyiram otomatis.</span>
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
                                        <span class="sub-question">2. Tidak ada bahan yang mudah terbakar yang digunakan pada konstruksi atap/langit-langit, dinding atau lantai.</span>
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
                                    <span class="sub-question">3. Semua bahan yang mudah terbakar/debu, serat atau endapan berminyak, berada setidaknya 11m dari area kerja.</span>
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

                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg me-3" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; padding: 12px 35px;">
                            <i class="fas fa-save me-2"></i>Update HRA
                        </button>
                        <a href="{{ route('hra.hot-works.index', $permit) }}" class="btn btn-secondary btn-lg" style="padding: 12px 35px;">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Permit Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Permit Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-12"><strong>Permit Number:</strong><br>{{ $permit->permit_number }}</div>
                            <div class="col-12"><strong>Work Title:</strong><br>{{ $permit->work_title }}</div>
                            <div class="col-12"><strong>Company:</strong><br>{{ $permit->receiver_company_name }}</div>
                            <div class="col-12"><strong>Period:</strong><br>{{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</div>
                            <div class="col-12"><strong>Location:</strong><br>{{ $permit->work_location }}</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success btn-lg w-100 mb-2" style="border-radius: 8px;">
                            <i class="fas fa-save me-2"></i>Update HRA Hot Works
                        </button>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Pastikan semua pertanyaan telah dijawab dengan benar
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Scripts -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.searchable-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select an option...',
        allowClear: true,
        width: '100%'
    });

    // Auto-update worker phone based on selection
    window.updateWorkerPhone = function() {
        const workerSelect = document.getElementById('worker_name');
        const phoneInput = document.getElementById('worker_phone');
        
        if (workerSelect.value) {
            const selectedOption = workerSelect.options[workerSelect.selectedIndex];
            phoneInput.value = selectedOption.dataset.phone || '';
        } else {
            phoneInput.value = '';
        }
    };

    // Auto-update end date when start date changes
    window.updateEndDate = function() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if (startDate.value) {
            endDate.value = startDate.value;
        }
    };

    // Auto-update end time when start time changes
    window.updateEndTime = function() {
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        
        if (startTime.value) {
            const [hours, minutes] = startTime.value.split(':');
            const startDateTime = new Date();
            startDateTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);
            
            // Add 9 hours
            startDateTime.setHours(startDateTime.getHours() + 9);
            
            const endHours = String(startDateTime.getHours()).padStart(2, '0');
            const endMinutes = String(startDateTime.getMinutes()).padStart(2, '0');
            endTime.value = `${endHours}:${endMinutes}`;
        }
    };

    // Form validation before submit
    $('form').on('submit', function(e) {
        const requiredRadios = [
            'hot_work_avoidable',
            'hot_work_designated_area',
            'flammable_materials_removed',
            'flammable_liquids_removed',
            'flammable_floors_wetted',
            'walls_ceiling_protected',
            'floors_swept_clean',
            'materials_other_side_removed',
            'drainage_sealed',
            'openings_sealed',
            'conveyor_belts_stopped',
            'fire_alarm_disabled',
            'fire_watch_assigned',
            'fire_extinguisher_available',
            'water_hose_available',
            'fire_blanket',
            'welding_machine_grounded',
            'gas_cylinders_secured',
            'hoses_cables_good_condition',
            'hot_work_equipment_inspected',
            'torch_shutoff_valves_working',
            'weather_conditions_suitable',
            'area_cleared_personnel'
        ];

        let isValid = true;
        let missingAnswers = [];

        // Check required radio buttons
        requiredRadios.forEach(function(name) {
            const radios = document.querySelectorAll(`input[name="${name}"]`);
            let checked = false;
            radios.forEach(function(radio) {
                if (radio.checked) checked = true;
            });
            if (!checked) {
                isValid = false;
                missingAnswers.push(name);
            }
        });

        // Check conditional fields
        const distanceInput = document.getElementById('q3_distance');
        if (distanceInput.value && parseFloat(distanceInput.value) >= 12) {
            const q3Radios = document.querySelectorAll('input[name="q3_flammable_moved"]');
            let q3Checked = false;
            q3Radios.forEach(radio => {
                if (radio.checked) q3Checked = true;
            });
            if (!q3Checked) {
                isValid = false;
                missingAnswers.push('q3_flammable_moved');
            }
        }

        const ventilationRadios = document.querySelectorAll('input[name="q12_ventilation_type"]');
        let ventilationTypeChecked = false;
        ventilationRadios.forEach(radio => {
            if (radio.checked) ventilationTypeChecked = true;
        });
        
        if (ventilationTypeChecked) {
            const q12Radios = document.querySelectorAll('input[name="q12_ventilation_adequate"]');
            let q12Checked = false;
            q12Radios.forEach(radio => {
                if (radio.checked) q12Checked = true;
            });
            if (!q12Checked) {
                isValid = false;
                missingAnswers.push('q12_ventilation_adequate');
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