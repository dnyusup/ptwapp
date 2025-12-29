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
                                    <label><input type="radio" name="fire_blanket" value="1" {{ old('fire_blanket') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_blanket" value="0" {{ old('fire_blanket') == '0' ? 'checked' : '' }}> Tidak</label>
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
                <h4 class="mb-1">Create HRA - Hot Works</h4>
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
    <form method="POST" action="{{ route('hra.hot-works.store', $permit) }}">
        @csrf
        
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
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ old('start_date', max(date('Y-m-d'), $permit->start_date->format('Y-m-d'))) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" required onchange="updateEndDate()">
                                <small class="text-muted">Harus dalam rentang: {{ $permit->start_date->format('d M Y') }} - {{ $permit->end_date->format('d M Y') }}</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="{{ old('start_time', '08:00') }}" required onchange="updateEndTime()">
                                <small class="text-muted">Default: 08:00</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ old('end_date', date('Y-m-d')) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" readonly required>
                                <small class="text-muted">Otomatis sama dengan tanggal mulai</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="{{ old('end_time', '17:00') }}" required>
                                <small class="text-muted">Otomatis +9 jam dari jam mulai</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" placeholder="Jelaskan detail pekerjaan yang akan dilakukan..." required>{{ old('work_description') }}</textarea>
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
                                    <label><input type="radio" name="hot_work_avoidable" value="1" {{ old('hot_work_avoidable') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="hot_work_avoidable" value="0" {{ old('hot_work_avoidable') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question: Can Hot Work be done in designated area -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Bisakah Pekerjaan Panas dilakukan di Area Pekerjaan Panas yang telah ditentukan? (Jika "Y", hentikan dan pindahkan lokasi pekerjaan panas)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="hot_work_designated_area" value="1" {{ old('hot_work_designated_area') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="hot_work_designated_area" value="0" {{ old('hot_work_designated_area') == '0' ? 'checked' : '' }}> Tidak</label>
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
                                    <label><input type="radio" name="flammable_materials_removed" value="1" {{ old('flammable_materials_removed') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="flammable_materials_removed" value="0" {{ old('flammable_materials_removed') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Cairan mudah terbakar, debu, serat, dan endapan minyak dihilangkan (debu di "dalam" dinding/atap/rongga)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="flammable_liquids_removed" value="1" {{ old('flammable_liquids_removed') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="flammable_liquids_removed" value="0" {{ old('flammable_liquids_removed') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Lantai yang mudah terbakar dibasahi, ditutup dengan pasir basah atau penutup tahan api yang tumpang tindih</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="flammable_floors_wetted" value="1" {{ old('flammable_floors_wetted') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="flammable_floors_wetted" value="0" {{ old('flammable_floors_wetted') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="flammable_floors_wetted" value="N/A" {{ old('flammable_floors_wetted') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 4 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Dinding/langit-langit/atap yang mudah terbakar dilindungi dengan penutup tahan api</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="walls_ceiling_protected" value="1" {{ old('walls_ceiling_protected') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="walls_ceiling_protected" value="0" {{ old('walls_ceiling_protected') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="walls_ceiling_protected" value="N/A" {{ old('walls_ceiling_protected') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 5 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Lantai disapu bersih dari bahan yang mudah terbakar</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="floors_swept_clean" value="1" {{ old('floors_swept_clean') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="floors_swept_clean" value="0" {{ old('floors_swept_clean') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 6 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Material mudah terbakar di sisi lain dinding, langit-langit atau atap disingkirkan (perhatikan insulasinya)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="materials_other_side_removed" value="1" {{ old('materials_other_side_removed') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="materials_other_side_removed" value="0" {{ old('materials_other_side_removed') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 7 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Atmosfer yang mudah meledak dihilangkan</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="explosive_atmosphere_removed" value="1" {{ old('explosive_atmosphere_removed') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="explosive_atmosphere_removed" value="0" {{ old('explosive_atmosphere_removed') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 8 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Semua bukaan dinding/lantai, termasuk saluran pembuangan, ditutup dengan penutup tahan api</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="wall_floor_openings_covered" value="1" {{ old('wall_floor_openings_covered') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="wall_floor_openings_covered" value="0" {{ old('wall_floor_openings_covered') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="wall_floor_openings_covered" value="N/A" {{ old('wall_floor_openings_covered') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 9 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Saluran, konveyor, katup/saluran pembuangan yang terbuka secara otomatis, dll, terlindungi, terisolasi, atau keduanya</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="ducts_conveyors_protected" value="1" {{ old('ducts_conveyors_protected') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="ducts_conveyors_protected" value="0" {{ old('ducts_conveyors_protected') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="ducts_conveyors_protected" value="N/A" {{ old('ducts_conveyors_protected') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 10 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Jika ada risiko kebakaran dari konduksi/radiasi, misalnya di sepanjang balok, tindakan pencegahan tambahan diterapkan</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_risk_prevention_applied" value="1" {{ old('fire_risk_prevention_applied') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_risk_prevention_applied" value="0" {{ old('fire_risk_prevention_applied') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="fire_risk_prevention_applied" value="N/A" {{ old('fire_risk_prevention_applied') == 'N/A' ? 'checked' : '' }}> N/A</label>
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
                                    <label><input type="radio" name="equipment_cleaned_flammable" value="1" {{ old('equipment_cleaned_flammable') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="equipment_cleaned_flammable" value="0" {{ old('equipment_cleaned_flammable') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="equipment_cleaned_flammable" value="N/A" {{ old('equipment_cleaned_flammable') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 12 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Wadah dikosongkan, dibersihkan, dan diuji bebas dari cairan dan uap yang mudah terbakar (Lengkapi form-H)</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="containers_emptied_cleaned" value="1" {{ old('containers_emptied_cleaned') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="containers_emptied_cleaned" value="0" {{ old('containers_emptied_cleaned') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="containers_emptied_cleaned" value="N/A" {{ old('containers_emptied_cleaned') == 'N/A' ? 'checked' : '' }}> N/A</label>
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
                                    <label><input type="radio" name="building_materials_non_flammable" value="1" {{ old('building_materials_non_flammable') == '1' ? 'checked' : '' }} onchange="toggleFollowUpQuestion(this.name, 'flammable_materials_cut_field', '0')"> Ya</label>
                                    <label><input type="radio" name="building_materials_non_flammable" value="0" {{ old('building_materials_non_flammable') == '0' ? 'checked' : '' }} onchange="toggleFollowUpQuestion(this.name, 'flammable_materials_cut_field', '0')"> Tidak</label>
                                    <label><input type="radio" name="building_materials_non_flammable" value="N/A" {{ old('building_materials_non_flammable') == 'N/A' ? 'checked' : '' }} onchange="toggleFollowUpQuestion(this.name, 'flammable_materials_cut_field', '0')"> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 14 -->
                        <div class="checklist-item" id="flammable_materials_cut_field" style="display: {{ old('building_materials_non_flammable') == '0' ? 'block' : 'none' }};">
                            <div class="condition-grid">
                                <span class="parent-question">Jika "tidak" pada pertanyaan di atas, bahan yang mudah terbakar HARUS dipotong hingga minimal 50 cm dan dilindungi oleh bahan pelindung yang tidak mudah terbakar</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="flammable_materials_cut_protected" value="1" {{ old('flammable_materials_cut_protected') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="flammable_materials_cut_protected" value="0" {{ old('flammable_materials_cut_protected') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="flammable_materials_cut_protected" value="N/A" {{ old('flammable_materials_cut_protected') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 15 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Ventilasi yang cukup di tempat kerja (alami atau buatan)</span>
                                <div class="checkbox-group">
                                    <select class="form-select me-3" name="ventilation_type" id="ventilation_type" style="width: 180px; min-width: 180px;" onchange="updateVentilationCheckbox()">
                                        <option value="">Pilih...</option>
                                        <option value="alami" {{ old('ventilation_type') == 'alami' ? 'selected' : '' }}>Alami</option>
                                        <option value="buatan" {{ old('ventilation_type') == 'buatan' ? 'selected' : '' }}>Buatan</option>
                                    </select>
                                    <label><input type="radio" name="ventilation_adequate" id="ventilation_adequate_ya" value="1" {{ old('ventilation_adequate') == '1' ? 'checked' : '' }} {{ old('ventilation_type') ? '' : 'disabled' }}> Ya</label>
                                    <label><input type="radio" name="ventilation_adequate" id="ventilation_adequate_tidak" value="0" {{ old('ventilation_adequate') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 16 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Lampu tiup dan tabung gas hanya boleh dipasang atau diganti di area terbuka dan berventilasi baik</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="gas_lamps_open_area" value="1" {{ old('gas_lamps_open_area') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="gas_lamps_open_area" value="0" {{ old('gas_lamps_open_area') == '0' ? 'checked' : '' }}> Tidak</label>
                                    <label><input type="radio" name="gas_lamps_open_area" value="N/A" {{ old('gas_lamps_open_area') == 'N/A' ? 'checked' : '' }}> N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 17 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Apakah semua peralatan telah dipasang dan pengalasan dimonitor dari pengelasan dalam kondisi baik?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="equipment_installed_monitored" value="1" {{ old('equipment_installed_monitored') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="equipment_installed_monitored" value="0" {{ old('equipment_installed_monitored') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 18 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="fw-bold text-primary">Semua pekerja yang ada di area tersebut diberitahu tentang pekerjaan panas yang sedang dilakukan</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="workers_notified" value="1" {{ old('workers_notified') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="workers_notified" value="0" {{ old('workers_notified') == '0' ? 'checked' : '' }}> Tidak</label>
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
                                        <label><input type="radio" name="apar_air" value="1" {{ old('apar_air') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_air" value="0" {{ old('apar_air') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- APAR Powder -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Powder</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_powder" value="1" {{ old('apar_powder') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_powder" value="0" {{ old('apar_powder') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- APAR CO2 -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">CO2</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_co2" value="1" {{ old('apar_co2') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_co2" value="0" {{ old('apar_co2') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- APAR Foam -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Foam</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="apar_foam" value="1" {{ old('apar_foam') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="apar_foam" value="0" {{ old('apar_foam') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fire Blanket -->
                        <div class="mb-4">
                            <div class="condition-grid">
                                <span class="sub-question">Fire blanket</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_blanket" value="1" {{ old('fire_blanket') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="fire_blanket" value="0" {{ old('fire_blanket') == '0' ? 'checked' : '' }}> Tidak</label>
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
                                    <input type="text" class="form-control" id="emergency_call_point" name="emergency_call_point" value="{{ old('emergency_call_point') }}" placeholder="Sebutkan lokasi titik panggilan darurat/alarm terdekat">
                                </div>
                            </div>
                        </div>

                        <!-- Sistem penyiram otomatis -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah sistem penyiram otomatis (jika ada) tidak berfungsi?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="sprinkler_system_disabled" value="1" {{ old('sprinkler_system_disabled') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="sprinkler_system_disabled" value="0" {{ old('sprinkler_system_disabled') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Sistem deteksi kebakaran -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah sistem deteksi kebakaran (jika berlaku) harus diisolasi >10 jam?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="fire_detection_isolated" value="1" {{ old('fire_detection_isolated') == '1' ? 'checked' : '' }} onchange="toggleInsurersFields(this.checked)"> Ya</label>
                                    <label><input type="radio" name="fire_detection_isolated" value="0" {{ old('fire_detection_isolated') == '0' ? 'checked' : '' }} onchange="toggleInsurersFields(false)"> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Insurers notification section -->
                        <div id="insurers_section" class="mb-4" style="display: {{ old('fire_detection_isolated') == '1' ? 'block' : 'none' }};">
                            <!-- Insurers notified of isolation -->
                            <div class="checklist-item">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label class="form-label fw-bold">Insurers notified of isolation</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="isolation_notified_by" class="form-label small text-muted">Oleh:</label>
                                        <input type="text" class="form-control" id="isolation_notified_by" name="isolation_notified_by" value="{{ old('isolation_notified_by') }}" placeholder="Nama yang memberitahu">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="isolation_notified_when" class="form-label small text-muted">Kapan:</label>
                                        <input type="text" class="form-control" id="isolation_notified_when" name="isolation_notified_when" value="{{ old('isolation_notified_when') }}" placeholder="Tanggal/waktu">
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
                                        <input type="text" class="form-control" id="reinstatement_notified_by" name="reinstatement_notified_by" value="{{ old('reinstatement_notified_by') }}" placeholder="Nama yang memberitahu">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reinstatement_notified_when" class="form-label small text-muted">Kapan:</label>
                                        <input type="text" class="form-control" id="reinstatement_notified_when" name="reinstatement_notified_when" value="{{ old('reinstatement_notified_when') }}" placeholder="Tanggal/waktu">
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
                                    <label><input type="radio" name="fire_watch_officer" value="1" {{ old('fire_watch_officer') == '1' ? 'checked' : '' }} onchange="toggleFireWatchField(this.checked)"> Ya</label>
                                    <label><input type="radio" name="fire_watch_officer" value="0" {{ old('fire_watch_officer') == '0' ? 'checked' : '' }} onchange="toggleFireWatchField(false)"> Tidak</label>
                                </div>
                            </div>
                            <div id="fire_watch_name_field" class="mt-3" style="display: {{ old('fire_watch_officer') == '1' ? 'block' : 'none' }};">
                                <label for="fire_watch_name" class="form-label small text-muted">Nama Petugas (jika Ya):</label>
                                <input type="text" class="form-control" id="fire_watch_name" name="fire_watch_name" value="{{ old('fire_watch_name') }}" placeholder="Masukkan nama petugas fire watch">
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
                                        <label><input type="radio" name="monitoring_sprinkler" value="1" {{ old('monitoring_sprinkler') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="monitoring_sprinkler" value="0" {{ old('monitoring_sprinkler') == '0' ? 'checked' : '' }}> Tidak</label>
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
                                        <label><input type="radio" name="monitoring_combustible" value="1" {{ old('monitoring_combustible') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="monitoring_combustible" value="0" {{ old('monitoring_combustible') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Material Distance -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">3. Semua bahan yang mudah terbakar/debu, serat atau endapan berminyak, berada setidaknya 11m dari area kerja.</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="monitoring_distance" value="1" {{ old('monitoring_distance') == '1' ? 'checked' : '' }}> Ya</label>
                                        <label><input type="radio" name="monitoring_distance" value="0" {{ old('monitoring_distance') == '0' ? 'checked' : '' }}> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Inspection Duration -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">4. Berapa lama inspeksi tambahan diperlukan?</span>
                                    <div class="checkbox-group">
                                        <label><input type="radio" name="additional_inspection_duration" value="30min" {{ old('additional_inspection_duration') == '30min' ? 'checked' : '' }}> 30min</label>
                                        <label><input type="radio" name="additional_inspection_duration" value="60min" {{ old('additional_inspection_duration') == '60min' ? 'checked' : '' }}> 60min</label>
                                        <label><input type="radio" name="additional_inspection_duration" value="90min" {{ old('additional_inspection_duration') == '90min' ? 'checked' : '' }}> 90min</label>
                                        <label><input type="radio" name="additional_inspection_duration" value="120min" {{ old('additional_inspection_duration') == '120min' ? 'checked' : '' }}> 120min</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-danger btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Create HRA Hot Work
                        </button>
                        <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary btn-lg px-5 ms-3">
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
    
    // Initialize end date and time on page load
    updateEndDate();
    updateEndTime();
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

function toggleInsurersFields(isChecked) {
    const section = document.getElementById('insurers_section');
    const inputs = section.querySelectorAll('input[type="text"]');
    
    if (isChecked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
        // Clear all text inputs in the section
        inputs.forEach(input => {
            input.value = '';
        });
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

function toggleFollowUpQuestion(fieldName, followUpFieldId, triggerValue) {
    const selectedRadio = document.querySelector(`input[name="${fieldName}"]:checked`);
    const followUpField = document.getElementById(followUpFieldId);
    
    if (selectedRadio && selectedRadio.value === triggerValue) {
        followUpField.style.display = 'block';
    } else {
        followUpField.style.display = 'none';
        // Clear the radio buttons in follow-up question when hiding
        const followUpRadios = followUpField.querySelectorAll('input[type="radio"]');
        followUpRadios.forEach(radio => {
            radio.checked = false;
        });
    }
}

function updateVentilationCheckbox() {
    const ventilationSelect = document.getElementById('ventilation_type');
    const radioYa = document.getElementById('ventilation_adequate_ya');
    const radioTidak = document.getElementById('ventilation_adequate_tidak');
    
    if (ventilationSelect && ventilationSelect.value) {
        radioYa.disabled = false;
        radioYa.parentElement.style.opacity = '1';
    } else {
        radioYa.disabled = true;
        radioYa.checked = false;
        radioYa.parentElement.style.opacity = '0.5';
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
    updateVentilationCheckbox();

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
            // New comprehensive assessment fields
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
            
            // Fire equipment and monitoring
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
