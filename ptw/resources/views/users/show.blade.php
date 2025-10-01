@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-user me-2 text-primary"></i>User Details</h4>
                <p class="text-muted mb-0">View user account information</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit User
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-xl mx-auto mb-4">
                        <div class="avatar-title bg-primary rounded-circle">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    @if($user->role === 'administrator')
                        <span class="badge bg-danger fs-6">Administrator</span>
                    @elseif($user->role === 'bekaert')
                        <span class="badge bg-primary fs-6">Bekaert</span>
                    @elseif($user->role === 'contractor')
                        <span class="badge bg-info fs-6">Contractor</span>
                    @elseif($user->role === 'supervisor')
                        <span class="badge bg-warning fs-6">Supervisor</span>
                    @elseif($user->role === 'safety_officer')
                        <span class="badge bg-success fs-6">Safety Officer</span>
                    @endif
                    
                    @if($user->id === auth()->id())
                        <div class="mt-2">
                            <span class="badge bg-secondary">Current User</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Account Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Account Created</h6>
                                <p class="text-muted mb-0">{{ $user->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <p class="text-muted mb-0">{{ $user->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($user->last_login_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Login</h6>
                                <p class="text-muted mb-0">{{ $user->last_login_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <div class="fw-semibold">{{ $user->phone ?? 'Not provided' }}</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Role</label>
                            <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                        </div>
                        
                        @if($user->role === 'bekaert' && $user->department)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Department</label>
                            <div class="fw-semibold">{{ $user->department }}</div>
                        </div>
                        @endif
                        
                        @if($user->role === 'contractor' && $user->company)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Company</label>
                            <div class="fw-semibold">{{ $user->company->company_name }}</div>
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Account Status</label>
                            <div>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key me-2"></i>Role Permissions
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->role === 'administrator')
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-crown me-2"></i>Administrator Privileges</h6>
                            <ul class="mb-0">
                                <li>Full system access and control</li>
                                <li>User management and role assignment</li>
                                <li>System configuration and settings</li>
                                <li>Complete permit workflow management</li>
                                <li>Advanced reporting and analytics</li>
                            </ul>
                        </div>
                    @elseif($user->role === 'supervisor')
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-user-tie me-2"></i>Supervisor Privileges</h6>
                            <ul class="mb-0">
                                <li>Approve and reject permit applications</li>
                                <li>View all permits in the system</li>
                                <li>Generate reports and analytics</li>
                                <li>Monitor permit compliance</li>
                            </ul>
                        </div>
                    @elseif($user->role === 'contractor')
                        <div class="alert alert-info">
                            <h6><i class="fas fa-hard-hat me-2"></i>Contractor Privileges</h6>
                            <ul class="mb-0">
                                <li>Create and submit new permits</li>
                                <li>Manage their own permits</li>
                                <li>Submit permits for approval</li>
                                <li>Update permit status during work</li>
                            </ul>
                        </div>
                    @elseif($user->role === 'safety_officer')
                        <div class="alert alert-success">
                            <h6><i class="fas fa-shield-alt me-2"></i>Safety Officer Privileges</h6>
                            <ul class="mb-0">
                                <li>Review safety requirements and compliance</li>
                                <li>Conduct safety assessments</li>
                                <li>Generate safety reports</li>
                                <li>Monitor incident reporting</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xl {
    width: 120px;
    height: 120px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 36px;
}

.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 12px;
    bottom: -12px;
    width: 2px;
    background: #e9ecef;
}

@include('layouts.sidebar-scripts')
@endsection
