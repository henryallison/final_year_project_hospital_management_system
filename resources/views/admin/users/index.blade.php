@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4 p-3">
        <h2 class="m-0">Manage Staff</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Add New User</a>
    <!-- Fixed controls container -->
    <div class="bg-white p-3 mb-3 rounded shadow-sm" style="position: sticky; top: 0; z-index: 100;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="dataTables_filter">
                    <label>Search:
                        <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="usersTable">
                    </label>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="dt-buttons btn-group flex-wrap" style="gap: 8px;">

                    <button class="btn btn-success buttons-excel buttons-html5" tabindex="0" aria-controls="usersTable" type="button">
                        <span>Excel</span>
                    </button>
                    <button class="btn btn-warning buttons-csv buttons-html5" tabindex="0" aria-controls="usersTable" type="button">
                        <span>CSV</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
    <table id="usersTable" class="table table-bordered text-center align-middle" style="white-space: nowrap;">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>License Number</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Is Active</th>
                <th>Last Login</th> <!-- New Column for Last Login -->
                <th>Last Login IP</th> <!-- New Column for Last IP -->
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
                <th>Profile Image</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->password }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>{{ $user->license_number }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '' }}</td>                
                <td>{{ ucfirst($user->gender) }}</td>
                <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
                <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Awaiting login' }}</td> <!-- Last Login -->
                <td>{{ $user->last_login_ip ? $user->last_login_ip : 'Awaiting login' }}</td> <!-- Last IP Address -->
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
                <td>
    <img src="{{ $user->profile_image
                ? (filter_var($user->profile_image, FILTER_VALIDATE_URL)
                    ? $user->profile_image
                    : asset('storage/' . $user->profile_image))
                : env('CLOUDINARY_DEFAULT_AVATAR', 'https://res.cloudinary.com/your-cloud/image/upload/default-avatar.png') }}"
         alt="Profile"
         style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
</td>

                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to permanently delete this user?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    /* Add horizontal lines between rows */
    #usersTable tbody tr {
        border-bottom: 1px solid #e0e0e0;
    }

    /* Add alternating row colors */
    #usersTable tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #usersTable tbody tr:nth-child(odd) {
        background-color: #ffffff;
    }

    /* Table styling */
    #usersTable th, #usersTable td {
        text-align: center;
        padding: 12px;
    }

    #usersTable thead th {
        background-color: #007bff;
        color: white;
    }

    #usersTable td {
        font-size: 14px;
    }

    #usersTable td a {
        margin-right: 5px;
    }

    /* Add hover effect on table rows */
    #usersTable tbody tr:hover {
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
</style>
@endpush

@push('scripts')
<!-- jQuery and DataTables JS -->
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
        var table = $('#usersTable').DataTable({
            paging: true,
            ordering: true,
            info: true,
            responsive: true,
            dom: 'lrtip', // We removed 'Bf' from dom since we're handling these elements manually
        });

        // Connect the search box to DataTables
        $('.dataTables_filter input').on('keyup change', function() {
            table.search(this.value).draw();
        });

        // Connect the buttons to DataTables
        $('.buttons-copy').on('click', function() {
            table.button('.buttons-copy').trigger();
        });

        $('.buttons-excel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        $('.buttons-csv').on('click', function() {
            table.button('.buttons-csv').trigger();
        });

        $('.buttons-print').on('click', function() {
            table.button('.buttons-print').trigger();
        });

        // Initialize the buttons
        new $.fn.dataTable.Buttons(table, {
            buttons: [
                'copy',
                'excel',
                'csv',
                'print'
            ]
        });
    });
</script>
@endpush
