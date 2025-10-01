@extends('layouts.app')

@section('styles')
<style>
.checklist-section {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
}

.checklist-item {
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.checklist-item:last-child {
    border-bottom: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}

.section-title {
    background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
}

.condition-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 15px;
    align-items: center;
}

.radio-group {
    display: flex;
    gap: 15px;
}

.radio-group label {
    display: flex;
    align-items: center;
    font-size: 14px;
}

.radio-group input[type="radio"] {
    margin-right: 5px;
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
                <h4 class="mb-1">Edit HRA - Work at Heights</h4>
                <p class="text-muted mb-0">
                    HRA Permit: <strong>{{ $hra->hra_permit_number }}</strong> | Main Permit: <strong>{{ $permit->permit_number }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('hra.work-at-heights.show', [$permit, $hra]) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to HRA Details
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

    <!-- HRA Form -->
    <form method="POST" action="{{ route('hra.work-at-heights.update', [$permit, $hra]) }}">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                
                <!-- Fixed Scaffolding Section -->
                <div class="checklist-section">
                    <div class="section-title">
                        <i class="fas fa-building me-2"></i>Fixed Scaffolding
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Jika menggunakan, tandai Y (jika diisi "Y" lanjutkan ke kolom sebelah kanan):</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_checked" value="1" {{ $hra->fixed_scaffolding_checked ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="fixed_scaffolding_checked" value="0" {{ !$hra->fixed_scaffolding_checked ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Sudah disetujui oleh SHE PTBI?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="1" {{ $hra->fixed_scaffolding_approved_by_she ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="fixed_scaffolding_approved_by_she" value="0" {{ !$hra->fixed_scaffolding_approved_by_she ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Operator terlatih? Penggunaannya tertulis? Menggunakan Alat Pelindung Jatuh?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_operator_trained" value="1" {{ $hra->fixed_scaffolding_operator_trained ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="fixed_scaffolding_operator_trained" value="0" {{ !$hra->fixed_scaffolding_operator_trained ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Operator terlatih? Penggunaannya tertulis? Menggunakan Alat Pelindung Jatuh?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_usage_correct" value="1" {{ $hra->fixed_scaffolding_usage_correct ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="fixed_scaffolding_usage_correct" value="0" {{ !$hra->fixed_scaffolding_usage_correct ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Tidak ada alat lain yang bisa dipakai? Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</span>
                            <div class="radio-group">
                                <label><input type="radio" name="fixed_scaffolding_fall_protection" value="1" {{ $hra->fixed_scaffolding_fall_protection ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="fixed_scaffolding_fall_protection" value="0" {{ !$hra->fixed_scaffolding_fall_protection ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Scaffolding Section -->
                <div class="checklist-section">
                    <div class="section-title">
                        <i class="fas fa-dolly me-2"></i>Mobile Scaffolding
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Mobile scaffolding</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_scaffolding_checked" value="1" {{ $hra->mobile_scaffolding_checked ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="mobile_scaffolding_checked" value="0" {{ !$hra->mobile_scaffolding_checked ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Sudah disetujui oleh SHE PTBI?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="1" {{ $hra->mobile_scaffolding_approved_by_she ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="mobile_scaffolding_approved_by_she" value="0" {{ !$hra->mobile_scaffolding_approved_by_she ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Operator terlatih? Penggunaannya tertulis? Menggunakan Alat Pelindung Jatuh?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_scaffolding_operator_trained" value="1" {{ $hra->mobile_scaffolding_operator_trained ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="mobile_scaffolding_operator_trained" value="0" {{ !$hra->mobile_scaffolding_operator_trained ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Operator terlatih? Penggunaannya tertulis? Menggunakan Alat Pelindung Jatuh?</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_scaffolding_usage_correct" value="1" {{ $hra->mobile_scaffolding_usage_correct ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="mobile_scaffolding_usage_correct" value="0" {{ !$hra->mobile_scaffolding_usage_correct ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checklist-item">
                        <div class="condition-grid">
                            <span>Tidak ada alat lain yang bisa dipakai? Gunakan untuk aktivitas jangka pendek dengan potensi bahaya minor</span>
                            <div class="radio-group">
                                <label><input type="radio" name="mobile_scaffolding_fall_protection" value="1" {{ $hra->mobile_scaffolding_fall_protection ? 'checked' : '' }}> Y</label>
                                <label><input type="radio" name="mobile_scaffolding_fall_protection" value="0" {{ !$hra->mobile_scaffolding_fall_protection ? 'checked' : '' }}> N</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save me-2"></i>Update HRA Permit
                        </button>
                        <a href="{{ route('hra.work-at-heights.show', [$permit, $hra]) }}" class="btn btn-secondary btn-lg px-5 ms-3">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- HRA Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i>HRA Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>HRA Number:</strong><br>{{ $hra->hra_permit_number }}</p>
                        <p><strong>Type:</strong><br>Work at Heights</p>
                        <p><strong>Created:</strong><br>{{ $hra->created_at->format('d M Y H:i') }}</p>
                        <p><strong>Last Updated:</strong><br>{{ $hra->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

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
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Important Notes
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>All safety requirements must be met</li>
                            <li><i class="fas fa-check text-success me-2"></i>Training certificates must be valid</li>
                            <li><i class="fas fa-check text-success me-2"></i>Equipment inspection required</li>
                            <li><i class="fas fa-check text-success me-2"></i>SHE approval is mandatory</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('layouts.sidebar-scripts')
@endsection
