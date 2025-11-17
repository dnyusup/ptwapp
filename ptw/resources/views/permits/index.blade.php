@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Permits to Work</h4>
                <p class="text-muted mb-0">Manage your permits to work</p>
            </div>
            <a href="{{ route('permits.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Permit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('permits.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="risk_level" class="form-label">Risk Level</label>
                    <select name="risk_level" id="risk_level" class="form-select">
                        <option value="">All Risk Levels</option>
                        <option value="low" {{ request('risk_level') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('risk_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('risk_level') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('risk_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Search by title or location..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Permits Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-file-alt me-2"></i>Permits List
            </h5>
        </div>
        <div class="card-body p-0">
            @if($permits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Permit Number</th>
                                <th>Work Title</th>
                                <th>Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permits as $permit)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-primary">{{ $permit->permit_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $permit->work_title }}</div>
                                    <small class="text-muted">{{ Str::limit($permit->work_description, 50) }}</small>
                                </td>
                                <td>{{ $permit->work_location ?: 'Not specified' }}</td>
                                <td>{{ $permit->start_date ? $permit->start_date->format('d M Y') : 'Not set' }}</td>
                                <td>{{ $permit->end_date ? $permit->end_date->format('d M Y') : 'Not set' }}</td>
                                <td>
                                    @if($permit->status === 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($permit->status === 'pending_approval')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($permit->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($permit->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($permit->status === 'expired')
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif($permit->status === 'pending_extension_approval')
                                        <span class="badge bg-warning">Pending Extension</span>
                                    @elseif($permit->status === 'in_progress')
                                        <span class="badge bg-info">In Progress</span>
                                    @elseif($permit->status === 'completed')
                                        <span class="badge bg-primary">Completed</span>
                                    @elseif($permit->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($permit->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $permit->user ? $permit->user->name : 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('permits.show', $permit) }}" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($permit->user_id == auth()->id() || auth()->user()->role === 'administrator')
                                            <a href="{{ route('permits.edit', $permit) }}" 
                                               class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('permits.destroy', $permit) }}" 
                                                  class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($permits->hasPages())
                    <div class="card-footer bg-white">
                        {{ $permits->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No permits found</h5>
                    <p class="text-muted">Create your first permit to work to get started.</p>
                    <a href="{{ route('permits.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Permit
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.sidebar-styles')
@include('layouts.sidebar-scripts')
@endsection
