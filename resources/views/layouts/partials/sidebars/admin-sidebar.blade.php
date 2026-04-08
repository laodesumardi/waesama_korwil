<div class="d-flex">

    <!-- HAMBURGER BUTTON -->
    <button class="btn btn-warning d-md-none m-2" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- SIDEBAR -->
    <div class="sidebar d-flex flex-column" id="sidebar">

        <!-- HEADER -->
        <div class="sidebar-header px-4 py-4">
            <h4 class="fw-bold mb-0 text-white">SiAbsensi</h4>
            <small class="text-light d-block">Academic Architecture</small>
        </div>

        <!-- ROLE -->
        <div class="px-3 py-2 mb-3">
            <span class="badge role-badge w-100 text-uppercase text-center">
                ADMIN DINAS
            </span>
        </div>

        <!-- MENU -->
        <div class="flex-grow-1 overflow-auto">
            <ul class="nav flex-column px-2 m-0">
                <li><a href="{{ url('/admin') }}" class="nav-link sidebar-link {{ request()->is('admin') ? 'active-menu' : '' }}"><i class="bi bi-house me-2"></i> Dashboard</a></li>
                <li><a href="{{ url('/admin/users') }}" class="nav-link sidebar-link {{ request()->is('admin/users*') ? 'active-menu' : '' }}"><i class="bi bi-people me-2"></i> Manajemen User</a></li>
                <li><a href="{{ url('/admin/korwil') }}" class="nav-link sidebar-link {{ request()->is('admin/korwil*') ? 'active-menu' : '' }}"><i class="bi bi-diagram-3 me-2"></i> Manajemen Korwil</a></li>
                <li><a href="{{ url('/admin/sekolah') }}" class="nav-link sidebar-link {{ request()->is('admin/sekolah*') ? 'active-menu' : '' }}"><i class="bi bi-building me-2"></i> Manajemen Sekolah</a></li>
                <li><a href="{{ url('/admin/periode') }}" class="nav-link sidebar-link {{ request()->is('admin/periode*') ? 'active-menu' : '' }}"><i class="bi bi-calendar3 me-2"></i> Periode Ajaran</a></li>
                <li><a href="{{ url('/admin/validasi') }}" class="nav-link sidebar-link {{ request()->is('admin/validasi*') ? 'active-menu' : '' }}"><i class="bi bi-check-circle me-2"></i> Validasi Absensi</a></li>
                <li><a href="{{ url('/admin/laporan') }}" class="nav-link sidebar-link {{ request()->is('admin/laporan*') ? 'active-menu' : '' }}"><i class="bi bi-bar-chart me-2"></i> Laporan Absensi</a></li>
            </ul>
        </div>

        <!-- FOOTER / LOGOUT -->
        <div class="sidebar-footer px-3 py-3 mt-auto">
            <div class="text-white mb-2 small">
                Login sebagai <strong>Admin</strong>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-warning w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="content flex-grow-1 p-4" id="mainContent">
        @yield('content')
    </div>

</div>

<style>
/* SIDEBAR */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background-color: #003566;
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease;
    z-index: 1050;
}

.sidebar-header {
    background-color: #001d3d;
}

.sidebar-footer {
    background-color: #001d3d;
    margin-top: auto;
}

.role-badge {
    background-color: #ffd60a;
    color: #003566;
    font-weight: 600;
    padding: 8px;
    border-radius: 8px;
}

.sidebar ul {
    padding-left: 0;
    margin: 0;
}

.sidebar li {
    list-style: none;
}

.sidebar-link {
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    margin-bottom: 2px;
    display: block;
}

.sidebar-link:hover {
    background-color: #ffc300;
    color: #003566;
}

.active-menu {
    background-color: #ffd60a;
    color: #003566 !important;
    font-weight: 600;
    border-left: 4px solid #ffc300;
}

/* CONTENT */
.content {
    margin-left: 250px;
    transition: margin-left 0.3s ease;
}

/* MOBILE */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 250px;
        height: 100%;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    .content {
        margin-left: 0;
    }

    #sidebarToggle {
        position: fixed;
        z-index: 1100;
    }

    /* optional overlay */
    .overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        z-index: 1040;
    }

    .overlay.show {
        display: block;
    }
}
</style>

<div class="overlay" id="overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function () {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
});
</script>
