@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100">
        <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-hard-hat fa-3x text-primary" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-2">PTW Portal</h3>
                        <p class="text-muted">Permit to Work Management</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       placeholder="Enter your email address">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="current-password"
                                       placeholder="Enter your password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-4 py-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>

                        <div class="text-center">
                            <p class="text-muted mb-0">Don't have an account? 
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">Sign Up</a>
                            </p>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <div class="text-center">
                        <img src="{{ asset('images/company-logo.png') }}" alt="Company Logo" class="img-fluid mx-auto d-block" style="max-height: 30px; opacity: 0.8;">
                        <p class="text-muted mt-2 mb-0 small">Powered by Company Solutions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
