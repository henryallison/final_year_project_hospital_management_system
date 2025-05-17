@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-info text-white rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Report Generation
                    </h4>
                    <button class="btn btn-sm btn-light">
                        <i class="fas fa-sync-alt me-1"></i> Refresh
                    </button>
                </div>
                <div class="card-body">
                    <p class="lead text-muted">
                        Welcome to the reporting center. Here you can generate performance summaries, patient stats, and other useful reports.
                    </p>

                    <div class="row row-cols-1 row-cols-md-2 g-4 mt-4">
                        <!-- Staff Summary -->
                        <div class="col">
                            <div class="card border-start border-primary border-4 h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><i class="fas fa-users me-2"></i>Staff Summary</h5>
                                    <p class="card-text">Generate a detailed report of all registered staff and roles.</p>
                                    <a href="#" class="btn btn-outline-primary">Generate</a>
                                </div>
                            </div>
                        </div>

                        <!-- Patient Report -->
                        <div class="col">
                            <div class="card border-start border-success border-4 h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><i class="fas fa-user-injured me-2"></i>Patient Report</h5>
                                    <p class="card-text">View statistics and trends of all patient records and visits.</p>
                                    <a href="#" class="btn btn-outline-success">Generate</a>
                                </div>
                            </div>
                        </div>

                        <!-- Appointments Report -->
                        <div class="col">
                            <div class="card border-start border-warning border-4 h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-warning"><i class="fas fa-calendar-check me-2"></i>Appointments</h5>
                                    <p class="card-text">Export upcoming and past appointments by department or doctor.</p>
                                    <a href="#" class="btn btn-outline-warning text-dark">Generate</a>
                                </div>
                            </div>
                        </div>

                        <!-- Medication Summary -->
                        <div class="col">
                            <div class="card border-start border-danger border-4 h-100 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-danger"><i class="fas fa-pills me-2"></i>Medication Summary</h5>
                                    <p class="card-text">Download medication schedules and administration history for patients.</p>
                                    <a href="#" class="btn btn-outline-danger">Generate</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Future Enhancements -->
                    <div class="alert alert-secondary mt-5 rounded-3">
                        <i class="fas fa-lightbulb me-2"></i> More report types are coming soon. Have suggestions? Let us know!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
