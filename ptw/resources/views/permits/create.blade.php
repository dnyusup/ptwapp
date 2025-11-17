@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
.work-type-item {
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    margin-bottom: 10px;
}

.work-type-item:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
    font-weight: 600;
}

.form-check-input {
    width: 1.2em;
    height: 1.2em;
    margin-top: 0.1em;
}

.form-check-label {
    font-size: 0.95rem;
    cursor: pointer;
    padding-left: 0.5rem;
}

.text-brown {
    color: #8B4513 !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa8cc 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffb000 100%);
}

.card {
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.card-header.bg-gradient-primary,
.card-header.bg-gradient-success,
.card-header.bg-gradient-info,
.card-header.bg-gradient-warning {
    border-bottom: none;
}

.form-floating > label {
    padding: 1rem 0.75rem;
    font-weight: 500;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select ~ label {
    transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 1rem 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Custom Select2 styling */
.select2-container--bootstrap-5 .select2-selection--single {
    border-radius: 10px !important;
    border: 2px solid #e9ecef !important;
    padding: 0.75rem !important;
    height: 58px !important;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0 !important;
    padding-top: 0.5rem !important;
    line-height: 1.5 !important;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 54px !important;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection--single {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.btn-lg {
    padding: 12px 30px;
    font-size: 1.1rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
}

.btn-outline-secondary:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .work-type-item {
        padding: 8px;
        margin-bottom: 8px;
    }
    
    .form-check-label {
        font-size: 0.9rem;
    }
    
    .card {
        margin-bottom: 1rem;
    }
    
    .btn-lg {
        padding: 10px 20px;
        font-size: 1rem;
    }
}

/* Select2 Dropdown Fix */
.select2-container {
    z-index: 9999 !important;
}

.select2-dropdown {
    z-index: 99999 !important;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    background: white;
}

.select2-container--bootstrap-5 .select2-selection {
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    min-height: calc(2.875rem + 2px) !important;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

/* Make Select2 consistent with regular form controls */
.select2-container--bootstrap-5 .select2-selection .select2-selection__rendered {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
    line-height: 1.5 !important;
}

/* Ensure dropdown appears above everything */
.select2-container--open .select2-dropdown {
    z-index: 999999 !important;
    position: absolute !important;
}

.select2-dropdown-above {
    z-index: 999999 !important;
}

/* Override any conflicting z-index from cards or other containers */
.card {
    z-index: auto !important;
}

.form-floating {
    z-index: auto !important;
    position: relative;
}

/* Ensure dropdown appears above everything */
.select2-container--open .select2-dropdown {
    z-index: 999999 !important;
    position: absolute !important;
}

.select2-dropdown-above {
    z-index: 999999 !important;
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
                <h4 class="mb-1">New Permit to Work</h4>
                <p class="text-muted mb-0">Create a new permit to work application</p>
            </div>
            <a href="{{ route('permits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Permits
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Content -->
    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('permits.store') }}">
                @csrf

                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="work_title" class="form-label fw-semibold">
                                    <i class="fas fa-briefcase me-2 text-primary"></i>Work Title *
                                </label>
                                <input type="text" class="form-control @error('work_title') is-invalid @enderror" 
                                       id="work_title" name="work_title" value="{{ old('work_title') }}" 
                                       placeholder="Enter work title..." required>
                                @error('work_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label fw-semibold">
                                    <i class="fas fa-building me-2 text-info"></i>Department *
                                </label>
                                <select class="form-select @error('department') is-invalid @enderror" 
                                        id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Maintenance" {{ old('department') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="IE" {{ old('department') == 'IE' ? 'selected' : '' }}>IE</option>
                                    <option value="EHS" {{ old('department') == 'EHS' ? 'selected' : '' }}>EHS</option>
                                    <option value="General" {{ old('department') == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="Human Resources" {{ old('department') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                    <option value="Supply Chain" {{ old('department') == 'Supply Chain' ? 'selected' : '' }}>Supply Chain</option>
                                    <option value="Quality" {{ old('department') == 'Quality' ? 'selected' : '' }}>Quality</option>
                                    <option value="Technical" {{ old('department') == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="Procurement" {{ old('department') == 'Procurement' ? 'selected' : '' }}>Procurement</option>
                                    <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="CORD" {{ old('department') == 'CORD' ? 'selected' : '' }}>CORD</option>
                                    <option value="WWD" {{ old('department') == 'WWD' ? 'selected' : '' }}>WWD</option>
                                    <option value="HP" {{ old('department') == 'HP' ? 'selected' : '' }}>HP</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <label for="work_location" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2 text-warning"></i>Work Location *
                                </label>
                                <input type="text" class="form-control @error('work_location') is-invalid @enderror" 
                                       id="work_location" name="work_location" value="{{ old('work_location') }}" 
                                       placeholder="Enter work location..." required>
                                @error('work_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="location_owner_id" class="form-label fw-semibold">
                                    <i class="fas fa-user-cog me-2 text-info"></i>Location Owner
                                </label>
                                <select class="form-select @error('location_owner_id') is-invalid @enderror" 
                                        id="location_owner_id" name="location_owner_id">
                                    <option value="">Select Location Owner</option>
                                    @foreach($bekaertUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('location_owner_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_owner_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <label for="equipment_tools" class="form-label fw-semibold">
                                    <i class="fas fa-tools me-2 text-secondary"></i>Equipment & Tools *
                                </label>
                                <textarea class="form-control @error('equipment_tools') is-invalid @enderror" 
                                          id="equipment_tools" name="equipment_tools" rows="3" 
                                          placeholder="List all equipment and tools that will be used..." required>{{ old('equipment_tools') }}</textarea>
                                @error('equipment_tools')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-success text-white">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-calendar-alt me-2"></i>Jadwal Pelaksanaan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    <label for="start_date">
                                        <i class="fas fa-calendar-plus me-2 text-success"></i>Start Date *
                                    </label>
                                    <div class="form-text">
                                        <small class="text-muted">Tidak dapat memilih tanggal sebelum hari ini</small>
                                    </div>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    <label for="end_date">
                                        <i class="fas fa-calendar-minus me-2 text-danger"></i>End Date *
                                    </label>
                                    <div class="form-text">
                                        <small class="text-muted">Maksimal 5 hari termasuk tanggal mulai</small>
                                    </div>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Description Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-file-alt me-2"></i>Deskripsi Pekerjaan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-12">
                                <label for="work_description" class="form-label fw-semibold">
                                    <i class="fas fa-edit me-2 text-info"></i>Detailed Work Description *
                                </label>
                                <textarea class="form-control @error('work_description') is-invalid @enderror" 
                                          id="work_description" name="work_description" rows="5" 
                                          placeholder="Provide detailed description of the work to be performed..." required>{{ old('work_description') }}</textarea>
                                @error('work_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- Permit Work Types -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-gradient-primary text-white">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-clipboard-check me-2"></i>Izin Kerja (PTW) HRA Tambahan - Pilih yang sesuai
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="work_at_heights" 
                                                       name="work_at_heights" value="1" {{ old('work_at_heights') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="work_at_heights">
                                                    <i class="fas fa-building text-warning me-2"></i>
                                                    Bekerja di Ketinggian (Work at Heights)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="hot_work" 
                                                       name="hot_work" value="1" {{ old('hot_work') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="hot_work">
                                                    <i class="fas fa-fire text-danger me-2"></i>
                                                    Pekerjaan Panas (Hot Work)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="loto_isolation" 
                                                       name="loto_isolation" value="1" {{ old('loto_isolation') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="loto_isolation">
                                                    <i class="fas fa-lock text-info me-2"></i>
                                                    LOTOTO - Isolasi/Menghilangkan Energi
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="line_breaking" 
                                                       name="line_breaking" value="1" {{ old('line_breaking') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="line_breaking">
                                                    <i class="fas fa-cut text-secondary me-2"></i>
                                                    Mematikan Line (Line breaking) (hidrolik line etc.)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="excavation" 
                                                       name="excavation" value="1" {{ old('excavation') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="excavation">
                                                    <i class="fas fa-shovel text-brown me-2"></i>
                                                    Penggalian (Excavation)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="confined_spaces" 
                                                       name="confined_spaces" value="1" {{ old('confined_spaces') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="confined_spaces">
                                                    <i class="fas fa-cube text-dark me-2"></i>
                                                    Memasuki Ruang Terbatas (Entering Confined spaces)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="explosive_atmosphere" 
                                                       name="explosive_atmosphere" value="1" {{ old('explosive_atmosphere') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium" for="explosive_atmosphere">
                                                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                                    Atmosfer berbahaya (Explosive atmosphere)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                <!-- Responsible Person Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-warning text-dark">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-users me-2"></i>Informasi Personil
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="responsible_person" class="form-label fw-semibold">
                                    <i class="fas fa-user-shield me-2 text-warning"></i>Responsible Person *
                                </label>
                                <input type="text" class="form-control @error('responsible_person') is-invalid @enderror" 
                                       id="responsible_person" name="responsible_person" 
                                       value="{{ old('responsible_person', auth()->user()->name) }}" 
                                       placeholder="Enter responsible person name..." required>
                                @error('responsible_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Person responsible for overseeing the work and ensuring safety compliance.
                                </div>
                                
                                <!-- Hidden field for responsible person email -->
                                <input type="hidden" name="responsible_person_email" value="{{ auth()->user()->email }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="receiver_contractor" class="form-label fw-semibold">
                                    <i class="fas fa-hard-hat me-2 text-primary"></i>Receiver/Pelaksana
                                </label>
                                <select class="form-select @error('receiver_name') is-invalid @enderror" 
                                        id="receiver_contractor" name="receiver_contractor" 
                                        onchange="updateReceiverFields()">
                                    <option value="">Select Contractor...</option>
                                    @foreach($contractors as $contractor)
                                        <option value="{{ $contractor->id }}" 
                                                data-name="{{ $contractor->name }}" 
                                                data-email="{{ $contractor->email }}"
                                                data-company="{{ $contractor->company ? $contractor->company->company_name : 'No Company' }}"
                                                {{ old('receiver_contractor') == $contractor->id ? 'selected' : '' }}>
                                            {{ $contractor->name }} ({{ $contractor->email }})
                                            @if($contractor->company)
                                                - {{ $contractor->company->company_name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('receiver_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Contractor who will receive and execute the work permit.
                                </div>
                                
                                <!-- Hidden fields for storing name and email -->
                                <input type="hidden" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}">
                                <input type="hidden" id="receiver_email" name="receiver_email" value="{{ old('receiver_email') }}">
                                <input type="hidden" id="receiver_company_name" name="receiver_company_name" value="{{ old('receiver_company_name') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Create Permit
                            </button>
                            <a href="{{ route('permits.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Your permit will be saved as draft and can be submitted for approval later.
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Permit Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <h6>Before Creating a Permit:</h6>
                        <ul class="list-unstyled mb-3">
                            <li><i class="fas fa-check text-success me-2"></i>Ensure all safety measures are identified</li>
                            <li><i class="fas fa-check text-success me-2"></i>Verify supervisor availability</li>
                            <li><i class="fas fa-check text-success me-2"></i>Check equipment requirements</li>
                            <li><i class="fas fa-check text-success me-2"></i>Review location accessibility</li>
                        </ul>

                        <h6>Risk Level Guide:</h6>
                        <div class="mb-2">
                            <span class="badge bg-success">Low</span> - Routine maintenance
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-warning">Medium</span> - Electrical work
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-danger">High</span> - Hot work
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-dark">Critical</span> - Confined space
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

function updateReceiverFields() {
    const select = document.getElementById('receiver_contractor');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        const name = selectedOption.getAttribute('data-name');
        const email = selectedOption.getAttribute('data-email');
        const company = selectedOption.getAttribute('data-company');
        
        document.getElementById('receiver_name').value = name;
        document.getElementById('receiver_email').value = email;
        document.getElementById('receiver_company_name').value = company;
    } else {
        document.getElementById('receiver_name').value = '';
        document.getElementById('receiver_email').value = '';
        document.getElementById('receiver_company_name').value = '';
    }
}

// Initialize receiver fields if form has old values
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('receiver_contractor');
    if (select.value) {
        updateReceiverFields();
    }
});
</script>

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for Location Owner with search functionality
    $('#location_owner_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select Location Owner...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('body'),
        dropdownCssClass: 'select2-dropdown-above'
    }).on('select2:open', function() {
        // Ensure dropdown is visible and positioned correctly
        $('.select2-dropdown').css({
            'z-index': '999999',
            'position': 'absolute'
        });
    });

    // Initialize Select2 for Receiver/Pelaksana with search functionality
    $('#receiver_contractor').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select Receiver/Pelaksana...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('body'),
        dropdownCssClass: 'select2-dropdown-above'
    }).on('select2:select', function(e) {
        // Trigger the existing updateReceiverFields function when selection changes
        updateReceiverFields();
    }).on('select2:open', function() {
        // Ensure dropdown is visible and positioned correctly
        $('.select2-dropdown').css({
            'z-index': '999999',
            'position': 'absolute'
        });
    });

    // Date validation logic
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Set minimum date for start_date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today);

    // Function to update end_date constraints based on start_date
    function updateEndDateConstraints() {
        const startDate = new Date(startDateInput.value);
        
        if (startDateInput.value) {
            // Set minimum end_date to same as start_date
            endDateInput.setAttribute('min', startDateInput.value);
            
            // Set maximum end_date to 4 days after start_date (5 days total including start date)
            const maxEndDate = new Date(startDate);
            maxEndDate.setDate(maxEndDate.getDate() + 4);
            const maxEndDateString = maxEndDate.toISOString().split('T')[0];
            endDateInput.setAttribute('max', maxEndDateString);

            // Clear end_date if it's outside the allowed range
            if (endDateInput.value) {
                const currentEndDate = new Date(endDateInput.value);
                if (currentEndDate < startDate || currentEndDate > maxEndDate) {
                    endDateInput.value = '';
                }
            }

            // Update form text with specific dates
            const endDateFormText = endDateInput.parentElement.querySelector('.form-text small');
            if (endDateFormText) {
                endDateFormText.textContent = `Maksimal ${maxEndDate.toLocaleDateString('id-ID')} (5 hari termasuk tanggal mulai)`;
            }
        } else {
            // Reset end_date constraints if no start_date is selected
            endDateInput.removeAttribute('min');
            endDateInput.removeAttribute('max');
            endDateInput.value = '';
            
            // Reset form text
            const endDateFormText = endDateInput.parentElement.querySelector('.form-text small');
            if (endDateFormText) {
                endDateFormText.textContent = 'Maksimal 5 hari termasuk tanggal mulai';
            }
        }
    }

    // Event listener for start_date changes
    startDateInput.addEventListener('change', updateEndDateConstraints);

    // Validation on end_date change
    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const maxDate = new Date(startDate);
            maxDate.setDate(maxDate.getDate() + 4); // 4 days after = 5 days total

            if (endDate < startDate) {
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
                endDateInput.value = '';
                return;
            }

            if (endDate > maxDate) {
                alert('Tanggal selesai maksimal 5 hari termasuk tanggal mulai!');
                endDateInput.value = '';
                return;
            }
        }
    });

    // Initialize constraints on page load
    updateEndDateConstraints();
});
</script>
@endpush

@include('layouts.sidebar-scripts')
@endsection
