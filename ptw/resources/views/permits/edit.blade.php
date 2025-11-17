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

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header.bg-gradient-primary {
    border-bottom: none;
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

@media (max-width: 768px) {
    .work-type-item {
        padding: 8px;
        margin-bottom: 8px;
    }
    
    .form-check-label {
        font-size: 0.9rem;
    }
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
                <h4 class="mb-1">Edit Permit to Work</h4>
                <p class="text-muted mb-0">Modify permit to work details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Permit Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('permits.update', $permit) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="work_title" class="form-label">Work Title *</label>
                                <input type="text" class="form-control @error('work_title') is-invalid @enderror" 
                                       id="work_title" name="work_title" 
                                       value="{{ old('work_title', $permit->work_title) }}" required>
                                @error('work_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department *</label>
                                <select class="form-select @error('department') is-invalid @enderror" 
                                        id="department" name="department" required>
                                    <option value="">Select Department</option>
                                    <option value="Maintenance" {{ old('department', $permit->department) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="IT" {{ old('department', $permit->department) == 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="IE" {{ old('department', $permit->department) == 'IE' ? 'selected' : '' }}>IE</option>
                                    <option value="EHS" {{ old('department', $permit->department) == 'EHS' ? 'selected' : '' }}>EHS</option>
                                    <option value="General" {{ old('department', $permit->department) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="Human Resources" {{ old('department', $permit->department) == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                    <option value="Supply Chain" {{ old('department', $permit->department) == 'Supply Chain' ? 'selected' : '' }}>Supply Chain</option>
                                    <option value="Quality" {{ old('department', $permit->department) == 'Quality' ? 'selected' : '' }}>Quality</option>
                                    <option value="Technical" {{ old('department', $permit->department) == 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Finance" {{ old('department', $permit->department) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                    <option value="Procurement" {{ old('department', $permit->department) == 'Procurement' ? 'selected' : '' }}>Procurement</option>
                                    <option value="Sales" {{ old('department', $permit->department) == 'Sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="CORD" {{ old('department', $permit->department) == 'CORD' ? 'selected' : '' }}>CORD</option>
                                    <option value="WWD" {{ old('department', $permit->department) == 'WWD' ? 'selected' : '' }}>WWD</option>
                                    <option value="HP" {{ old('department', $permit->department) == 'HP' ? 'selected' : '' }}>HP</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="work_location" class="form-label">Work Location *</label>
                                <input type="text" class="form-control @error('work_location') is-invalid @enderror" 
                                       id="work_location" name="work_location" 
                                       value="{{ old('work_location', $permit->work_location) }}" required>
                                @error('work_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="location_owner_id" class="form-label">Location Owner</label>
                                <select class="form-select @error('location_owner_id') is-invalid @enderror" 
                                        id="location_owner_id" name="location_owner_id">
                                    <option value="">Select Location Owner</option>
                                    @foreach($bekaertUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('location_owner_id', $permit->location_owner_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_owner_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="equipment_tools" class="form-label fw-semibold">
                                    <i class="fas fa-tools me-2 text-secondary"></i>Equipment & Tools *
                                </label>
                                <textarea class="form-control @error('equipment_tools') is-invalid @enderror" 
                                          id="equipment_tools" name="equipment_tools" rows="3" 
                                          placeholder="List all equipment and tools that will be used..." required>{{ old('equipment_tools', $permit->equipment_tools) }}</textarea>
                                @error('equipment_tools')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date *</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" 
                                       value="{{ old('start_date', $permit->start_date ? $permit->start_date->format('Y-m-d') : '') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                <div class="form-text">
                                    <small class="text-muted">Tidak dapat memilih tanggal sebelum hari ini</small>
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date *</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" 
                                       value="{{ old('end_date', $permit->end_date ? $permit->end_date->format('Y-m-d') : '') }}" required>
                                <div class="form-text">
                                    <small class="text-muted">Maksimal 5 hari termasuk tanggal mulai</small>
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="work_description" class="form-label">Work Description *</label>
                            <textarea class="form-control @error('work_description') is-invalid @enderror" 
                                      id="work_description" name="work_description" rows="4" required>{{ old('work_description', $permit->work_description) }}</textarea>
                            @error('work_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permit Work Types -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-gradient-primary text-white">
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-clipboard-check me-2"></i>Izin Kerja (PTW) - Pilih yang sesuai
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="work-type-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="work_at_heights" 
                                                       name="work_at_heights" value="1" 
                                                       {{ old('work_at_heights', $permit->work_at_heights) ? 'checked' : '' }}>
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
                                                       name="hot_work" value="1" 
                                                       {{ old('hot_work', $permit->hot_work) ? 'checked' : '' }}>
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
                                                       name="loto_isolation" value="1" 
                                                       {{ old('loto_isolation', $permit->loto_isolation) ? 'checked' : '' }}>
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
                                                       name="line_breaking" value="1" 
                                                       {{ old('line_breaking', $permit->line_breaking) ? 'checked' : '' }}>
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
                                                       name="excavation" value="1" 
                                                       {{ old('excavation', $permit->excavation) ? 'checked' : '' }}>
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
                                                       name="confined_spaces" value="1" 
                                                       {{ old('confined_spaces', $permit->confined_spaces) ? 'checked' : '' }}>
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
                                                       name="explosive_atmosphere" value="1" 
                                                       {{ old('explosive_atmosphere', $permit->explosive_atmosphere) ? 'checked' : '' }}>
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="responsible_person" class="form-label">Responsible Person *</label>
                                <input type="text" class="form-control @error('responsible_person') is-invalid @enderror" 
                                       id="responsible_person" name="responsible_person" 
                                       value="{{ old('responsible_person', $permit->responsible_person) }}" required>
                                @error('responsible_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Hidden field for responsible person email -->
                                <input type="hidden" name="responsible_person_email" 
                                       value="{{ old('responsible_person_email', $permit->responsible_person_email ?? auth()->user()->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="receiver_contractor" class="form-label">Receiver/Pelaksana</label>
                                <select class="form-select @error('receiver_name') is-invalid @enderror" 
                                        id="receiver_contractor" name="receiver_contractor" 
                                        onchange="updateReceiverFields()">
                                    <option value="">Select Contractor...</option>
                                    @foreach($contractors as $contractor)
                                        <option value="{{ $contractor->id }}" 
                                                data-name="{{ $contractor->name }}" 
                                                data-email="{{ $contractor->email }}"
                                                data-company="{{ $contractor->company ? $contractor->company->company_name : 'No Company' }}"
                                                {{ (old('receiver_name', $permit->receiver_name) == $contractor->name) ? 'selected' : '' }}>
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
                                
                                <!-- Hidden fields for storing name and email -->
                                <input type="hidden" id="receiver_name" name="receiver_name" value="{{ old('receiver_name', $permit->receiver_name) }}">
                                <input type="hidden" id="receiver_email" name="receiver_email" value="{{ old('receiver_email', $permit->receiver_email) }}">
                                <input type="hidden" id="receiver_company_name" name="receiver_company_name" value="{{ old('receiver_company_name', $permit->receiver_company_name) }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Permit
                            </button>
                            <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Current Status</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @switch($permit->status)
                            @case('pending')
                                <span class="badge bg-warning fs-6">Pending Approval</span>
                                @break
                            @case('approved')
                                <span class="badge bg-success fs-6">Approved</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger fs-6">Rejected</span>
                                @break
                            @case('completed')
                                <span class="badge bg-info fs-6">Completed</span>
                                @break
                        @endswitch
                    </div>
                    
                    <div class="small text-muted">
                        <p><strong>Created:</strong> {{ $permit->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Last Updated:</strong> {{ $permit->updated_at->format('d M Y H:i') }}</p>
                        <p><strong>Created By:</strong> {{ $permit->user ? $permit->user->name : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Edit Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="small text-muted">
                        <h6>Important Notes:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-info-circle text-info me-2"></i>Changes will need re-approval</li>
                            <li><i class="fas fa-clock text-warning me-2"></i>Update dates carefully</li>
                            <li><i class="fas fa-shield-alt text-success me-2"></i>Review safety requirements</li>
                            <li><i class="fas fa-user-check text-primary me-2"></i>Confirm supervisor details</li>
                        </ul>

                        @if($permit->status === 'approved')
                        <div class="alert alert-info alert-sm mt-3">
                            <small><strong>Note:</strong> Editing an approved permit will reset its status to pending.</small>
                        </div>
                        @endif
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

// Initialize receiver fields if form has values
document.addEventListener('DOMContentLoaded', function() {
    const receiverName = document.getElementById('receiver_name').value;
    const select = document.getElementById('receiver_contractor');
    
    // Pre-select contractor if receiver_name exists
    if (receiverName) {
        for (let option of select.options) {
            if (option.getAttribute('data-name') === receiverName) {
                option.selected = true;
                break;
            }
        }
    }
});
</script>

<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for Location Owner
    $('#location_owner_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select Location Owner...',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for Receiver/Pelaksana
    $('#receiver_contractor').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select Receiver/Pelaksana...',
        allowClear: true,
        width: '100%'
    }).on('select2:select', function(e) {
        // Trigger the existing updateReceiverFields function when selection changes
        updateReceiverFields();
    });

    // Handle form floating labels for Select2
    $('#location_owner_id').on('select2:select select2:clear', function() {
        const label = $(this).siblings('label');
        if ($(this).val()) {
            label.addClass('active');
        } else {
            label.removeClass('active');
        }
    });

    $('#receiver_contractor').on('select2:select select2:clear', function() {
        const label = $(this).siblings('label');
        if ($(this).val()) {
            label.addClass('active');
        } else {
            label.removeClass('active');
        }
    });

    // Set initial label state if there are pre-selected values
    if ($('#location_owner_id').val()) {
        $('#location_owner_id').siblings('label').addClass('active');
    }
    if ($('#receiver_contractor').val()) {
        $('#receiver_contractor').siblings('label').addClass('active');
    }

    // Date validation logic - Enhanced version with better browser support
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    if (!startDateInput || !endDateInput) {
        console.error('Date inputs not found');
        return;
    }

    console.log('Date validation initialized for edit page');

    // Set minimum date for start_date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today);

    // Function to update end_date constraints based on start_date
    function updateEndDateConstraints() {
        console.log('Updating constraints, start date:', startDateInput.value);
        
        if (startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            
            // Set minimum end_date to same as start_date
            endDateInput.setAttribute('min', startDateInput.value);
            
            // Set maximum end_date to 4 days after start_date (5 days total including start date)
            const maxEndDate = new Date(startDate);
            maxEndDate.setDate(maxEndDate.getDate() + 4);
            const maxEndDateString = maxEndDate.toISOString().split('T')[0];
            
            console.log('Setting max date to:', maxEndDateString);
            endDateInput.setAttribute('max', maxEndDateString);
            
            // Double-check: force constraint by disabling invalid dates
            endDateInput.style.maxDate = maxEndDateString;

            // Clear end_date if it's outside the allowed range
            if (endDateInput.value) {
                const currentEndDate = new Date(endDateInput.value);
                if (currentEndDate < startDate || currentEndDate > maxEndDate) {
                    console.log('Clearing invalid end date:', endDateInput.value);
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
            console.log('Resetting constraints');
            endDateInput.removeAttribute('min');
            endDateInput.removeAttribute('max');
            
            // Reset form text
            const endDateFormText = endDateInput.parentElement.querySelector('.form-text small');
            if (endDateFormText) {
                endDateFormText.textContent = 'Maksimal 5 hari termasuk tanggal mulai';
            }
        }
    }

    // Event listener for start_date changes
    startDateInput.addEventListener('change', function() {
        console.log('Start date changed to:', this.value);
        updateEndDateConstraints();
    });

    // Event listener for start_date input (real-time)
    startDateInput.addEventListener('input', function() {
        console.log('Start date input:', this.value);
        updateEndDateConstraints();
    });

    // Additional validation on end_date input to prevent manual typing of invalid dates
    endDateInput.addEventListener('input', function() {
        if (startDateInput.value && this.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(this.value);
            const maxDate = new Date(startDate);
            maxDate.setDate(maxDate.getDate() + 4);

            if (endDate > maxDate) {
                console.log('Invalid end date detected via input, clearing');
                this.value = '';
                alert('Tanggal selesai maksimal 5 hari termasuk tanggal mulai!');
            }
        }
    });

    // Validation on end_date change
    endDateInput.addEventListener('change', function() {
        console.log('End date changed to:', this.value);
        
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const maxDate = new Date(startDate);
            maxDate.setDate(maxDate.getDate() + 4); // 4 days after = 5 days total

            console.log('Validating:', {
                startDate: startDate.toISOString().split('T')[0],
                endDate: endDate.toISOString().split('T')[0], 
                maxDate: maxDate.toISOString().split('T')[0]
            });

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

    // Force update constraints after DOM is fully ready
    setTimeout(function() {
        console.log('Initializing constraints on page load');
        updateEndDateConstraints();
        
        // Force trigger constraint update if start_date has value
        if (startDateInput.value) {
            console.log('Existing start date found:', startDateInput.value);
            updateEndDateConstraints();
        }
    }, 100);

    // Additional backup validation on form submit
    const form = startDateInput.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                const maxDate = new Date(startDate);
                maxDate.setDate(maxDate.getDate() + 4);

                if (endDate > maxDate) {
                    e.preventDefault();
                    alert('Tanggal selesai maksimal 5 hari termasuk tanggal mulai!');
                    endDateInput.focus();
                    return false;
                }
            }
        });
    }
});
</script>

@include('layouts.sidebar-scripts')
@endsection
