@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Area Management</h4>
                <p class="text-muted mb-0">Manage work areas and responsible persons</p>
            </div>
            <a href="{{ route('settings.areas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Area
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('settings.areas.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Search</label>
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search by area name..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        @if($search ?? '')
                            <a href="{{ route('settings.areas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Areas Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>All Areas
                <span class="badge bg-primary ms-2">{{ $areas->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($areas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Area Name</th>
                                <th>Description</th>
                                <th>Responsible Persons</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($areas as $area)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $area->name }}</div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($area->description, 50) ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($area->responsibles->count() > 0)
                                        @foreach($area->responsibles as $responsible)
                                            <span class="badge bg-info me-1">{{ $responsible->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No responsible assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($area->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('settings.areas.edit', $area) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('settings.areas.destroy', $area) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this area?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($areas->hasPages())
                <div class="card-footer">
                    {{ $areas->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No areas found</h5>
                    <p class="text-muted mb-3">Get started by creating your first area.</p>
                    <a href="{{ route('settings.areas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Area
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
