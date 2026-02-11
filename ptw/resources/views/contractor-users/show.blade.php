@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-user me-2 text-primary"></i>User Details</h4>
                <p class="text-muted mb-0">Viewing profile for {{ $user->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('contractor-users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit User
                </a>
                <a href="{{ route('contractor-users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Team
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="30%"><i class="fas fa-user me-2 text-muted"></i>Full Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-envelope me-2 text-muted"></i>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-phone me-2 text-muted"></i>Phone</th>
                                <td>{{ $user->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-user-tag me-2 text-muted"></i>Role</th>
                                <td>
                                    <span class="badge bg-info">Contractor</span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-building me-2 text-muted"></i>Company</th>
                                <td>
                                    {{ $company->company_name ?? 'N/A' }}
                                    @if($company->company_code)
                                        <span class="badge bg-secondary ms-2">{{ $company->company_code }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-circle me-2 text-muted"></i>Status</th>
                                <td>
                                    @if($user->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($user->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Account Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Created At</small>
                            <strong>{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</strong>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}</strong>
                        </li>
                        @if($user->email_verified_at)
                        <li class="mb-3">
                            <small class="text-muted d-block">Email Verified</small>
                            <strong>{{ $user->email_verified_at->format('d M Y, H:i') }}</strong>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Permanently delete this user account. This action cannot be undone.</p>
                    <form method="POST" action="{{ route('contractor-users.destroy', $user->id) }}" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.sidebar-styles')
@include('layouts.sidebar-scripts')

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
