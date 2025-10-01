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
                <h4 class="mb-1"><i class="fas fa-user-plus me-2 text-primary"></i>Add New User</h4>
                <p class="text-muted mb-0">Create a new user account</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">No Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="toggleDepartmentField()">
                                <option value="">Select Role</option>
                                <option value="bekaert" {{ old('role') === 'bekaert' ? 'selected' : '' }}>Bekaert</option>
                                <option value="contractor" {{ old('role') === 'contractor' ? 'selected' : '' }}>Contractor</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>
                                    <strong>Bekaert:</strong> Internal company staff<br>
                                    <strong>Contractor:</strong> External contractor personnel
                                </small>
                            </div>
                        </div>

                        <!-- Company field - only shown for Contractor role -->
                        <div class="mb-3 d-none" id="companyField">
                            <label for="company_id" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                            <select class="form-select @error('company_id') is-invalid @enderror" 
                                    id="company_id" name="company_id">
                                <option value="">Pilih Perusahaan</option>
                                @foreach($companies as $company)
                                    @if($company->is_active)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }} ({{ $company->company_code }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>Required for Contractor users.</small>
                            </div>
                        </div>

                        <!-- Department field - only shown for Bekaert role -->
                        <div class="mb-3 d-none" id="departmentField">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <select class="form-select @error('department') is-invalid @enderror" 
                                    id="department" name="department">
                                <option value="">Select Department</option>
                                <option value="Maintenance" {{ old('department') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                                <option value="IE" {{ old('department') == 'IE' ? 'selected' : '' }}>IE</option>
                                <option value="EHS" {{ old('department') == 'EHS' ? 'selected' : '' }}>EHS</option>
                                <option value="General" {{ old('department') == 'General' ? 'selected' : '' }}>General</option>
                                <option value="Human Resources" {{ old('department') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                <option value="Supply Chain" {{ old('department') == 'Supply Chain' ? 'selected' : '' }}>Supply Chain</option>
                                <option value="Quality" {{ old('department') == 'Quality' ? 'selected' : '' }}>Quality</option>
                                <option value="Technical" {{ old('department') == 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Procurement" {{ old('department') == 'Procurement' ? 'selected' : '' }}>Procurement</option>
                                <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                <option value="CORD" {{ old('department') == 'CORD' ? 'selected' : '' }}>CORD</option>
                                <option value="WWD" {{ old('department') == 'WWD' ? 'selected' : '' }}>WWD</option>
                                <option value="HP" {{ old('department') == 'HP' ? 'selected' : '' }}>HP</option>
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>Required for Bekaert employees.</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Role Permissions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-danger"><i class="fas fa-crown me-2"></i>Administrator</h6>
                        <ul class="list-unstyled small mb-3">
                            <li><i class="fas fa-check text-success me-2"></i>Full system access</li>
                            <li><i class="fas fa-check text-success me-2"></i>User management</li>
                            <li><i class="fas fa-check text-success me-2"></i>System settings</li>
                            <li><i class="fas fa-check text-success me-2"></i>All permit actions</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning"><i class="fas fa-user-tie me-2"></i>Supervisor</h6>
                        <ul class="list-unstyled small mb-3">
                            <li><i class="fas fa-check text-success me-2"></i>Approve/reject permits</li>
                            <li><i class="fas fa-check text-success me-2"></i>View all permits</li>
                            <li><i class="fas fa-check text-success me-2"></i>Generate reports</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info"><i class="fas fa-hard-hat me-2"></i>Contractor</h6>
                        <ul class="list-unstyled small mb-3">
                            <li><i class="fas fa-check text-success me-2"></i>Create permits</li>
                            <li><i class="fas fa-check text-success me-2"></i>Manage own permits</li>
                            <li><i class="fas fa-check text-success me-2"></i>Submit for approval</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-success"><i class="fas fa-shield-alt me-2"></i>Safety Officer</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-2"></i>Review safety requirements</li>
                            <li><i class="fas fa-check text-success me-2"></i>Safety compliance checks</li>
                            <li><i class="fas fa-check text-success me-2"></i>Incident reporting</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        departmentField.classList.remove('d-none');
        departmentField.classList.add('fade-in');
        companyField.classList.add('d-none');
        companyField.classList.remove('fade-in');
        
        if (departmentInput) {
            departmentInput.required = true;
        }
        if (companyInput) {
            companyInput.required = false;
            companyInput.value = '';
        }
    } else if (roleSelect.value.toLowerCase() === 'contractor') {
        departmentField.classList.add('d-none');
        departmentField.classList.remove('fade-in');
        companyField.classList.remove('d-none');
        companyField.classList.add('fade-in');
        
        if (departmentInput) {
            departmentInput.required = false;
            departmentInput.value = '';
        }
        if (companyInput) {
            companyInput.required = true;
        }
    } else {
        departmentField.classList.add('d-none');
        departmentField.classList.remove('fade-in');
        companyField.classList.add('d-none');
        companyField.classList.remove('fade-in');
        
        if (departmentInput) {
            departmentInput.required = false;
            departmentInput.value = '';
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
