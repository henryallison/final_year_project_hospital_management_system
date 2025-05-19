@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
                        <h5 class="mb-0">Appointment Details</h5>
                        <span class="badge
                            @if($appointment->status === 'scheduled') bg-info
                            @elseif($appointment->status === 'completed') bg-success
                            @elseif($appointment->status === 'cancelled') bg-danger
                            @elseif($appointment->status === 'rescheduled') bg-warning text-dark
                            @else bg-secondary
                            @endif
                        ">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>

                    <div class="card-body bg-light-subtle">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">Patient Information</h6>
                                <p><strong>Name:</strong> {{ $appointment->patient->name ?? 'N/A' }}</p>
                                <p><strong>Contact:</strong> {{ $appointment->patient->contact_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Doctor Information</h6>
                                @if($appointment->doctor)
                                    <p><strong>Name:</strong> Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</p>
                                    <p><strong>Doctor ID:</strong> {{ $appointment->doctor->id }}</p>
                                @else
                                    <p><strong>Name:</strong> N/A</p>
                                    <p><strong>Doctor ID:</strong> N/A</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">Appointment Details</h6>
                                <p><strong>Date/Time:</strong> {{ $appointment->formatted_date }}</p>
                                <p><strong>Purpose:</strong> {{ $appointment->purpose }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Additional Information</h6>
                                <p><strong>Description:</strong></p>
                                <div class="border p-3 rounded bg-white text-muted">
                                    {{ $appointment->description ?? 'No additional description' }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>

                            <div class="d-flex gap-2 flex-wrap">
                                @if($appointment->status === 'scheduled')
                                    <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-times-circle me-2"></i>Cancel
                                        </button>
                                    </form>
                                    <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="fas fa-check-circle me-2"></i>Complete
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
