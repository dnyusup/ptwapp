@extends('layouts.app')

@section('styles')
<style>
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

#departmentField, #companyField {
    transition: all 0.3s ease;
}

#departmentField.d-none, #companyField.d-none {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
@endsection

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-user-edit me-2 text-primary"></i>Edit User</h4>
                <p class="text-muted mb-0">Update user account information</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">No Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                <div class="form-text">Leave blank to keep current password</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="toggleDepartmentField()">
                                <option value="">Select Role</option>
                                <option value="bekaert" {{ old('role', $user->role) === 'bekaert' ? 'selected' : '' }}>Bekaert</option>
                                <option value="contractor" {{ old('role', $user->role) === 'contractor' ? 'selected' : '' }}>Contractor</option>
                                @if(auth()->user()->role === 'administrator')
                                <option value="administrator" {{ old('role', $user->role) === 'administrator' ? 'selected' : '' }}>Administrator</option>
                                @endif
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>
                                    <strong>Bekaert:</strong> Internal company staff<br>
                                    <strong>Contractor:</strong> External contractor personnel<br>
                                    <strong>Administrator:</strong> Full system access
                                </small>
                            </div>
                        </div>

                        <!-- Company field - only shown for Contractor role -->
                        <div class="mb-3 d-none" id="companyField">
                            <div class="card border-warning">
                                <div class="card-body bg-light">
                                    <label for="company_id" class="form-label">
                                        <i class="fas fa-building me-2 text-warning"></i>Nama Perusahaan <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('company_id') is-invalid @enderror" 
                                            id="company_id" name="company_id">
                                        <option value="">Pilih Perusahaan</option>
                                        @foreach($companies as $company)
                                            @if($company->is_active)
                                                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                                                    {{ $company->company_name }} ({{ $company->company_code }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Required for Contractor users.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Department field - only shown for Bekaert role -->
                        <div id="departmentField" class="mb-3 d-none">
                            <div class="card border-info">
                                <div class="card-body bg-light">
                                    <label for="department" class="form-label">
                                        <i class="fas fa-building me-2 text-info"></i>Department <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('department') is-invalid @enderror" 
                                            id="department" name="department">
                                        <option value="">Select Department</option>
                                        <option value="Maintenance" {{ old('department', $user->department) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="IT" {{ old('department', $user->department) == 'IT' ? 'selected' : '' }}>IT</option>
                                        <option value="IE" {{ old('department', $user->department) == 'IE' ? 'selected' : '' }}>IE</option>
                                        <option value="EHS" {{ old('department', $user->department) == 'EHS' ? 'selected' : '' }}>EHS</option>
                                        <option value="General" {{ old('department', $user->department) == 'General' ? 'selected' : '' }}>General</option>
                                        <option value="Human Resources" {{ old('department', $user->department) == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                        <option value="Supply Chain" {{ old('department', $user->department) == 'Supply Chain' ? 'selected' : '' }}>Supply Chain</option>
                                        <option value="Quality" {{ old('department', $user->department) == 'Quality' ? 'selected' : '' }}>Quality</option>
                                        <option value="Technical" {{ old('department', $user->department) == 'Technical' ? 'selected' : '' }}>Technical</option>
                                        <option value="Finance" {{ old('department', $user->department) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Procurement" {{ old('department', $user->department) == 'Procurement' ? 'selected' : '' }}>Procurement</option>
                                        <option value="Sales" {{ old('department', $user->department) == 'Sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="CORD" {{ old('department', $user->department) == 'CORD' ? 'selected' : '' }}>CORD</option>
                                        <option value="WWD" {{ old('department', $user->department) == 'WWD' ? 'selected' : '' }}>WWD</option>
                                        <option value="HP" {{ old('department', $user->department) == 'HP' ? 'selected' : '' }}>HP</option>
                                    </select>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Required for Bekaert employees.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>User Details
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
                        <small class="text-muted">Current Role:</small>
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
                </div>
            </div>
        </div>
    </div>
</div>

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

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Ensure proper spacing and layout */
@media (max-width: 991.98px) {
    .col-lg-8, .col-lg-4 {
        margin-bottom: 1rem;
    }
}

/* Card consistency */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

function toggleDepartmentField() {
    const roleSelect = document.getElementById('role');
    const departmentField = document.getElementById('departmentField');
    const departmentInput = document.getElementById('department');
    const companyField = document.getElementById('companyField');
    const companyInput = document.getElementById('company_id');
    
    if (roleSelect.value.toLowerCase() === 'bekaert') {
        // Show department field for Bekaert role
        departmentField.classList.remove('d-none');
        departmentField.classList.add('fade-in');
        if (departmentInput) {
            departmentInput.required = true;
        }
        
        // Hide company field for Bekaert role
        if (companyField) {
            companyField.classList.add('d-none');
            companyField.classList.remove('fade-in');
        }
        if (companyInput) {
            companyInput.required = false;
            companyInput.value = '';
        }
    } else if (roleSelect.value.toLowerCase() === 'contractor') {
        // Show company field for Contractor role
        if (companyField) {
            companyField.classList.remove('d-none');
            companyField.classList.add('fade-in');
        }
        if (companyInput) {
            companyInput.required = true;
        }
        
        // Hide department field for Contractor role
        departmentField.classList.add('d-none');
        departmentField.classList.remove('fade-in');
        if (departmentInput) {
            departmentInput.required = false;
            departmentInput.value = '';
        }
    } else {
        // Hide both fields for other roles (Administrator)
        departmentField.classList.add('d-none');
        departmentField.classList.remove('fade-in');
        if (departmentInput) {
            departmentInput.required = false;
            departmentInput.value = '';
        }
        
        if (companyField) {
            companyField.classList.add('d-none');
            companyField.classList.remove('fade-in');
        }
        if (companyInput) {
            companyInput.required = false;
            companyInput.value = '';
        }
    }
}

// Show department field if Bekaert is already selected (for validation errors)
document.addEventListener('DOMContentLoaded', function() {
    toggleDepartmentField();
});
</script>

@include('layouts.sidebar-scripts')
@endsection
