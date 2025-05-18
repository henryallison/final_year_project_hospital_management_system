@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('/images/login-bg.jpg') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    
    /* Mobile-responsive card styles */
    .card {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    /* Responsive adjustments */
    .login-card-wrapper {
        margin-left: 0;
        padding: 15px;
        width: 100%;
    }

    /* Code input styling */
    .code-input {
        letter-spacing: 0.5em;
        font-size: 1.2rem;
        text-align: center;
    }
    
    /* Error message styling */
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
    }
    
    /* Responsive breakpoints */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }
        
        .code-input {
            font-size: 1.1rem;
            height: 50px;
        }
        
        .btn {
            padding: 12px;
            font-size: 16px;
        }
        
        .card-header h4 {
            font-size: 1.25rem;
        }
    }

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
                    <h4 class="mb-0 text-center">{{ __('Verify Reset Code') }}</h4>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.code.verify.submit') }}" id="codeVerificationForm">
                        @csrf

                        <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                        <div class="mb-4">
                            <label for="code" class="form-label text-muted">
                                {{ __('4-Digit Code') }}
                            </label>
                            <input id="code" type="text"
                                   class="form-control @error('code') is-invalid @enderror code-input"
                                   name="code" value="{{ old('code') }}"
                                   required autocomplete="off" autofocus
                                   maxlength="4" pattern="\d{4}" inputmode="numeric"
                                   placeholder="Enter 4-digit code">
                            
                            <!-- Frontend error message container -->
                            <div id="codeError" class="error-message">
                                Please enter a valid 4-digit code
                            </div>

                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                {{ __('Verify Code') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        const codeError = document.getElementById('codeError');
        const form = document.getElementById('codeVerificationForm');

        if (codeInput) {
            // Auto-focus on load
            codeInput.focus();

            // Real-time validation
            codeInput.addEventListener('input', function() {
                const isValid = /^\d{4}$/.test(this.value);
                
                if (this.value.length > 0 && !isValid) {
                    codeError.style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    codeError.style.display = 'none';
                    this.classList.remove('is-invalid');
                }

                // Auto-submit when valid 4 digits entered
                if (isValid) {
                    form.submit();
                }
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (!/^\d{4}$/.test(codeInput.value)) {
                    e.preventDefault();
                    codeError.style.display = 'block';
                    codeInput.classList.add('is-invalid');
                    codeInput.focus();
                }
            });
        }
    });
</script>
@endsection
