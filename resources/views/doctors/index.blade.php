<!-- resources/views/doctors/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white fw-bold fs-5">
            All Doctors
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <a href="{{ route('doctors.create') }}" class="btn btn-sm btn-success mb-3">Add New Doctor</a>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->name }}</td>
                            <td>{{ $doctor->specialization }}</td>
                            <td>{{ $doctor->contact_number }}</td>
                            <td>{{ $doctor->address }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No doctors found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
