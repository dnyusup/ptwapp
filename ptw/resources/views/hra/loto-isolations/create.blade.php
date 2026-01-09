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
                <h4 class="mb-1">Create HRA - LOTO/Isolation</h4>
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
    <form method="POST" action="{{ route('hra.loto-isolations.store', $permit) }}">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                
                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
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
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ old('start_date', date('Y-m-d')) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" required onchange="updateEndDate()">
                                <small class="text-muted">Default: hari ini</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_time" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" 
                                       value="{{ old('start_time', '08:00') }}" required onchange="updateEndTime()">
                                <small class="text-muted">Default: 08:00</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ old('end_date', date('Y-m-d')) }}" 
                                       min="{{ $permit->start_date->format('Y-m-d') }}"
                                       max="{{ $permit->end_date->format('Y-m-d') }}" readonly required>
                                <small class="text-muted">Otomatis sama dengan tanggal mulai</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_time" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" id="end_time" name="end_time" 
                                       value="{{ old('end_time', '17:00') }}" required>
                                <small class="text-muted">Otomatis +9 jam dari jam mulai</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" placeholder="Jelaskan detail pekerjaan yang akan dilakukan..." required>{{ old('work_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pre Isolation Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-clipboard-check me-2"></i>Pre Isolation
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Apakah P&ID dan/atau rencana kelistrikan yang sesuai telah ditinjau?</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pid_reviewed" id="pid_reviewed_ya" value="ya" {{ old('pid_reviewed') == 'ya' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="pid_reviewed_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="pid_reviewed" id="pid_reviewed_tidak" value="tidak" {{ old('pid_reviewed') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pid_reviewed_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Electrical Isolation Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #fd7e14 0%, #dc6a12 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                <i class="fas fa-bolt me-2"></i>Electrical Isolation
                            </h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="electrical_enabled" name="electrical_enabled" value="1" {{ old('electrical_enabled') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-2" for="electrical_enabled">Aktifkan</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="electrical_body" style="{{ old('electrical_enabled') ? '' : 'display: none;' }}">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Sedang mengerjakan instalasi HV?</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="electrical_hv_installation" id="electrical_hv_ya" value="ya" {{ old('electrical_hv_installation') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="electrical_hv_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="electrical_hv_installation" id="electrical_hv_tidak" value="tidak" {{ old('electrical_hv_installation') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="electrical_hv_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Electrical Isolation Table -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No.</th>
                                        <th>Isolation Description</th>
                                        <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                                        <th style="width: 100px;" class="text-center">Lock & Tag</th>
                                        <th style="width: 100px;" class="text-center">Zero Energy</th>
                                        <th style="width: 100px;" class="text-center">Try Out</th>
                                        <th style="width: 100px;" class="text-center">Removal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i <= 5; $i++)
                                    <tr>
                                        <td class="text-center"><strong>E{{ $i }}</strong></td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="electrical_isolations[{{ $i }}][description]" 
                                                   value="{{ old('electrical_isolations.'.$i.'.description') }}" 
                                                   placeholder="Deskripsi isolasi...">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="electrical_isolations[{{ $i }}][stop_isolate]" 
                                                   value="1" {{ old('electrical_isolations.'.$i.'.stop_isolate') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="electrical_isolations[{{ $i }}][lock_tag]" 
                                                   value="1" {{ old('electrical_isolations.'.$i.'.lock_tag') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="electrical_isolations[{{ $i }}][zero_energy]" 
                                                   value="1" {{ old('electrical_isolations.'.$i.'.zero_energy') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="electrical_isolations[{{ $i }}][try_out]" 
                                                   value="1" {{ old('electrical_isolations.'.$i.'.try_out') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="electrical_isolations[{{ $i }}][removal]" 
                                                   value="1" {{ old('electrical_isolations.'.$i.'.removal') ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Energy Control Method -->
                        <div class="row">
                            <div class="col-12">
                                <label for="electrical_energy_control_method" class="form-label">Metode untuk mengendalikan energi yang tersimpan:</label>
                                <textarea class="form-control" id="electrical_energy_control_method" 
                                          name="electrical_energy_control_method" rows="4" 
                                          placeholder="Jelaskan metode pengendalian energi...">{{ old('electrical_energy_control_method') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mechanical Isolation Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                <i class="fas fa-cogs me-2"></i>Mechanical Isolation
                            </h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="mechanical_enabled" name="mechanical_enabled" value="1" {{ old('mechanical_enabled') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-2" for="mechanical_enabled">Aktifkan</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="mechanical_body" style="{{ old('mechanical_enabled') ? '' : 'display: none;' }}">
                        <!-- Yes/No Questions in 2 columns -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Gravitasi</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_gravitasi" id="mechanical_gravitasi_ya" value="ya" {{ old('mechanical_gravitasi') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_gravitasi_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_gravitasi" id="mechanical_gravitasi_tidak" value="tidak" {{ old('mechanical_gravitasi') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_gravitasi_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Hidrolik</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_hidrolik" id="mechanical_hidrolik_ya" value="ya" {{ old('mechanical_hidrolik') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_hidrolik_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_hidrolik" id="mechanical_hidrolik_tidak" value="tidak" {{ old('mechanical_hidrolik') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_hidrolik_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Kelembaman</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_kelembaman" id="mechanical_kelembaman_ya" value="ya" {{ old('mechanical_kelembaman') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_kelembaman_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_kelembaman" id="mechanical_kelembaman_tidak" value="tidak" {{ old('mechanical_kelembaman') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_kelembaman_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Spring</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_spring" id="mechanical_spring_ya" value="ya" {{ old('mechanical_spring') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_spring_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_spring" id="mechanical_spring_tidak" value="tidak" {{ old('mechanical_spring') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_spring_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Pneumatik</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_pneumatik" id="mechanical_pneumatik_ya" value="ya" {{ old('mechanical_pneumatik') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_pneumatik_ya">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="mechanical_pneumatik" id="mechanical_pneumatik_tidak" value="tidak" {{ old('mechanical_pneumatik') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mechanical_pneumatik_tidak">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">Lainnya</label>
                                    <input type="text" class="form-control form-control-sm" style="width: 200px;" 
                                           name="mechanical_lainnya" value="{{ old('mechanical_lainnya') }}" 
                                           placeholder="Sebutkan...">
                                </div>
                            </div>
                        </div>

                        <!-- Mechanical Isolation Table -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No.</th>
                                        <th>Isolation Description</th>
                                        <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                                        <th style="width: 100px;" class="text-center">Lock & Tag</th>
                                        <th style="width: 100px;" class="text-center">Zero Energy</th>
                                        <th style="width: 100px;" class="text-center">Try Out</th>
                                        <th style="width: 100px;" class="text-center">Removal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i <= 5; $i++)
                                    <tr>
                                        <td class="text-center"><strong>M{{ $i }}</strong></td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="mechanical_isolations[{{ $i }}][description]" 
                                                   value="{{ old('mechanical_isolations.'.$i.'.description') }}" 
                                                   placeholder="Deskripsi isolasi...">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="mechanical_isolations[{{ $i }}][stop_isolate]" 
                                                   value="1" {{ old('mechanical_isolations.'.$i.'.stop_isolate') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="mechanical_isolations[{{ $i }}][lock_tag]" 
                                                   value="1" {{ old('mechanical_isolations.'.$i.'.lock_tag') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="mechanical_isolations[{{ $i }}][zero_energy]" 
                                                   value="1" {{ old('mechanical_isolations.'.$i.'.zero_energy') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="mechanical_isolations[{{ $i }}][try_out]" 
                                                   value="1" {{ old('mechanical_isolations.'.$i.'.try_out') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="mechanical_isolations[{{ $i }}][removal]" 
                                                   value="1" {{ old('mechanical_isolations.'.$i.'.removal') ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Energy Control Method -->
                        <div class="row">
                            <div class="col-12">
                                <label for="mechanical_energy_control_method" class="form-label">Metode untuk mengendalikan energi yang tersimpan:</label>
                                <textarea class="form-control" id="mechanical_energy_control_method" 
                                          name="mechanical_energy_control_method" rows="4" 
                                          placeholder="Jelaskan metode pengendalian energi...">{{ old('mechanical_energy_control_method') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Process Isolation Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6610f2 0%, #4c0fb8 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                <i class="fas fa-industry me-2"></i>Process Isolation
                            </h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="process_enabled" name="process_enabled" value="1" {{ old('process_enabled') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-2" for="process_enabled">Aktifkan</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="process_body" style="{{ old('process_enabled') ? '' : 'display: none;' }}">
                        <!-- Process Isolation Table -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No.</th>
                                        <th>Isolation Description</th>
                                        <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                                        <th style="width: 100px;" class="text-center">Lock & Tag</th>
                                        <th style="width: 100px;" class="text-center">Zero Energy</th>
                                        <th style="width: 100px;" class="text-center">Try Out</th>
                                        <th style="width: 100px;" class="text-center">Removal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i <= 5; $i++)
                                    <tr>
                                        <td class="text-center"><strong>P{{ $i }}</strong></td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="process_isolations[{{ $i }}][description]" 
                                                   value="{{ old('process_isolations.'.$i.'.description') }}" 
                                                   placeholder="Deskripsi isolasi...">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="process_isolations[{{ $i }}][stop_isolate]" 
                                                   value="1" {{ old('process_isolations.'.$i.'.stop_isolate') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="process_isolations[{{ $i }}][lock_tag]" 
                                                   value="1" {{ old('process_isolations.'.$i.'.lock_tag') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="process_isolations[{{ $i }}][zero_energy]" 
                                                   value="1" {{ old('process_isolations.'.$i.'.zero_energy') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="process_isolations[{{ $i }}][try_out]" 
                                                   value="1" {{ old('process_isolations.'.$i.'.try_out') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="process_isolations[{{ $i }}][removal]" 
                                                   value="1" {{ old('process_isolations.'.$i.'.removal') ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Energy Control Method -->
                        <div class="row">
                            <div class="col-12">
                                <label for="process_energy_control_method" class="form-label">Metode untuk mengendalikan energi yang tersimpan:</label>
                                <textarea class="form-control" id="process_energy_control_method" 
                                          name="process_energy_control_method" rows="4" 
                                          placeholder="Jelaskan metode pengendalian energi...">{{ old('process_energy_control_method') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Utility Isolation Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #e83e8c 0%, #c71d6f 100%);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                                <i class="fas fa-plug me-2"></i>Utility Isolation
                            </h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="utility_enabled" name="utility_enabled" value="1" {{ old('utility_enabled') ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                                <label class="form-check-label ms-2" for="utility_enabled">Aktifkan</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="utility_body" style="{{ old('utility_enabled') ? '' : 'display: none;' }}">
                        <!-- Utility Isolation Table -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No.</th>
                                        <th>Isolation Description</th>
                                        <th style="width: 100px;" class="text-center">Stop & Isolate</th>
                                        <th style="width: 100px;" class="text-center">Lock & Tag</th>
                                        <th style="width: 100px;" class="text-center">Zero Energy</th>
                                        <th style="width: 100px;" class="text-center">Try Out</th>
                                        <th style="width: 100px;" class="text-center">Removal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 0; $i <= 5; $i++)
                                    <tr>
                                        <td class="text-center"><strong>U{{ $i }}</strong></td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" 
                                                   name="utility_isolations[{{ $i }}][description]" 
                                                   value="{{ old('utility_isolations.'.$i.'.description') }}" 
                                                   placeholder="Deskripsi isolasi...">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="utility_isolations[{{ $i }}][stop_isolate]" 
                                                   value="1" {{ old('utility_isolations.'.$i.'.stop_isolate') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="utility_isolations[{{ $i }}][lock_tag]" 
                                                   value="1" {{ old('utility_isolations.'.$i.'.lock_tag') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="utility_isolations[{{ $i }}][zero_energy]" 
                                                   value="1" {{ old('utility_isolations.'.$i.'.zero_energy') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="utility_isolations[{{ $i }}][try_out]" 
                                                   value="1" {{ old('utility_isolations.'.$i.'.try_out') ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="utility_isolations[{{ $i }}][removal]" 
                                                   value="1" {{ old('utility_isolations.'.$i.'.removal') ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <!-- Energy Control Method -->
                        <div class="row">
                            <div class="col-12">
                                <label for="utility_energy_control_method" class="form-label">Metode untuk mengendalikan energi yang tersimpan:</label>
                                <textarea class="form-control" id="utility_energy_control_method" 
                                          name="utility_energy_control_method" rows="4" 
                                          placeholder="Jelaskan metode pengendalian energi...">{{ old('utility_energy_control_method') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Isolasi Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-clipboard-check me-2"></i>Verifikasi Isolasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Daerah yang terpengaruh -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="affected_area" class="form-label mb-0" style="flex: 1;">Daerah yang akan terpengaruh oleh isolasi?</label>
                                <input type="text" class="form-control" id="affected_area" name="affected_area" 
                                       style="max-width: 400px;" value="{{ old('affected_area') }}">
                            </div>
                        </div>

                        <!-- Question 1 -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <p class="mb-0" style="flex: 1;">Semua individu yang terkena dampak (termasuk yang tidak berada di area terdekat) diberitahu tentang isolasi, untuk tetap menjauh dan tidak mencoba mengoperasikan peralatan</p>
                                <div class="d-flex gap-3 ms-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="all_individuals_informed" id="all_individuals_informed_ya" value="ya" {{ old('all_individuals_informed') == 'ya' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="all_individuals_informed_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="all_individuals_informed" id="all_individuals_informed_tidak" value="tidak" {{ old('all_individuals_informed') == 'tidak' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="all_individuals_informed_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Question 2 -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <p class="mb-0" style="flex: 1;">Semua orang yang bekerja pada peralatan <strong class="text-danger">HARUS</strong> LOTOTO secara individual dengan kunci pribadi dan merupakan satu-satunya yang berwenang untuk melepasnya.</p>
                                <div class="d-flex gap-3 ms-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="individual_lototo_required" id="individual_lototo_required_ya" value="ya" {{ old('individual_lototo_required') == 'ya' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="individual_lototo_required_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="individual_lototo_required" id="individual_lototo_required_tidak" value="tidak" {{ old('individual_lototo_required') == 'tidak' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="individual_lototo_required_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Question 3 -->
                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <p class="mb-0" style="flex: 1;">PtW Issuer <strong class="text-danger">HARUS</strong> memiliki kunci LOTOTO pada setiap isolasi (atau kotak LoToTo terkait).</p>
                                <div class="d-flex gap-3 ms-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ptw_issuer_lototo_key" id="ptw_issuer_lototo_key_ya" value="ya" {{ old('ptw_issuer_lototo_key') == 'ya' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ptw_issuer_lototo_key_ya">Ya</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ptw_issuer_lototo_key" id="ptw_issuer_lototo_key_tidak" value="tidak" {{ old('ptw_issuer_lototo_key') == 'tidak' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ptw_issuer_lototo_key_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Line Breaking Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #fd7e14 0%, #dc6a12 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-unlink me-2"></i>Line Breaking
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Konten baris sebelumnya -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="line_content_before" class="form-label mb-0" style="flex: 1;">Konten baris sebelumnya:</label>
                                <input type="text" class="form-control" id="line_content_before" name="line_content_before" 
                                       style="max-width: 400px;" value="{{ old('line_content_before') }}">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Question 1 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Tidak ada tekanan sisa di saluran</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_no_residual_pressure" id="lb_no_residual_pressure_ya" value="ya" {{ old('lb_no_residual_pressure') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_no_residual_pressure_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_no_residual_pressure" id="lb_no_residual_pressure_tidak" value="tidak" {{ old('lb_no_residual_pressure') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_no_residual_pressure_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 2 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Katup pembuangan terbuka dan tidak tersumbat</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_drain_valve_open" id="lb_drain_valve_open_ya" value="ya" {{ old('lb_drain_valve_open') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_drain_valve_open_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_drain_valve_open" id="lb_drain_valve_open_tidak" value="tidak" {{ old('lb_drain_valve_open') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_drain_valve_open_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 3 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Emergency arrangements - showers, extinguisher</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_emergency_arrangements" id="lb_emergency_arrangements_ya" value="ya" {{ old('lb_emergency_arrangements') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_emergency_arrangements_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_emergency_arrangements" id="lb_emergency_arrangements_tidak" value="tidak" {{ old('lb_emergency_arrangements') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_emergency_arrangements_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 4 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Garis diisolasi dengan pelat atau sekop</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_line_isolated" id="lb_line_isolated_ya" value="ya" {{ old('lb_line_isolated') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_line_isolated_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_line_isolated" id="lb_line_isolated_tidak" value="tidak" {{ old('lb_line_isolated') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_line_isolated_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 5 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Garisnya kosong</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_line_empty" id="lb_line_empty_ya" value="ya" {{ old('lb_line_empty') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_line_empty_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_line_empty" id="lb_line_empty_tidak" value="tidak" {{ old('lb_line_empty') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_line_empty_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 6 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Garisnya bersih</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_line_clean" id="lb_line_clean_ya" value="ya" {{ old('lb_line_clean') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_line_clean_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_line_clean" id="lb_line_clean_tidak" value="tidak" {{ old('lb_line_clean') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_line_clean_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Question 7 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Tidak ada serat asbes/keramik ex: gasket</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_no_asbestos" id="lb_no_asbestos_ya" value="ya" {{ old('lb_no_asbestos') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_no_asbestos_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_no_asbestos" id="lb_no_asbestos_tidak" value="tidak" {{ old('lb_no_asbestos') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_no_asbestos_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 8 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">Saluran/pipa tidak butuh dukungan lebih lanjut</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_pipe_no_support_needed" id="lb_pipe_no_support_needed_ya" value="ya" {{ old('lb_pipe_no_support_needed') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_pipe_no_support_needed_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_pipe_no_support_needed" id="lb_pipe_no_support_needed_tidak" value="tidak" {{ old('lb_pipe_no_support_needed') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_pipe_no_support_needed_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question 9 -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">LoToTo/ pengurasan reservoir/kontainer terkait</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_lototo_drainage" id="lb_lototo_drainage_ya" value="ya" {{ old('lb_lototo_drainage') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_lototo_drainage_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="lb_lototo_drainage" id="lb_lototo_drainage_tidak" value="tidak" {{ old('lb_lototo_drainage') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lb_lototo_drainage_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Line purged with -->
                                <div class="mb-3">
                                    <label class="form-label">Line purged with:</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="lb_purged_air" id="lb_purged_air" value="1" {{ old('lb_purged_air') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="lb_purged_air">Air</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="lb_purged_water" id="lb_purged_water" value="1" {{ old('lb_purged_water') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="lb_purged_water">Water</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="lb_purged_n2" id="lb_purged_n2" value="1" {{ old('lb_purged_n2') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="lb_purged_n2">N2</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Control -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="lb_additional_control" class="form-label">Jika "Tidak" untuk salah satu hal di atas, berikan alasan dan jelaskan kontrol tambahan:</label>
                                <textarea class="form-control" id="lb_additional_control" name="lb_additional_control" rows="4" 
                                          placeholder="Jelaskan alasan dan kontrol tambahan...">{{ old('lb_additional_control') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-info btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Create HRA LOTO/Isolation
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
                            <li><i class="fas fa-check text-success me-2"></i>Verify isolation procedures</li>
                            <li><i class="fas fa-check text-success me-2"></i>Check lockout/tagout devices</li>
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
    // Initialize Select2 for dropdowns
    $('.searchable-select').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('body'),
        width: '100%',
        placeholder: 'Ketik untuk mencari...'
    });
    
    // Initialize end date and time on page load
    updateEndDate();
    updateEndTime();
});

function updateWorkerPhone() {
    const workerSelect = document.getElementById('worker_name');
    const phoneInput = document.getElementById('worker_phone');
    const selectedOption = workerSelect.options[workerSelect.selectedIndex];
    
    if (selectedOption && selectedOption.dataset.phone) {
        phoneInput.value = selectedOption.dataset.phone;
    } else {
        phoneInput.value = '';
    }
}

function updateEndDate() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date');
    
    if (startDate) {
        endDate.value = startDate;
    }
}

function updateEndTime() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time');
    
    if (startTime) {
        // Parse start time and add 9 hours
        const [hours, minutes] = startTime.split(':');
        const startHour = parseInt(hours);
        const endHour = (startHour + 9) % 24; // Handle overflow to next day
        
        // Format end time
        const formattedEndTime = String(endHour).padStart(2, '0') + ':' + minutes;
        endTime.value = formattedEndTime;
    }
}

// Toggle Isolation Cards
function setupIsolationToggle(checkboxId, bodyId, requiredFields) {
    const checkbox = document.getElementById(checkboxId);
    const body = document.getElementById(bodyId);
    
    if (!checkbox || !body) return;
    
    function toggleSection() {
        if (checkbox.checked) {
            body.style.display = 'block';
            // Add required to specific fields if needed
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) field.setAttribute('required', 'required');
            });
        } else {
            body.style.display = 'none';
            // Remove required from fields
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) field.removeAttribute('required');
            });
            // Also remove required from all inputs inside the body
            body.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
        }
    }
    
    checkbox.addEventListener('change', toggleSection);
    // Run on page load
    toggleSection();
}

// Initialize all toggles
document.addEventListener('DOMContentLoaded', function() {
    setupIsolationToggle('electrical_enabled', 'electrical_body', ['electrical_hv_ya']);
    setupIsolationToggle('mechanical_enabled', 'mechanical_body', []);
    setupIsolationToggle('process_enabled', 'process_body', []);
    setupIsolationToggle('utility_enabled', 'utility_body', []);
});
</script>
@endpush
@endsection
