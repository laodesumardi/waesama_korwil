<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAGU - Sistem Monitoring Absensi Guru</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (opsional untuk ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* ===== GLOBAL STYLE ===== */
        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            scroll-behavior: smooth;
        }

        /* Tombol custom utama */
        .btn-login {
            background-color: #001d3d;
            color: white;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background-color: #003566;
            color: white;
            transform: translateY(-1px);
        }

        /* Garis aktif untuk navigasi */
        .menu-link {
            position: relative;
            padding-bottom: 8px;
            transition: color 0.2s;
            cursor: pointer;
        }

        .active-line {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: #fca311;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .menu-link.active .active-line {
            width: 100%;
        }

        .menu-link:hover {
            color: #fca311 !important;
        }

        /* Statistik item */
        .stat-item {
            border-right: 1px solid #ddd;
        }
        .stat-item:last-child {
            border-right: none;
        }

        /* Section fitur */
        .fitur-section {
            min-height: auto;
            padding: 60px 0;
        }

        /* Perbaikan card */
        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important;
        }

        /* Responsive stats */
        @media (max-width: 768px) {
            .stat-item {
                border-right: none;
                border-bottom: 1px solid #eee;
                padding-bottom: 1rem;
                margin-bottom: 1rem;
            }
            .stat-item:last-child {
                border-bottom: none;
            }
        }

        /* animasi smooth untuk anchor */
        html {
            scroll-behavior: smooth;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>
  </head>
  <body>

    <!-- ======================= NAVBAR ======================= -->
    <nav class="py-3 bg-white shadow-sm navbar navbar-expand-lg sticky-top">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}" style="color:#001d3d;">SIMAGU</a>

            <!-- Toggle button mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu + Login -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="mx-auto text-center navbar-nav gap-lg-4">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link active" href="{{ url('/#beranda') }}">
                            Beranda
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link text-secondary" href="{{ url('/#tentang') }}">
                            Tentang Sistem
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link text-secondary" href="{{ url('/#fitur') }}">
                            Fitur
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link text-secondary" href="{{ url('/rekap-korwil') }}">
                            Korwil
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link text-secondary" href="{{ url('/#kontak') }}">
                            Kontak
                            <span class="active-line"></span>
                        </a>
                    </li>
                </ul>

                <!-- Login / Dashboard (sesuai auth laravel) -->
                <div class="mt-3 text-center mt-lg-0">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 btn btn-login">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 btn btn-login">
                            Login Sistem
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ======================= JUMBOTRON / BERANDA ======================= -->
    <section class="p-0 container-fluid" id="beranda">
        <div class="text-white d-flex align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #0d1b3d, #0a0f2c);">
            <div class="container py-5">
                <div class="row align-items-center gy-5">

                    <!-- Kiri: Teks -->
                    <div class="text-center col-lg-6 text-lg-start">
                        <h1 class="fw-bold display-4">
                            Sistem Monitoring Absensi Guru SMP Berbasis Digital
                        </h1>
                        <p class="mt-3 lead text-light opacity-75">
                            Platform untuk membantu Dinas Pendidikan memantau kehadiran guru secara real-time di seluruh sekolah. Transparansi data untuk integritas pendidikan.
                        </p>
                        <div class="gap-3 mt-4 d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-4 py-2 btn btn-warning fw-semibold">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 btn btn-warning fw-semibold">
                                    Login Sistem
                                </a>
                            @endauth
                            <a href="{{ url('/#tentang') }}" class="px-4 py-2 btn btn-outline-light">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>

                    <!-- Kanan: Gambar ilustrasi -->
                    <div class="text-center col-lg-6" style="height:400px;">
                        <img src="{{ asset('img/hero.jpg') }}"
                            class="rounded-4 shadow-lg w-100 h-100 object-fit-cover"
                            alt="Dashboard SIMAGU"
                            onerror="this.src='https://placehold.co/650x450/1e2a5e/white?text=Dashboard+SIMAGU'">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= STATISTIK ======================= -->
    <div class="py-5 bg-light">
        <div class="container">
            <div class="row text-center gy-4">
                <div class="col-md-3 col-6 stat-item">
                    <h1 class="fw-bold text-dark">120+</h1>
                    <p class="text-uppercase small text-secondary mb-0">Sekolah Terdaftar</p>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <h1 class="fw-bold text-dark">1500+</h1>
                    <p class="text-uppercase small text-secondary mb-0">Guru Aktif</p>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <h1 class="fw-bold text-dark">30+</h1>
                    <p class="text-uppercase small text-secondary mb-0">Operator Wilayah</p>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <h1 class="fw-bold text-warning">REAL</h1>
                    <p class="text-uppercase small text-secondary mb-0">Monitoring Time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= TENTANG SISTEM (FITUR INTRO) ======================= -->
    <section class="fitur-section bg-white" id="tentang">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="text-center col-lg-6 text-lg-start">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill">Tentang Sistem</span>
                    <h3 class="mb-3 fw-bold text-dark display-6">Sistem Monitoring Absensi Guru</h3>
                    <p class="mb-3 text-dark-emphasis">
                        Kami percaya bahwa kedisiplinan adalah kunci utama kualitas proses belajar mengajar di setiap sekolah.
                    </p>
                    <p class="text-dark-emphasis">
                        Sistem ini membantu meminimalisir kesalahan pelaporan manual serta mengelola data sekolah dan guru secara terpusat dalam satu platform digital.
                    </p>
                </div>

                <!-- Card Fitur -->
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-12 col-sm-6">
                            <div class="card border-0 shadow h-100 card-hover">
                                <div class="card-body">
                                    <div class="mb-2 text-primary"><i class="fas fa-shield-alt fs-2"></i></div>
                                    <h6 class="fw-bold text-dark">Otentikasi Aman</h6>
                                    <small class="text-muted">Verifikasi data guru secara aman menggunakan sistem cloud terenkripsi.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="card border-0 shadow h-100 card-hover" style="background-color:#001d3d; color:white;">
                                <div class="card-body">
                                    <div class="mb-2"><i class="fas fa-chart-line fs-2"></i></div>
                                    <h6 class="fw-bold">Analisis & Laporan</h6>
                                    <small>Laporan kehadiran guru terintegrasi dan dapat dianalisis setiap semester.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="card border-0 shadow h-100 card-hover">
                                <div class="card-body">
                                    <div class="mb-2 text-success"><i class="fas fa-clock fs-2"></i></div>
                                    <h6 class="fw-bold">Real-Time</h6>
                                    <small class="text-muted">Pantau kehadiran guru secara langsung dari dashboard pusat.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="card border-0 shadow h-100 card-hover">
                                <div class="card-body">
                                    <div class="mb-2 text-warning"><i class="fas fa-database fs-2"></i></div>
                                    <h6 class="fw-bold">Terintegrasi</h6>
                                    <small class="text-muted">Sinkronisasi dengan fingerprint dan aplikasi sekolah.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= ALUR KERJA (FITUR) ======================= -->
    <section class="py-5" style="background:#f3f4f6;" id="fitur">
        <div class="container text-center">
            <p class="text-uppercase small fw-bold" style="color:#c59d5f; letter-spacing:2px;">Alur Kerja</p>
            <h3 class="mb-5 fw-bold">Sederhana & Efisien</h3>
            <div class="row justify-content-center g-5">
                <!-- Step 1 -->
                <div class="col-md-4">
                    <div class="mb-3 rounded shadow-sm d-inline-flex align-items-center justify-content-center"
                         style="width:70px;height:70px;background:#0f1f3d;color:white;font-weight:bold;font-size:22px;">
                        01
                    </div>
                    <h6 class="mt-3 fw-bold fs-5">Guru Absensi</h6>
                    <p class="mt-2 text-muted">Guru melakukan absensi melalui aplikasi sekolah atau mesin fingerprint terintegrasi.</p>
                </div>
                <!-- Step 2 -->
                <div class="col-md-4">
                    <div class="mb-3 rounded shadow-sm d-inline-flex align-items-center justify-content-center"
                         style="width:70px;height:70px;background:#0f1f3d;color:white;font-weight:bold;font-size:22px;">
                        02
                    </div>
                    <h6 class="mt-3 fw-bold fs-5">Data Tersimpan</h6>
                    <p class="mt-2 text-muted">Data dikirimkan secara otomatis ke server pusat SIMAGU dan dienkripsi untuk keamanan.</p>
                </div>
                <!-- Step 3 -->
                <div class="col-md-4">
                    <div class="mb-3 rounded shadow-sm d-inline-flex align-items-center justify-content-center"
                         style="width:70px;height:70px;background:#f59e0b;color:white;font-weight:bold;font-size:22px;">
                        03
                    </div>
                    <h6 class="mt-3 fw-bold fs-5">Dinas Monitor</h6>
                    <p class="mt-2 text-muted">Dinas Pendidikan melihat rangkuman data melalui dashboard monitoring pusat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= KONTAK ======================= -->
    <section class="py-5 bg-light" id="kontak">
        <div class="container">
            <div class="mb-5 text-center">
                <h2 class="fw-bold">Hubungi Kami</h2>
                <p class="text-muted">Butuh informasi lebih lanjut mengenai Sistem Monitoring Absensi Guru? Tim kami siap membantu Anda.</p>
            </div>
            <div class="row g-4">
                <!-- Info kontak -->
                <div class="col-lg-5">
                    <div class="p-4 bg-white rounded-4 shadow h-100">
                        <h5 class="mb-4 fw-bold"><i class="fas fa-map-marker-alt me-2 text-danger"></i> Informasi Kontak</h5>
                        <div class="mb-3">
                            <strong>Alamat</strong>
                            <p class="mb-0 text-muted">Dinas Pendidikan Kota / Kabupaten Anda, Gedung Pusat Lantai 3</p>
                        </div>
                        <div class="mb-3">
                            <strong>Email</strong>
                            <p class="mb-0 text-muted">info@simagu.id</p>
                        </div>
                        <div class="mb-3">
                            <strong>Telepon</strong>
                            <p class="mb-0 text-muted">(021) 12345678</p>
                        </div>
                        <div>
                            <strong>Jam Operasional</strong>
                            <p class="mb-0 text-muted">Senin - Jumat, 08.00 - 16.00</p>
                        </div>
                    </div>
                </div>
                <!-- Form kontak -->
                <div class="col-lg-7">
                    <div class="p-4 bg-white rounded-4 shadow">
                        <h5 class="mb-4 fw-bold"><i class="fas fa-paper-plane me-2 text-primary"></i> Kirim Pesan</h5>
                        <form action="#" method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Nama lengkap" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email aktif" required>
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" placeholder="Subjek pesan">
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="px-5 py-2 btn btn-dark">Kirim Pesan <i class="fas fa-arrow-right ms-1"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= FOOTER ======================= -->
    <footer class="pt-5 pb-3 text-white" style="background:#001d3d;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3 fw-bold fs-4">SIMAGU</h5>
                    <p class="small text-light opacity-75">Sistem Monitoring Absensi Guru yang membantu Dinas Pendidikan memantau kehadiran guru secara real-time dan terintegrasi.</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="mb-3 fw-bold">Menu</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ url('/#beranda') }}" class="text-light text-decoration-none opacity-75">Beranda</a></li>
                        <li class="mb-2"><a href="{{ url('/#tentang') }}" class="text-light text-decoration-none opacity-75">Tentang Sistem</a></li>
                        <li class="mb-2"><a href="{{ url('/#fitur') }}" class="text-light text-decoration-none opacity-75">Alur Kerja</a></li>
                        <li class="mb-2"><a href="{{ url('/rekap-korwil') }}" class="text-light text-decoration-none opacity-75">Rekap Korwil</a></li>
                        <li><a href="{{ url('/#kontak') }}" class="text-light text-decoration-none opacity-75">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h6 class="mb-3 fw-bold">Kontak Kami</h6>
                    <p class="mb-1 small text-light opacity-75">📧 info@simagu.id</p>
                    <p class="mb-1 small text-light opacity-75">📞 (021) 12345678</p>
                    <p class="small text-light opacity-75">🏢 Dinas Pendidikan, Pusat</p>
                </div>
            </div>
            <hr class="my-4 border-light opacity-25">
            <div class="text-center small text-light opacity-75">
                © 2026 SIMAGU - Sistem Monitoring Absensi Guru | All rights reserved.
            </div>
        </div>
    </footer>

    <!-- JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            // Ambil semua menu link
            const menuLinks = document.querySelectorAll(".menu-link");

            // Fungsi untuk mengatur active menu berdasarkan URL saat ini
            function setActiveMenu() {
                const currentPath = window.location.pathname;
                const currentHash = window.location.hash;

                menuLinks.forEach(link => {
                    link.classList.remove("active");
                    const href = link.getAttribute("href");

                    // Untuk halaman rekap korwil
                    if (currentPath === '/rekap-korwil' && href === '/rekap-korwil') {
                        link.classList.add("active");
                    }
                    // Untuk anchor links di halaman utama
                    else if (currentPath === '/' || currentPath === '') {
                        if (href === currentHash || (href === '/' && currentHash === '') || (href === '/#beranda' && (!currentHash || currentHash === '#beranda'))) {
                            link.classList.add("active");
                        }
                    }
                });
            }

            // Smooth scroll untuk anchor link di halaman yang sama
            menuLinks.forEach(link => {
                link.addEventListener("click", function(e) {
                    const href = this.getAttribute("href");

                    // Jika link menuju ke halaman yang sama (anchor)
                    if (href.startsWith('/#')) {
                        e.preventDefault();
                        const targetId = href.substring(2); // hapus '/#'
                        const targetElement = document.getElementById(targetId);
                        if (targetElement) {
                            targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
                            // update active setelah klik
                            menuLinks.forEach(l => l.classList.remove("active"));
                            this.classList.add("active");

                            // Update URL tanpa reload
                            history.pushState(null, null, href);
                        }
                    }
                    // Jika link ke halaman lain, biarkan default behavior
                    // (termasuk ke /rekap-korwil)
                });
            });

            // Set active menu saat load
            setActiveMenu();

            // Update active menu saat scroll di halaman utama
            const sections = [
                document.getElementById("beranda"),
                document.getElementById("tentang"),
                document.getElementById("fitur"),
                document.getElementById("kontak")
            ];

            function setActiveOnScroll() {
                // Hanya jalankan di halaman utama
                if (window.location.pathname !== '/' && window.location.pathname !== '') {
                    return;
                }

                let current = "";
                const scrollPos = window.scrollY + 120;

                sections.forEach(section => {
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                            current = section.getAttribute("id");
                        }
                    }
                });

                menuLinks.forEach(link => {
                    const href = link.getAttribute("href");
                    if (href === `/#${current}`) {
                        link.classList.add("active");
                    } else if (current === "" && href === "/#beranda") {
                        link.classList.add("active");
                    } else if (href !== "/rekap-korwil") {
                        link.classList.remove("active");
                    }
                });
            }

            window.addEventListener("scroll", setActiveOnScroll);
            window.addEventListener("load", setActiveOnScroll);
        })();
    </script>
  </body>
</html>
