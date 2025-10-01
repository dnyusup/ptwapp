@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- jQuery (must be loaded first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

.select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 0.5rem 0.75rem;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #0d6efd;
    color: white;
}

.select2-container--bootstrap-5 .select2-selection--single {
    height: calc(2.25rem + 2px);
    border: 1px solid #ced4da;
}
</style>

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-file-alt me-2 text-warning"></i>Edit Method Statement</h4>
                <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('method-statements.show', $permit->permit_number) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye me-2"></i>View Method Statement
                </a>
                <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('method-statements.update', $permit->permit_number) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="responsible_person_name" class="form-label">Nama Penanggung Jawab</label>
                        <select class="form-select" id="responsible_person_name" name="responsible_person_name" required>
                            <option value="">Pilih Penanggung Jawab...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->name }}" 
                                        {{ old('responsible_person_name', $methodStatement->responsible_person_name) == $user->name ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih dari user perusahaan: {{ $permit->receiver_company_name ?? 'Tidak ada perusahaan' }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="method_statement_date" class="form-label">Tanggal Method Statement</label>
                        <input type="date" class="form-control" id="method_statement_date" name="method_statement_date" value="{{ old('method_statement_date', $methodStatement->method_statement_date ? $methodStatement->method_statement_date->format('Y-m-d') : date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="permit_receiver_name" class="form-label">Nama Permit Receiver</label>
                        <input type="text" class="form-control" id="permit_receiver_name" name="permit_receiver_name" value="{{ old('permit_receiver_name', $methodStatement->permit_receiver_name ?? ($permit->receiver_name ?? ($permit->receiver->name ?? ''))) }}" readonly>
                        <small class="text-muted">Otomatis dari data permit</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="permit_issuer_name" class="form-label">Nama Pembuat Method Statement</label>
                        <input type="text" class="form-control" id="permit_issuer_name" name="permit_issuer_name" value="{{ old('permit_issuer_name', $methodStatement->permit_issuer_name ?? auth()->user()->name) }}" readonly>
                        <small class="text-muted">Otomatis dari user yang login</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsible Persons -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Responsible Persons</h5>
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
                                @if($methodStatement->responsible_persons && count($methodStatement->responsible_persons) > 0)
                                    @foreach($methodStatement->responsible_persons as $index => $person)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <select class="form-select responsible-person-select" name="responsible_persons[]" data-placeholder="Pilih responsible person...">
                                                <option value="">Pilih responsible person...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ json_encode(['name' => $user->name, 'email' => $user->email]) }}" 
                                                            data-email="{{ $user->email }}"
                                                            {{ isset($person['name']) && $person['name'] == $user->name ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger remove-person-row" {{ $loop->first && $loop->count == 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
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
                                @endif
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
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Work Method Explanations</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label for="work_access_explanation" class="form-label fw-bold">Jelaskan cara untuk menuju dan dari lokasi kerja, termasuk platform permanen, scaffolding (hand rails, toe boards, dll) dan metode lainnya.</label>
                    <textarea class="form-control" id="work_access_explanation" name="work_access_explanation" rows="3">{{ old('work_access_explanation', $methodStatement->work_access_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="safety_equipment_explanation" class="form-label fw-bold">Sebutkan APD dan peralatan safety lainnya yang digunakan.</label>
                    <textarea class="form-control" id="safety_equipment_explanation" name="safety_equipment_explanation" rows="3">{{ old('safety_equipment_explanation', $methodStatement->safety_equipment_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="training_competency_explanation" class="form-label fw-bold">Tulis training/kompetensi/pengalaman yang melakukan pekerjaan dan training khusus untuk pekerjaan tertentu.</label>
                    <textarea class="form-control" id="training_competency_explanation" name="training_competency_explanation" rows="3">{{ old('training_competency_explanation', $methodStatement->training_competency_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="route_identification_explanation" class="form-label fw-bold">Identifikasi rute untuk pejalan kaki, kendaraan, mesin dan peralatan.</label>
                    <textarea class="form-control" id="route_identification_explanation" name="route_identification_explanation" rows="3">{{ old('route_identification_explanation', $methodStatement->route_identification_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="work_area_preparation_explanation" class="form-label fw-bold">Lokasi untuk peralatan off-job dan penyimpanan material, dan tempat istirahat, dan pengaturan keamanan.</label>
                    <textarea class="form-control" id="work_area_preparation_explanation" name="work_area_preparation_explanation" rows="3">{{ old('work_area_preparation_explanation', $methodStatement->work_area_preparation_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="work_sequence_explanation" class="form-label fw-bold">Tuliskan urutan pekerjaan dilakukan dan kendala yang mungkin terjadi untuk menyelesaikan pekerjaan.</label>
                    <textarea class="form-control" id="work_sequence_explanation" name="work_sequence_explanation" rows="3">{{ old('work_sequence_explanation', $methodStatement->work_sequence_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="equipment_maintenance_explanation" class="form-label fw-bold">Tuliskan peralatan yang dibutuhkan, bagaimana diperiksa dan apa yang harus yang digunakan, termasuk membahas, fitting.</label>
                    <textarea class="form-control" id="equipment_maintenance_explanation" name="equipment_maintenance_explanation" rows="3">{{ old('equipment_maintenance_explanation', $methodStatement->equipment_maintenance_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="platform_explanation" class="form-label fw-bold">Jelaskan platform sementara yang harus diperiksa dan siapa yang desain.</label>
                    <textarea class="form-control" id="platform_explanation" name="platform_explanation" rows="3">{{ old('platform_explanation', $methodStatement->platform_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="hand_washing_explanation" class="form-label fw-bold">Jelaskan apa pengaruh cuaca dan kemungkinannya menyebabkan pekerjaan tidak dapat dilakukan.</label>
                    <textarea class="form-control" id="hand_washing_explanation" name="hand_washing_explanation" rows="3">{{ old('hand_washing_explanation', $methodStatement->hand_washing_explanation) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="work_area_cleanliness_explanation" class="form-label fw-bold">Jelaskan cara menjaga kebersihan dan kerapian area kerja, kantor/bedeng/akomodasi sementara dan material yang disimpan dan disebrang.</label>
                    <textarea class="form-control" id="work_area_cleanliness_explanation" name="work_area_cleanliness_explanation" rows="3">{{ old('work_area_cleanliness_explanation', $methodStatement->work_area_cleanliness_explanation) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Risk Assessment Table -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Risk Assessment</h5>
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
                                @if($methodStatement->risk_activities && count($methodStatement->risk_activities) > 0)
                                    @foreach($methodStatement->risk_activities as $index => $activity)
                                        @if($activity)
                                        <tr>
                                            <td><input type="text" class="form-control" name="risk_activities[]" value="{{ old('risk_activities.'.$index, $activity) }}" placeholder="Identifikasi bahaya..."></td>
                                            <td>
                                                <select class="form-select" name="risk_levels[]">
                                                    <option value="">Select Risk Level</option>
                                                    <option value="High" class="text-danger" {{ old('risk_levels.'.$index, $methodStatement->risk_levels[$index] ?? '') === 'High' ? 'selected' : '' }}>High</option>
                                                    <option value="Medium" class="text-warning" {{ old('risk_levels.'.$index, $methodStatement->risk_levels[$index] ?? '') === 'Medium' ? 'selected' : '' }}>Medium</option>
                                                    <option value="Low" class="text-success" {{ old('risk_levels.'.$index, $methodStatement->risk_levels[$index] ?? '') === 'Low' ? 'selected' : '' }}>Low</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="control_measures[]" value="{{ old('control_measures.'.$index, $methodStatement->control_measures[$index] ?? '') }}" placeholder="Tindakan pengendalian..."></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-risk-row">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @else
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
                                @endif
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
                            <i class="fas fa-check me-2"></i>Update Method Statement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.getElementById('add-risk-row');
    const tbody = document.getElementById('risk-assessment-tbody');

    addButton.addEventListener('click', function() {
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
        tbody.appendChild(newRow);
        updateRemoveButtons();
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-risk-row')) {
            e.target.closest('tr').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = tbody.querySelectorAll('tr');
        const removeButtons = tbody.querySelectorAll('.remove-risk-row');
        
        removeButtons.forEach(button => {
            button.disabled = rows.length <= 1;
        });
    }

    // Initialize remove buttons state on page load
    updateRemoveButtons();

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
            window.initializeSelect2(newSelect[0]);
            
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

    // Initialize responsible persons buttons on page load
    updateRemovePersonButtons();
});

// Initialize Select2 for Responsible Person fields
$(document).ready(function() {
    // Global functions for Select2 formatting
    window.formatUser = function(user) {
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
    };

    window.formatUserSelection = function(user) {
        return user.text;
    };

    // Function to initialize Select2 with consistent config
    window.initializeSelect2 = function(element) {
        $(element).select2({
            theme: 'bootstrap-5',
            placeholder: 'Search and select responsible person...',
            allowClear: true,
            dropdownParent: $('body'),
            templateResult: window.formatUser,
            templateSelection: window.formatUserSelection,
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    };

    // Initialize Select2 for Basic Information - Nama Penanggung Jawab
    $('#responsible_person_name').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select Penanggung Jawab...',
        allowClear: true,
        templateResult: window.formatUser,
        templateSelection: window.formatUserSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Initialize Select2 for existing Responsible Persons
    $('.responsible-person-select').each(function() {
        window.initializeSelect2(this);
    });
});
</script>

@include('layouts.sidebar-scripts')
@endsection
