@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white fw-bold fs-5">
            Edit Patient - {{ $patient->name }}
        </div>
        <div class="card-body">
            <form action="{{ route('patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Personal Information Section -->
                    <div class="col-12">
                        <h5 class="mb-3 text-primary">Personal Information</h5>
                    </div>

                    <!-- Name -->
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $patient->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                               value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" required>
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Contact -->
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                               value="{{ old('contact_number', $decryptedData['contact_number'] ?? '') }}">
                        @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $decryptedData['address'] ?? '') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Sensitive Health Information Section (Will be encrypted) -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary">Sensitive Health Information (Encrypted)</h5>
                    </div>

                    <!-- Blood Type -->
                    <div class="col-md-4">
                        <label class="form-label">Blood Type</label>
                        <select name="blood_type" class="form-select @error('blood_type') is-invalid @enderror">
                            <option value="">-- Select Blood Type --</option>
                            <option value="A+" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type', $decryptedData['blood_type'] ?? '') === 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('blood_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Height -->
                    <div class="col-md-4">
                        <label class="form-label">Height (cm)</label>
                        <input type="number" name="height" class="form-control @error('height') is-invalid @enderror"
                               value="{{ old('height', $decryptedData['height'] ?? '') }}" min="50" max="250">
                        @error('height') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Weight -->
                    <div class="col-md-4">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror"
                               value="{{ old('weight', $decryptedData['weight'] ?? '') }}" min="2" max="300" step="0.1">
                        @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Chronic Conditions -->
                    <div class="col-md-6">
                        <label class="form-label">Chronic Conditions</label>
                        <textarea name="chronic_conditions" class="form-control @error('chronic_conditions') is-invalid @enderror" rows="3">{{ old('chronic_conditions', $decryptedData['chronic_conditions'] ?? '') }}</textarea>
                        @error('chronic_conditions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Family Medical History -->
                    <div class="col-md-6">
                        <label class="form-label">Family Medical History</label>
                        <textarea name="family_medical_history" class="form-control @error('family_medical_history') is-invalid @enderror" rows="3">{{ old('family_medical_history', $decryptedData['family_medical_history'] ?? '') }}</textarea>
                        @error('family_medical_history') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- General Health Information Section -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary">General Health Information</h5>
                    </div>

                    <!-- Medical History -->
                    <div class="col-md-6">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control @error('medical_history') is-invalid @enderror" rows="2">{{ old('medical_history', $patient->medical_history) }}</textarea>
                        @error('medical_history') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Allergies -->
                    <div class="col-md-6">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control @error('allergies') is-invalid @enderror" rows="2">{{ old('allergies', $patient->allergies) }}</textarea>
                        @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Current Medications -->
                    <div class="col-md-12">
                        <label class="form-label">Current Medications</label>
                        <textarea name="current_medications" class="form-control @error('current_medications') is-invalid @enderror" rows="2">{{ old('current_medications', $patient->current_medications) }}</textarea>
                        @error('current_medications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Hospitalization Information Section -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary">Hospitalization Information</h5>
                    </div>

                    <!-- Date of Admission -->
                    <div class="col-md-6">
                        <label class="form-label">Date of Admission</label>
                        <input type="date" name="admission_date" class="form-control @error('admission_date') is-invalid @enderror"
                               value="{{ old('admission_date', $patient->admission_date->format('Y-m-d')) }}" required>
                        @error('admission_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Select Status --</option>
                            <option value="active" {{ old('status', $patient->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="discharged" {{ old('status', $patient->status) === 'discharged' ? 'selected' : '' }}>Discharged</option>
                            <option value="transferred" {{ old('status', $patient->status) === 'transferred' ? 'selected' : '' }}>Transferred</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Date of Discharge (conditional) -->
                    <div class="col-md-6" id="dischargeDateField" style="display: {{ in_array($patient->status, ['discharged', 'transferred']) ? 'block' : 'none' }}">
                        <label class="form-label">Date of Discharge</label>
                        <input type="date" name="discharge_date" id="discharge_date"
                               class="form-control @error('discharge_date') is-invalid @enderror"
                               value="{{ old('discharge_date', $patient->discharge_date ? $patient->discharge_date->format('Y-m-d') : '') }}">
                        @error('discharge_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Doctor Field - Remove conditional display -->
<div class="col-md-6" id="doctorField">
    <label class="form-label">Assign Doctor</label>
    @if(auth()->user()->isAdmin())
        <select name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
            <option value="">-- Select Doctor --</option>
            @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ old('doctor_id', $patient->doctor_id) == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                </option>
            @endforeach
        </select>
    @else
        <!-- For non-admin doctors, show their name as readonly -->
        <input type="hidden" name="doctor_id" value="{{ auth()->id() }}">
        <input type="text" class="form-control" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" readonly>
    @endif
    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<!-- Nurse Field - Remove conditional display -->
<div class="col-md-6" id="nurseField">
    <label class="form-label">Assign Nurse (Optional)</label>
    <select name="nurse_id" class="form-select @error('nurse_id') is-invalid @enderror">
        <option value="">-- Select Nurse --</option>
        @foreach($nurses as $nurse)
            <option value="{{ $nurse->id }}" {{ old('nurse_id', $patient->nurse_id) == $nurse->id ? 'selected' : '' }}>
                {{ $nurse->first_name }} {{ $nurse->last_name }}
            </option>
        @endforeach
    </select>
    @error('nurse_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Update Patient</button>
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const dischargeDateField = document.getElementById('dischargeDateField');
        const dischargeDateInput = document.getElementById('discharge_date');

        // Function to toggle discharge date field
        function toggleFields() {
            if (statusSelect.value === 'active') {
                dischargeDateField.style.display = 'none';
                dischargeDateInput.removeAttribute('required');
                dischargeDateInput.value = '';
            } else {
                dischargeDateField.style.display = 'block';
                dischargeDateInput.setAttribute('required', 'required');
            }
        }

        // Initial check
        toggleFields();

        // Event listener for status change
        statusSelect.addEventListener('change', toggleFields);
    });
</script>
@endsection
