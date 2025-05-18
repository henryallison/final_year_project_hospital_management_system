@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">Profile Information</h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <!-- Profile Picture -->
                        <div class="profile-image-wrapper text-center mb-4">
    @php
    $profileImage = filter_var($user->profile_image, FILTER_VALIDATE_URL)
        ? $user->profile_image
        : ($user->profile_image
            ? asset('storage/' . $user->profile_image)
            : env('CLOUDINARY_DEFAULT_AVATAR', 'https://res.cloudinary.com/your-cloud/image/upload/default-avatar.png'));
@endphp

<div class="profile-image-wrapper text-center mb-4">
    <div class="avatar-container mx-auto">
        <img src="{{ $profileImage }}"
             class="profile-image"
             alt="{{ $user->name }}'s Profile"
             style="width:100%; height:100%; object-fit:cover; object-position:center;">
    </div>
</div>

<div class="profile-name-container mt-3 text-center text-md-start">
    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
    <div class="role-badge d-inline-block mt-2">{{ ucfirst($user->role) }}</div>
</div>

<!-- Profile Details -->
<div class="col-12 col-md-8 mt-3 mt-md-0">
    <div class="profile-details">
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="detail-item mb-3">
                    <div class="detail-label fw-bold">Email</div>
                    <div class="detail-value text-break">{{ $user->email }}</div>
                </div>

                <div class="detail-item mb-3">
                    <div class="detail-label fw-bold">Phone</div>
                    <div class="detail-value">{{ $user->phone ?? 'Not provided' }}</div>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <div class="detail-item mb-3">
                    <div class="detail-label fw-bold">Date of Birth</div>
                    <div class="detail-value">
                        @if($user->date_of_birth)
                            {{ \Carbon\Carbon::parse($user->date_of_birth)->format('F j, Y') }}
                        @else
                            Not provided
                        @endif
                    </div>
                </div>

                <div class="detail-item mb-3">
                    <div class="detail-label fw-bold">Address</div>
                    <div class="detail-value">{{ $user->address ?? 'Not provided' }}</div>
                </div>

                @if($user->license_number)
                <div class="detail-item mb-3">
                    <div class="detail-label fw-bold">License Number</div>
                    <div class="detail-value">{{ $user->license_number }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Super-sized Avatar Container */
    .avatar-container {
        width: 280px;  /* Increased from 180px */
        height: 280px; /* Increased from 180px */
        border-radius: 50%;
        border: 6px solid #ffffff; /* Thicker border */
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); /* Stronger shadow */
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        margin-bottom: 1.5rem; /* Added spacing */
    }

    .avatar-container:hover {
        transform: scale(1.06); /* More pronounced hover effect */
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2); /* Deeper shadow on hover */
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .avatar-container:hover .profile-image {
        transform: scale(1.05); /* Slightly stronger zoom */
    }

    /* Your existing unchanged styles below */
    .profile-details {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 1.5rem;
    }

    .detail-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .detail-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .detail-label {
        flex: 0 0 120px;
        font-weight: 500;
        color: #666;
    }

    .detail-value {
        flex: 1;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 10px;
    }
</style>
@endsection
