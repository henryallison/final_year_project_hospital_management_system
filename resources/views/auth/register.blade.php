@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-xl-9 col-lg-10">
                @if(session('success'))
                    <div class="alert alert-modern alert-success alert-dismissible fade show mb-5">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="alert-content ms-3">
                                <strong>{{ session('success') }}</strong>
                                <div class="staff-details mt-3">
                                    <div class="detail-item">
                                        <span class="detail-label">Name:</span>
                                        <span class="detail-value">{{ session('staff_details')['name'] }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">{{ session('staff_details')['email'] }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Role:</span>
                                        <span class="badge bg-modern-primary">{{ session('staff_details')['role'] }}</span>
                                    </div>
                                    @if(session('staff_details')['license'])
                                        <div class="detail-item">
                                            <span class="detail-label">License:</span>
                                            <span class="detail-value">{{ session('staff_details')['license'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="modern-form-card">
                    <div class="form-header">
                        <h2 class="form-title">
                            <i class="fas fa-user-plus me-2"></i>
                            {{ __('Register New Staff') }}
                        </h2>
                        <p class="form-subtitle">Fill in the details below to register a new staff member</p>
                    </div>

                    <div class="form-body">
<form method="POST" action="{{ route('register') }}" class="modern-form" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-4">
                                <!-- Personal Info Section -->
                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                                               name="first_name" value="{{ old('first_name') }}" required autofocus placeholder=" ">
                                        <label for="first_name">{{ __('First Name') }}</label>
                                        @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                                               name="last_name" value="{{ old('last_name') }}" required placeholder=" ">
                                        <label for="last_name">{{ __('Last Name') }}</label>
                                        @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group floating-label">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}" required autocomplete="email" placeholder=" ">
                                        <label for="email">{{ __('Email Address') }}</label>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Professional Info Section -->
                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                            <option value=""></option>
                                            <option value="nurse" {{ old('role') == 'nurse' ? 'selected' : '' }}>Nurse</option>
                                            <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <label for="role">{{ __('Role') }}</label>
                                        @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="license_number" type="text" class="form-control @error('license_number') is-invalid @enderror"
                                               name="license_number" value="{{ old('license_number') }}" placeholder=" ">
                                        <label for="license_number">{{ __('License Number') }}</label>
                                        @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Info Section -->
                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone') }}" placeholder=" ">
                                        <label for="phone">{{ __('Phone Number') }}</label>
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                               name="date_of_birth" value="{{ old('date_of_birth') }}" placeholder=" ">
                                        <label for="date_of_birth">{{ __('Date of Birth') }}</label>
                                        @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group floating-label">
                                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                               name="address" value="{{ old('address') }}" placeholder=" ">
                                        <label for="address">{{ __('Address') }}</label>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                                            <option value=""></option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        <label for="gender">{{ __('Gender') }}</label>
                                        @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password Section -->
                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="new-password" placeholder=" ">
                                        <label for="password">{{ __('Password') }}</label>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group floating-label">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required autocomplete="new-password" placeholder=" ">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    </div>
                                </div>
                            <div class="col-md-6">
    <div class="form-group floating-label">
        <input id="profile_image" type="file" class="form-control @error('profile_image') is-invalid @enderror"
               name="profile_image" accept="image/*" placeholder=" ">
        <label for="profile_image">{{ __('Profile Image') }}</label>
        @error('profile_image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>


                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-modern-primary w-100 py-3">
                                        <i class="fas fa-user-plus me-2"></i>{{ __('Register Staff Member') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Color Scheme */
        :root {
            --modern-primary: #4361ee;
            --modern-primary-light: #eef2ff;
            --modern-secondary: #3f37c9;
            --modern-dark: #1e1e2d;
            --modern-light: #f8f9fa;
            --modern-border: #e0e0e0;
            --modern-success: #4cc9a0;
            --modern-danger: #f72585;
        }

        /* Base Styles */
        .modern-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 3rem;
        }

        .form-header {
            background: linear-gradient(135deg, var(--modern-primary), var(--modern-secondary));
            color: white;
            padding: 2.5rem 3rem;
        }

        .form-title {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            opacity: 0.9;
            font-weight: 400;
            margin-bottom: 0;
        }

        .form-body {
            padding: 3rem;
        }

        /* Floating Label Form Groups */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .floating-label label {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: #6c757d;
            transition: all 0.2s ease;
            pointer-events: none;
            background: white;
            padding: 0 0.5rem;
            transform-origin: left top;
        }

        .floating-label .form-control:focus ~ label,
        .floating-label .form-control:not(:placeholder-shown) ~ label,
        .floating-label .form-select:focus ~ label,
        .floating-label .form-select:not([value=""]) ~ label {
            transform: translateY(-1.2rem) scale(0.85);
            color: var(--modern-primary);
        }

        /* Form Controls */
        .form-control, .form-select {
            height: calc(3.5rem + 2px);
            padding: 1.5rem 1rem 0.5rem;
            border: 2px solid var(--modern-border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--modern-primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }

        /* Modern Buttons */
        .btn-modern-primary {
            background-color: var(--modern-primary);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 1rem 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-modern-primary:hover {
            background-color: var(--modern-secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-modern-primary:active {
            transform: translateY(0);
        }

        /* Modern Alert */
        .alert-modern {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
        }

        .alert-icon {
            font-size: 1.5rem;
            color: var(--modern-success);
        }

        .alert-content {
            flex: 1;
        }

        /* Staff Details */
        .staff-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
        }

        .detail-label {
            font-weight: 500;
            color: #6b7280;
        }

        .detail-value {
            font-weight: 600;
        }

        /* Badges */
        .badge {
            font-weight: 600;
            padding: 0.5em 0.8em;
            border-radius: 6px;
        }

        .bg-modern-primary {
            background-color: var(--modern-primary);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .form-header {
                padding: 1.5rem;
            }

            .form-body {
                padding: 1.5rem;
            }

            .staff-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
