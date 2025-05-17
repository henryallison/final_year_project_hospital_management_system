@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Appointment Details</h5>
                        <div class="badge bg-{{ $appointment->statusBadgeColor }} status-badge">
                            {{ ucfirst($appointment->status) }}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                           <div class="col-md-6">
    <h6>Patient Information</h6>
    <p><strong>Name:</strong> {{ $appointment->patient->name ?? 'N/A' }}</p>
    <p><strong>Contact:</strong> {{ $appointment->patient->contact_number ?? 'N/A' }}</p>
</div>
                            <div class="col-md-6">
    <h6>Doctor Information</h6>
    @if($appointment->doctor)
        <p><strong>Name:</strong> Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</p>
        <p><strong>Doctor ID:</strong> {{ $appointment->doctor->id }}</p>
    @else
        <p><strong>Name:</strong> N/A</p>
        <p><strong>Doctor ID:</strong> N/A</p>
    @endif
</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Appointment Details</h6>
                                <p><strong>Date/Time:</strong> {{ $appointment->formatted_date }}</p>
                                <p><strong>Purpose:</strong> {{ $appointment->purpose }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Additional Information</h6>
                                <p><strong>Description:</strong></p>
                                <div class="border p-2 rounded bg-light">
                                    {{ $appointment->description ?? 'No additional description' }}
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>

                            <div class="d-flex gap-2 flex-wrap">
    @if($appointment->status === 'scheduled')
        <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-times-circle me-2"></i>Cancel
            </button>
        </form>
        <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
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
