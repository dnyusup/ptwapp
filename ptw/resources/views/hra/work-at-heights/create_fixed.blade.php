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
}

.checkbox-group label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 500;
    color: #495057;
}

.checkbox-group input[type="checkbox"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
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
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="fixed_scaffolding_checked" value="1" onchange="toggleSubQuestions('fixed_scaffolding_sub', this.checked)"> Yes</label>
                            </div>
                        </div>
                        
                        <div id="fixed_scaffolding_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="fixed_scaffolding_approved_by_she" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Scaffolding -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Mobile scaffolding</span>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="mobile_scaffolding_checked" value="1" onchange="toggleSubQuestions('mobile_scaffolding_sub', this.checked)"> Yes</label>
                            </div>
                        </div>
                        
                        <div id="mobile_scaffolding_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Sudah disetujui oleh SHE PTBI?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="mobile_scaffolding_approved_by_she" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Elevation Platform -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Mobile elevation platform</span>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="mobile_elevation_checked" value="1" onchange="toggleSubQuestions('mobile_elevation_sub', this.checked)"> Yes</label>
                            </div>
                        </div>
                        
                        <div id="mobile_elevation_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Operator terlatih?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="mobile_elevation_training_provided" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Penggunaannya tertulis?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="mobile_elevation_used_before" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Menggunakan Alat Pelindung Jatuh?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="mobile_elevation_location_marked" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tangga -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Tangga</span>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="ladder_checked" value="1" onchange="toggleSubQuestions('ladder_sub', this.checked)"> Yes</label>
                            </div>
                        </div>
                        
                        <div id="ladder_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Tidak ada alat lain yang bisa dipakai?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="mobile_elevation_no_other_tools" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="mobile_elevation_activities_short" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Diperiksa dan di-tag</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="ladder_area_barriers" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pekerja dilatih menggunakannya</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="safety_personnel_required" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fall Arrest -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Fall arrest seperti FBH digunakan?</span>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="fall_arrest_used" value="1" onchange="toggleSubQuestions('fall_arrest_sub', this.checked)"> Yes</label>
                            </div>
                        </div>
                        
                        <div id="fall_arrest_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Diperiksa sebelum digunakan</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="area_closed_from_below" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Tim pat cantolan ditunjuk oleh SHE PTB atau orang lain dengan kualifikasi</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="materials_secured" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roof Work -->
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span class="parent-question">Pekerjaan di Atap (Roof Work)</span>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="roof_work_checked" value="1" onchange="toggleSubQuestions('roof_work_sub', this.checked)"> Yes</label>
                            </div>
                        </div>
                        
                        <div id="roof_work_sub" class="sub-questions">
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Atap kuat menahan beban?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="roof_load_capacity" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Apakah ada atap yang rawan?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="roof_fragile_areas" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Pelindung jatuh/pelindung disisi tersedia?</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="roof_fall_protection" value="1"> Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="checklist-item">
                                <div class="condition-grid">
                                    <span class="sub-question">Area dibarikade pada sisi bawah</span>
                                    <div class="checkbox-group">
                                        <label><input type="checkbox" name="emergency_exit_point" value="1"> Yes</label>
                                    </div>
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
        // Reset all checkboxes in this container
        const checkboxes = container.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false);
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
</script>
@endpush

@endsection
