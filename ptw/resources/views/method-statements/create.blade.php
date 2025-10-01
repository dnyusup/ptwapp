@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
.select2-user-option {
    padding: 8px 0;
}

.select2-user-option .fw-bold {
    font-weight: 600;
    color: #212529;
}

.select2-user-option .text-muted {
    font-size: 0.875rem;
    color: #6c757d;
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
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    min-height: calc(2.25rem + 2px) !important;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 0.5rem 0.75rem;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #0d6efd;
    color: white;
}

/* Ensure dropdown appears above everything */
.select2-container--open .select2-dropdown {
    z-index: 999999 !important;
    position: absolute !important;
}

/* Validation styling */
.is-invalid {
    border-color: #dc3545 !important;
    background-color: #fff5f5 !important;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.is-invalid ~ .invalid-feedback {
    display: block;
}
</style>
@endsection

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Create Method Statement</h4>
                <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
            </div>
            <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Permit
            </a>
        </div>
    </div>

    <form action="{{ route('method-statements.store', $permit->permit_number) }}" method="POST">
        @csrf
        
        <!-- Basic Information Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="responsible_person_name" class="form-label">Nama Penanggung Jawab</label>
                        <select class="form-select" id="responsible_person_name" name="responsible_person_name" required>
                            <option value="">Pilih Penanggung Jawab...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" 
                                        {{ old('responsible_person_name', $permit->responsible_person) == $user->name ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih dari user perusahaan: {{ $permit->receiver_company_name ?? 'Tidak ada perusahaan' }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="method_statement_date" class="form-label">Tanggal Method Statement</label>
                        <input type="date" class="form-control" id="method_statement_date" name="method_statement_date" value="{{ old('method_statement_date', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="permit_receiver_name" class="form-label">Nama Permit Receiver</label>
                        <input type="text" class="form-control" id="permit_receiver_name" name="permit_receiver_name" value="{{ old('permit_receiver_name', $permit->receiver_name ?? ($permit->receiver->name ?? '')) }}" readonly>
                        <small class="text-muted">Otomatis dari data permit</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="permit_issuer_name" class="form-label">Nama Pembuat Method Statement</label>
                        <input type="text" class="form-control" id="permit_issuer_name" name="permit_issuer_name" value="{{ old('permit_issuer_name', auth()->user()->name) }}" readonly>
                        <small class="text-muted">Otomatis dari user yang login</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsible Persons -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Personil Yang Bertanggung Jawab</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Nama bertanggung jawab memaparkan kepastian pada Method Statement</p>
                
                <div id="responsible-persons-table">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="responsible-persons-tbody">
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <select class="form-select responsible-person-select" name="responsible_persons[]" data-placeholder="Pilih responsible person...">
                                            <option value="">Pilih responsible person...</option>
                                            @foreach($users as $user)
                                                <option value="{{ json_encode(['name' => $user->name, 'email' => $user->email]) }}" data-email="{{ $user->email }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-person-row" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-person-row">
                        <i class="fas fa-plus me-1"></i>Add Person
                    </button>
                </div>
            </div>
        </div>

        <!-- Work Method Explanations -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Detail Langkah-Langkah Pekerjaan</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Catatan:</strong> Semua field di bawah ini wajib diisi jika Anda ingin <strong>Submit Method Statement</strong>. 
                    Untuk <strong>Save as Draft</strong>, field dapat dikosongkan sementara.
                </div>
                <div class="mb-4">
                    <label for="work_access_explanation" class="form-label fw-bold">Jelaskan cara untuk menuju dan dari lokasi kerja, termasuk platform permanen, scaffolding (hand rails, toe boards, dll) dan metode lainnya. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="work_access_explanation" name="work_access_explanation" rows="3" required>{{ old('work_access_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="safety_equipment_explanation" class="form-label fw-bold">Sebutkan APD dan peralatan safety lainnya yang digunakan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="safety_equipment_explanation" name="safety_equipment_explanation" rows="3" required>{{ old('safety_equipment_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="training_competency_explanation" class="form-label fw-bold">Tulis training/kompetensi/pengalaman yang melakukan pekerjaan dan training khusus untuk pekerjaan tertentu. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="training_competency_explanation" name="training_competency_explanation" rows="3" required>{{ old('training_competency_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="route_identification_explanation" class="form-label fw-bold">Identifikasi rute untuk pejalan kaki, kendaraan, mesin dan peralatan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="route_identification_explanation" name="route_identification_explanation" rows="3" required>{{ old('route_identification_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="work_area_preparation_explanation" class="form-label fw-bold">Lokasi untuk peralatan off-job dan penyimpanan material, dan tempat istirahat, dan pengaturan keamanan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="work_area_preparation_explanation" name="work_area_preparation_explanation" rows="3" required>{{ old('work_area_preparation_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="work_sequence_explanation" class="form-label fw-bold">Tuliskan urutan pekerjaan dilakukan dan kendala yang mungkin terjadi untuk menyelesaikan pekerjaan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="work_sequence_explanation" name="work_sequence_explanation" rows="3" required>{{ old('work_sequence_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="equipment_maintenance_explanation" class="form-label fw-bold">Tuliskan peralatan yang dibutuhkan, bagaimana diperiksa dan apa yang harus yang digunakan, termasuk membahas, fitting. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="equipment_maintenance_explanation" name="equipment_maintenance_explanation" rows="3" required>{{ old('equipment_maintenance_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="platform_explanation" class="form-label fw-bold">Jelaskan platform sementara yang harus diperiksa dan siapa yang desain. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="platform_explanation" name="platform_explanation" rows="3" required>{{ old('platform_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="hand_washing_explanation" class="form-label fw-bold">Jelaskan apa pengaruh cuaca dan kemungkinannya menyebabkan pekerjaan tidak dapat dilakukan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="hand_washing_explanation" name="hand_washing_explanation" rows="3" required>{{ old('hand_washing_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="work_area_cleanliness_explanation" class="form-label fw-bold">Jelaskan cara menjaga kebersihan dan kerapian area kerja, kantor/bedeng/akomodasi sementara dan material yang disimpan dan disebrang. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="work_area_cleanliness_explanation" name="work_area_cleanliness_explanation" rows="3" required>{{ old('work_area_cleanliness_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>
            </div>
        </div>

        <!-- Risk Assessment Table -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Identifikasi Bahaya</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Aktivitas yang diidentifikasi sebagai <strong>Berisiko Tinggi</strong> dan <strong>Medium</strong> membutuhkan tindakan pengendalian dan kontrol.</p>
                
                <div id="risk-assessment-table">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Bahaya</th>
                                    <th>Risiko</th>
                                    <th>Tindakan Pengendalian</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="risk-assessment-tbody">
                                <tr>
                                    <td><input type="text" class="form-control" name="risk_activities[]" placeholder="Identifikasi bahaya..."></td>
                                    <td>
                                        <select class="form-select" name="risk_levels[]">
                                            <option value="">Select Risk Level</option>
                                            <option value="High" class="text-danger">High</option>
                                            <option value="Medium" class="text-warning">Medium</option>
                                            <option value="Low" class="text-success">Low</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="control_measures[]" placeholder="Tindakan pengendalian..."></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove-risk-row" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-risk-row">
                        <i class="fas fa-plus me-1"></i>Add Row
                    </button>
                </div>

                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Tuliskan detail tindakan pengendalian untuk aktivitas berisiko Medium dan Tinggi
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <div>
                        <button type="submit" name="save_draft" class="btn btn-outline-primary me-2">
                            <i class="fas fa-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Submit Method Statement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Risk Assessment functionality
    const addRiskButton = document.getElementById('add-risk-row');
    const riskTbody = document.getElementById('risk-assessment-tbody');

    addRiskButton.addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" class="form-control" name="risk_activities[]" placeholder="Identifikasi bahaya..."></td>
            <td>
                <select class="form-select" name="risk_levels[]">
                    <option value="">Select Risk Level</option>
                    <option value="High" class="text-danger">High</option>
                    <option value="Medium" class="text-warning">Medium</option>
                    <option value="Low" class="text-success">Low</option>
                </select>
            </td>
            <td><input type="text" class="form-control" name="control_measures[]" placeholder="Tindakan pengendalian..."></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger remove-risk-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        riskTbody.appendChild(newRow);
        updateRemoveRiskButtons();
    });

    riskTbody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-risk-row')) {
            e.target.closest('tr').remove();
            updateRemoveRiskButtons();
        }
    });

    function updateRemoveRiskButtons() {
        const rows = riskTbody.querySelectorAll('tr');
        const removeButtons = riskTbody.querySelectorAll('.remove-risk-row');
        
        removeButtons.forEach(button => {
            button.disabled = rows.length <= 1;
        });
    }

    // Responsible Persons functionality
    const addPersonButton = document.getElementById('add-person-row');
    const personTbody = document.getElementById('responsible-persons-tbody');

    if (addPersonButton && personTbody) {
        addPersonButton.addEventListener('click', function() {
            const currentRows = personTbody.querySelectorAll('tr').length;
            const newRowNumber = currentRows + 1;
            
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${newRowNumber}</td>
                <td>
                    <select class="form-select responsible-person-select" name="responsible_persons[]" data-placeholder="Pilih responsible person...">
                        <option value="">Pilih responsible person...</option>
                        @foreach($users as $user)
                            <option value="{{ json_encode(['name' => $user->name, 'email' => $user->email]) }}" data-email="{{ $user->email }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-person-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            personTbody.appendChild(newRow);
            
            // Initialize Select2 for the new select with same configuration as existing ones
            const newSelect = $(newRow).find('.responsible-person-select');
            initializeSelect2(newSelect[0]);
            
            updateRemovePersonButtons();
            updatePersonRowNumbers();
        });
    }

    personTbody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-person-row')) {
            e.target.closest('tr').remove();
            updateRemovePersonButtons();
            updatePersonRowNumbers();
        }
    });

    function updateRemovePersonButtons() {
        const rows = personTbody.querySelectorAll('tr');
        const removeButtons = personTbody.querySelectorAll('.remove-person-row');
        
        removeButtons.forEach(button => {
            button.disabled = rows.length <= 1;
        });
    }

    function updatePersonRowNumbers() {
        const rows = personTbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            const numberCell = row.querySelector('td:first-child');
            numberCell.textContent = index + 1;
        });
    }
});
</script>

@endsection

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Global functions for Select2 formatting
function formatUser(user) {
    if (!user.id) {
        return user.text;
    }

    var email = $(user.element).data('email');
    if (!email) {
        // For responsible_person_name field, extract email from text
        var match = user.text.match(/\(([^)]+)\)$/);
        if (match) {
            email = match[1];
            var name = user.text.replace(/\s*\([^)]*\)$/, '');
            var $user = $(
                '<div class="select2-user-option">' +
                    '<div class="fw-bold">' + name + '</div>' +
                    '<div class="text-muted small">' + email + '</div>' +
                '</div>'
            );
            return $user;
        }
        return user.text;
    }

    var $user = $(
        '<div class="select2-user-option">' +
            '<div class="fw-bold">' + user.text + '</div>' +
            '<div class="text-muted small">' + email + '</div>' +
        '</div>'
    );

    return $user;
}

function formatUserSelection(user) {
    return user.text;
}

// Function to initialize Select2 with consistent config
function initializeSelect2(element) {
    $(element).select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select responsible person...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('body'),
        templateResult: formatUser,
        templateSelection: formatUserSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });
}

$(document).ready(function() {
    // Initialize Select2 for Basic Information - Nama Penanggung Jawab
    $('#responsible_person_name').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select Penanggung Jawab...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('body'),
        templateResult: formatUser,
        templateSelection: formatUserSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Initialize Select2 for existing Responsible Persons
    $('.responsible-person-select').each(function() {
        initializeSelect2(this);
    });

    // Form validation for Work Method Explanations - only for Submit, not Save Draft
    $('button[name="submit"]').on('click', function(e) {
        let isValid = true;
        let firstInvalidField = null;
        
        // List of required Work Method Explanation fields
        const requiredFields = [
            'work_access_explanation',
            'safety_equipment_explanation', 
            'training_competency_explanation',
            'route_identification_explanation',
            'work_area_preparation_explanation',
            'work_sequence_explanation',
            'equipment_maintenance_explanation',
            'platform_explanation',
            'hand_washing_explanation',
            'work_area_cleanliness_explanation'
        ];

        // Check each required field
        requiredFields.forEach(function(fieldName) {
            const field = $('textarea[name="' + fieldName + '"]');
            const value = field.val().trim();
            
            if (value === '') {
                isValid = false;
                field.addClass('is-invalid');
                if (firstInvalidField === null) {
                    firstInvalidField = field;
                }
            } else {
                field.removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            
            // Show alert message
            if (!$('#work-method-alert').length) {
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" id="work-method-alert" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Field Wajib Belum Diisi!</strong> 
                        Semua field pada bagian "Work Method Explanations" harus diisi sebelum submit.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.content-header').after(alertHtml);
            }
            
            // Scroll to first invalid field
            if (firstInvalidField) {
                $('html, body').animate({
                    scrollTop: firstInvalidField.offset().top - 100
                }, 500);
                firstInvalidField.focus();
            }
        }
    });

    // Allow Save Draft without validation
    $('button[name="save_draft"]').on('click', function(e) {
        // Remove any existing validation styling for save draft
        $('textarea.is-invalid').removeClass('is-invalid');
        $('#work-method-alert').remove();
    });

    // Remove validation error styling when user starts typing
    $('textarea[required]').on('input', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endpush

@include('layouts.sidebar-scripts')
