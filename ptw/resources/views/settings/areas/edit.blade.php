@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
.select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
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
                <h4 class="mb-1"><i class="fas fa-edit me-2 text-primary"></i>Edit Area</h4>
                <p class="text-muted mb-0">Update area information</p>
            </div>
            <a href="{{ route('settings.areas.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Areas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Area Information
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('settings.areas.update', $area) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Area Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $area->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $area->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="responsibles" class="form-label">Responsible Persons (EHS) <span class="text-danger">*</span></label>
                            <select class="form-select @error('responsibles') is-invalid @enderror" 
                                    id="responsibles" name="responsibles[]" multiple>
                                @foreach($ehsUsers as $user)
                                    <option value="{{ $user->id }}" 
                                            {{ in_array($user->id, old('responsibles', $area->responsibles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('responsibles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>Select one or more EHS department users as responsible persons for this area.</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       {{ old('is_active', $area->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <div class="form-text">
                                <small>Inactive areas will not be shown in selection lists.</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Area
                            </button>
                            <a href="{{ route('settings.areas.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Help
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        <strong>Area Name:</strong> Enter a unique name for the work area (e.g., "Compressor Room", "Cooling Tower", etc.)
                    </p>
                    <p class="text-muted small mb-2">
                        <strong>Description:</strong> Optional detailed description of the area.
                    </p>
                    <p class="text-muted small mb-0">
                        <strong>Responsible Persons:</strong> Select EHS department users who are responsible for this area. They will receive notifications related to permits in this area.
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Deleting this area will remove it permanently. This action cannot be undone.
                    </p>
                    <form action="{{ route('settings.areas.destroy', $area) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this area? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash me-2"></i>Delete Area
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#responsibles').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select responsible persons...',
        allowClear: true
    });
});
</script>
@endpush
