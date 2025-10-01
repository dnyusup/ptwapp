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
                
                <!-- HRA Hot Work Safety Checklist -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-clipboard-check me-2"></i>HRA Hot Work Assessment
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                    
                        <!-- Question 1 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah alternatif pengganti pekerjaan panas (Hot work) sudah dipertimbangkan</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q1_alternative_considered" value="1" {{ old('q1_alternative_considered') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q1_alternative_considered" value="0" {{ old('q1_alternative_considered') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah peralatan diperiksa dan apakah dalam kondisi baik?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q2_equipment_checked" value="1" {{ old('q2_equipment_checked') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q2_equipment_checked" value="0" {{ old('q2_equipment_checked') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Benda mudah terbakar (flammable) & dapat terbakar (combustible) dipindah?</span>
                                <div class="checkbox-group">
                                    <input type="number" class="form-control" id="q3_distance" name="q3_distance" placeholder="12" min="12" style="width: 80px; height: 32px; margin-right: 8px;" value="{{ old('q3_distance') }}" onchange="toggleQ3Checkbox()">
                                    <span class="small">m (min 12m)</span>
                                    <label><input type="radio" id="q3_flammable_moved_ya" name="q3_flammable_moved" value="1" {{ old('q3_flammable_moved') == '1' ? 'checked' : '' }} disabled> Ya</label>
                                    <label><input type="radio" id="q3_flammable_moved_tidak" name="q3_flammable_moved" value="0" {{ old('q3_flammable_moved') == '0' ? 'checked' : '' }} disabled> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 4 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Jika tidak bisa dipindah: flammable atau combustible dilindungi oleh lembar logam dan/atau cover tahan api</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q4_protected_cover" value="1" {{ old('q4_protected_cover') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q4_protected_cover" value="0" {{ old('q4_protected_cover') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 5 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Kotoran atau debu dibersihkan?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q5_debris_cleaned" value="1" {{ old('q5_debris_cleaned') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q5_debris_cleaned" value="0" {{ old('q5_debris_cleaned') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 6 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Area sekitar termasuk tangki, pipa, dinding, dll diperiksa sebagai antisipasi jika flammable/combustible material tersembunyi?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q6_area_inspected" value="1" {{ old('q6_area_inspected') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q6_area_inspected" value="0" {{ old('q6_area_inspected') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 7 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Apakah dinding yang dapat terbakar, atap dan/atau struktur lainnya ada di lokasi?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q7_flammable_structures" value="1" {{ old('q7_flammable_structures') == '1' ? 'checked' : '' }} onchange="toggleActionField('q7_actions_field', this.value == '1')"> Ya</label>
                                    <label><input type="radio" name="q7_flammable_structures" value="0" {{ old('q7_flammable_structures') == '0' ? 'checked' : '' }} onchange="toggleActionField('q7_actions_field', this.value == '1')"> Tidak</label>
                                </div>
                            </div>
                            <div id="q7_actions_field" class="mt-2" style="display: {{ old('q7_flammable_structures') == '1' ? 'block' : 'none' }};">
                                <label class="form-label small text-muted">Jika "Ya" apa yang dilakukan (contoh: membasahi, menutup dengan lembar logam, dll):</label>
                                <textarea class="form-control" name="q7_actions_taken" rows="2" placeholder="Jelaskan tindakan yang dilakukan...">{{ old('q7_actions_taken') }}</textarea>
                            </div>
                        </div>

                        <!-- Question 8 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Selimut/blanket tahan api atau screen dipasang untuk membatasi bunga api?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q8_fire_blanket" value="1" {{ old('q8_fire_blanket') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q8_fire_blanket" value="0" {{ old('q8_fire_blanket') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 9 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Tutup valve otomatis, saluran pembuangan (drain), cover, dll?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q9_valve_drain_covered" value="1" {{ old('q9_valve_drain_covered') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q9_valve_drain_covered" value="0" {{ old('q9_valve_drain_covered') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 10 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Isolasi ducting/conveyor/exhaust yang mungkin kemasukan bunga api atau material terbakar?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q10_isolation_ducting" value="1" {{ old('q10_isolation_ducting') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q10_isolation_ducting" value="0" {{ old('q10_isolation_ducting') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 11 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Lubang dan lubang pembuangan tertutup (sealing pada joint, chinks, bukaan, ducting, dll)?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q11_holes_sealed" value="1" {{ old('q11_holes_sealed') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q11_holes_sealed" value="0" {{ old('q11_holes_sealed') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 12 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Ventilasi cukup di lokasi pekerjaan?</span>
                                <div class="checkbox-group">
                                    <span class="small">(</span>
                                    <label><input type="radio" name="q12_ventilation_type" value="alami" {{ old('q12_ventilation_type') == 'alami' ? 'checked' : '' }} onchange="toggleQ12Checkbox()"> alami</label>
                                    <label><input type="radio" name="q12_ventilation_type" value="buatan" {{ old('q12_ventilation_type') == 'buatan' ? 'checked' : '' }} onchange="toggleQ12Checkbox()"> buatan</label>
                                    <span class="small">)</span>
                                    <label><input type="radio" id="q12_ventilation_adequate_ya" name="q12_ventilation_adequate" value="1" {{ old('q12_ventilation_adequate') == '1' ? 'checked' : '' }} disabled> Ya</label>
                                    <label><input type="radio" id="q12_ventilation_adequate_tidak" name="q12_ventilation_adequate" value="0" {{ old('q12_ventilation_adequate') == '0' ? 'checked' : '' }} disabled> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 13 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Peralatan listrik dan kabel terlindungi?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q13_electrical_protected" value="1" {{ old('q13_electrical_protected') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q13_electrical_protected" value="0" {{ old('q13_electrical_protected') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 14 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Peralatan/mesin disekitarnya, pipa dan material terlindungi?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q14_equipment_protected" value="1" {{ old('q14_equipment_protected') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q14_equipment_protected" value="0" {{ old('q14_equipment_protected') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 15 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Pekerjaan panas yang berada di atas, tambahan perlindungan disediakan di bawah?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q15_overhead_protection" value="1" {{ old('q15_overhead_protection') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q15_overhead_protection" value="0" {{ old('q15_overhead_protection') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 16 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Lokasi kerja diberi tanda/barikade yang memadai?</span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q16_area_marked" value="1" {{ old('q16_area_marked') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q16_area_marked" value="0" {{ old('q16_area_marked') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Question 17 -->
                        <div class="checklist-item">
                            <div class="condition-grid">
                                <span class="parent-question">Gas monitoring untuk kemungkinan adanya gas flammable harus dilakukan <u>sebelum</u> pekerjaan dilakukan<br><small class="text-muted">Jika "Ya" formulir H-Exposures harus diisi.</small></span>
                                <div class="checkbox-group">
                                    <label><input type="radio" name="q17_gas_monitoring" value="1" {{ old('q17_gas_monitoring') == '1' ? 'checked' : '' }}> Ya</label>
                                    <label><input type="radio" name="q17_gas_monitoring" value="0" {{ old('q17_gas_monitoring') == '0' ? 'checked' : '' }}> Tidak</label>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Additional Control Measures Card -->
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
                                    placeholder="Jelaskan pengendalian tambahan yang diperlukan untuk memastikan keselamatan Hot Work...">{{ old('additional_controls') }}</textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Cantumkan semua tindakan pengendalian tambahan seperti: fire watch, fire extinguisher location, emergency procedures, dll.
                                </div>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="alert alert-info mt-3">
                            <small><strong>NB:</strong> Isolasi pada peralatan / lines membutuhkan formulir I-Isolation diisi</small>
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

function toggleQ3Checkbox() {
    const distanceInput = document.getElementById('q3_distance');
    const radioYa = document.getElementById('q3_flammable_moved_ya');
    const radioTidak = document.getElementById('q3_flammable_moved_tidak');
    const distance = parseFloat(distanceInput.value);
    
    if (distance && distance >= 12) {
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

function toggleQ12Checkbox() {
    const radioButtons = document.querySelectorAll('input[name="q12_ventilation_type"]');
    const radioYa = document.getElementById('q12_ventilation_adequate_ya');
    const radioTidak = document.getElementById('q12_ventilation_adequate_tidak');
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
    
    // Initialize Q3 checkbox state
    toggleQ3Checkbox();
    
    // Initialize Q12 checkbox state
    toggleQ12Checkbox();

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
            'q1_alternative_considered',
            'q2_equipment_checked', 
            'q4_protected_cover',
            'q5_debris_cleaned',
            'q6_area_inspected',
            'q7_flammable_structures',
            'q8_fire_blanket',
            'q9_valve_drain_covered',
            'q10_isolation_ducting',
            'q11_holes_sealed',
            'q13_electrical_protected',
            'q14_equipment_protected',
            'q15_overhead_protection',
            'q16_area_marked',
            'q17_gas_monitoring'
        ];

        let isValid = true;
        let missingAnswers = [];

        radioGroups.forEach(groupName => {
            const radios = document.querySelectorAll(`input[name="${groupName}"]`);
            let isChecked = false;
            
            radios.forEach(radio => {
                if (radio.checked) {
                    isChecked = true;
                }
            });
            
            if (!isChecked) {
                isValid = false;
                missingAnswers.push(groupName);
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
