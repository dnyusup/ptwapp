@extends('layouts.app')

@section('content')
<!-- Mobile Menu Toggle -->
<button class="mobile-menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="text-center">
        <i class="fas fa-hard-hat fa-2x"></i>
        <h5>PTW System</h5>
        <small>{{ ucfirst(auth()->user()->role) }}</small>
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link active" href="{{ route('dashboard') }}">
            <i class="fas fa-dashboard"></i>Dashboard
        </a>
        <a class="nav-link" href="{{ route('permits.index') }}">
            <i class="fas fa-file-alt"></i>Permits
        </a>
        <a class="nav-link" href="{{ route('permits.create') }}">
            <i class="fas fa-plus"></i>New Permit
        </a>
        @if(auth()->user()->role === 'administrator')
        <a class="nav-link" href="#">
            <i class="fas fa-users"></i>Users
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-chart-bar"></i>Reports
        </a>
        @endif
    </nav>

    <div class="mt-auto p-3">
        <div class="d-grid">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Header Dashboard -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2>Dashboard</h2>
                <p>Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
                <a href="{{ route('permits.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>New Permit
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card primary">
                <div class="card-body">
                    <div class="stats-number">{{ $stats['total_permits'] }}</div>
                    <div class="stats-label">Total Permits</div>
                    <i class="fas fa-file-alt stats-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card warning">
                <div class="card-body">
                    <div class="stats-number">{{ $stats['pending_permits'] }}</div>
                    <div class="stats-label">Pending</div>
                    <i class="fas fa-clock stats-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card success">
                <div class="card-body">
                    <div class="stats-number">{{ $stats['approved_permits'] }}</div>
                    <div class="stats-label">Approved</div>
                    <i class="fas fa-check-circle stats-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card info">
                <div class="card-body">
                    <div class="stats-number">{{ $stats['in_progress_permits'] }}</div>
                    <div class="stats-label">In Progress</div>
                    <i class="fas fa-cogs stats-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('permits.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New Permit
                        </a>
                        <a href="{{ route('permits.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Permits
                        </a>
                        @if(auth()->user()->role === 'administrator')
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-chart-bar me-2"></i>View Reports
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Permits -->
        <div class="col-xl-8 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2 text-primary"></i>Recent Permits
                        </h5>
                        <a href="{{ route('permits.index') }}" class="btn btn-outline-primary btn-sm">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recent_permits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Permit Number</th>
                                    <th>Work Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <i class="fas fa-check-circle fa-2x position-absolute opacity-25" style="top: 15px; right: 15px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stats-card info">
                            <div class="card-body text-center position-relative">
                                <div class="stats-number">{{ $stats['in_progress_permits'] }}</div>
                                <div class="stats-label">In Progress</div>
                                <i class="fas fa-cogs fa-2x position-absolute opacity-25" style="top: 15px; right: 15px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                            <div class="stats-number text-info">{{ $stats['in_progress_permits'] }}</div>
                            <div class="text-muted">In Progress</div>
                            <i class="fas fa-cog fa-2x text-info opacity-25 position-absolute" style="top: 15px; right: 15px;"></i>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions dengan Glass Effect -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('permits.create') }}" class="btn btn-primary w-100 p-3 text-decoration-none">
                                            <i class="fas fa-plus fa-2x mb-2 d-block"></i>
                                            <span class="fw-semibold">Create New Permit</span>
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="{{ route('permits.index') }}" class="btn btn-success w-100 p-3 text-decoration-none">
                                            <i class="fas fa-list fa-2x mb-2 d-block"></i>
                                            <span class="fw-semibold">View All Permits</span>
                                        </a>
                                    </div>
                                    @if(auth()->user()->role === 'administrator')
                                    <div class="col-md-4 mb-3">
                                        <a href="#" class="btn btn-warning w-100 p-3 text-decoration-none">
                                            <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                            <span class="fw-semibold">View Reports</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Permits -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock text-primary me-2"></i>Recent Permits
                                    </h5>
                                    <a href="{{ route('permits.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                
                                @if($recent_permits->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Permit Number</th>
                                                <th>Work Location</th>
                                                <th>Responsible Person</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_permits as $permit)
                                            <tr>
                                                <td>
                                                    <strong class="text-primary">{{ $permit->permit_number }}</strong>
                                                </td>
                                                <td>{{ $permit->work_location }}</td>
                                                <td>{{ $permit->responsible_person }}</td>
                                                <td>
                                                    @php
                                                        $badgeClass = match($permit->status) {
                                                            'draft' => 'bg-secondary',
                                                            'pending_approval' => 'bg-warning',
                                                            'approved' => 'bg-success',
                                                            'in_progress' => 'bg-info',
                                                            'completed' => 'bg-primary',
                                                            'cancelled' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $permit->status)) }}</span>
                                                </td>
                                                <td>{{ $permit->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('permits.show', $permit) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No permits yet</h5>
                                    <p class="text-muted">Create your first permit to get started.</p>
                                    <a href="{{ route('permits.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create New Permit
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});
</script>
@endsection
