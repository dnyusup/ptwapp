@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
/* Custom styling for form elements */
.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.08);
}

.card-header {
    border: none;
    border-radius: 15px 15px 0 0;
    padding: 20px;
}

.card-body {
    padding: 25px;
}

.select2-container--bootstrap-5 .select2-selection--single {
    height: calc(3.5rem + 2px);
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
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
                <h4 class="mb-1">Create HRA - Explosive Atmosphere</h4>
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
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('hra.explosive-atmospheres.store', $permit) }}">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                
                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #a71e2a 100%);">
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

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-danger btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Create HRA Explosive Atmosphere
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
                            <li><i class="fas fa-check text-success me-2"></i>Check gas monitoring equipment</li>
                            <li><i class="fas fa-check text-success me-2"></i>Verify explosion-proof tools</li>
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
    // Initialize Select2 for searchable dropdowns
    $('.searchable-select').select2({
        placeholder: 'Pilih option...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap-5'
    });
});

// Function to update worker phone based on selected worker
function updateWorkerPhone() {
    var selectedOption = document.getElementById('worker_name');
    var phoneField = document.getElementById('worker_phone');
    
    if (selectedOption.selectedIndex > 0) {
        var selectedWorker = selectedOption.options[selectedOption.selectedIndex];
        var phone = selectedWorker.getAttribute('data-phone');
        phoneField.value = phone || '';
    } else {
        phoneField.value = '';
    }
}

// Validasi datetime range
$(document).ready(function() {
    function validateDateTimeRange() {
        const startDateTime = $('#start_datetime').val();
        const endDateTime = $('#end_datetime').val();
        const startDate = new Date(startDateTime);
        const endDate = new Date(endDateTime);

        if (startDateTime && endDateTime) {
            if (startDate >= endDate) {
                $('#end_datetime').get(0).setCustomValidity('Tanggal selesai harus setelah tanggal mulai');
                return false;
            } else {
                $('#end_datetime').get(0).setCustomValidity('');
                return true;
            }
        }
        return true;
    }

    $('#start_datetime, #end_datetime').on('change', validateDateTimeRange);
    
    $('form').on('submit', function(e) {
        if (!validateDateTimeRange()) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
@endsection