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
            font-size: 14px; /* Better fit for mobile */
            padding: 10px;
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
                    <h4 class="mb-0 text-center">{{ __('Reset Your Password') }}</h4>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/password/reset') }}" id="password-reset-form">
                        @csrf

                        <!-- Hidden Fields -->
                        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                        <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-muted">{{ __('New Password') }}</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password"
                                   minlength="8"
                                   placeholder="Enter new password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label text-muted">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Confirm new password">

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Display general errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('password-reset-form');

            form.addEventListener('submit', function(e) {
                // Client-side validation
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password-confirm').value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters!');
                    return false;
                }

                return true;
            });
        });
    </script>
@endsection
