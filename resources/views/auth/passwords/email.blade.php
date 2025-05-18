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
        border: none;
        width: 100%;
        max-width: 500px; /* Maximum width on larger screens */
        margin: 0 auto; /* Center the card */
    }

    /* Responsive adjustments */
    .reset-card-wrapper {
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

        .alert {
            font-size: 14px; /* Better fit for alert messages */
            padding: 10px 15px;
        }
    }

    /* Larger screens adjustments */
    @media (min-width: 769px) {
        .reset-card-wrapper {
            padding: 0;
        }
    }
</style>

<div class="container py-3 py-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6 reset-card-wrapper">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white py-3 border-0 rounded-top-4">
                    <h4 class="mb-0 text-center">{{ __('Reset Password') }}</h4>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                {{ __('Send Verification Code') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
