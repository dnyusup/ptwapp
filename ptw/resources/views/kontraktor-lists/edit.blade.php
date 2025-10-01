@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-edit me-2 text-primary"></i>Edit Kontraktor</h4>
                <p class="text-muted mb-0">Edit {{ $kontraktorList->company_name }}</p>
            </div>
            <a href="{{ route('kontraktor-lists.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('kontraktor-lists.update', $kontraktorList) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" name="company_name" value="{{ old('company_name', $kontraktorList->company_name) }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="company_code" class="form-label">Company Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_code') is-invalid @enderror" 
                               id="company_code" name="company_code" value="{{ old('company_code', $kontraktorList->company_code) }}" required>
                        @error('company_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Unique code for this company</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="3">{{ old('address', $kontraktorList->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                               id="contact_person" name="contact_person" value="{{ old('contact_person', $kontraktorList->contact_person) }}">
                        @error('contact_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $kontraktorList->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $kontraktorList->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $kontraktorList->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Status
                            </label>
                        </div>
                    </div>
                </div>

                @if($kontraktorList->users()->count() > 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Associated Users:</strong> This contractor has {{ $kontraktorList->users()->count() }} user(s) associated with it.
                </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kontraktor-lists.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Kontraktor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')
@endsection
