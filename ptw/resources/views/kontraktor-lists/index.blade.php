@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-building me-2 text-primary"></i>Manage Kontraktor List</h4>
                <p class="text-muted mb-0">Kelola daftar perusahaan kontraktor</p>
            </div>
            <a href="{{ route('kontraktor-lists.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Kontraktor
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Company Name</th>
                            <th>Company Code</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kontraktors as $kontraktor)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $kontraktor->company_name }}</div>
                                    @if($kontraktor->address)
                                        <small class="text-muted">{{ Str::limit($kontraktor->address, 50) }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $kontraktor->company_code }}</span></td>
                                <td>{{ $kontraktor->contact_person ?: '-' }}</td>
                                <td>{{ $kontraktor->phone ?: '-' }}</td>
                                <td>{{ $kontraktor->email ?: '-' }}</td>
                                <td>
                                    @if($kontraktor->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('kontraktor-lists.show', $kontraktor) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('kontraktor-lists.edit', $kontraktor) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('kontraktor-lists.destroy', $kontraktor) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this kontraktor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No kontraktor found. <a href="{{ route('kontraktor-lists.create') }}">Add the first one</a>.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $kontraktors->links() }}
            </div>
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')
@endsection
