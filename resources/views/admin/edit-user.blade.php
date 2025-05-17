@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit User: {{ $user->first_name }} {{ $user->last_name }}</h2>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Ensure method PUT for updates -->

        <div class="mb-3">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
            @error('first_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
            @error('last_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role">Role</label>
            <select name="role" class="form-control" required>
                <option value="doctor" @selected($user->role == 'doctor')>Doctor</option>
                <option value="nurse" @selected($user->role == 'nurse')>Nurse</option>
                <option value="admin" @selected($user->role == 'admin')>Admin</option>
            </select>
            @error('role')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
            @error('address')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}">
            @error('date_of_birth')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control">
                <option value="male" @selected($user->gender == 'male')>Male</option>
                <option value="female" @selected($user->gender == 'female')>Female</option>
                <option value="other" @selected($user->gender == 'other')>Other</option>
            </select>
            @error('gender')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
