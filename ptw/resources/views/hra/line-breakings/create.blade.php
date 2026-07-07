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
                <h4 class="mb-1">Create HRA - Line Breaking</h4>
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
    <form method="POST" action="{{ route('hra.line-breakings.store', $permit) }}">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                
                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
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

                <!-- Work Area Photo Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #d63384 100%);">
                        <h5 class="mb-0" style="font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                            <i class="fas fa-camera me-2"></i>Foto Lokasi Area Kerja
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Desktop Camera Interface -->
                        <div id="desktopCameraInterface" class="d-none d-md-block">
                            <!-- No camera message -->
                            <div id="noCameraPanel" style="display: none;">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Kamera tidak tersedia di perangkat ini. Silakan gunakan HP untuk mengambil foto.
                                </div>
                            </div>
                            
                            <!-- Start camera button -->
                            <div id="cameraStartPanel">
                                <button type="button" class="btn btn-primary w-100" id="startCameraBtn">
                                    <i class="fas fa-camera me-2"></i>Buka Kamera
                                </button>
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-info-circle me-1"></i>Foto hanya bisa diambil langsung dari kamera
                                </p>
                            </div>
                            
                            <!-- Camera preview -->
                            <div id="cameraPreviewPanel" style="display: none;">
                                <video id="cameraVideo" autoplay playsinline class="w-100 rounded" style="max-height: 300px; background: #000;"></video>
                                <div class="d-flex gap-2 mt-2 justify-content-center">
                                    <button type="button" class="btn btn-success" id="capturePhotoBtn">
                                        <i class="fas fa-camera me-1"></i>Ambil Foto
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="stopCameraBtn">
                                        <i class="fas fa-times me-1"></i>Tutup Kamera
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Captured photo preview -->
                            <div id="capturedPhotoPanel" style="display: none;">
                                <img id="capturedImage" src="" alt="Captured" class="img-fluid rounded" style="max-height: 300px;">
                                <div class="d-flex gap-2 mt-2 justify-content-center">
                                    <button type="button" class="btn btn-warning" id="retakePhotoBtn">
                                        <i class="fas fa-redo me-1"></i>Ambil Ulang
                                    </button>
                                    <button type="button" class="btn btn-danger" id="removePhotoBtn">
                                        <i class="fas fa-trash me-1"></i>Hapus Foto
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Hidden canvas for capturing -->
                            <canvas id="photoCanvas" style="display: none;"></canvas>
                        </div>

                        <!-- Mobile Camera Interface -->
                        <div id="mobileCameraInterface" class="d-md-none">
                            <div id="mobilePhotoInput">
                                <input type="file" class="form-control" id="mobile_photo_input" 
                                       accept="image/*" capture="environment">
                                <p class="text-muted small mt-2 mb-0">
                                    <i class="fas fa-info-circle me-1"></i>Tekan untuk mengambil foto menggunakan kamera HP
                                </p>
                            </div>
                            
                            <div id="mobilePhotoPreview" style="display: none;">
                                <img id="mobilePreviewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                <div class="d-flex gap-2 mt-2 justify-content-center">
                                    <button type="button" class="btn btn-warning" onclick="retakeMobilePhoto()">
                                        <i class="fas fa-redo me-1"></i>Ambil Ulang
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="removeMobilePhoto()">
                                        <i class="fas fa-trash me-1"></i>Hapus Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden inputs for form submission -->
                        <input type="hidden" id="work_area_photo_data" name="work_area_photo_data">
                        <input type="file" id="work_area_photo" name="work_area_photo" style="display: none;">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-secondary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Create HRA Line Breaking
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
                            <li><i class="fas fa-check text-success me-2"></i>Check line isolation procedures</li>
                            <li><i class="fas fa-check text-success me-2"></i>Verify depressurization complete</li>
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

// ========================
// Camera Functionality
// ========================

document.getElementById('mobile_photo_input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) handleMobilePhoto(file);
});

