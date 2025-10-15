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

/* Styling for disabled test listrik fields */
.tes-listrik-field:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.tes-listrik-field:disabled + .form-check-label {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Styling for disabled utility lainnya checkboxes */
.utility-lainnya-checkbox:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    border-radius: 10px;
    border: 1px solid #ced4da;
}

.btn {
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Enhanced form styling */
.form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding: 12px 15px;
    font-size: 14px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.main-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding-top: 20px;
}

.content-header {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.08);
}

.form-check-input {
    width: 1.25em;
    height: 1.25em;
    margin-top: 0.125em;
    border-radius: 0.375em;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    border-top: none;
    padding: 12px;
}

.table td {
    padding: 12px;
    vertical-align: middle;
}

.table-bordered {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    overflow: hidden;
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
                <h4 class="mb-1">Edit HRA - LOTO/Isolation</h4>
                <p class="text-muted mb-0">Edit the LOTO/Isolation hazard risk assessment for Permit: <strong>{{ $permit->permit_number }}</strong></p>
            </div>
            <a href="{{ route('hra.loto-isolations.show', [$permit, $hraLotoIsolation]) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to HRA Details
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('hra.loto-isolations.update', [$permit, $hraLotoIsolation]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                    <i class="fas fa-info-circle me-2"></i>Basic Information
                </h5>
            </div>
            <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="worker_name" class="form-label">Worker Name</label>
                        <input type="text" class="form-control" id="worker_name" name="worker_name" 
                               value="{{ old('worker_name', $hraLotoIsolation->worker_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="worker_phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="worker_phone" name="worker_phone" 
                               value="{{ old('worker_phone', $hraLotoIsolation->worker_phone) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="supervisor_name" class="form-label">Supervisor Name</label>
                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" 
                               value="{{ old('supervisor_name', $hraLotoIsolation->supervisor_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="work_location" class="form-label">Work Location</label>
                        <input type="text" class="form-control" id="work_location" name="work_location" 
                               value="{{ old('work_location', $hraLotoIsolation->work_location) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ old('start_date', \Carbon\Carbon::parse($hraLotoIsolation->start_datetime)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" 
                               value="{{ old('start_time', \Carbon\Carbon::parse($hraLotoIsolation->start_datetime)->format('H:i')) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ old('end_date', \Carbon\Carbon::parse($hraLotoIsolation->end_datetime)->format('Y-m-d')) }}" required readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" 
                               value="{{ old('end_time', \Carbon\Carbon::parse($hraLotoIsolation->end_datetime)->format('H:i')) }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="work_description" class="form-label">Work Description</label>
                        <textarea class="form-control" id="work_description" name="work_description" rows="3" required>{{ old('work_description', $hraLotoIsolation->work_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Isolasi Mesin/Tangki Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: none;">
                <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                    <i class="fas fa-cogs me-2"></i>Isolasi Mesin/Tangki
                </h5>
            </div>
            <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                
                <!-- Nama/No. Mesin/Tangki -->
                <div class="row mb-4">
                    <div class="col-12">
                        <label for="machine_tank_name" class="form-label" style="font-weight: 600;">Nama/No. mesin/tangki:</label>
                        <input type="text" class="form-control" id="machine_tank_name" name="machine_tank_name" 
                               value="{{ old('machine_tank_name', $hraLotoIsolation->machine_tank_name) }}" placeholder="Masukkan nama atau nomor mesin/tangki" required>
                    </div>
                </div>

                <!-- Isolasi Energi Table -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3" style="font-weight: 600; color: #495057;">Isolasi Energi (pilih yang sesuai):</h6>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 14px;">
                                <thead style="background-color: #e9ecef;">
                                    <tr>
                                        <th style="width: 25%; font-weight: 600;">Jenis Energi</th>
                                        <th style="width: 18.75%; text-align: center; font-weight: 600;">mati/off</th>
                                        <th style="width: 18.75%; text-align: center; font-weight: 600;">dikunci</th>
                                        <th style="width: 18.75%; text-align: center; font-weight: 600;">diperiksa</th>
                                        <th style="width: 18.75%; text-align: center; font-weight: 600;">dipasang tag</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Panel Listrik -->
                                    <tr>
                                        <td style="font-weight: 500;">Panel Listrik</td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="panel_listrik_mati" value="1" {{ old('panel_listrik_mati', $hraLotoIsolation->panel_listrik_mati) ? 'checked' : '' }}>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="panel_listrik_dikunci" value="1" {{ old('panel_listrik_dikunci', $hraLotoIsolation->panel_listrik_dikunci) ? 'checked' : '' }}>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="panel_listrik_diperiksa" value="1" {{ old('panel_listrik_diperiksa', $hraLotoIsolation->panel_listrik_diperiksa) ? 'checked' : '' }}>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="form-check-input" name="panel_listrik_dipasang_tag" value="1" {{ old('panel_listrik_dipasang_tag', $hraLotoIsolation->panel_listrik_dipasang_tag) ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    <!-- Add other energy types... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <button type="submit" class="btn btn-info btn-lg px-5">
                    <i class="fas fa-save me-2"></i>Update HRA LOTO/Isolation
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Copy the JavaScript functions from create.blade.php
function toggleTestListrikFields() {
    const needsTest = document.querySelector('input[name="membutuhkan_tes_listrik_on"]:checked');
    const fields = document.querySelectorAll('.tes-listrik-field');
    const textareaAlasan = document.getElementById('alasan_live_test');
    const inputVoltage = document.getElementById('maximum_voltage');
    
    if (needsTest && needsTest.value === 'ya') {
        fields.forEach(field => {
            field.disabled = false;
        });
        if (textareaAlasan) textareaAlasan.required = true;
    } else {
        fields.forEach(field => {
            field.disabled = true;
            if (field.type === 'radio') {
                field.checked = false;
            }
        });
        if (textareaAlasan) {
            textareaAlasan.required = false;
            textareaAlasan.value = '';
        }
        if (inputVoltage) {
            inputVoltage.value = '';
        }
    }
}

function toggleLainnyaCheckboxes() {
    const lainnyaNama = document.getElementById('utility_lainnya_nama');
    const checkboxes = document.querySelectorAll('.utility-lainnya-checkbox');
    
    if (lainnyaNama && lainnyaNama.value.trim().length >= 5) {
        checkboxes.forEach(checkbox => {
            checkbox.disabled = false;
        });
    } else {
        checkboxes.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.checked = false;
        });
    }
}

// Date/time handling
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const startTimeInput = document.getElementById('start_time');
    const endDateInput = document.getElementById('end_date');
    const endTimeInput = document.getElementById('end_time');
    
    function updateEndDateTime() {
        if (startDateInput.value) {
            endDateInput.value = startDateInput.value;
            
            if (startTimeInput.value) {
                const [hours, minutes] = startTimeInput.value.split(':');
                const startTime = new Date();
                startTime.setHours(parseInt(hours), parseInt(minutes));
                startTime.setHours(startTime.getHours() + 9);
                
                const endHours = startTime.getHours().toString().padStart(2, '0');
                const endMinutes = startTime.getMinutes().toString().padStart(2, '0');
                endTimeInput.value = `${endHours}:${endMinutes}`;
            }
        }
    }
    
    startDateInput.addEventListener('change', updateEndDateTime);
    startTimeInput.addEventListener('change', updateEndDateTime);
    
    // Initialize toggle states
    toggleTestListrikFields();
    toggleLainnyaCheckboxes();
    
    // Add event listeners
    document.querySelectorAll('input[name="membutuhkan_tes_listrik_on"]').forEach(radio => {
        radio.addEventListener('change', toggleTestListrikFields);
    });
    
    document.getElementById('utility_lainnya_nama').addEventListener('input', toggleLainnyaCheckboxes);
});
</script>
@endsection