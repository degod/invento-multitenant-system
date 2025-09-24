<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invento Dashboard</title>
    <link href="{{ asset('bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: #495057;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>

<body class="d-flex">

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3">
        <h4 class="text-white">Invento</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="#userMenu" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center">
                    <span class="me-2 bi bi-people"></span>User Management
                </a>
                <ul class="collapse list-unstyled ps-3" id="userMenu">
                    <li><a href="#" class="nav-link">Users</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="d-flex flex-column flex-grow-1">

        <!-- Topbar -->
        <nav class="topbar navbar navbar-expand navbar-light px-3">
            <div class="container-fluid">
                <div class="ms-auto">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <main class="content">
            <h3>Dashboard</h3>
            <p>Welcome to your dashboard.</p>
        </main>
    </div>

    <script src="{{ asset('bootstrap5/js/bootstrap.bundle.min.js') }}"></script>
    <link href="{{ asset('bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
</body>

</html>