function handleMobilePhoto(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            const MAX_W = 1280, MAX_H = 960;
            let w = img.width, h = img.height;
            if (w > MAX_W || h > MAX_H) {
                const ratio = Math.min(MAX_W / w, MAX_H / h);
                w = Math.round(w * ratio);
                h = Math.round(h * ratio);
            }
            const canvas = document.createElement('canvas');
            canvas.width = w;
            canvas.height = h;
            canvas.getContext('2d').drawImage(img, 0, 0, w, h);
            const resizedBase64 = canvas.toDataURL('image/jpeg', 0.82);
            document.getElementById('mobilePreviewImage').src = resizedBase64;
            document.getElementById('mobilePhotoInput').style.display = 'none';
            document.getElementById('mobilePhotoPreview').style.display = 'block';
            document.getElementById('work_area_photo_data').value = resizedBase64;
            canvas.toBlob(function(blob) {
                try {
                    const resizedFile = new File([blob], 'work_area_photo.jpg', { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(resizedFile);
                    document.getElementById('work_area_photo').files = dt.files;
                } catch (err) {}
            }, 'image/jpeg', 0.82);
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function retakeMobilePhoto() { resetMobilePhoto(); }
function removeMobilePhoto() { resetMobilePhoto(); }
function resetMobilePhoto() {
    document.getElementById('mobile_photo_input').value = '';
    document.getElementById('work_area_photo').value = '';
    document.getElementById('work_area_photo_data').value = '';
    document.getElementById('mobilePreviewImage').src = '';
    document.getElementById('mobilePhotoInput').style.display = 'block';
    document.getElementById('mobilePhotoPreview').style.display = 'none';
}

let cameraStream = null, capturedBlob = null;

function resetCameraInterface() {
    stopCamera();
    document.getElementById('noCameraPanel').style.display = 'none';
    document.getElementById('cameraStartPanel').style.display = 'block';
    document.getElementById('cameraPreviewPanel').style.display = 'none';
    document.getElementById('capturedPhotoPanel').style.display = 'none';
    document.getElementById('capturedImage').src = '';
    capturedBlob = null;
    document.getElementById('work_area_photo').value = '';
    document.getElementById('work_area_photo_data').value = '';
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
    const video = document.getElementById('cameraVideo');
    if (video) video.srcObject = null;
}

function showNoCameraMessage() {
    document.getElementById('cameraStartPanel').style.display = 'none';
    document.getElementById('noCameraPanel').style.display = 'block';
}

document.getElementById('startCameraBtn').addEventListener('click', async function() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        document.getElementById('cameraVideo').srcObject = cameraStream;
        document.getElementById('cameraStartPanel').style.display = 'none';
        document.getElementById('cameraPreviewPanel').style.display = 'block';
    } catch (error) {
        showNoCameraMessage();
        alert('Tidak dapat mengakses kamera. Silakan gunakan HP untuk mengambil foto.');
    }
});

document.getElementById('capturePhotoBtn').addEventListener('click', function() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('photoCanvas');
    const MAX_W = 1280, MAX_H = 720;
    let vw = video.videoWidth, vh = video.videoHeight;
    if (vw > MAX_W || vh > MAX_H) {
        const ratio = Math.min(MAX_W / vw, MAX_H / vh);
        vw = Math.round(vw * ratio);
        vh = Math.round(vh * ratio);
    }
    canvas.width = vw;
    canvas.height = vh;
    canvas.getContext('2d').drawImage(video, 0, 0, vw, vh);
    canvas.toBlob(function(blob) {
        capturedBlob = blob;
        document.getElementById('capturedImage').src = URL.createObjectURL(blob);
        stopCamera();
        document.getElementById('cameraPreviewPanel').style.display = 'none';
        document.getElementById('capturedPhotoPanel').style.display = 'block';
        const reader = new FileReader();
        reader.onloadend = () => document.getElementById('work_area_photo_data').value = reader.result;
        reader.readAsDataURL(blob);
        try {
            const dt = new DataTransfer();
            dt.items.add(new File([blob], 'work_area_photo.jpg', { type: 'image/jpeg' }));
            document.getElementById('work_area_photo').files = dt.files;
        } catch (err) {}
    }, 'image/jpeg', 0.82);
});

document.getElementById('stopCameraBtn').addEventListener('click', () => {
    stopCamera();
    document.getElementById('cameraPreviewPanel').style.display = 'none';
    document.getElementById('cameraStartPanel').style.display = 'block';
});

document.getElementById('retakePhotoBtn').addEventListener('click', () => {
    document.getElementById('capturedPhotoPanel').style.display = 'none';
    document.getElementById('cameraStartPanel').style.display = 'block';
    document.getElementById('capturedImage').src = '';
    document.getElementById('work_area_photo').value = '';
    document.getElementById('work_area_photo_data').value = '';
    capturedBlob = null;
});

document.getElementById('removePhotoBtn').addEventListener('click', () => resetCameraInterface());
</script>
@endpush
@endsection