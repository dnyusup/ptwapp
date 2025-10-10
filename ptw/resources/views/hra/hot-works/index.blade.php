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
                <h4 class="mb-1">HRA - Hot Work List</h4>
                <p class="text-muted mb-0">
                    Main Permit: <strong>{{ $permit->permit_number }}</strong> - {{ $permit->work_title }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('permits.show', $permit) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Permit Utama
                </a>
                @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                    @if($permit->status === 'active')
                        <a href="{{ route('hra.hot-works.create', $permit) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat HRA Baru
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary" disabled title="HRA hanya dapat dibuat ketika status permit adalah Active">
                            <i class="fas fa-lock me-2"></i>Buat HRA Baru
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-fire fa-2x mb-2"></i>
                    <h4>{{ $hras->count() }}</h4>
                    <small>Total HRA Hot Works</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4>{{ $hras->where('q1_alternative_considered', true)->count() }}</h4>
                    <small>Assessments Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-fire-extinguisher fa-2x mb-2"></i>
                    <h4>{{ $hras->where('fire_watch_officer', true)->count() }}</h4>
                    <small>With Fire Watch Officer</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-eye fa-2x mb-2"></i>
                    <h4>{{ $hras->where('gas_monitoring_required', true)->count() }}</h4>
                    <small>Gas Monitoring Required</small>
                </div>
            </div>
        </div>
    </div>

    <!-- HRA List -->
    @if($hras->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-fire me-2"></i>HRA Hot Work Permits
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>HRA Number</th>
                            <th>Worker Name</th>
                            <th>Work Location</th>
                            <th>Work Period</th>
                            <th>Fire Safety</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hras as $index => $hra)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $hra->hra_permit_number }}</strong>
                            </td>
                            <td>
                                <strong>{{ $hra->worker_name ?? 'N/A' }}</strong>
                                @if($hra->worker_phone)
                                    <br><small class="text-muted">{{ $hra->worker_phone }}</small>
                                @endif
                            </td>
                            <td>{{ $hra->work_location ?? 'N/A' }}</td>
                            <td>
                                @if($hra->start_datetime && $hra->end_datetime)
                                    <small>
                                        {{ $hra->start_datetime->format('d M Y H:i') }}<br>
                                        <strong>to</strong><br>
                                        {{ $hra->end_datetime->format('d M Y H:i') }}
                                    </small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($hra->fire_watch_officer)
                                        <span class="badge bg-success">Fire Watch</span>
                                    @endif
                                    @if($hra->apar_air || $hra->apar_powder || $hra->apar_co2)
                                        <span class="badge bg-warning">APAR Ready</span>
                                    @endif
                                    @if($hra->gas_monitoring_required)
                                        <span class="badge bg-info">Gas Monitor</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $hra->user->name ?? 'N/A' }}</td>
                            <td>{{ $hra->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hra.hot-works.show', [$permit, $hra]) }}" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                                    <a href="{{ route('hra.hot-works.edit', [$permit, $hra]) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('{{ $hra->hra_permit_number }}', {{ $hra->id }})" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-fire fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No HRA Hot Work Permits</h5>
            <p class="text-muted mb-4">No HRA Hot Work permits have been created for this main permit yet.</p>
            @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                @if($permit->status === 'active')
                    <a href="{{ route('hra.hot-works.create', $permit) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First HRA Hot Work Permit
                    </a>
                @else
                    <button type="button" class="btn btn-secondary" disabled title="HRA can only be created when permit status is Active">
                        <i class="fas fa-lock me-2"></i>Create First HRA Hot Work Permit
                    </button>
                @endif
            @endif
        </div>
    </div>
    @endif

    <!-- Hidden Delete Forms -->
    @foreach($hras as $hra)
    <form id="deleteForm{{ $hra->id }}" method="POST" action="{{ route('hra.hot-works.destroy', [$permit, $hra]) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</div>

@include('layouts.sidebar-scripts')

@push('scripts')
<script>
function confirmDelete(hraNumber, hraId) {
    if (confirm(`Are you sure you want to delete HRA Hot Work permit "${hraNumber}"? This action cannot be undone.`)) {
        document.getElementById('deleteForm' + hraId).submit();
    }
}
</script>
@endpush
@endsection