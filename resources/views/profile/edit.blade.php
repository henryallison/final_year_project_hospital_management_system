@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8"> <!-- Changed to col-12 for better mobile handling -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">Edit Profile</h4>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                    </div>
                </div>

                <div class="card-body p-3"> <!-- Reduced padding for mobile -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Image Upload -->
                        <div class="text-center mb-4">
                            <div class="avatar-upload mx-auto" style="max-width: 150px;">
                                <div class="avatar-preview rounded-circle"
                                     style="width: 120px; height: 120px; background-size: cover; background-position: center; background-image: url('{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('images/default-avatar.png') }}');">
                                </div>
                                <label for="profile_image" class="btn btn-primary mt-3 w-100">
                                    <i class="fas fa-camera me-2"></i> Change Photo
                                    <input type="file" id="profile_image" name="profile_image" accept=".png, .jpg, .jpeg" class="d-none">
                                </label>
                                @error('profile_image')
                                    <div class="invalid-feedback d-block text-center">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="row g-2"> <!-- Added g-2 for consistent gutter spacing -->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-2">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       id="date_of_birth" name="date_of_birth"
                                       value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone"
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Change Section -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body p-3"> <!-- Reduced padding -->
                                <h5 class="card-title mb-3">Change Password</h5>

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 w-100 w-md-auto"> <!-- Added w-100 for mobile -->
                                <i class="fas fa-save me-2"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@push('scripts')
<script>
    // Preview profile image before upload
    document.getElementById('profile_image').addEventListener('change', function(event) {
        const preview = document.querySelector('.avatar-preview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.style.backgroundImage = 'url(' + e.target.result + ')';
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });

    // Password field validation logic
    document.querySelector('form').addEventListener('submit', function(e) {
        const currentPass = document.getElementById('current_password').value;
        const newPass = document.getElementById('password').value;
        const confirmPass = document.getElementById('password_confirmation').value;

        // Check if any password field has content
        if (currentPass || newPass || confirmPass) {
            // Validate that all fields are filled
            if (!currentPass || !newPass || !confirmPass) {
                e.preventDefault();
                alert('Please fill all password fields if you want to change your password');
                return false;
            }

            // Validate that new passwords match
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('New password and confirmation password do not match');
                return false;
            }

            // Validate password strength (optional)
            if (newPass.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return false;
            }
        }

        return true;
    });
</script>
@endpush
@endpush
@endsection
