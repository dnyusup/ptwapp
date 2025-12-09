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
                    <label for="safe_access_explanation" class="form-label fw-bold">Tentukan akses aman ke dan dari lokasi kerja, termasuk platform permanen, scaffolds (pegangan tangan, papan kaki, dll.), dan menara seluler. Dan bagaimana akses tanpa izin akan dicegah. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="safe_access_explanation" name="safe_access_explanation" rows="3" required>{{ old('safe_access_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="ppe_safety_equipment_explanation" class="form-label fw-bold">Tentukan APD dan peralatan keselamatan yang akan digunakan, dan kapan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="ppe_safety_equipment_explanation" name="ppe_safety_equipment_explanation" rows="3" required>{{ old('ppe_safety_equipment_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="qualifications_training_explanation" class="form-label fw-bold">Cantumkan kualifikasi/pelatihan/pengalaman mereka yang diizinkan untuk melaksanakan pekerjaan tersebut dan pelatihan khusus apa pun untuk pekerjaan spesifik ini. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="qualifications_training_explanation" name="qualifications_training_explanation" rows="3" required>{{ old('qualifications_training_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="safe_routes_identification_explanation" class="form-label fw-bold">Mengidentifikasi rute akses aman untuk pejalan kaki, kendaraan, pabrik dan peralatan, dll. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="safe_routes_identification_explanation" name="safe_routes_identification_explanation" rows="3" required>{{ old('safe_routes_identification_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="storage_security_explanation" class="form-label fw-bold">Lokasi untuk penyimpanan peralatan dan material di luar pekerjaan dan pengaturan penandaan, penanganan, dan keamanan di tempat kerja. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="storage_security_explanation" name="storage_security_explanation" rows="3" required>{{ old('storage_security_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="equipment_checklist_explanation" class="form-label fw-bold">Buat daftar perlengkapan yang dibutuhkan, bagaimana perlengkapan tersebut akan disediakan, dan pemeriksaan apa saja yang perlu dilakukan, termasuk cranes, slings, dan lain-lain. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="equipment_checklist_explanation" name="equipment_checklist_explanation" rows="3" required>{{ old('equipment_checklist_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="work_order_explanation" class="form-label fw-bold">Tentukan urutan pekerjaan. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="work_order_explanation" name="work_order_explanation" rows="3" required>{{ old('work_order_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="temporary_work_explanation" class="form-label fw-bold">Jelaskan pekerjaan sementara yang akan disediakan dan tanggung jawab atas desain yang kompeten, misalnya scaffolding, trench supports, penyangga lantai sementara, dll. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="temporary_work_explanation" name="temporary_work_explanation" rows="3" required>{{ old('temporary_work_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="weather_conditions_explanation" class="form-label fw-bold">Pertimbangan tentang dampak cuaca dan keterbatasan dalam bekerja dalam kondisi buruk. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="weather_conditions_explanation" name="weather_conditions_explanation" rows="3" required>{{ old('weather_conditions_explanation') }}</textarea>
                    <div class="invalid-feedback">Field ini wajib diisi.</div>
                </div>

                <div class="mb-4">
                    <label for="area_maintenance_explanation" class="form-label fw-bold">Pengaturan untuk menjaga area kerja tetap bersih dan rapi, akomodasi sementara, dan area penyimpanan material. <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="area_maintenance_explanation" name="area_maintenance_explanation" rows="3" required>{{ old('area_maintenance_explanation') }}</textarea>
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
                                            <option value="P1" style="background-color: #dc3545; color: white; font-weight: bold;">Intolerable / Unacceptable Risk (P1)</option>
                                            <option value="P2" style="background-color: #dc3545; color: white; font-weight: bold;">Intolerable / Unacceptable Risk (P2)</option>
                                            <option value="P3" style="background-color: #dc3545; color: white; font-weight: bold;">Intolerable / Unacceptable Risk (P3)</option>
                                            <option value="P4" style="background-color: #ffc107; color: #000; font-weight: bold;">Medium Risk - Look to reduce (P4)</option>
                                            <option value="AR" style="background-color: #198754; color: white; font-weight: bold;">Tolerable / Acceptable Risk (AR)</option>
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
                    <option value="P1" style="background-color: #dc3545; color: white; font-weight: bold;">Intolerable / Unacceptable Risk (P1)</option>
                    <option value="P2" style="background-color: #dc3545; color: white; font-weight: bold;">Intolerable / Unacceptable Risk (P2)</option>
                    <option value="P3" style="background-color: #dc3545; color: white; font-weight: bold;">Intolerable / Unacceptable Risk (P3)</option>
                    <option value="P4" style="background-color: #ffc107; color: #000; font-weight: bold;">Medium Risk - Look to reduce (P4)</option>
                    <option value="AR" style="background-color: #198754; color: white; font-weight: bold;">Tolerable / Acceptable Risk (AR)</option>
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
