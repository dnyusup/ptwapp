@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-user-circle me-2 text-primary"></i>My Profile</h4>
                <p class="text-muted mb-0">Manage your account information and security</p>
            </div>
        </div>
    </div>

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

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update-profile') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-2"></i>Phone Number
                                </label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user-tag me-2"></i>Role
                                </label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                @if($user->department)
                                    <div class="form-text">Department: {{ $user->department }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key me-2"></i>Current Password
                            </label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>New Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm New Password
                                </label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Details Sidebar -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Account Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary rounded-circle">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </div>
                        <h6>{{ $user->name }}</h6>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <small class="text-muted">Role:</small>
                        <div>
                            @if($user->role === 'administrator')
                                <span class="badge bg-danger">Administrator</span>
                            @elseif($user->role === 'bekaert')
                                <span class="badge bg-primary">Bekaert</span>
                                @if($user->department)
                                    <span class="badge bg-secondary ms-1">{{ $user->department }}</span>
                                @endif
                            @elseif($user->role === 'contractor')
                                <span class="badge bg-info">Contractor</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Member Since:</small>
                        <div>{{ $user->created_at->format('d F Y') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Last Updated:</small>
                        <div>{{ $user->updated_at->format('d F Y H:i') }}</div>
                    </div>
                    
                    @if($user->last_login_at)
                    <div class="mb-3">
                        <small class="text-muted">Last Login:</small>
                        <div>{{ $user->last_login_at->format('d F Y H:i') }}</div>
                    </div>
                    @endif

                    @if($user->phone)
                    <div class="mb-3">
                        <small class="text-muted">Phone:</small>
                        <div>{{ $user->phone }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Account Security -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Account Security
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>Password</strong>
                            <small class="d-block text-muted">Last changed: {{ $user->updated_at->format('d M Y') }}</small>
                        </div>
                        <span class="badge bg-success">Strong</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Two-Factor Authentication</strong>
                            <small class="d-block text-muted">Add extra security to your account</small>
                        </div>
                        <span class="badge bg-warning">Disabled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.sidebar-styles')

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 24px;
}

/* Form improvements */
.form-label {
    font-weight: 500;
    color: #374151;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

/* Card enhancements */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

/* Badge improvements */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* Button hover effects */
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .col-lg-8, .col-lg-4 {
        margin-bottom: 1rem;
    }
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

@include('layouts.sidebar-scripts')
@endsection
