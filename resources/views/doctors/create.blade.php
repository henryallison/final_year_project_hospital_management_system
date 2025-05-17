<!-- resources/views/doctors/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white fw-bold fs-5">
            Add New Doctor
        </div>
        <div class="card-body">
            <form action="{{ route('doctors.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Specialization -->
                    <div class="col-md-6">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror" required>
                        @error('specialization') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror">
                        @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2"></textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Save Doctor</button>
                    <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
