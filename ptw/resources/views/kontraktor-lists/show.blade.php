@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-building me-2 text-primary"></i>{{ $kontraktorList->company_name }}</h4>
                <p class="text-muted mb-0">Kontraktor Details</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('kontraktor-lists.edit', $kontraktorList) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                <a href="{{ route('kontraktor-lists.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Company Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Company Name:</strong>
                            <p class="mb-0">{{ $kontraktorList->company_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Company Code:</strong>
                            <p class="mb-0"><span class="badge bg-secondary">{{ $kontraktorList->company_code }}</span></p>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Address:</strong>
                            <p class="mb-0">{{ $kontraktorList->address ?: 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Contact Person:</strong>
                            <p class="mb-0">{{ $kontraktorList->contact_person ?: 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phone:</strong>
                            <p class="mb-0">{{ $kontraktorList->phone ?: 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong>
                            <p class="mb-0">{{ $kontraktorList->email ?: 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong>
                            <p class="mb-0">
                                @if($kontraktorList->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Users -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Associated Users</h5>
                </div>
                <div class="card-body">
                    @if($kontraktorList->users()->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($kontraktorList->users as $user)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $user->name }}</h6>
                                            <p class="mb-1 text-muted small">{{ $user->email }}</p>
                                            <small class="text-muted">{{ ucfirst($user->role) }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ ucfirst($user->role) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <small class="text-muted">Total: {{ $kontraktorList->users()->count() }} user(s)</small>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No users associated with this contractor yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Activity Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Created:</strong>
                    <p class="mb-0">{{ $kontraktorList->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Last Updated:</strong>
                    <p class="mb-0">{{ $kontraktorList->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')
@endsection
