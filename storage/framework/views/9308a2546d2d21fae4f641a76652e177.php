<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="mb-4 p-3">
        <h2 class="m-0">Manage Tasks</h2>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Task::class)): ?>
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary mb-3">Create New Task</a>
    <?php endif; ?>

    <!-- Fixed controls container -->
    <div class="bg-white p-3 mb-3 rounded shadow-sm" style="position: sticky; top: 0; z-index: 100;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="dataTables_filter">
                    <label>Search:
                        <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="tasksTable">
                    </label>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="dt-buttons btn-group flex-wrap" style="gap: 8px;">
                    <button class="btn btn-success buttons-excel buttons-html5" tabindex="0" aria-controls="tasksTable" type="button">
                        <span>Excel</span>
                    </button>
                    <button class="btn btn-warning buttons-csv buttons-html5" tabindex="0" aria-controls="tasksTable" type="button">
                        <span>CSV</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tasksTable" class="table table-bordered text-center align-middle" style="white-space: nowrap;">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Nurse</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($task->id); ?></td>
                    <td><?php echo e($task->title); ?></td>
                    <td><?php echo e($task->patient->name); ?></td>
                    <td><?php echo e($task->doctor?->first_name); ?> <?php echo e($task->doctor?->last_name); ?></td>
<td><?php echo e($task->nurse?->first_name); ?> <?php echo e($task->nurse?->last_name); ?></td>

                    <td><?php echo e($task->due_date->format('Y-m-d H:i')); ?></td>
                    <td>
                        <span class="badge bg-<?php echo e($task->statusBadgeColor); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $task->status))); ?>

                        </span>
                    </td>
                    <td><?php echo e($task->created_at->format('Y-m-d H:i')); ?></td>
                    <td><?php echo e($task->updated_at->format('Y-m-d H:i')); ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?php echo e(route('tasks.show', $task->id)); ?>" class="btn btn-sm btn-info">View</a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                                <a href="<?php echo e(route('tasks.edit', $task->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
                                <form action="<?php echo e(route('tasks.destroy', $task->id)); ?>" method="POST" style="display:inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    /* Add horizontal lines between rows */
    #tasksTable tbody tr {
        border-bottom: 1px solid #e0e0e0;
    }

    /* Add alternating row colors */
    #tasksTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #tasksTable tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Table styling */
    #tasksTable th, #tasksTable td {
        text-align: center;
        padding: 12px;
    }

    #tasksTable thead th {
        background-color: #007bff;
        color: white;
    }

    #tasksTable td {
        font-size: 14px;
    }

    #tasksTable td a {
        margin-right: 5px;
    }

    /* Add hover effect on table rows */
    #tasksTable tbody tr:hover {
        background-color: #e2e2e2;
    }

    /* Fixed controls styling */
    .dataTables_filter label {
        margin-bottom: 0;
    }

    .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }

    .dt-buttons {
        margin-bottom: 0;
    }

    /* Button styling */
    .dt-buttons .btn {
        margin-left: 8px;
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 4px;
    }

    /* Specific button colors */
    .buttons-copy {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .buttons-excel {
        background-color: #28a745;
        border-color: #28a745;
    }
    .buttons-csv {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    .buttons-print {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    /* Status badge colors */
    .badge.bg-secondary {
        background-color: #6c757d !important;
    }
    .badge.bg-primary {
        background-color: #0d6efd !important;
    }
    .badge.bg-success {
        background-color: #198754 !important;
    }
    .badge.bg-danger {
        background-color: #dc3545 !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function () {
        var table = $('#tasksTable').DataTable({
            paging: true,
            ordering: true,
            info: true,
            responsive: true,
            dom: 'lrtip', // We removed 'Bf' from dom since we're handling these elements manually
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: 'btn btn-success',
                    title: 'Tasks_Export',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    className: 'btn btn-warning',
                    title: 'Tasks_Export',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });

        // Connect the search box to DataTables
        $('.dataTables_filter input').on('keyup change', function() {
            table.search(this.value).draw();
        });

        // Connect the buttons to DataTables
        $('.buttons-excel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        $('.buttons-csv').on('click', function() {
            table.button('.buttons-csv').trigger();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/tasks/index.blade.php ENDPATH**/ ?>