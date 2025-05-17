<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Healthcare System') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .navbar-nav .nav-item {
            margin-right: 5px;
        }
        .navbar-nav .btn {
            color: rgba(255, 255, 255, 0.8);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-nav .btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .user-role-badge {
            font-size: 0.7rem;
            vertical-align: middle;
            margin-left: 5px;
        }
        .dropdown-item.logout-btn {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 0.25rem 1.5rem;
        }
        .dropdown-item.logout-btn:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-hospital me-2"></i>{{ config('app.name', 'Healthcare System') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side - Main Navigation -->
                <ul class="navbar-nav me-auto">
                    @auth
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="btn btn-outline-light">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                    <li class="nav-item">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-light">
                <i class="fas fa-tasks me-1"></i> Tasks
            </a>
        </li>



                        <!-- Admin Links -->
                        @if(auth()->user()->isAdmin())
                        <!-- Patients -->
                        <li class="nav-item">
                            <a href="{{ route('patients.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-procedures me-1"></i> Patients
                            </a>
                        </li>
                            <li class="nav-item">
                                <a href="{{ route('appointments.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-calendar-alt me-1"></i> Appointments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-users-cog me-1"></i> Staff
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.logs.index') }}" class="btn btn-outline-light">
                                    <i class="fas fa-clipboard-list me-1"></i> System Logs
                                </a>
                            </li>
                        @endif

                        <!-- Doctor Links -->
@if(auth()->user()->isDoctor())
    <li class="nav-item">
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-light">
            <i class="fas fa-calendar-check me-1"></i> Appointments
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('patients.index', ['doctor' => auth()->id()]) }}" class="btn btn-outline-light">
            <i class="fas fa-user-md me-1"></i> My Patients
        </a>
    </li>
@endif

<!-- Nurse Links -->
@if(auth()->user()->isNurse())
    <li class="nav-item">
        <a href="{{ route('patients.index', ['nurse' => auth()->id()]) }}" class="btn btn-outline-light">
            <i class="fas fa-user-nurse me-1"></i>Assignments Patients
        </a>
    </li>
@endif

<!-- Profile Link for All Authenticated Users -->
@auth
    <li class="nav-item">
        <a href="{{ route('profile.show') }}" class="btn btn-outline-light">
            <i class="fas fa-user-cog me-1"></i> Manage Profile
        </a>
    </li>
@endauth
                    @endauth
                </ul>

                <!-- Right Side - User Menu -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-light">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>

                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ auth()->user()->name }}
                                <span class="badge bg-light text-primary user-role-badge">{{ ucfirst(auth()->user()->role) }}</span>
                            </a>

                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons and Export Plugins -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize Bootstrap dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    });
</script>

@stack('scripts')
</body>
</html>
