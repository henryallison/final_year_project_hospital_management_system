@extends('layouts.app')

@section('content')
<style>
    /* Keep your existing background styles */
    body {
        background: url('/images/login-bg.jpg') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }

    /* Mobile-responsive form styles */
    .card {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        width: 100%;
        max-width: 500px; /* Maximum width on larger screens */
        margin: 0 auto; /* Center the card */
    }

    /* Responsive adjustments */
    .login-card-wrapper {
        margin-left: 0; /* Remove the negative margin */
        padding: 15px; /* Add padding on mobile */
        width: 100%;
    }

    /* Stack form elements vertically on small screens */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }

        .form-control {
            font-size: 16px; /* Larger text for mobile */
            height: 48px; /* Larger touch targets */
        }

        .btn {
            padding: 12px; /* Larger button for mobile */
            font-size: 16px;
        }

        .card-header h4 {
            font-size: 1.25rem; /* Slightly smaller header on mobile */
        }
    }

    /* Larger screens adjustments */
    @media (min-width: 769px) {
        .login-card-wrapper {
            padding: 0;
        }
    }
</style>

<div class="container py-3 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 login-card-wrapper">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white py-3 border-0 rounded-top-4">
                    <h4 class="mb-0 text-center">{{ __('Login') }}</h4>
                </div>

                <div class="card-body p-3 p-md-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label text-muted">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="Enter your email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-muted">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password"
                                   placeholder="Enter your password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if (Route::has('password.request'))
                            <div class="mb-3 text-end">
                                <a class="text-primary text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
