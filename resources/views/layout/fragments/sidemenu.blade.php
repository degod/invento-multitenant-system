<!-- Start Sidebar -->
<nav class="sidebar d-flex flex-column p-3">
    <h4 class="text-white">Invento</h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('dashboard') }}" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-speedometer2"></span> Dashboard
            </a>
        </li>
        @admin
        <li>
            <a href="#userMenu" data-bs-toggle="collapse" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-people"></span>User Management
                <span class="ms-auto bi bi-caret-down-fill"></span>
            </a>
            <ul class="collapse list-unstyled ps-3" id="userMenu">
                <li><a href="{{ route('users.index') }}" class="nav-link">Users</a></li>
            </ul>
        </li>
        <li>
            <a href="{{ route('tenants.index') }}" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-person"></span> Tenants
            </a>
        </li>
        @endadmin
        <li>
            <a href="{{ route('buildings.index') }}" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-building"></span> Buildings
            </a>
        </li>
        <li>
            <a href="{{ route('flats.index') }}" class="nav-link text-white d-flex align-items-center">
                <span class="me-2 bi bi-house"></span> Flats
            </a>
        </li>
    </ul>
</nav>
<!-- End Sidebar -->