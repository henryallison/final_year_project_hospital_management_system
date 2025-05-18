@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h4 class="mb-0">Edit User: {{ $user->first_name }} {{ $user->last_name }}</h4>
                    </div>
                    <div class="card-body p-4">
<form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                                    @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                                    @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone *</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" required>
                                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Date of Birth *</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" required>
                                    @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender *</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="male" @selected($user->gender == 'male')>Male</option>
                                        <option value="female" @selected($user->gender == 'female')>Female</option>
                                        <option value="other" @selected($user->gender == 'other')>Other</option>
                                    </select>
                                    @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="role" class="form-label">Role *</label>
                                    <select name="role" class="form-select" required>
                                        <option value="doctor" @selected($user->role == 'doctor')>Doctor</option>
                                        <option value="nurse" @selected($user->role == 'nurse')>Nurse</option>
                                        <option value="admin" @selected($user->role == 'admin')>Admin</option>
                                    </select>
                                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="license_number" class="form-label">License Number *</label>
                                    <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $user->license_number) }}" required>
                                    @error('license_number') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                    <input type="password" name="password" class="form-control">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        <div class="col-md-6">
    <div class="form-group floating-label" style="position: relative;">
        <!-- Profile Image Upload -->
        <div class="d-flex flex-column align-items-center">
            <div class="avatar-preview mb-3 rounded-circle"
                 style="width: 120px;
                        height: 120px;
                        background-size: cover;
                        background-position: center;
                        background-image: url('{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('images/default-avatar.png') }}');">
            </div>

            <div class="w-100">
                <label for="profile_image" class="form-label">{{ __('Profile Image') }}</label>
                <input type="file" id="profile_image"
                       class="form-control @error('profile_image') is-invalid @enderror"
                       name="profile_image" accept="image/*">
                @error('profile_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>


                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                <button type="submit" class="btn btn-success px-4">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
