@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
/* Custom styling for checklist items */
.checklist-item {
    padding: 15px 0;
    border-bottom: 1px                    </div>
                </div>

                <!-- Additional Work Conditions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #e63946 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-exclamation-triangle me-2"></i>Kondisi Pekerjaan
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        
                            <!-- Area di bawah pekerjaan berlangsung ditutup -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Area di bawah pekerjaan berlangsung ditutup dari lalu lintas/pejalan kaki</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="area_below_closed" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Gangguan pada atau sekitar lokasi pekerjaan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Gangguan pada atau sekitar lokasi pekerjaan (cable ducts/tray, single cables, pipa, dll)</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="work_area_disturbances" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Ventilasi, cerobong, bukaan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Ventilasi, cerobong, bukaan yang mengeluarkan udara/air yang panas/berbau/berbahaya</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="ventilation_hazards" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>

                        <!-- Bagian dari mesin/peralatan harus dilindungi -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Bagian dari mesin/peralatan harus dilindungi</span>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="equipment_protection" value="1"> Yes</label>
                                </div>
                            </div>
                        </div>

                        <!-- Terdapat titik untuk keluar dalam kondisi darurat -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Terdapat titik untuk keluar dalam kondisi darurat</span>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="emergency_exit_available" value="1"> Yes</label>
                                </div>
                            </div>
                        </div>

                        <!-- Material/alat yang perlu dinaik/turunkan -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Material/alat yang perlu dinaik/turunkan</span>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="material_handling" value="1"> Yes</label>
                                </div>
                            </div>
                        </div>

                        <!-- Personnel Safety atau Petugas lain yang diperlukan -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Personnel Safety atau Petugas lain yang diperlukan</span>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="safety_personnel_needed" value="1"> Yes</label>
                                </div>
                            </div>
                        </div>

                        <!-- Lainnya -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Lainnya</span>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="other_conditions_check" value="1" onchange="toggleOtherInput(this.checked)"> Yes</label>
                                </div>
                            </div>
                            <div id="other_conditions_input" class="mt-3" style="display: none;">
                                <textarea class="form-control" name="other_conditions_text" rows="3" 
                                          placeholder="Jelaskan kondisi lain yang perlu diperhatikan..."></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">" #e9ecef;
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
                            <div class="col-md-6 mb-3">
                                <label for="start_datetime" class="form-label">Tanggal & Jam Mulai</label>
                                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                       value="{{ old('start_datetime') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_datetime" class="form-label">Tanggal & Jam Selesai</label>
                                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                       value="{{ old('end_datetime') }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" placeholder="Jelaskan detail pekerjaan yang akan dilakukan..." required>{{ old('work_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- HRA Work at Heights Assessment Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; border: none;">
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
                                <label><input type="radio" name="fixed_scaffolding_checked" value="1" onchange="toggleSubQuestions('fixed_scaffolding_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="fixed_scaffolding_checked" value="0" onchange="toggleSubQuestions('fixed_scaffolding_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="fixed_scaffolding_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="1"> Ya</label>
                                        <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="0"> Tidak</label>
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
                                <label><input type="radio" name="mobile_scaffolding_checked" value="1" onchange="toggleSubQuestions('mobile_scaffolding_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="mobile_scaffolding_checked" value="0" onchange="toggleSubQuestions('mobile_scaffolding_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="mobile_scaffolding_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="1"> Ya</label>
                                        <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="0"> Tidak</label>
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
                                <label><input type="radio" name="mobile_elevation_checked" value="1" onchange="toggleSubQuestions('mobile_elevation_sub', this.value == '1')"> Ya</label>
                                <label><input type="radio" name="mobile_elevation_checked" value="0" onchange="toggleSubQuestions('mobile_elevation_sub', this.value == '1')"> Tidak</label>
                            </div>
                        </div>
                        
                        <div id="mobile_elevation_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Operator terlatih?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_training_provided" value="1"> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_training_provided" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Penggunaannya tertulis?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_used_before" value="1"> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_used_before" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Menggunakan Alat Pelindung Jatuh?</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="mobile_elevation_location_marked" value="1"> Ya</label>
                                        <label><input type="radio" name="mobile_elevation_location_marked" value="0"> Tidak</label>
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
                            <i class="fas fa-exclamation-triangle me-2"></i>Work Conditions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="checklist-container">
                            
                            <!-- Area di bawah pekerjaan berlangsung ditutup -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Area di bawah pekerjaan berlangsung ditutup dari lalu lintas/pejalan kaki</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="area_below_closed" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Gangguan pada atau sekitar lokasi pekerjaan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Gangguan pada atau sekitar lokasi pekerjaan (cable ducts/tray, single cables, pipa, dll)</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="work_area_disturbances" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Ventilasi, cerobong, bukaan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Ventilasi, cerobong, bukaan yang mengeluarkan udara/air yang panas/berbau/berbahaya</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="ventilation_hazards" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian dari mesin/peralatan harus dilindungi -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Bagian dari mesin/peralatan harus dilindungi</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="equipment_protection" value="1"> Ya</label>
                                        <label><input type="radio" name="equipment_protection" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Terdapat titik untuk keluar dalam kondisi darurat -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Terdapat titik untuk keluar dalam kondisi darurat</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="emergency_exit_available" value="1"> Ya</label>
                                        <label><input type="radio" name="emergency_exit_available" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Material/alat yang perlu diamankan -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Material/alat yang perlu diamankan/diturunkan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="material_handling" value="1"> Ya</label>
                                        <label><input type="radio" name="material_handling" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Personnel Safety atau Petugas lain -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Personnel Safety atau Petugas lain yang diperlukan</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="safety_personnel_needed" value="1"> Ya</label>
                                        <label><input type="radio" name="safety_personnel_needed" value="0"> Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Lainnya/Others -->
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="parent-question">Lainnya (Others)</span>
                                    <div class="radio-group">
                                        <label><input type="radio" name="other_conditions_check" value="1" onchange="toggleOtherInput(this.value == '1')"> Ya</label>
                                        <label><input type="radio" name="other_conditions_check" value="0" onchange="toggleOtherInput(this.value == '1')"> Tidak</label>
                                    </div>
                                </div>
                                
                                <div id="other_conditions_input" style="display: none; margin-top: 10px;">
                                    <textarea name="other_conditions_text" class="form-control" rows="3" placeholder="Jelaskan kondisi kerja lainnya..."></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Environmental Conditions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-cloud-rain me-2"></i>Environmental Conditions
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
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="terang"> terang</label>
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="remang-remang"> remang-remang</label>
                                            <label class="me-2"><input type="radio" name="visibility_condition" value="gelap"> gelap</label>
                                            <label><input type="radio" name="visibility_condition" value="berkabut"> berkabut</label>
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
                                            <label class="me-2"><input type="radio" name="rain_condition" value="tidak"> tidak</label>
                                            <label class="me-2"><input type="radio" name="rain_condition" value="rintik"> rintik</label>
                                            <label class="me-2"><input type="radio" name="rain_condition" value="gerimis"> gerimis</label>
                                            <label><input type="radio" name="rain_condition" value="deras"> deras</label>
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
                                            <label class="me-2"><input type="radio" name="surface_condition" value="kering"> kering</label>
                                            <label class="me-2"><input type="radio" name="surface_condition" value="basah"> basah</label>
                                            <label><input type="radio" name="surface_condition" value="licin"> licin</label>
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
                                            <label class="me-2"><input type="radio" name="wind_condition" value="tidak"> tidak</label>
                                            <label class="me-2"><input type="radio" name="wind_condition" value="kecil"> kecil</label>
                                            <label class="me-2"><input type="radio" name="wind_condition" value="sedang"> sedang</label>
                                            <label><input type="radio" name="wind_condition" value="kuat"> kuat</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permukaan licin dari tumpahan oli atau bahan kimia -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Permukaan licin dari tumpahan oli atau bahan kimia?</span>
                                        <div class="checkbox-group d-flex flex-wrap gap-2 gap-md-3">
                                            <label class="me-2"><input type="radio" name="chemical_spill_condition" value="Ya"> Ya</label>
                                            <label><input type="radio" name="chemical_spill_condition" value="Tidak"> Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lainnya -->
                            <div class="col-12">
                                <div class="checklist-item">
                                    <div class="condition-grid">
                                        <span class="parent-question">Lainnya</span>
                                        <div class="radio-group">
                                            <label><input type="radio" name="environment_other" value="1" onchange="toggleEnvironmentOtherInput(this.value == '1')"> Ya</label>
                                            <label><input type="radio" name="environment_other" value="0" onchange="toggleEnvironmentOtherInput(this.value == '1')"> Tidak</label>
                                        </div>
                                    </div>
                                    
                                    <div id="environment_other_input" style="display: none; margin-top: 10px;">
                                        <textarea name="environment_other_conditions" class="form-control" rows="3" placeholder="Jelaskan kondisi lingkungan lainnya..."></textarea>
                                    </div>
                                </div>
                            </div>

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
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="additional_controls" class="form-label">
                                    <strong>Pengendalian Tambahan yang Diperlukan</strong>
                                </label>
                                <textarea name="additional_controls" id="additional_controls" class="form-control" rows="6" 
                                    placeholder="Jelaskan pengendalian tambahan yang diperlukan untuk memastikan keselamatan kerja..."></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Cantumkan semua tindakan pengendalian tambahan yang diperlukan seperti: penggunaan APD khusus, prosedur darurat, koordinasi dengan departemen lain, dll.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Create HRA Permit
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
</script>
@endpush

@endsection
