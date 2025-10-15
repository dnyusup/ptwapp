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
                <h4 class="mb-1">HRA - LOTO/Isolation List</h4>
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
                        <a href="{{ route('hra.loto-isolations.create', $permit) }}" class="btn btn-primary">
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
                    <h4>{{ $hraLotoIsolations->count() }}</h4>
                    <small>Total HRA Permits</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-cogs fa-2x mb-2"></i>
                    <h4>{{ $hraLotoIsolations->where('machine_tank_name', '!=', '')->count() }}</h4>
                    <small>Machine/Tank Isolations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-bolt fa-2x mb-2"></i>
                    <h4>{{ $hraLotoIsolations->where('membutuhkan_tes_listrik_on', 'ya')->count() }}</h4>
                    <small>Electrical Testing Required</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h4>{{ $hraLotoIsolations->where('isi_line_pipa', '!=', '')->count() }}</h4>
                    <small>Pipe Shutdowns</small>
                </div>
            </div>
        </div>
    </div>

    <!-- HRA List -->
    @if($hraLotoIsolations->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>HRA LOTO/Isolation Permits
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>HRA Number</th>
                            <th>Machine/Tank</th>
                            <th>Worker Name</th>
                            <th>Supervisor</th>
                            <th>Work Location</th>
                            <th>Duration</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hraLotoIsolations as $index => $hra)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong class="text-primary">{{ $hra->hra_permit_number }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $hra->machine_tank_name ?? '-' }}</strong>
                                    @if($hra->membutuhkan_tes_listrik_on == 'ya')
                                        <br><small class="badge bg-warning text-dark">Electrical Test Required</small>
                                    @endif
                                    @if($hra->isi_line_pipa)
                                        <br><small class="badge bg-danger">Pipe: {{ Str::limit($hra->isi_line_pipa, 20) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $hra->worker_name }}</strong>
                                    @if($hra->worker_phone)
                                        <br><small class="text-muted">{{ $hra->worker_phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $hra->supervisor_name }}</td>
                            <td>{{ $hra->work_location }}</td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <strong>Start:</strong> {{ \Carbon\Carbon::parse($hra->start_datetime)->format('d/m/Y H:i') }}
                                    <br>
                                    <strong>End:</strong> {{ \Carbon\Carbon::parse($hra->end_datetime)->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $hra->created_at->format('d/m/Y H:i') }}
                                    <br>by {{ $hra->user->name ?? 'System' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hra.loto-isolations.show', [$permit, $hra]) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id)
                                        <a href="{{ route('hra.loto-isolations.edit', [$permit, $hra]) }}" class="btn btn-sm btn-outline-warning" title="Edit HRA">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('hra.loto-isolations.destroy', [$permit, $hra]) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this HRA LOTO/Isolation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete HRA">
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
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-clipboard-list fa-4x text-muted"></i>
            </div>
            <h5 class="text-muted mb-3">No HRA LOTO/Isolation Permits Found</h5>
            <p class="text-muted mb-4">
                Belum ada HRA LOTO/Isolation yang dibuat untuk permit ini.
                @if($permit->status === 'active' && (auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id))
                    Klik tombol di bawah untuk membuat HRA baru.
                @endif
            </p>
            @if($permit->status === 'active' && (auth()->user()->role === 'administrator' || auth()->user()->id === $permit->user_id))
                <a href="{{ route('hra.loto-isolations.create', $permit) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat HRA LOTO/Isolation Pertama
                </a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection