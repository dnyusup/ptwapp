@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

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
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card blue">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['total_permits'] }}</div>
                        <div class="summary-label">Total Permits</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card green">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['active_permits'] }}</div>
                        <div class="summary-label">Active</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card orange">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['waiting_approval_permits'] ?? $stats['pending_permits'] }}</div>
                        <div class="summary-label">Waiting Approval</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card gray" style="background: linear-gradient(135deg, #78909C 0%, #546E7A 100%) !important; color: white !important;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['draft_permits'] ?? $stats['in_progress_permits'] }}</div>
                        <div class="summary-label">Draft</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['expired_permits'] }}</div>
                        <div class="summary-label">Expired</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card" style="background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['completed_permits'] }}</div>
                        <div class="summary-label">Completed</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card" style="background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); color: white;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['pending_extension_permits'] }}</div>
                        <div class="summary-label">Pending Extension</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card summary-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $stats['rejected_permits'] }}</div>
                        <div class="summary-label">Rejected</div>
                        <div class="summary-detail">Selengkapnya</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="summary-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
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
                                    <th>Work Title</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_permits as $permit)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $permit->permit_number }}</span>
                                    </td>
                                    <td>{{ Str::limit($permit->work_title, 50) }}</td>
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
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($permit->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $permit->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('permits.show', $permit) }}" class="btn btn-outline-primary btn-sm">
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

    <!-- Today's Work Section -->
    <div class="row mb-4">
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-calendar-day me-2 text-primary"></i>Pekerjaan Hari Ini
                            </h5>
                            <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="text-center">
                                <div class="fw-bold text-primary small">{{ $today_summary['total'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Total</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold text-success small">{{ $today_summary['active'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Active</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold text-danger small">{{ $today_summary['expired'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Expired</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold text-info small">{{ $today_summary['completed'] }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">Done</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($today_permits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Permit</th>
                                    <th>Work Title</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($today_permits as $permit)
                                <tr class="{{ $permit->status === 'expired' ? 'table-danger' : ($permit->status === 'completed' ? 'table-success' : '') }}">
                                    <td>
                                        <div class="fw-semibold small">{{ $permit->permit_number }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $permit->start_date->format('d/m') }} - {{ $permit->end_date->format('d/m') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium small">{{ Str::limit($permit->work_title, 25) }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($permit->work_location, 20) }}</div>
                                    </td>
                                    <td>
                                        @if($permit->status === 'active')
                                            <span class="badge bg-success badge-sm">Active</span>
                                        @elseif($permit->status === 'expired')
                                            <span class="badge bg-danger badge-sm">Expired</span>
                                        @elseif($permit->status === 'completed')
                                            <span class="badge bg-info badge-sm">Done</span>
                                        @else
                                            <span class="badge bg-secondary badge-sm">{{ ucfirst($permit->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('permits.show', $permit) }}" class="btn btn-outline-primary btn-sm">
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
                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Pekerjaan Hari Ini</h5>
                        <p class="text-muted">Tidak ada permit yang dijadwalkan untuk hari ini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')
@endsection
