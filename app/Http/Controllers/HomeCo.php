@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Welcome, {{ $user->name }} <span class="badge bg-{{ $user->roleBadgeColor() }}">{{ ucfirst($user->role) }}</span></h2>
            <p class="text-muted">
                Last login: {{ $user->last_login_at?->format('M j, Y g:i a') ?? 'First login' }} |
                Department: {{ $user->department ?? 'General' }}
            </p>
        </div>
    </div>

    <!-- Stats Dashboard -->
    <div class="row mb-4">
        <!-- Common Stats -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Patients</h5>
                    <p class="card-text display-4">{{ $stats['total_patients'] }}</p>
                    <a href="{{ route('patients.index') }}" class="text-white">View All</a>
                </div>
            </div>
        </div>

        <!-- Doctor-Specific Stats -->
        @if($user->isDoctor())
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">My Patients</h5>
                    <p class="card-text display-4">{{ $stats['my_patients'] }}</p>
                    <a href="{{ route('patients.index', ['doctor' => $user->id]) }}" class="text-white">View My Patients</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Active Cases</h5>
                    <p class="card-text display-4">{{ $stats['active_cases'] }}</p>
                    <a href="{{ route('patients.index', ['doctor' => $user->id, 'status' => 'active']) }}" class="text-white">
                        View Active
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Nurse-Specific Stats -->
        @if($user->isNurse())
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Assigned Patients</h5>
                    <p class="card-text display-4">{{ $stats['assigned_patients'] }}</p>
                    <a href="{{ route('patients.index', ['nurse' => $user->id]) }}" class="text-dark">View My Assignments</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Medications Due</h5>
                    <p class="card-text display-4">{{ $stats['medications_due'] }}</p>
                    <a href="{{ route('medications.index', ['nurse' => $user->id]) }}" class="text-white">
                        View Schedule
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Admin-Specific Stats -->
        @if($user->isAdmin())
        <div class="col-md-4">
            <div class="card text-white bg-dark mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Staff</h5>
                    <p class="card-text display-4">{{ $stats['total_staff'] }}</p>
                    <a href="{{ route('users.index') }}" class="text-white">Manage Staff</a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Patients Table -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Recent Patients</h5>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-secondary" id="refresh-patients">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Doctor</th>
                            @if($user->isNurse())<th>Nurse</th>@endif
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_patients'] as $patient)
                        <tr>
                            <td>
                                <a href="{{ route('patients.show', $patient) }}">
                                    {{ $patient->name }}
                                </a>
                            </td>
                            <td>{{ $patient->age }} yrs</td>
                            <td>
                                <span class="badge bg-{{ $patient->statusBadgeColor() }}">
                                    {{ ucfirst($patient->status) }}
                                </span>
                            </td>
                            <td>{{ $patient->doctor->name }}</td>
                            @if($user->isNurse())
                            <td>{{ $patient->nurse?->name ?? 'Unassigned' }}</td>
                            @endif
                            <td>{{ $patient->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($user->canEditPatient($patient))
                                <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $user->isNurse() ? 7 : 6 }}" class="text-center">No patients found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h5>Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('patients.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Add New Patient
                </a>

                @if($user->isDoctor())
                <a href="{{ route('appointments.create') }}" class="btn btn-success">
                    <i class="fas fa-calendar-plus me-1"></i> Create Appointment
                </a>
                @endif

                @if($user->isNurse())
                <a href="{{ route('medications.create') }}" class="btn btn-warning">
                    <i class="fas fa-pills me-1"></i> Record Medication
                </a>
                @endif

                @if($user->isAdmin())
                <a href="{{ route('reports.generate') }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-1"></i> Generate Reports
                </a>
                @endif

                <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list me-1"></i> View All Patients
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh patients every 60 seconds
    setInterval(function() {
        $.get('{{ route('patients.recent') }}', function(data) {
            $('table tbody').html(data);
        });
    }, 60000);

    // Manual refresh button
    $('#refresh-patients').click(function() {
        $.get('{{ route('patients.recent') }}', function(data) {
            $('table tbody').html(data);
            toastr.success('Patient list updated');
        });
    });
</script>
@endpush
@endsection
