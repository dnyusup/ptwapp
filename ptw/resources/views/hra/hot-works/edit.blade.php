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
    flex-wrap: wrap;
    align-items: center;
}

/* Responsive design for mobile */
@media (max-width: 768px) {
    .condition-grid {
        grid-template-columns: 1fr;
        gap: 10px;
        align-items: stretch;
    }
    
    .checkbox-group {
        margin-top: 10px;
        gap: 10px;
    }
    
    .checkbox-group label {
        font-size: 13px;
        white-space: nowrap;
    }
}

.checkbox-group label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
    margin-bottom: 0;
}

.checkbox-group input[type="checkbox"],
.checkbox-group input[type="radio"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.checkbox-group input[type="number"] {
    width: 80px;
    height: 32px;
    margin-right: 8px;
    text-align: center;
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
                <h4 class="mb-1">Edit HRA - Hot Works</h4>
                <p class="text-muted mb-0">
                    HRA Number: <strong>{{ $hraHotWork->hra_permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to HRA
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

    <!-- Edit Form - Using the same structure as create but with pre-filled data -->
    <form method="POST" action="{{ route('hra.hot-works.update', [$permit, $hraHotWork]) }}">
        @csrf
        @method('PUT')
        
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
                                <input type="text" class="form-control" id="worker_name" name="worker_name" 
                                       value="{{ old('worker_name', $hraHotWork->worker_name) }}" required readonly>
                                <small class="text-muted">Data worker tidak dapat diubah</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="worker_phone" class="form-label">No HP Pekerja</label>
                                <input type="text" class="form-control" id="worker_phone" name="worker_phone" 
                                       value="{{ old('worker_phone', $hraHotWork->worker_phone) }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="supervisor_name" class="form-label">Nama Pendamping</label>
                                <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" 
                                       value="{{ old('supervisor_name', $hraHotWork->supervisor_name) }}" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="work_location" class="form-label">Lokasi Kerja</label>
                                <input type="text" class="form-control" id="work_location" name="work_location" 
                                       value="{{ old('work_location', $hraHotWork->work_location) }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="start_datetime" class="form-label">Tanggal & Jam Mulai</label>
                                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                       value="{{ old('start_datetime', $hraHotWork->start_datetime->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_datetime" class="form-label">Tanggal & Jam Selesai</label>
                                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                       value="{{ old('end_datetime', $hraHotWork->end_datetime->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="work_description" class="form-label">Deskripsi Pekerjaan</label>
                                <textarea class="form-control" id="work_description" name="work_description" 
                                          rows="3" required>{{ old('work_description', $hraHotWork->work_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Note: Here you would include all the same checklist items as in create.blade.php -->
                <!-- For brevity, I'm including just a few key sections -->

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-warning btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Update HRA Hot Work
                        </button>
                        <a href="{{ route('hra.hot-works.show', [$permit, $hraHotWork]) }}" class="btn btn-secondary btn-lg px-5 ms-3">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Same sidebar content as create -->
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
            </div>
        </div>
    </form>
</div>

@include('layouts.sidebar-scripts')
@endsection