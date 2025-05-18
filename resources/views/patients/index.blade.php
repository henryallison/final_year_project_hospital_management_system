@extends('layouts.app')
@php
use Illuminate\Support\Facades\Crypt;
@endphp

@section('content')
<div class="container">
    <div class="mb-4 p-3">
        <h2 class="m-0">Manage Patients</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

@if(auth()->user()->isAdmin())
    <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Add New Patient</a>
@endif

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
                @foreach($patients as $patient)
                @php
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
                @endphp
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->date_of_birth->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($patient->gender) }}</td>
                    <td>
                        <span class="truncated-text">{{ $decryptedData['contact_number'] ?? $patient->contact_number }}</span>
                        @if(strlen($decryptedData['contact_number'] ?? $patient->contact_number) > 15)
                        <button class="btn btn-link btn-sm view-more" data-fulltext="{{ $decryptedData['contact_number'] ?? $patient->contact_number }}" title="View full contact">View</button>
                        @endif
                    </td>
                    <td>
                        <span class="truncated-text">{{ Str::limit($decryptedData['address'] ?? $patient->address, 20) }}</span>
                        @if(strlen($decryptedData['address'] ?? $patient->address) > 20)
                        <button class="btn btn-link btn-sm view-more" data-fulltext="{{ $decryptedData['address'] ?? $patient->address }}" title="View full address">View</button>
                        @endif
                    </td>
                    <td>
                        <span class="truncated-text">
                            <strong>Blood:</strong> {{ $decryptedData['blood_type'] }}<br>
                            <strong>Ht:</strong> {{ $decryptedData['height'] }} cm<br>
                            <strong>Wt:</strong> {{ $decryptedData['weight'] }} kg
                        </span>
                    </td>
                    <td>
                        <span class="truncated-text">{{ Str::limit($patient->medical_history, 20) }}</span>
                        @if(strlen($patient->medical_history) > 20)
                        <button class="btn btn-link btn-sm view-more" data-fulltext="{{ $patient->medical_history }}" title="View full medical history">View</button>
                        @endif
                    </td>
                    <td>
                        <span class="truncated-text">{{ Str::limit($patient->allergies, 20) }}</span>
                        @if(strlen($patient->allergies) > 20)
                        <button class="btn btn-link btn-sm view-more" data-fulltext="{{ $patient->allergies }}" title="View full allergies">View</button>
                        @endif
                    </td>
                    <td>
                        <span class="truncated-text">{{ Str::limit($patient->current_medications, 20) }}</span>
                        @if(strlen($patient->current_medications) > 20)
                        <button class="btn btn-link btn-sm view-more" data-fulltext="{{ $patient->current_medications }}" title="View full medications">View</button>
                        @endif
                    </td>
                    <td>
                        @if($patient->doctor)
                            {{ $patient->doctor->first_name }} {{ $patient->doctor->last_name }}
                        @else
                            Unassigned
                        @endif
                    </td>
                    <td>
                        @if($patient->nurse)
                            {{ $patient->nurse->first_name }} {{ $patient->nurse->last_name }}
                        @else
                            Unassigned
                        @endif
                    </td>

                    <td>{{ $patient->admission_date->format('Y-m-d') }}</td>
                    <td>{{ $patient->discharge_date ? $patient->discharge_date->format('Y-m-d') : 'pending' }}</td>
                    <td>
    @if(auth()->user()->canEditPatient($patient))
        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Edit</a>
    @endif

    <!-- Add this View button -->
    <button class="btn btn-info btn-sm view-patient" data-patient-id="{{ $patient->id }}">View</button>

    @if(auth()->user()->isAdmin())
        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"
                onclick="return confirm('Are you sure you want to permanently delete this patient?')">
                Delete
            </button>
        </form>
    @endif
</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Patient Details Modal -->
<div class="modal fade" id="patientDetailsModal" tabindex="-1" aria-labelledby="patientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="patientDetailsModalLabel">Patient Full Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="patientDetailsContent">
                <!-- Content will be loaded here via AJAX -->
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
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
@endsection

@push('styles')
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
@endpush

@push('scripts')
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

    // Add this to your existing $(document).ready() function
$(document).on('click', '.view-patient', function() {
    const patientId = $(this).data('patient-id');
    const modal = $('#patientDetailsModal');

    // Show loading spinner
    $('#patientDetailsContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);

    // Show modal
    modal.modal('show');

    // Load patient details via AJAX
    $.get(`/patients/${patientId}/details`, function(data) {
        $('#patientDetailsContent').html(data);
    }).fail(function() {
        $('#patientDetailsContent').html(`
            <div class="alert alert-danger">
                Failed to load patient details. Please try again.
            </div>
        `);
    });
});

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
@endpush
