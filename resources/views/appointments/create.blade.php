@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Create New Appointment</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('appointments.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="patient_id" class="form-label">Patient</label>
                                    <select class="form-select @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" required>
                                        <option value="">Select Patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->name }} (ID: {{ $patient->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="doctor_id" class="form-label">Doctor</label>
                                    <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" required>
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} (ID: {{ $doctor->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="appointment_date" class="form-label">Appointment Date & Time</label>
                                    <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror"
                                           id="appointment_date" name="appointment_date"
                                           value="{{ old('appointment_date') }}" required>
                                    @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" style="margin-bottom: 1rem;">
    <label for="status" class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
    <div class="form-control" style="display: block;
                                    width: 100%;
                                    padding: 0.375rem 0.75rem;
                                    font-size: 1rem;
                                    font-weight: 400;
                                    line-height: 1.5;
                                    color: #212529;
                                    background-color: #f8f9fa;
                                    background-clip: padding-box;
                                    border: 1px solid #ced4da;
                                    border-radius: 0.375rem;
                                    cursor: not-allowed;">
        Scheduled
        <input type="hidden" name="status" value="scheduled">
    </div>
    @error('status')
    <div class="invalid-feedback" style="width: 100%;
                                        margin-top: 0.25rem;
                                        font-size: 0.875em;
                                        color: #dc3545;">
        {{ $message }}
    </div>
    @enderror
</div>
                            </div>

                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose</label>
                                <input type="text" class="form-control @error('purpose') is-invalid @enderror"
                                       id="purpose" name="purpose"
                                       value="{{ old('purpose') }}" required>
                                @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>Create Appointment
                                </button>
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
