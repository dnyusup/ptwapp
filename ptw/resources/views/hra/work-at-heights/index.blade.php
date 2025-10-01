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
                <h4 class="mb-1">HRA - Work at Heights List</h4>
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
                        <a href="{{ route('hra.work-at-heights.create', $permit) }}" class="btn btn-primary">
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
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                    <h4>{{ $hras->count() }}</h4>
                    <small>Total HRA Permits</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4>{{ $hras->where('fixed_scaffolding_checked', true)->count() + $hras->where('mobile_scaffolding_checked', true)->count() }}</h4>
                    <small>Active Assessments</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-2x mb-2"></i>
                    <h4>{{ $hras->where('fixed_scaffolding_checked', true)->count() }}</h4>
                    <small>Fixed Scaffolding</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-dolly fa-2x mb-2"></i>
                    <h4>{{ $hras->where('mobile_scaffolding_checked', true)->count() }}</h4>
                    <small>Mobile Scaffolding</small>
                </div>
            </div>
        </div>
    </div>

    <!-- HRA List -->
    @if($hras->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>HRA Work at Heights Permits
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>HRA Number</th>
                            <th>Scaffolding Type</th>
                            <th>Worker Name</th>
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
                                @if($hra->fixed_scaffolding_checked && $hra->mobile_scaffolding_checked)
                                    <span class="badge bg-primary">Fixed & Mobile</span>
                                @elseif($hra->fixed_scaffolding_checked)
                                    <span class="badge bg-warning">Fixed Scaffolding</span>
                                @elseif($hra->mobile_scaffolding_checked)
                                    <span class="badge bg-info">Mobile Scaffolding</span>
                                @else
                                    <span class="badge bg-secondary">No Scaffolding</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $hra->worker_name ?? 'N/A' }}</strong>
                                @if($hra->worker_phone)
                                    <br><small class="text-muted">{{ $hra->worker_phone }}</small>
                                @endif
                            </td>
                            <td>{{ $hra->user->name ?? 'N/A' }}</td>
                            <td>{{ $hra->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hra.work-at-heights.show', [$permit, $hra]) }}" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                                    <a href="{{ route('hra.work-at-heights.edit', [$permit, $hra]) }}" 
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
            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No HRA Work at Heights Permits</h5>
            <p class="text-muted mb-4">No HRA permits have been created for this main permit yet.</p>
            @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                @if($permit->status === 'active')
                    <a href="{{ route('hra.work-at-heights.create', $permit) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First HRA Permit
                    </a>
                @else
                    <button type="button" class="btn btn-secondary" disabled title="HRA can only be created when permit status is Active">
                        <i class="fas fa-lock me-2"></i>Create First HRA Permit
                    </button>
                @endif
            @endif
        </div>
    </div>
    @endif

    <!-- Hidden Delete Forms -->
    @foreach($hras as $hra)
    <form id="deleteForm{{ $hra->id }}" method="POST" action="{{ route('hra.work-at-heights.destroy', [$permit, $hra]) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</div>

@include('layouts.sidebar-scripts')

@push('scripts')
<script>
function confirmDelete(hraNumber, hraId) {
    if (confirm(`Are you sure you want to delete HRA permit "${hraNumber}"? This action cannot be undone.`)) {
        document.getElementById('deleteForm' + hraId).submit();
    }
}
</script>
@endpush
@endsection
