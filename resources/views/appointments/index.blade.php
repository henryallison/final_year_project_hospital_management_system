@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-4 p-3">
            <h2 class="m-0">Manage Appointments</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
    <a href="{{ route('appointments.create') }}"
       style="display: inline-flex;
              align-items: center;
              padding: 0.5rem 1rem;
              font-size: 0.9rem;
              font-weight: 400;
              line-height: 1.5;
              color: #fff;
              background-color: #0d6efd;
              border: 1px solid #0d6efd;
              border-radius: 0.375rem;
              text-decoration: none;
              white-space: nowrap;
              transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
        <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>Add New Appointment
    </a>

    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <button id="exportExcel"
                style="display: inline-flex;
                       align-items: center;
                       padding: 0.5rem 1rem;
                       font-size: 0.9rem;
                       font-weight: 400;
                       line-height: 1.5;
                       color: #fff;
                       background-color: #198754;
                       border: 1px solid #198754;
                       border-radius: 0.375rem;
                       cursor: pointer;
                       white-space: nowrap;
                       transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
            <i class="fas fa-file-excel" style="margin-right: 0.5rem;"></i>Excel
        </button>
        <button id="exportCsv"
                style="display: inline-flex;
                       align-items: center;
                       padding: 0.5rem 1rem;
                       font-size: 0.9rem;
                       font-weight: 400;
                       line-height: 1.5;
                       color: #000;
                       background-color: #ffc107;
                       border: 1px solid #ffc107;
                       border-radius: 0.375rem;
                       cursor: pointer;
                       white-space: nowrap;
                       transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
            <i class="fas fa-file-csv" style="margin-right: 0.5rem;"></i>CSV
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
                @foreach($appointments as $appointment)
    <tr>
        <td>{{ $appointment->id }}</td>
        <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
<td>
    @if($appointment->doctor)
        Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}
    @else
        N/A
    @endif
</td>
        <td>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
        <td>
            <span class="truncated-text">{{ Str::limit($appointment->purpose, 30) }}</span>
            @if(strlen($appointment->purpose) > 30)
                <button class="btn btn-link btn-sm view-more"
                        data-fulltext="{{ $appointment->purpose }}"
                        title="View full purpose">
                    <i class="fas fa-eye"></i>
                </button>
            @endif
                        </td>
                        <td>
                        <span class="status-badge status-{{ $appointment->status }}">
                            {{ ucfirst($appointment->status) }}
                            </span>

                        </td>
                        <td>
    <div class="d-flex gap-1">
        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-info btn-sm" title="View">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                    onclick="return confirm('Are you sure you want to delete this appointment?')">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</td>

                    </tr>
                @endforeach
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

@endsection

@push('styles')
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

@endpush

@push('scripts')
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
@endpush
