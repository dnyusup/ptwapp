@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row w-100">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold text-primary">Create Account</h3>
                        <p class="text-muted">Join the PTW System</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="Enter your email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   name="phone" value="{{ old('phone') }}"
                                   placeholder="Enter your phone number">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       placeholder="Enter password (min. 8 characters)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm your password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required onchange="toggleDepartmentCompanyField()">
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
                                @foreach($companies ?? [] as $company)
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
                            <a href="{{ route('login') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDepartmentCompanyField() {
    var role = document.getElementById('role').value;
    var deptField = document.getElementById('departmentField');
    var compField = document.getElementById('companyField');
    if (role === 'bekaert') {
        deptField.classList.remove('d-none');
        compField.classList.add('d-none');
    } else if (role === 'contractor') {
        compField.classList.remove('d-none');
        deptField.classList.add('d-none');
    } else {
        deptField.classList.add('d-none');
        compField.classList.add('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleDepartmentCompanyField();
    document.getElementById('role').addEventListener('change', toggleDepartmentCompanyField);
});
</script>
@endpush
