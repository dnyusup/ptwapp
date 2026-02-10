@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-users-cog me-2 text-primary"></i>User Management</h4>
                <p class="text-muted mb-0">Manage system users and their roles</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New User
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search by name or email..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
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

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>All Users
                <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Created</th>
                                <th>Last Login</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-title bg-primary rounded-circle">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $user->name }}</div>
                                            @if($user->id === auth()->id())
                                                <small class="text-primary">(You)</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'administrator')
                                        <span class="badge bg-danger">Administrator</span>
                                    @elseif($user->role === 'supervisor')
                                        <span class="badge bg-warning">Supervisor</span>
                                    @elseif($user->role === 'contractor')
                                        <span class="badge bg-info">Contractor</span>
                                    @elseif($user->role === 'safety_officer')
                                        <span class="badge bg-success">Safety Officer</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->company)
                                        <span class="text-dark fw-medium">{{ $user->company->company_name }}</span>
                                        @if($user->company->company_code)
                                            <br><small class="text-muted">{{ $user->company->company_code }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->department)
                                        <span class="text-muted small">{{ $user->department }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->format('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($user->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if(auth()->user()->canViewAdminUsers() || $user->role !== 'administrator')
                                            <a href="{{ route('users.show', $user) }}" 
                                               class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary" disabled title="Access Denied - Administrator Only">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif

                                        @if(auth()->user()->canEditAdminUsers() || $user->role !== 'administrator')
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary" disabled title="Access Denied - Administrator Only">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif

                                        @if($user->id !== auth()->id() && auth()->user()->canDeleteUsers() && (auth()->user()->canEditAdminUsers() || $user->role !== 'administrator'))
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @elseif($user->role === 'administrator' && !auth()->user()->canEditAdminUsers())
                                            <button class="btn btn-outline-secondary" disabled title="Access Denied - Administrator Only">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif

                                        @if($user->status === 'pending' && auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS')
                                            <form method="POST" action="{{ route('users.activate', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Aktivasi User">
                                                    <i class="fas fa-check"></i> Aktivasi
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
                @if($users->hasPages())
                    <div class="card-footer bg-white">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No users found</h5>
                    @if($search)
                        <p class="text-muted">No users match your search criteria.</p>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times me-2"></i>Clear Search
                        </a>
                    @else
                        <p class="text-muted">Start by adding your first user.</p>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New User
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

/* Fix horizontal scrollbar - make it always visible without scrolling down */
.table-responsive {
    max-height: calc(100vh - 320px);
    overflow: auto;
}

/* Sticky table header */
.table-responsive thead {
    position: sticky;
    top: 0;
    z-index: 1;
    background: #f8f9fa;
}

/* Make table cells not wrap to reduce horizontal scroll need */
.table-responsive table th,
.table-responsive table td {
    white-space: nowrap;
}
</style>

@include('layouts.sidebar-styles')
@include('layouts.sidebar-scripts')
@endsection
