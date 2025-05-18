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
        border: none;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
    }

    .reset-card-wrapper {
        margin-left: 0;
        padding: 15px;
        width: 100%;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }

        .form-control {
            font-size: 16px;
            height: 48px;
        }

        .btn {
            padding: 12px;
            font-size: 16px;
        }

        .card-header h4 {
            font-size: 1.25rem;
        }

        .alert {
            font-size: 14px;
            padding: 10px;
        }
    }

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

                    <form method="POST" action="{{ url('/password/reset') }}">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                        <input type="hidden" name="token" value="{{ $token ?? old('token') }}">

                        <div class="mb-3">
                            <label for="password" class="form-label text-muted">{{ __('New Password') }}</label>
                            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" placeholder="Enter new password">
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label text-muted">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password">
                        </div>

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
