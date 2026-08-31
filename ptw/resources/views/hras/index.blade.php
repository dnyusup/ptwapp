@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">HRA's (High Risk Activities)</h4>
                <p class="text-muted mb-0">All HRA forms across every permit to work</p>
            </div>
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
            <form method="GET" action="{{ route('hras.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-2 col-md-3">
                    <label for="type" class="form-label">HRA Type</label>
                    <select name="type" id="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($typeList as $t)
                            <option value="{{ $t['key'] }}" {{ request('type') == $t['key'] ? 'selected' : '' }}>{{ $t['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label">Work Date</label>
                    <div class="d-flex gap-1 align-items-center">
                        <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="From" onchange="this.form.submit()">
                        <span class="text-muted">-</span>
                        <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="To" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="col-lg-1 col-md-2">
                    <label for="area" class="form-label">Area</label>
                    <select name="area" id="area" class="form-select" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="HRA no, permit, worker..." value="{{ request('search') }}">
                </div>
                <div class="col-lg-2 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('hras.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- HRA Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-triangle-exclamation me-2"></i>HRA List
                </h5>
                <small class="text-muted">
                    @if(request()->hasAny(['type', 'status', 'date_from', 'date_to', 'search', 'area']))
                        <i class="fas fa-filter me-1"></i>Filtered results: <strong>{{ $hras->total() }}</strong>
                    @else
                        Total HRA: <strong>{{ $hras->total() }}</strong>
                    @endif
                </small>
            </div>
        </div>
        <div class="card-body p-0">
            @if($hras->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>HRA Number</th>
                                <th>Type</th>
                                <th>Permit</th>
                                <th>Work Title</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Worker</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hras as $hra)
                            <tr>
                                <td><span class="fw-semibold text-primary">{{ $hra['hra_permit_number'] }}</span></td>
                                <td>
                                    <span class="text-nowrap"><i class="{{ $hra['type_icon'] }} me-1 text-muted"></i>{{ $hra['type_label'] }}</span>
                                </td>
                                <td>
                                    @if($hra['permit_id'])
                                        <a href="{{ route('permits.show', $hra['permit_id']) }}" class="text-decoration-none fw-medium">
                                            {{ $hra['permit_number'] }}
                                        </a>
                                    @else
                                        {{ $hra['permit_number'] }}
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($hra['work_title'], 40) }}</td>
                                <td>{{ $hra['company'] }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($hra['location'], 30) }}</td>
                                <td>{{ $hra['worker_name'] }}</td>
                                <td class="text-nowrap">{{ $hra['start_datetime'] ? \Carbon\Carbon::parse($hra['start_datetime'])->format('d M Y H:i') : '-' }}</td>
                                <td class="text-nowrap">{{ $hra['end_datetime'] ? \Carbon\Carbon::parse($hra['end_datetime'])->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @php
                                        $approval = $hra['approval'];
                                        $status = $hra['status'];
                                    @endphp
                                    @if($approval === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($approval === 'pending')
                                        <span class="badge bg-warning">Pending Approval</span>
                                    @elseif($approval === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($status === 'completed')
                                        <span class="badge bg-primary">Completed</span>
                                    @elseif($status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @elseif($status)
                                        <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $hra['created_by'] }}</td>
                                <td class="text-center">
                                    @if($hra['show_url'])
                                        <a href="{{ $hra['show_url'] }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($hras->hasPages())
                    <div class="card-footer bg-white">
                        {{ $hras->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-triangle-exclamation fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No HRA found</h5>
                    <p class="text-muted">HRA forms created inside a permit will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.sidebar-styles')
@include('layouts.sidebar-scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('form[method="GET"]');
    const searchInput = document.getElementById('search');
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    filterForm.submit();
                }
            }, 500);
        });
    }
});
</script>

@endsection
