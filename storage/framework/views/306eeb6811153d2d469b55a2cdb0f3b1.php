<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-0 fw-semibold">
                        <i class="fas fa-tasks me-2"></i><?php echo e(isset($task) ? 'Edit' : 'Create'); ?> Task
                    </h2>
                    <p class="mb-0 small opacity-75">
                        <?php echo e(isset($task) ? 'Update task details' : 'Create a new task assignment'); ?>

                    </p>
                </div>
                <?php if(isset($task)): ?>
                <span class="badge bg-white text-dark rounded-pill py-2 px-3 shadow-sm">
                    <i class="fas fa-circle me-1 small text-<?php echo e($task->statusBadgeColor); ?>"></i>
                    <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body p-4">
            <form id="taskForm" action="<?php echo e(isset($task) ? route('tasks.update', $task->id) : route('tasks.store')); ?>" method="POST" class="needs-validation" novalidate>
                <?php echo csrf_field(); ?>
                <?php if(isset($task)): ?>
                    <?php echo method_field('PUT'); ?>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Patient Selection -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="patient_id" id="patient_id" class="form-select <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select Patient</option>
                                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($patient->id); ?>"
                                        <?php echo e(old('patient_id', isset($task) ? $task->patient_id : '') == $patient->id ? 'selected' : ''); ?>>
                                        <?php echo e($patient->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="patient_id" class="text-muted small">Patient</label>
                            <div class="invalid-feedback">
                                Please select a patient
                            </div>
                            <?php $__errorArgs = ['patient_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Doctor Selection -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <?php if(auth()->user()->isDoctor()): ?>
                                <input type="hidden" name="doctor_id" value="<?php echo e(auth()->id()); ?>">
                                <input type="text" class="form-control bg-light" id="doctor_id_display"
                                       value="Dr. <?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?>" readonly>
                                <label for="doctor_id_display" class="text-muted small">Assigned Doctor</label>
                            <?php else: ?>
                                <select name="doctor_id" id="doctor_id" class="form-select <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Select Doctor</option>
                                    <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($doctor->id); ?>"
                                            <?php echo e(old('doctor_id', isset($task) ? $task->doctor_id : '') == $doctor->id ? 'selected' : ''); ?>>
                                            Dr. <?php echo e($doctor->first_name); ?> <?php echo e($doctor->last_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <label for="doctor_id" class="text-muted small">Assign Doctor</label>
                                <div class="invalid-feedback">
                                    Please select a doctor
                                </div>
                            <?php endif; ?>
                            <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Nurse Selection -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="nurse_id" id="nurse_id" class="form-select <?php $__errorArgs = ['nurse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select Nurse</option>
                                <?php $__currentLoopData = $nurses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nurse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($nurse->id); ?>"
                                        <?php echo e(old('nurse_id', isset($task) ? $task->nurse_id : '') == $nurse->id ? 'selected' : ''); ?>>
                                        Nurse <?php echo e($nurse->first_name); ?> <?php echo e($nurse->last_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <label for="nurse_id" class="text-muted small">Assign Nurse</label>
                            <div class="invalid-feedback">
                                Please select a nurse
                            </div>
                            <?php $__errorArgs = ['nurse_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="datetime-local" name="due_date" id="due_date"
                                   class="form-control <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
value="<?php echo e(old('due_date', isset($task) ? $task->due_date->format('Y-m-d\TH:i') : '')); ?>"
                            <label for="due_date" class="text-muted small">Due Date & Time</label>
                            <div class="invalid-feedback">
                                Please select a valid due date
                            </div>
                            <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" name="title" id="title"
                                   class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('title', isset($task) ? $task->title : '')); ?>"
                                   placeholder="Enter task title" required>
                            <label for="title" class="text-muted small">Task Title</label>
                            <div class="invalid-feedback">
                                Please provide a task title
                            </div>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea name="description" id="description"
                                      class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      style="height: 120px"
                                      placeholder="Enter detailed description" required><?php echo e(old('description', isset($task) ? $task->description : '')); ?></textarea>
                            <label for="description" class="text-muted small">Task Description</label>
                            <div class="invalid-feedback">
                                Please provide a task description
                            </div>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <?php if(isset($task)): ?>
                        <!-- Status Update Section -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-dark mb-3 d-flex align-items-center">
                                        <i class="fas fa-sync-alt me-2"></i>Status Update
                                    </h5>
                                    <div class="form-floating mb-3">
                                        <select name="status" id="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="pending" <?php echo e(old('status', $task->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                            <option value="in_progress" <?php echo e(old('status', $task->status) == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                                            <?php if(auth()->user()->isAdmin() || auth()->user()->isDoctor()): ?>
                                                <option value="completed" <?php echo e(old('status', $task->status) == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                                <option value="failed" <?php echo e(old('status', $task->status) == 'failed' ? 'selected' : ''); ?>>Failed</option>
                                            <?php endif; ?>
                                        </select>
                                        <label for="status" class="text-muted small">Current Status</label>
                                        <div class="invalid-feedback">
                                            Please select a status
                                        </div>
                                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <?php if(auth()->user()->can('complete', $task)): ?>
                                    <div class="form-check form-switch ps-0">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" id="mark_completed" name="mark_completed">
                                            <label class="form-check-label fw-medium" for="mark_completed">Mark as completed immediately</label>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-dark mb-3 d-flex align-items-center">
                                        <i class="fas fa-notes-medical me-2"></i>Clinical Notes
                                    </h5>
                                    <div class="form-floating">
                                        <textarea name="notes" id="notes"
                                                  class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                  style="height: 120px"
                                                  placeholder="Enter any additional notes"><?php echo e(old('notes', $task->notes)); ?></textarea>
                                        <label for="notes" class="text-muted small">Additional Notes</label>
                                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-lg btn-outline-secondary px-4 rounded-pill bg-primary text-white">
                        <i class="fas fa-arrow-left me-2 "></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-lg btn-dark px-4 rounded-pill shadow-sm bg-primary text-white" id="submitBtn">
                        <span id="submitText">
                            <i class="fas fa-save me-2"></i><?php echo e(isset($task) ? 'Update' : 'Create'); ?> Task
                        </span>
                        <span id="submitSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    :root {
        --primary-color: #0d6efd; /* Bootstrap blue */
        --primary-dark: #0a58ca;
        --light-color: #f8f9fa;
        --dark-color: #1a1f29;
        --border-radius: 12px;
    }

    body {
        background-color: #f0f4ff;
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.1);
    }

    .card-header {
        background: var(--primary-color);
        color: white;
        border-radius: 0 !important;
        padding: 1.5rem 2rem;
    }

    .rounded-xl {
        border-radius: var(--border-radius) !important;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .form-floating > label {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        border: 1px solid #cfe2ff;
        height: calc(3.5rem + 2px);
        transition: all 0.2s;
        background-color: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    textarea.form-control {
        min-height: 120px;
        height: auto !important;
    }

    .btn {
        border-radius: 50px;
        font-weight: 600;
        padding: 0.8rem 1.75rem;
        transition: all 0.3s;
        letter-spacing: 0.5px;
    }

    .btn-lg {
        padding: 0.9rem 2rem;
        font-size: 1rem;
    }

    .btn-outline-secondary {
        border: 2px solid #b6d4fe;
        color: #0d6efd;
    }

    .btn-outline-secondary:hover {
        background-color: #e7f1ff;
        color: #0a58ca;
        border-color: #9ec5fe;
    }

    .btn-dark {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }

    .btn-dark:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-1px);
    }

    .invalid-feedback {
        font-size: 0.8rem;
        margin-top: 0.35rem;
    }

    .form-check-input {
        width: 2.5em;
        height: 1.25em;
    }

    .form-check-label {
        font-size: 0.9rem;
    }

    .badge {
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.5rem 1rem;
        background-color: var(--primary-color);
        color: white;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(13, 110, 253, 0.15) !important;
    }

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(13, 110, 253, 0.1) !important;
    }

    .needs-validation .was-validated .form-control:invalid,
    .needs-validation .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
        padding-right: 1.25rem;
    }

    .spinner-border {
        vertical-align: middle;
        color: var(--primary-color);
    }
</style>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('taskForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');

        if (form) {
            form.addEventListener('submit', function(event) {
                // Check form validity before proceeding
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Show validation messages
                    form.classList.add('was-validated');

                    // If spinner is already showing, hide it
                    if (!submitSpinner.classList.contains('d-none')) {
                        submitSpinner.classList.add('d-none');
                        submitText.classList.remove('d-none');
                        submitBtn.disabled = false;
                    }
                    return;
                }

                // Only show spinner if form is valid
                submitBtn.disabled = true;
                submitText.classList.add('d-none');
                submitSpinner.classList.remove('d-none');
            });

            // Add input event listeners to all required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.addEventListener('input', function() {
                    // If field was invalid and now has value
                    if (this.value.trim() !== '' && this.classList.contains('is-invalid')) {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        }

        // Auto-set completed status if checkbox is checked
        const markCompleted = document.getElementById('mark_completed');
        const statusSelect = document.getElementById('status');

        if (markCompleted && statusSelect) {
            markCompleted.addEventListener('change', function() {
                if (this.checked) {
                    statusSelect.value = 'completed';
                    // Trigger validation
                    statusSelect.dispatchEvent(new Event('change'));
                }
            });
        }

        // Add current time to due date if empty for new tasks
        <?php if (! (isset($task))): ?>
            const dueDateField = document.getElementById('due_date');
            if (dueDateField && !dueDateField.value) {
                const now = new Date();
                const timezoneOffset = now.getTimezoneOffset() * 60000;
                const localISOTime = new Date(now - timezoneOffset).toISOString().slice(0, 16);
                dueDateField.value = localISOTime;
            }
        <?php endif; ?>
    });

    // Enhanced validation for all forms with class 'needs-validation'
    (function() {
        'use strict';

        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    // Find first invalid field and focus it
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }

                form.classList.add('was-validated');
            }, false);

            // Add real-time validation on blur
            form.querySelectorAll('input, select, textarea').forEach(input => {
                input.addEventListener('blur', () => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
            });
        });
    })();
</script>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/tasks/edit.blade.php ENDPATH**/ ?>