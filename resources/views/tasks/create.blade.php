@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">{{ isset($task) ? 'Edit' : 'Create' }} Task</h2>
        </div>
        <div class="card-body">
            <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
                @csrf
                @if(isset($task))
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Patient Selection -->
                    <div class="col-md-6 mb-3">
                        <label for="patient_id" class="form-label">Patient</label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}"
                                    {{ (old('patient_id', isset($task) ? $task->patient_id : '') == $patient->id) ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Doctor Selection -->
                    <div class="col-md-6 mb-3">
                        <label for="doctor_id" class="form-label">Doctor</label>
                        @if(auth()->user()->isDoctor())
                            <input type="hidden" name="doctor_id" value="{{ auth()->id() }}">
                            <input type="text" class="form-control"
                                   value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" readonly>
                        @else
                            <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ (old('doctor_id', isset($task) ? $task->doctor_id : '') == $doctor->id) ? 'selected' : '' }}>
                                        {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nurse Selection -->
                    <div class="col-md-6 mb-3">
                        <label for="nurse_id" class="form-label">Nurse</label>
                        <select name="nurse_id" id="nurse_id" class="form-select @error('nurse_id') is-invalid @enderror" required>
                            <option value="">Select Nurse</option>
                            @foreach($nurses as $nurse)
                                <option value="{{ $nurse->id }}"
                                    {{ (old('nurse_id', isset($task) ? $task->nurse_id : '') == $nurse->id) ? 'selected' : '' }}>
                                    {{ $nurse->first_name }} {{ $nurse->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('nurse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="datetime-local" name="due_date" id="due_date"
                               class="form-control @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date', isset($task) ? $task->due_date->format('Y-m-d\TH:i') : '') }}" required>
                        @error('due_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="col-12 mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', isset($task) ? $task->title : '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="4" required>{{ old('description', isset($task) ? $task->description : '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(isset($task))
                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                                        <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ old('status', $task->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                                    @endif
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          rows="2">{{ old('notes', $task->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .form-control, .form-select {
        border-radius: 5px;
        padding: 10px;
    }
    textarea.form-control {
        min-height: 120px;
    }
    .btn {
        padding: 8px 20px;
        border-radius: 5px;
    }
    .invalid-feedback {
        display: block;
        margin-top: 5px;
    }
</style>
@endsection
