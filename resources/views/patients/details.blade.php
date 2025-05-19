<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h4 class="mb-3">Basic Information</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td>{{ $patient->name }}</td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td>{{ $patient->date_of_birth->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ ucfirst($patient->gender) }}</td>
                    </tr>
                    <tr>
                        <th>Contact Number</th>
                        <td>{{ $decryptedData['contact_number'] }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $decryptedData['address'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <h4 class="mb-3">Medical Information</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Blood Type</th>
                        <td>{{ $decryptedData['blood_type'] }}</td>
                    </tr>
                    <tr>
                        <th>Height</th>
                        <td>{{ $decryptedData['height'] }} cm</td>
                    </tr>
                    <tr>
                        <th>Weight</th>
                        <td>{{ $decryptedData['weight'] }} kg</td>
                    </tr>
                    <tr>
                        <th>Chronic Conditions</th>
                        <td>{{ $decryptedData['chronic_conditions'] }}</td>
                    </tr>
                    <tr>
                        <th>Family Medical History</th>
                        <td>{{ $decryptedData['family_medical_history'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h4 class="mb-3">Medical Details</h4>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Medical History</h5>
                    <p class="card-text">{{ $patient->medical_history }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Allergies</h5>
                    <p class="card-text">{{ $patient->allergies }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h4 class="mb-3">Treatment Information</h4>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Current Medications</h5>
                    <p class="card-text">{{ $patient->current_medications }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Admission Details</h5>
                    <p><strong>Admission Date:</strong> {{ $patient->admission_date->format('Y-m-d') }}</p>
                    <p><strong>Discharge Date:</strong> {{ $patient->discharge_date ? $patient->discharge_date->format('Y-m-d') : 'Unknown' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($patient->status) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h4 class="mb-3">Assigned Staff</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                   <tr>
    <th>Doctor</th>
    <td>
        @if($patient->doctor)
            {{ $patient->doctor->first_name }} {{ $patient->doctor->last_name }}
        @else
            Unassigned
        @endif
    </td>
</tr>
<tr>
    <th>Nurse</th>
    <td>
        @if($patient->nurse)
            {{ $patient->nurse->first_name }} {{ $patient->nurse->last_name }}
        @else
            Unassigned
        @endif
    </td>
</tr>
                </table>
            </div>
        </div>
    </div>
</div>
