<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invento - @yield('title', 'Multitenant App')</title>
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

    @stack('styles')
</head>

<body class="d-flex">

    <!-- Sidebar -->
    @include('layout.fragments.sidemenu')

    <!-- Main Content -->
    <div class="d-flex flex-column flex-grow-1">

        <!-- Topbar -->
        @include('layout.fragments.topbar')

        <!-- Content Area -->
        <main class="content">
            @yield('body_content')
        </main>
    </div>

    <script src="{{ asset('bootstrap5/js/bootstrap.bundle.min.js') }}"></script>
    <link href="{{ asset('bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">

    @stack('scripts')
</body>

</html>