@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-0">
            <h2 class="h5 mb-0 fw-bold">Task Details</h2>
            <span class="badge rounded-pill bg-{{ $task->statusBadgeColor }} py-2 px-3">
                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
            </span>
        </div>

        <div class="card-body">
            <!-- Staff Information Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-user-injured me-2"></i>Patient
                            </h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user-circle me-2 text-muted"></i>
                                <p class="mb-0"><strong>Name:</strong> {{ $task->patient?->name ?? 'Not assigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-user-md me-2"></i>Doctor
                            </h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-briefcase-medical me-2 text-muted"></i>
                                <p class="mb-0"><strong>Name:</strong> {{ $task->doctor?->first_name }} {{ $task->doctor?->last_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-user-nurse me-2"></i>Nurse
                            </h5>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-syringe me-2 text-muted"></i>
                                <p class="mb-0"><strong>Name:</strong> {{ $task->nurse?->first_name }} {{ $task->nurse?->last_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Details Section -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-tasks me-2"></i>Task Details
                            </h5>
                            <div class="mb-3">
                                <h6 class="fw-bold">Title</h6>
                                <p class="ps-3">{{ $task->title }}</p>
                            </div>
                            <div>
                                <h6 class="fw-bold">Description</h6>
                                <div class="ps-3 p-2 bg-light rounded">
                                    {{ $task->description ?? 'No description provided' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-clock me-2"></i>Timing
                            </h5>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="fw-bold">Created:</span>
                                <span>{{ $task->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="fw-bold">Due Date:</span>
                                <span class="{{ $task->isOverdue ? 'text-danger' : '' }}">
                                    {{ $task->due_date->format('M d, Y H:i') }}
                                    @if($task->isOverdue)
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @endif
                                </span>
                            </div>
                            @if($task->completed_at)
                            <div class="d-flex justify-content-between py-2">
                                <span class="fw-bold">Completed:</span>
                                <span>{{ $task->completed_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($task->notes)
            <div class="mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </h5>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($task->notes)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div class="d-flex gap-2">
                    @can('update', $task)
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning px-4">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    @endcan
                    @can('delete', $task)
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4" onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="fas fa-trash-alt me-2"></i>Delete
                            </button>
                        </form>
                    @endcan
                </div>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .badge {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .text-primary {
        color: #4e73df !important;
    }
</style>
@endpush
