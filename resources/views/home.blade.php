@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="mb-2 fw-bold">Welcome, {{ $user->first_name }} {{ $user->last_name }}</h2>
            <p class="text-muted">
                You logged in: {{ $user->last_login_at?->format('M j, Y g:i a') ?? 'First login' }}. Hope you have a nice day.
            </p>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row justify-content-center g-4">
        <!-- Total Patients Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4 text-center">
                    <div class="icon-circle bg-primary-light mb-4 mx-auto">
                        <i class="fas fa-users text-primary fs-3"></i>
                    </div>
                    <h5 class="card-title text-dark mb-3">Total Patients</h5>
                    <p class="display-4 fw-bold text-primary mb-2">{{ $stats['total_patients'] ?? 0 }}</p>
                    <small class="text-muted">All registered patients in the system</small>
                </div>
            </div>
        </div>

        <!-- Tasks Card -->
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4 text-center">
                    <div class="icon-circle bg-info-light mb-4 mx-auto">
                        <i class="fas fa-tasks text-info fs-3"></i>
                    </div>
                    <h5 class="card-title text-dark mb-3">
                        @if($user->isAdmin())
                            Total Tasks
                        @elseif($user->isDoctor())
                            My Tasks
                        @elseif($user->isNurse())
                            Assigned Tasks
                        @endif
                    </h5>
                    <p class="display-4 fw-bold text-info mb-2">{{ $stats['total_tasks'] ?? 0 }}</p>
                    <small class="text-muted">
                        @if($user->isAdmin())
                            All tasks in the system
                        @elseif($user->isDoctor())
                            Tasks assigned to you
                        @elseif($user->isNurse())
                            Tasks under your care
                        @endif
                    </small>
                </div>
            </div>
        </div>

        @if($user->isDoctor())
            <!-- My Patients Card -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-success-light mb-4 mx-auto">
                            <i class="fas fa-user-md text-success fs-3"></i>
                        </div>
                        <h5 class="card-title text-dark mb-3">My Patients</h5>
                        <p class="display-4 fw-bold text-success mb-2">{{ $stats['my_patients'] ?? 0 }}</p>
                        <small class="text-muted">Patients under your direct care</small>
                    </div>
                </div>
            </div>
        @endif

        @if($user->isNurse())
            <!-- Assigned Patients Card -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-warning-light mb-4 mx-auto">
                            <i class="fas fa-user-nurse text-warning fs-3"></i>
                        </div>
                        <h5 class="card-title text-dark mb-3">Assigned Patients</h5>
                        <p class="display-4 fw-bold text-warning mb-2">{{ $stats['assigned_patients'] ?? 0 }}</p>
                        <small class="text-muted">Patients under your care</small>
                    </div>
                </div>
            </div>
        @endif

        @if($user->isAdmin())
            <!-- Total Staff Card -->
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-dark-light mb-4 mx-auto">
                            <i class="fas fa-id-card-alt text-dark fs-3"></i>
                        </div>
                        <h5 class="card-title text-dark mb-3">Total Staff</h5>
                        <p class="display-4 fw-bold text-dark mb-2">{{ $stats['total_staff'] ?? 0 }}</p>
                        <small class="text-muted">All registered medical and admin staff</small>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->role === 'doctor' || auth()->user()->role === 'admin')
    <!-- Appointment Overview Card -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg h-100">
            <div class="card-body p-4 text-center">
                <div class="icon-circle bg-purple-light mb-4 mx-auto">
                    <i class="fas fa-calendar-check text-purple fs-3"></i>
                </div>
                <h5 class="card-title text-dark mb-3">Appointment Overview</h5>
                <div class="chart-container mx-auto" style="height: 120px; width: 120px;">
                    <canvas id="appointmentChart" width="120" height="120"></canvas>
                </div>
                <small class="text-muted mt-3">Appointments by status</small>
            </div>
        </div>
    </div>
@endif
</div>

@push('styles')
<style>
    .icon-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
    .bg-info-light { background-color: rgba(23, 162, 184, 0.1); }
    .bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
    .bg-dark-light { background-color: rgba(33, 37, 41, 0.1); }
    .bg-purple-light { background-color: rgba(111, 66, 193, 0.1); }
    .text-purple { color: #6f42c1; }
    .card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush

@push('scripts')
    <script>
        const ctx = document.getElementById('appointmentChart').getContext('2d');
        const appointmentChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Scheduled', 'Completed', 'Cancelled', 'Rescheduled'],
                datasets: [{
                    data: [
                        {{ $stats['scheduled_appointments'] ?? 0 }},
                        {{ $stats['completed_appointments'] ?? 0 }},
                        {{ $stats['cancelled_appointments'] ?? 0 }},
                        {{ $stats['rescheduled_appointments'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.8)',
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                    cutout: '65%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Auto-refresh stats every 60 seconds
        setInterval(function() {
            location.reload();
        }, 60000);
    </script>
@endpush
@endsection
