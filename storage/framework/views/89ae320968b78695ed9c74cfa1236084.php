<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="mb-4 p-3">
            <h2 class="m-0">Manage Appointments</h2>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Appointment
            </a>

            <div class="d-flex gap-2">
    <button class="btn btn-success" id="exportExcel">
        <i class="fas fa-file-excel me-2"></i>Excel
    </button>
    <button class="btn btn-warning" id="exportCsv">
        <i class="fas fa-file-csv me-2"></i>CSV
    </button>
</div>

        </div>

        <!-- Search Box -->
        <div class="bg-white p-3 mb-3 rounded shadow-sm">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="search" class="form-control" placeholder="Search appointments..." id="searchInput">
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="table-responsive">
            <table id="appointmentsTable" class="table table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date/Time</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($appointment->id); ?></td>
        <td><?php echo e($appointment->patient->name ?? 'N/A'); ?></td>
<td>
    <?php if($appointment->doctor): ?>
        Dr. <?php echo e($appointment->doctor->first_name); ?> <?php echo e($appointment->doctor->last_name); ?>

    <?php else: ?>
        N/A
    <?php endif; ?>
</td>
        <td><?php echo e($appointment->appointment_date->format('M d, Y h:i A')); ?></td>
        <td>
            <span class="truncated-text"><?php echo e(Str::limit($appointment->purpose, 30)); ?></span>
            <?php if(strlen($appointment->purpose) > 30): ?>
                <button class="btn btn-link btn-sm view-more"
                        data-fulltext="<?php echo e($appointment->purpose); ?>"
                        title="View full purpose">
                    <i class="fas fa-eye"></i>
                </button>
            <?php endif; ?>
                        </td>
                        <td>
                        <span class="status-badge status-<?php echo e($appointment->status); ?>">
                            <?php echo e(ucfirst($appointment->status)); ?>

                            </span>

                        </td>
                        <td>
    <div class="d-flex gap-1">
        <a href="<?php echo e(route('appointments.show', $appointment->id)); ?>" class="btn btn-info btn-sm" title="View">
            <i class="fas fa-eye"></i>
        </a>
        <a href="<?php echo e(route('appointments.edit', $appointment->id)); ?>" class="btn btn-warning btn-sm" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <form action="<?php echo e(route('appointments.destroy', $appointment->id)); ?>" method="POST" class="d-inline">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                    onclick="return confirm('Are you sure you want to delete this appointment?')">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="contentModalLabel">Full Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be inserted here dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="copyToClipboard">
                        <i class="fas fa-copy me-2"></i>Copy to Clipboard
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        border-radius: 0.5rem;
        color: #fff;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
    }

    .status-scheduled {
        background-color: #0d6efd; /* Bootstrap primary */
    }

    .status-completed {
        background-color: #198754; /* Bootstrap success */
    }

    .status-cancelled {
        background-color: #dc3545; /* Bootstrap danger */
    }

    .status-rescheduled {
        background-color: #ffc107; /* Bootstrap warning */
        color: #000;
    }

    .truncated-text {
        display: inline-block;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#appointmentsTable').DataTable({
                dom: 'rtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        className: 'btn btn-success',
                        title: 'Appointments_Export',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        className: 'btn btn-warning',
                        title: 'Appointments_Export',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                paging: true,
                ordering: true,
                info: true,
                responsive: true
            });

            // Connect custom buttons to DataTable export
            $('#exportExcel').click(function() {
                table.button(0).trigger();
            });

            $('#exportCsv').click(function() {
                table.button(1).trigger();
            });

            // Search functionality
            $('#searchInput').keyup(function() {
                table.search($(this).val()).draw();
            });

            // View More functionality
            $(document).on('click', '.view-more', function() {
                const fullText = $(this).data('fulltext');
                $('#modalContent')
                    .css('white-space', 'pre-wrap')
                    .css('word-wrap', 'break-word')
                    .css('overflow-wrap', 'break-word')
                    .text(fullText);
                $('#contentModal').modal('show');
            });

            // Copy to clipboard functionality
            $('#copyToClipboard').click(function() {
                const content = $('#modalContent').text();
                navigator.clipboard.writeText(content).then(function() {
                    alert('Content copied to clipboard!');
                }, function() {
                    alert('Failed to copy content');
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/appointments/index.blade.php ENDPATH**/ ?>