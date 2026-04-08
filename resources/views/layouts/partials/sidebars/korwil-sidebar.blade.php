<div class="d-flex">

    <!-- HAMBURGER BUTTON -->
    <button class="btn btn-warning d-md-none m-2 position-fixed" id="sidebarToggle" style="z-index: 1100; top: 10px; left: 10px;">
        <i class="bi bi-list"></i>
    </button>

    <!-- SIDEBAR -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="sidebar-header px-4 py-4">
            <h4 class="fw-bold mb-0 text-white">SiAbsensi</h4>
            <small class="text-light d-block">Academic Architecture</small>
        </div>

        <div class="px-3 py-2 mb-3">
            <span class="badge role-badge w-100 text-uppercase text-center">
                <i class="bi bi-diagram-3 me-1"></i> KOORDINATOR WILAYAH
            </span>
        </div>

        <div class="flex-grow-1 overflow-auto">
            <ul class="nav flex-column px-2 m-0">
                <li>
                    <a href="{{ route('korwil.dashboard') }}" class="nav-link sidebar-link {{ request()->routeIs('korwil.dashboard') ? 'active-menu' : '' }}">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('korwil.dashboard') }}#sekolah" class="nav-link sidebar-link">
                        <i class="bi bi-building me-2"></i> Sekolah Binaan
                    </a>
                </li>

            </ul>
        </div>

        <div class="sidebar-footer px-3 py-3 mt-auto">
            <div class="text-white mb-2 small">
                <i class="bi bi-person-circle me-1"></i> Login sebagai <strong>{{ auth()->user()->name }}</strong>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-warning w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="content flex-grow-1 p-4" id="mainContent">
        @yield('content')
    </div>
</div>

<div class="overlay" id="overlay"></div>

<style>
.sidebar {
    width: 280px;
    min-height: 100vh;
    background: linear-gradient(180deg, #003566 0%, #001d3d 100%);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease-in-out;
    z-index: 1050;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.sidebar-header {
    background-color: rgba(0, 29, 61, 0.5);
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-footer {
    background-color: rgba(0, 29, 61, 0.5);
    border-top: 1px solid rgba(255,255,255,0.1);
}

.role-badge {
    background: linear-gradient(135deg, #ffd60a 0%, #ffc300 100%);
    color: #003566;
    font-weight: 700;
    padding: 10px;
    border-radius: 10px;
}

.sidebar-link {
    color: #e0e0e0;
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.sidebar-link i { font-size: 1.2rem; width: 25px; }
.sidebar-link:hover { background-color: #ffc300; color: #003566; transform: translateX(5px); }

.active-menu {
    background: linear-gradient(135deg, #ffd60a 0%, #ffc300 100%);
    color: #003566 !important;
    font-weight: 700;
}

.content {
    margin-left: 280px;
    transition: margin-left 0.3s ease-in-out;
    background-color: #f8f9fa;
    min-height: 100vh;
    width: calc(100% - 280px);
}

@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); width: 280px; }
    .sidebar.show { transform: translateX(0); }
    .content { margin-left: 0; width: 100%; }
    .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; }
    .overlay.show { display: block; }
}

@media (min-width: 769px) { #sidebarToggle { display: none !important; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });
});
</script>
