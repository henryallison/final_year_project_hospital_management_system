<?php
use Illuminate\Support\Facades\Crypt;
?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="mb-4 p-3">
        <h2 class="m-0">Manage Patients</h2>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

<?php if(auth()->user()->isAdmin()): ?>
    <a href="<?php echo e(route('patients.create')); ?>" class="btn btn-primary mb-3">Add New Patient</a>
<?php endif; ?>

    <!-- Fixed controls container -->
    <div class="bg-white p-3 mb-3 rounded shadow-sm" style="position: sticky; top: 0; z-index: 100;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="dataTables_filter">
                    <label>Search:
                        <input type="search" class="form-control form-control-sm" placeholder="" id="searchInput">
                    </label>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="dt-buttons btn-group flex-wrap" style="gap: 8px;">
                    <button class="btn btn-success buttons-excel buttons-html5" id="exportExcel">
                        <span>Excel</span>
                    </button>
                    <button class="btn btn-warning buttons-csv buttons-html5" id="exportCsv">
                        <span>CSV</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="patientsTable" class="table table-bordered text-center align-middle" style="white-space: nowrap;">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Vital Info</th>
                    <th>Medical History</th>
                    <th>Allergies</th>
                    <th>Current Meds</th>
                    <th>Doctor</th>
                    <th>Nurse</th>

                    <th>Admission Date</th>
                    <th>Discharge Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    try {
                        $decryptedData = json_decode(Crypt::decryptString($patient->encrypted_data), true);
                    } catch (\Exception $e) {
                        $decryptedData = [
                            'blood_type' => 'N/A',
                            'height' => 'N/A',
                            'weight' => 'N/A',
                            'chronic_conditions' => 'N/A',
                            'family_medical_history' => 'N/A'
                        ];
                    }
                ?>
                <tr>
                    <td><?php echo e($patient->id); ?></td>
                    <td><?php echo e($patient->name); ?></td>
                    <td><?php echo e($patient->date_of_birth->format('Y-m-d')); ?></td>
                    <td><?php echo e(ucfirst($patient->gender)); ?></td>
                    <td>
                        <span class="truncated-text"><?php echo e($decryptedData['contact_number'] ?? $patient->contact_number); ?></span>
                        <?php if(strlen($decryptedData['contact_number'] ?? $patient->contact_number) > 15): ?>
                        <button class="btn btn-link btn-sm view-more" data-fulltext="<?php echo e($decryptedData['contact_number'] ?? $patient->contact_number); ?>" title="View full contact">View</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="truncated-text"><?php echo e(Str::limit($decryptedData['address'] ?? $patient->address, 20)); ?></span>
                        <?php if(strlen($decryptedData['address'] ?? $patient->address) > 20): ?>
                        <button class="btn btn-link btn-sm view-more" data-fulltext="<?php echo e($decryptedData['address'] ?? $patient->address); ?>" title="View full address">View</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="truncated-text">
                            <strong>Blood:</strong> <?php echo e($decryptedData['blood_type']); ?><br>
                            <strong>Ht:</strong> <?php echo e($decryptedData['height']); ?> cm<br>
                            <strong>Wt:</strong> <?php echo e($decryptedData['weight']); ?> kg
                        </span>
                    </td>
                    <td>
                        <span class="truncated-text"><?php echo e(Str::limit($patient->medical_history, 20)); ?></span>
                        <?php if(strlen($patient->medical_history) > 20): ?>
                        <button class="btn btn-link btn-sm view-more" data-fulltext="<?php echo e($patient->medical_history); ?>" title="View full medical history">View</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="truncated-text"><?php echo e(Str::limit($patient->allergies, 20)); ?></span>
                        <?php if(strlen($patient->allergies) > 20): ?>
                        <button class="btn btn-link btn-sm view-more" data-fulltext="<?php echo e($patient->allergies); ?>" title="View full allergies">View</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="truncated-text"><?php echo e(Str::limit($patient->current_medications, 20)); ?></span>
                        <?php if(strlen($patient->current_medications) > 20): ?>
                        <button class="btn btn-link btn-sm view-more" data-fulltext="<?php echo e($patient->current_medications); ?>" title="View full medications">View</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($patient->doctor): ?>
                            <?php echo e($patient->doctor->first_name); ?> <?php echo e($patient->doctor->last_name); ?>

                        <?php else: ?>
                            Unassigned
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($patient->nurse): ?>
                            <?php echo e($patient->nurse->first_name); ?> <?php echo e($patient->nurse->last_name); ?>

                        <?php else: ?>
                            Unassigned
                        <?php endif; ?>
                    </td>

                    <td><?php echo e($patient->admission_date->format('Y-m-d')); ?></td>
                    <td><?php echo e($patient->discharge_date ? $patient->discharge_date->format('Y-m-d') : 'pending'); ?></td>
                    <td>
    <?php if(auth()->user()->canEditPatient($patient)): ?>
        <a href="<?php echo e(route('patients.edit', $patient->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
    <?php endif; ?>

    <?php if(auth()->user()->isAdmin()): ?>
        <form action="<?php echo e(route('patients.destroy', $patient->id)); ?>" method="POST" style="display:inline-block;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure you want to permanently delete this patient?')">
                Delete
            </button>
        </form>
    <?php endif; ?>
</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for displaying full content -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentModalLabel">Full Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be inserted here dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="copyToClipboard">Copy to Clipboard</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Hide DataTable's built-in controls */
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dt-buttons {
        display: none;
    }

    /* Rest of your styles */
    #patientsTable tbody tr {
        border-bottom: 1px solid #e0e0e0;
    }
    #patientsTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #patientsTable tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }
    #patientsTable th, #patientsTable td {
        text-align: center;
        padding: 12px;
    }
    #patientsTable thead th {
        background-color: #007bff;
        color: white;
    }
    #patientsTable td {
        font-size: 14px;
    }
    #patientsTable td a {
        margin-right: 5px;
    }
    #patientsTable tbody tr:hover {
        background-color: #e2e2e2;
    }
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
    .dt-buttons .btn {
        margin-left: 8px;
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 4px;
    }
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
    .truncated-text {
        display: inline-block;
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }
    .view-more {
        padding: 0;
        font-size: 0.8rem;
        vertical-align: middle;
    }
    .modal-body {
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-wrap: break-word; /* Add this line */
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#patientsTable').DataTable({
        dom: 'rtip', // Removed 'Bf' to hide built-in buttons and filter
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                className: 'btn btn-success',
                title: 'Patients_Export',
                exportOptions: {
                    columns: ':not(:last-child)',
                    format: {
                        body: function(data, row, column, node) {
                            if (column === 11) { // Status column
                                return $(node).text().trim();
                            }
                            if ($(node).hasClass('truncated-text')) {
                                return $(node).text().trim();
                            }
                            return data;
                        }
                    }
                }
            },
            {
                extend: 'csvHtml5',
                text: 'CSV',
                className: 'btn btn-warning',
                title: 'Patients_Export',
                exportOptions: {
                    columns: ':not(:last-child)',
                    format: {
                        body: function(data, row, column, node) {
                            if (column === 12) {
                                return $(node).text().trim();
                            }
                            if ($(node).hasClass('truncated-text')) {
                                return $(node).text().trim();
                            }
                            return data;
                        }
                    }
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

    // Search functionality using custom input
    $('#searchInput').keyup(function() {
        table.search($(this).val()).draw();
    });

    // View More functionality
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/patients/index.blade.php ENDPATH**/ ?>