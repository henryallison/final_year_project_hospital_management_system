<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Appointment Details</h5>
                        <div class="badge bg-<?php echo e($appointment->statusBadgeColor); ?> status-badge">
                            <?php echo e(ucfirst($appointment->status)); ?>

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                           <div class="col-md-6">
    <h6>Patient Information</h6>
    <p><strong>Name:</strong> <?php echo e($appointment->patient->name ?? 'N/A'); ?></p>
    <p><strong>Contact:</strong> <?php echo e($appointment->patient->contact_number ?? 'N/A'); ?></p>
</div>
                            <div class="col-md-6">
    <h6>Doctor Information</h6>
    <?php if($appointment->doctor): ?>
        <p><strong>Name:</strong> Dr. <?php echo e($appointment->doctor->first_name); ?> <?php echo e($appointment->doctor->last_name); ?></p>
        <p><strong>Doctor ID:</strong> <?php echo e($appointment->doctor->id); ?></p>
    <?php else: ?>
        <p><strong>Name:</strong> N/A</p>
        <p><strong>Doctor ID:</strong> N/A</p>
    <?php endif; ?>
</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Appointment Details</h6>
                                <p><strong>Date/Time:</strong> <?php echo e($appointment->formatted_date); ?></p>
                                <p><strong>Purpose:</strong> <?php echo e($appointment->purpose); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Additional Information</h6>
                                <p><strong>Description:</strong></p>
                                <div class="border p-2 rounded bg-light">
                                    <?php echo e($appointment->description ?? 'No additional description'); ?>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo e(route('appointments.edit', $appointment->id)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>

                            <div class="d-flex gap-2 flex-wrap">
    <?php if($appointment->status === 'scheduled'): ?>
        <form action="<?php echo e(route('appointments.cancel', $appointment->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-times-circle me-2"></i>Cancel
            </button>
        </form>
        <form action="<?php echo e(route('appointments.complete', $appointment->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle me-2"></i>Complete
            </button>
        </form>
    <?php endif; ?>

    <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/appointments/show.blade.php ENDPATH**/ ?>