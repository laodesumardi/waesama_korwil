<div class="d-flex">

    <!-- HAMBURGER BUTTON -->
    <button class="btn btn-warning d-md-none m-2 position-fixed" id="sidebarToggle" style="z-index: 1100; top: 10px; left: 10px;">
        <i class="bi bi-list"></i>
    </button>

    <!-- SIDEBAR -->
    <div class="sidebar d-flex flex-column" id="sidebar">

        <!-- HEADER (fixed, tidak ikut scroll) -->
        <div class="sidebar-header px-4 py-4 flex-shrink-0">
            <h4 class="fw-bold mb-0 text-white">SiAbsensi</h4>
            <small class="text-light d-block">Academic Architecture</small>
        </div>

        <!-- ROLE (fixed, tidak ikut scroll) -->
        <div class="px-3 py-2 mb-3 flex-shrink-0">
            <span class="badge role-badge w-100 text-uppercase text-center">
                <i class="bi bi-person-badge me-1"></i> OPERATOR SEKOLAH
            </span>
        </div>

        <!-- MENU - SCROLLABLE AREA (ini yang akan di-scroll) -->
        <div class="sidebar-menu flex-grow-1">
            <ul class="nav flex-column px-2">
                <li>
                    <a href="{{ route('operator.dashboard') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.dashboard') ? 'active-menu' : '' }}">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>

                <!-- Menu Data Master -->
                <li>
                    <a href="{{ route('operator.siswa.index') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.siswa*') ? 'active-menu' : '' }}">
                        <i class="bi bi-people me-2"></i> Data Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('operator.guru.index') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.guru*') ? 'active-menu' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Data Guru
                    </a>
                </li>

                <!-- Menu Absensi -->
                <li>
                    <a href="{{ route('operator.absensi.siswa') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.absensi.siswa') ? 'active-menu' : '' }}">
                        <i class="bi bi-people me-2"></i> Absensi Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('operator.absensi.guru') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.absensi.guru') ? 'active-menu' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Absensi Guru
                    </a>
                </li>
                <li>
                    <a href="{{ route('operator.absensi.history') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.absensi.history') || request()->routeIs('operator.absensi.show') ? 'active-menu' : '' }}">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Absensi
                    </a>
                </li>

                <!-- Menu Profil -->
                <li>
                    <a href="{{ route('operator.profile') }}" class="nav-link sidebar-link {{ request()->routeIs('operator.profile') ? 'active-menu' : '' }}">
                        <i class="bi bi-person-circle me-2"></i> Profil Saya
                    </a>
                </li>
            </ul>
        </div>

        <!-- INFO SEKOLAH TUGAS (fixed di bawah, tidak ikut scroll) -->
        @php
            $assignment = App\Models\UserAssignment::where('user_id', auth()->id())
                ->where('target_type', 'sekolah')
                ->with('target')
                ->first();
            $sekolahTugas = $assignment ? $assignment->target : null;
        @endphp

        @if($sekolahTugas)
        <div class="px-3 py-3 border-top border-light border-opacity-10 flex-shrink-0">
            <div class="small text-white-50 mb-2">
                <i class="bi bi-building me-1"></i> Sekolah Tugas
            </div>
            <div class="bg-white bg-opacity-10 rounded p-2">
                <div class="small text-white fw-semibold">{{ $sekolahTugas->nama_sekolah }}</div>
                <div class="text-white-50" style="font-size: 11px">
                    <i class="bi bi-qr-code me-1"></i> {{ $sekolahTugas->npsn }}
                </div>
            </div>
        </div>
        @endif

        <!-- FOOTER / LOGOUT (fixed di bawah, tidak ikut scroll) -->
        <div class="sidebar-footer px-3 py-3 flex-shrink-0">
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

    <!-- CONTENT -->
    <div class="content flex-grow-1 p-4" id="mainContent">
        @yield('content')
    </div>

</div>

<!-- OVERLAY -->
<div class="overlay" id="overlay"></div>

<style>
/* SIDEBAR STYLES */
.sidebar {
    width: 280px;
    height: 100vh; /* Gunakan height: 100vh agar sidebar memenuhi tinggi layar */
    background: linear-gradient(180deg, #003566 0%, #001d3d 100%);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease-in-out;
    z-index: 1050;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    overflow: hidden; /* Mencegah scroll di sidebar utama */
}

.sidebar-header {
    background-color: rgba(0, 29, 61, 0.5);
    border-bottom: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0; /* Mencegah header mengecil */
}

.sidebar-footer {
    background-color: rgba(0, 29, 61, 0.5);
    border-top: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0; /* Mencegah footer mengecil */
}

.role-badge {
    background: linear-gradient(135deg, #ffd60a 0%, #ffc300 100%);
    color: #003566;
    font-weight: 700;
    padding: 10px;
    border-radius: 10px;
    letter-spacing: 1px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.sidebar ul {
    padding-left: 0;
    margin: 0;
}

.sidebar li {
    list-style: none;
}

.sidebar-link {
    color: #e0e0e0;
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    font-weight: 500;
}

.sidebar-link i {
    font-size: 1.2rem;
    width: 25px;
}

.sidebar-link:hover {
    background-color: #ffc300;
    color: #003566;
    transform: translateX(5px);
}

.active-menu {
    background: linear-gradient(135deg, #ffd60a 0%, #ffc300 100%);
    color: #003566 !important;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(255, 195, 0, 0.3);
}

.active-menu i {
    color: #003566 !important;
}

/* SCROLLABLE MENU AREA - INI YANG MEMBUAT SIDEBAR BISA DI-SCROLL */
.sidebar-menu {
    overflow-y: auto; /* Scroll vertikal */
    overflow-x: hidden; /* Sembunyikan scroll horizontal */
    flex: 1 1 auto; /* Mengambil sisa ruang yang tersedia */
    min-height: 0; /* Penting untuk flexbox agar scroll bisa bekerja */
}

/* Custom scrollbar untuk menu agar terlihat menarik */
.sidebar-menu::-webkit-scrollbar {
    width: 5px;
}

.sidebar-menu::-webkit-scrollbar-track {
    background: #001d3d;
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: #ffc300;
    border-radius: 10px;
}

.sidebar-menu::-webkit-scrollbar-thumb:hover {
    background: #ffd60a;
}

/* CONTENT STYLES */
.content {
    margin-left: 280px;
    transition: margin-left 0.3s ease-in-out;
    background-color: #f8f9fa;
    min-height: 100vh;
    width: calc(100% - 280px);
}

/* MOBILE RESPONSIVE */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    .content {
        margin-left: 0;
        width: 100%;
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
        transition: all 0.3s ease;
    }

    .overlay.show {
        display: block;
    }
}

/* DESKTOP */
@media (min-width: 769px) {
    #sidebarToggle {
        display: none !important;
    }
}

/* ANIMATIONS */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content > * {
    animation: fadeIn 0.3s ease-out;
}
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

    // Tutup sidebar saat klik link di mobile
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

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        }, 250);
    });
});
</script>
