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
                                       value="{{ old('machine_tank_name') }}" placeholder="Masukkan nama atau nomor mesin/tangki" required>
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
                                                    <input type="checkbox" class="form-check-input" name="panel_listrik_mati" value="1" {{ old('panel_listrik_mati') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="panel_listrik_dikunci" value="1" {{ old('panel_listrik_dikunci') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="panel_listrik_diperiksa" value="1" {{ old('panel_listrik_diperiksa') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="panel_listrik_dipasang_tag" value="1" {{ old('panel_listrik_dipasang_tag') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Pneumatic -->
                                            <tr>
                                                <td style="font-weight: 500;">Pneumatic</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="pneumatic_mati" value="1" {{ old('pneumatic_mati') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="pneumatic_dikunci" value="1" {{ old('pneumatic_dikunci') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="pneumatic_diperiksa" value="1" {{ old('pneumatic_diperiksa') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="pneumatic_dipasang_tag" value="1" {{ old('pneumatic_dipasang_tag') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Hydraulic -->
                                            <tr>
                                                <td style="font-weight: 500;">Hydraulic</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="hydraulic_mati" value="1" {{ old('hydraulic_mati') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="hydraulic_dikunci" value="1" {{ old('hydraulic_dikunci') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="hydraulic_diperiksa" value="1" {{ old('hydraulic_diperiksa') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="hydraulic_dipasang_tag" value="1" {{ old('hydraulic_dipasang_tag') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Gravitasi -->
                                            <tr>
                                                <td style="font-weight: 500;">Gravitasi</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="gravitasi_mati" value="1" {{ old('gravitasi_mati') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="gravitasi_dikunci" value="1" {{ old('gravitasi_dikunci') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="gravitasi_diperiksa" value="1" {{ old('gravitasi_diperiksa') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="gravitasi_dipasang_tag" value="1" {{ old('gravitasi_dipasang_tag') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Spring/Per -->
                                            <tr>
                                                <td style="font-weight: 500;">Spring/Per</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="spring_per_mati" value="1" {{ old('spring_per_mati') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="spring_per_dikunci" value="1" {{ old('spring_per_dikunci') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="spring_per_diperiksa" value="1" {{ old('spring_per_diperiksa') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="spring_per_dipasang_tag" value="1" {{ old('spring_per_dipasang_tag') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Rotasi/Gerakan -->
                                            <tr>
                                                <td style="font-weight: 500;">Rotasi/Gerakan</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="rotasi_gerakan_mati" value="1" {{ old('rotasi_gerakan_mati') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="rotasi_gerakan_dikunci" value="1" {{ old('rotasi_gerakan_dikunci') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="rotasi_gerakan_diperiksa" value="1" {{ old('rotasi_gerakan_diperiksa') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="rotasi_gerakan_dipasang_tag" value="1" {{ old('rotasi_gerakan_dipasang_tag') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Isolasi Listrik Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #fd7e14 0%, #e55d00 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-bolt me-2"></i>Isolasi Listrik
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        
                        <!-- Note -->
                        <div class="alert alert-info mb-4" style="border-left: 4px solid #17a2b8;">
                            <i class="fas fa-info-circle me-2"></i>
                            <small><em>Pekerjaan harus dilakukan oleh orang yang kompeten</em></small>
                        </div>

                        <!-- Left Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3" style="font-weight: 600; color: #495057;">Panel Listrik:</h6>
                                
                                <!-- Bekerja pada panel listrik -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label" style="font-weight: 500;">Bekerja pada panel listrik?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bekerja_panel_listrik" id="panel_listrik_ya" value="ya" {{ old('bekerja_panel_listrik') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="panel_listrik_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bekerja_panel_listrik" id="panel_listrik_tidak" value="tidak" {{ old('bekerja_panel_listrik') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="panel_listrik_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Referensi Manual -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="referensi_manual_panel" class="form-label" style="font-weight: 500;">Referensi Manual</label>
                                        <input type="text" class="form-control" id="referensi_manual_panel" name="referensi_manual_panel" 
                                               value="{{ old('referensi_manual_panel') }}" placeholder="Masukkan referensi manual">
                                    </div>
                                </div>

                                <!-- Panel Listrik Items -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <!-- Saklar diposisi OFF -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Saklar diposisi "OFF" dan digembok?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="saklar_diposisi_off" id="saklar_off_ya" value="ya" {{ old('saklar_diposisi_off') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="saklar_off_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="saklar_diposisi_off" id="saklar_off_tidak" value="tidak" {{ old('saklar_diposisi_off') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="saklar_off_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tag dipasang -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Tag dipasang?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tag_dipasang_panel" id="tag_panel_ya" value="ya" {{ old('tag_dipasang_panel') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tag_panel_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tag_dipasang_panel" id="tag_panel_tidak" value="tidak" {{ old('tag_dipasang_panel') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tag_panel_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sekring/CB dimatikan -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Sekring/CB dimatikan/dicabut?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sekring_cb_dimatikan" id="sekring_cb_ya" value="ya" {{ old('sekring_cb_dimatikan') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="sekring_cb_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sekring_cb_dimatikan" id="sekring_cb_tidak" value="tidak" {{ old('sekring_cb_dimatikan') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="sekring_cb_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Panel OFF -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Panel OFF?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="panel_off_panel" id="panel_off_ya" value="ya" {{ old('panel_off_panel') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="panel_off_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="panel_off_panel" id="panel_off_tidak" value="tidak" {{ old('panel_off_panel') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="panel_off_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3" style="font-weight: 600; color: #495057;">Sistem Mekanis:</h6>
                                
                                <!-- Bekerja pada sistem mekanis -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label" style="font-weight: 500;">Bekerja pada sistem mekanis?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bekerja_sistem_mekanis" id="sistem_mekanis_ya" value="ya" {{ old('bekerja_sistem_mekanis') == 'ya' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sistem_mekanis_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bekerja_sistem_mekanis" id="sistem_mekanis_tidak" value="tidak" {{ old('bekerja_sistem_mekanis') == 'tidak' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sistem_mekanis_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Referensi Manual -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="referensi_manual_sistem" class="form-label" style="font-weight: 500;">Referensi Manual</label>
                                        <input type="text" class="form-control" id="referensi_manual_sistem" name="referensi_manual_sistem" 
                                               value="{{ old('referensi_manual_sistem') }}" placeholder="Masukkan referensi manual">
                                    </div>
                                </div>

                                <!-- Sistem Mekanis Items -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <!-- Safety switch 3-phase -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Safety switch 3-phase diposisi "OFF" dan digembok?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="safety_switch_off" id="safety_switch_ya" value="ya" {{ old('safety_switch_off') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="safety_switch_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="safety_switch_off" id="safety_switch_tidak" value="tidak" {{ old('safety_switch_off') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="safety_switch_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tag dipasang sistem -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Tag dipasang?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tag_dipasang_sistem" id="tag_sistem_ya" value="ya" {{ old('tag_dipasang_sistem') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tag_sistem_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tag_dipasang_sistem" id="tag_sistem_tidak" value="tidak" {{ old('tag_dipasang_sistem') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="tag_sistem_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sekring/CB sistem -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Sekring/CB dimatikan/dicabut?</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sekring_cb_sistem_dimatikan" id="sekring_sistem_ya" value="ya" {{ old('sekring_cb_sistem_dimatikan') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="sekring_sistem_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sekring_cb_sistem_dimatikan" id="sekring_sistem_tidak" value="tidak" {{ old('sekring_cb_sistem_dimatikan') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="sekring_sistem_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sudah dicoba dinyalakan -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Sudah dicoba dinyalakan dan terbukti 'OFF'</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sudah_dicoba_dinyalakan" id="dicoba_nyala_ya" value="ya" {{ old('sudah_dicoba_dinyalakan') == 'ya' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="dicoba_nyala_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sudah_dicoba_dinyalakan" id="dicoba_nyala_tidak" value="tidak" {{ old('sudah_dicoba_dinyalakan') == 'tidak' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="dicoba_nyala_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tes Listrik Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-bolt me-2"></i>Tes Listrik
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        
                        <div class="row">
                            <!-- Left Section -->
                            <div class="col-md-6">
                                <!-- Membutuhkan tes dengan listrik ON -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-8">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Membutuhkan tes dengan listrik ON?</label>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="membutuhkan_tes_listrik_on" id="tes_listrik_ya" value="ya" {{ old('membutuhkan_tes_listrik_on') == 'ya' ? 'checked' : '' }} onchange="toggleTestListrikFields()">
                                            <label class="form-check-label" for="tes_listrik_ya" style="font-size: 12px;">Ya</label>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="membutuhkan_tes_listrik_on" id="tes_listrik_tidak" value="tidak" {{ old('membutuhkan_tes_listrik_on') == 'tidak' ? 'checked' : '' }} onchange="toggleTestListrikFields()">
                                            <label class="form-check-label" for="tes_listrik_tidak" style="font-size: 12px;">Tidak</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jika Y detailkan item di bawah -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label style="font-size: 13px; font-weight: 500; margin-bottom: 10px;">Jika "Y" detailkan item di bawah:</label>
                                        
                                        <!-- Safety barrier -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Safety barrier</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="safety_barrier" id="safety_barrier_ya" value="ya" {{ old('safety_barrier') == 'ya' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="safety_barrier_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="safety_barrier" id="safety_barrier_tidak" value="tidak" {{ old('safety_barrier') == 'tidak' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="safety_barrier_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Full face protection -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Full face protection</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="full_face_protection" id="full_face_ya" value="ya" {{ old('full_face_protection') == 'ya' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="full_face_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="full_face_protection" id="full_face_tidak" value="tidak" {{ old('full_face_protection') == 'tidak' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="full_face_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Insulated gloves -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Insulated gloves</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="insulated_gloves" id="gloves_ya" value="ya" {{ old('insulated_gloves') == 'ya' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="gloves_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="insulated_gloves" id="gloves_tidak" value="tidak" {{ old('insulated_gloves') == 'tidak' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="gloves_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Insulated mat -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Insulated mat</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="insulated_mat" id="mat_ya" value="ya" {{ old('insulated_mat') == 'ya' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="mat_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="insulated_mat" id="mat_tidak" value="tidak" {{ old('insulated_mat') == 'tidak' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="mat_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Full length sleeves -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Full length sleeves</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="full_length_sleeves" id="sleeves_ya" value="ya" {{ old('full_length_sleeves') == 'ya' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="sleeves_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="full_length_sleeves" id="sleeves_tidak" value="tidak" {{ old('full_length_sleeves') == 'tidak' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="sleeves_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tool insulation satisfactory -->
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-8">
                                                <label style="font-size: 13px; margin-bottom: 0;">Tool insulation satisfactory</label>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="tool_insulation_satisfactory" id="tool_ya" value="ya" {{ old('tool_insulation_satisfactory') == 'ya' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="tool_ya" style="font-size: 12px;">Ya</label>
                                                </div>
                                            </div>
                                            <div class="col-2 text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input tes-listrik-field" type="radio" name="tool_insulation_satisfactory" id="tool_tidak" value="tidak" {{ old('tool_insulation_satisfactory') == 'tidak' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="tool_tidak" style="font-size: 12px;">Tidak</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="col-md-6">
                                <!-- Maximum voltage -->
                                <div class="row mb-3">
                                    <div class="col-8">
                                        <label for="maximum_voltage" class="form-label" style="font-weight: 500;">maximum voltage?</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input type="number" class="form-control tes-listrik-field" id="maximum_voltage" name="maximum_voltage" 
                                                   value="{{ old('maximum_voltage') }}" placeholder="0" disabled>
                                            <span class="input-group-text" style="font-size: 12px;">Volt</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alasan untuk live test -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="alasan_live_test" class="form-label" style="font-weight: 500; color: #dc3545;">Alasan untuk live test: MANDATORY</label>
                                        <textarea class="form-control tes-listrik-field" id="alasan_live_test" name="alasan_live_test" rows="4" 
                                                  placeholder="Masukkan alasan mengapa live test diperlukan..." disabled>{{ old('alasan_live_test') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Isolasi Utility Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-wrench me-2"></i>Isolasi Utility (pilih yang relevan)
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        
                        <!-- Utility Isolation Matrix -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size: 14px;">
                                        <thead style="background-color: #e9ecef;">
                                            <tr>
                                                <th style="width: 25%; font-weight: 600;">Medium</th>
                                                <th style="width: 18.75%; text-align: center; font-weight: 600;">off</th>
                                                <th style="width: 18.75%; text-align: center; font-weight: 600;">secured/locked</th>
                                                <th style="width: 18.75%; text-align: center; font-weight: 600;">checked</th>
                                                <th style="width: 18.75%; text-align: center; font-weight: 600;">tagged</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Listrik -->
                                            <tr>
                                                <td style="font-weight: 500;">Listrik</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_listrik_off" value="1" {{ old('utility_listrik_off') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_listrik_secured" value="1" {{ old('utility_listrik_secured') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_listrik_checked" value="1" {{ old('utility_listrik_checked') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_listrik_tagged" value="1" {{ old('utility_listrik_tagged') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Cooling water -->
                                            <tr>
                                                <td style="font-weight: 500;">Cooling water</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_cooling_water_off" value="1" {{ old('utility_cooling_water_off') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_cooling_water_secured" value="1" {{ old('utility_cooling_water_secured') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_cooling_water_checked" value="1" {{ old('utility_cooling_water_checked') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_cooling_water_tagged" value="1" {{ old('utility_cooling_water_tagged') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Oil Hidrolik -->
                                            <tr>
                                                <td style="font-weight: 500;">Oil Hidrolik</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_oil_hidrolik_off" value="1" {{ old('utility_oil_hidrolik_off') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_oil_hidrolik_secured" value="1" {{ old('utility_oil_hidrolik_secured') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_oil_hidrolik_checked" value="1" {{ old('utility_oil_hidrolik_checked') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_oil_hidrolik_tagged" value="1" {{ old('utility_oil_hidrolik_tagged') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Kompresor -->
                                            <tr>
                                                <td style="font-weight: 500;">Kompresor</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_kompresor_off" value="1" {{ old('utility_kompresor_off') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_kompresor_secured" value="1" {{ old('utility_kompresor_secured') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_kompresor_checked" value="1" {{ old('utility_kompresor_checked') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_kompresor_tagged" value="1" {{ old('utility_kompresor_tagged') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Vacuum -->
                                            <tr>
                                                <td style="font-weight: 500;">Vacuum</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_vacuum_off" value="1" {{ old('utility_vacuum_off') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_vacuum_secured" value="1" {{ old('utility_vacuum_secured') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_vacuum_checked" value="1" {{ old('utility_vacuum_checked') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_vacuum_tagged" value="1" {{ old('utility_vacuum_tagged') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Gas -->
                                            <tr>
                                                <td style="font-weight: 500;">Gas</td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_gas_off" value="1" {{ old('utility_gas_off') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_gas_secured" value="1" {{ old('utility_gas_secured') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_gas_checked" value="1" {{ old('utility_gas_checked') ? 'checked' : '' }}>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input" name="utility_gas_tagged" value="1" {{ old('utility_gas_tagged') ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            
                                            <!-- Lainnya -->
                                            <tr>
                                                <td style="font-weight: 500;">
                                                    Lainnya: 
                                                    <input type="text" class="form-control form-control-sm mt-1" id="utility_lainnya_nama" name="utility_lainnya_nama" 
                                                           value="{{ old('utility_lainnya_nama') }}" placeholder="Sebutkan..." style="font-size: 12px;" 
                                                           oninput="toggleLainnyaCheckboxes()">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input utility-lainnya-checkbox" name="utility_lainnya_off" value="1" {{ old('utility_lainnya_off') ? 'checked' : '' }} disabled>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input utility-lainnya-checkbox" name="utility_lainnya_secured" value="1" {{ old('utility_lainnya_secured') ? 'checked' : '' }} disabled>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input utility-lainnya-checkbox" name="utility_lainnya_checked" value="1" {{ old('utility_lainnya_checked') ? 'checked' : '' }} disabled>
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" class="form-check-input utility-lainnya-checkbox" name="utility_lainnya_tagged" value="1" {{ old('utility_lainnya_tagged') ? 'checked' : '' }} disabled>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Error message for utility lainnya validation -->
                                @error('utility_lainnya_checkboxes')
                                    <div class="alert alert-danger mt-2" style="font-size: 13px;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Line diisolasi dengan plat -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-6">
                                <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Line diisolasi dengan plat?</label>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="line_diisolasi_plat" id="line_plat_ya" value="ya" {{ old('line_diisolasi_plat') == 'ya' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="line_plat_ya" style="font-size: 12px;">Ya</label>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="line_diisolasi_plat" id="line_plat_tidak" value="tidak" {{ old('line_diisolasi_plat') == 'tidak' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="line_plat_tidak" style="font-size: 12px;">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- Alasan dan deskripsi isolasi -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="alasan_deskripsi_isolasi" class="form-label" style="font-weight: 500;">Jika "Tidak" berikan alasannya dan deskripsikan bagaimana isolasi akan dilakukan:</label>
                                <textarea class="form-control" id="alasan_deskripsi_isolasi" name="alasan_deskripsi_isolasi" rows="3" 
                                          placeholder="Masukkan alasan dan deskripsi isolasi...">{{ old('alasan_deskripsi_isolasi') }}</textarea>
                            </div>
                        </div>

                        <!-- Area yang terdampak isolasi -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="area_terdampak_isolasi" class="form-label" style="font-weight: 500;">Area yang terdampak isolasi?</label>
                                <input type="text" class="form-control" id="area_terdampak_isolasi" name="area_terdampak_isolasi" 
                                       value="{{ old('area_terdampak_isolasi') }}" placeholder="Sebutkan area yang terdampak...">
                            </div>
                        </div>

                        <!-- Apakah area tersebut sudah diberitahu -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-6">
                                <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Apakah area tersebut sudah diberitahu?</label>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="area_sudah_diberitahu" id="area_diberitahu_ya" value="ya" {{ old('area_sudah_diberitahu') == 'ya' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_diberitahu_ya" style="font-size: 12px;">Ya</label>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="area_sudah_diberitahu" id="area_diberitahu_tidak" value="tidak" {{ old('area_sudah_diberitahu') == 'tidak' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="area_diberitahu_tidak" style="font-size: 12px;">Tidak</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mematikan Pipa Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-exclamation-triangle me-2"></i>Mematikan Pipa
                        </h5>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 25px;">
                        
                        <!-- Isi dari line/pipa -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="isi_line_pipa" class="form-label" style="font-weight: 500;">Isi dari line/pipa:</label>
                                <input type="text" class="form-control" id="isi_line_pipa" name="isi_line_pipa" 
                                       value="{{ old('isi_line_pipa') }}" placeholder="Sebutkan isi dari line/pipa...">
                            </div>
                        </div>

                        <!-- Left Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Tidak ada sisa tekanan dalam pipa -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-8">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Tidak ada sisa tekanan dalam pipa</label>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tidak_ada_sisa_tekanan" id="sisa_tekanan_ya" value="ya" {{ old('tidak_ada_sisa_tekanan') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sisa_tekanan_ya" style="font-size: 12px;">Ya</label>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tidak_ada_sisa_tekanan" id="sisa_tekanan_tidak" value="tidak" {{ old('tidak_ada_sisa_tekanan') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="sisa_tekanan_tidak" style="font-size: 12px;">Tidak</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Drain/bleed valves terbuka dan tidak terblok -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-8">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Drain/bleed valves terbuka dan tidak terblok</label>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="drain_bleed_valves" id="drain_valves_ya" value="ya" {{ old('drain_bleed_valves') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="drain_valves_ya" style="font-size: 12px;">Ya</label>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="drain_bleed_valves" id="drain_valves_tidak" value="tidak" {{ old('drain_bleed_valves') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="drain_valves_tidak" style="font-size: 12px;">Tidak</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pipa di-purged dengan -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 10px;">Pipa di-purged dengan:</label>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="pipa_purged_udara" id="purged_udara" value="1" {{ old('pipa_purged_udara') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="purged_udara" style="font-size: 13px;">Udara</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="pipa_purged_air" id="purged_air" value="1" {{ old('pipa_purged_air') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="purged_air" style="font-size: 13px;">Air</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="pipa_purged_nitrogen" id="purged_nitrogen" value="1" {{ old('pipa_purged_nitrogen') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="purged_nitrogen" style="font-size: 13px;">Nitrogen</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pipa diisolasi dengan plat -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-8">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Pipa diisolasi dengan plat</label>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pipa_diisolasi_plat" id="pipa_plat_ya" value="ya" {{ old('pipa_diisolasi_plat') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pipa_plat_ya" style="font-size: 12px;">Ya</label>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pipa_diisolasi_plat" id="pipa_plat_tidak" value="tidak" {{ old('pipa_diisolasi_plat') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pipa_plat_tidak" style="font-size: 12px;">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Pipa Kosong -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-8">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Pipa Kosong</label>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pipa_kosong" id="pipa_kosong_ya" value="ya" {{ old('pipa_kosong') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pipa_kosong_ya" style="font-size: 12px;">Ya</label>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pipa_kosong" id="pipa_kosong_tidak" value="tidak" {{ old('pipa_kosong') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pipa_kosong_tidak" style="font-size: 12px;">Tidak</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pipa Bersih -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-8">
                                        <label style="font-size: 14px; font-weight: 500; margin-bottom: 0;">Pipa Bersih</label>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pipa_bersih" id="pipa_bersih_ya" value="ya" {{ old('pipa_bersih') == 'ya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pipa_bersih_ya" style="font-size: 12px;">Ya</label>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pipa_bersih" id="pipa_bersih_tidak" value="tidak" {{ old('pipa_bersih') == 'tidak' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pipa_bersih_tidak" style="font-size: 12px;">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alasan dan deskripsi isolasi pipa -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="alasan_deskripsi_isolasi_pipa" class="form-label" style="font-weight: 500;">Jika "Tidak" berikan alasan dan deskripsikan bagaimana isolasi dilakukan:</label>
                                <textarea class="form-control" id="alasan_deskripsi_isolasi_pipa" name="alasan_deskripsi_isolasi_pipa" rows="3" 
                                          placeholder="Masukkan alasan dan deskripsi isolasi pipa...">{{ old('alasan_deskripsi_isolasi_pipa') }}</textarea>
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

function toggleTestListrikFields() {
    const tesListrikYa = document.getElementById('tes_listrik_ya');
    const tesListrikFields = document.querySelectorAll('.tes-listrik-field');
    const alasanTextarea = document.getElementById('alasan_live_test');
    
    if (tesListrikYa.checked) {
        // Enable all fields when "Ya" is selected
        tesListrikFields.forEach(field => {
            field.disabled = false;
        });
        // Make alasan required when "Ya" is selected
        alasanTextarea.required = true;
    } else {
        // Disable all fields when "Tidak" is selected or nothing is selected
        tesListrikFields.forEach(field => {
            field.disabled = true;
            // Clear the field values when disabled
            if (field.type === 'radio') {
                field.checked = false;
            } else {
                field.value = '';
            }
        });
        // Remove required attribute when "Tidak" is selected
        alasanTextarea.required = false;
    }
}

function toggleLainnyaCheckboxes() {
    const lainnyaInput = document.getElementById('utility_lainnya_nama');
    const lainnyaCheckboxes = document.querySelectorAll('.utility-lainnya-checkbox');
    
    // Check if input has at least 5 characters
    if (lainnyaInput.value.trim().length >= 5) {
        // Enable all checkboxes when input has at least 5 characters
        lainnyaCheckboxes.forEach(checkbox => {
            checkbox.disabled = false;
        });
    } else {
        // Disable all checkboxes and clear them when input is less than 5 characters
        lainnyaCheckboxes.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.checked = false; // Clear the checkbox when disabled
        });
    }
}

// Initialize the state on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTestListrikFields();
    toggleLainnyaCheckboxes(); // Initialize lainnya checkboxes state
});
</script>
@endpush
@endsection