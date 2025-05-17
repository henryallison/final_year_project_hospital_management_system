@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('/images/login-bg.jpg') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
    }
    .login-card-wrapper {
        margin-left: -150px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 login-card-wrapper">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white py-3 border-0 rounded-top-4">
                    <h4 class="mb-0 text-center">{{ __('Verify Reset Code') }}</h4>
                </div>

                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.code.verify.submit') }}">
                        @csrf

                        <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                        <div class="mb-4">
                            <label for="code" class="form-label text-muted">
                                {{ __('4-Digit Code') }}
                            </label>
                            <input id="code" type="text"
                                   class="form-control @error('code') is-invalid @enderror text-center"
                                   name="code" value="{{ old('code') }}"
                                   required autocomplete="off" autofocus
                                   maxlength="4" pattern="\d{4}" inputmode="numeric"
                                   placeholder="Enter 4-digit code"
                                   style="letter-spacing: 0.5em; font-size: 1.2rem;">

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
    // Auto-focus and auto-advance for code input
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.focus();

            // Auto-advance when 4 digits are entered
            codeInput.addEventListener('input', function() {
                if (this.value.length === 4) {
                    this.form.submit();
                }
            });
        }
    });
</script>
@endsection
