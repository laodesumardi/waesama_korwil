<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAGU - Rekap Data Koordinator Wilayah</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* ===== GLOBAL STYLE ===== */
        * {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
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

        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .nav-link {
            color: #001d3d;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #fca311 !important;
        }

        .nav-link.active {
            color: #fca311 !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #001d3d 0%, #003566 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        /* Stat Card */
        .stat-card {
            background: white;
            border-radius: 20px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Section Title */
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #ffc300, #ffd60a);
            border-radius: 3px;
        }

        /* Table */
        .table-custom {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .table-custom thead th {
            background: #001d3d;
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table-custom tbody tr:hover {
            background-color: rgba(255, 195, 0, 0.05);
        }

        /* Progress Bar */
        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar-custom {
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 10px;
        }

        /* Badge */
        .badge-korwil {
            background: linear-gradient(135deg, #ffc300, #ffd60a);
            color: #001d3d;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 30px;
        }

        /* Footer */
        .footer {
            background: #001d3d;
            color: white;
        }

        /* Animasi */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Object Fit */
        .object-fit-cover {
            object-fit: cover;
        }

        /* Tab Navigation */
        .nav-tabs-custom {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #fca311;
            border: none;
        }

        .nav-tabs-custom .nav-link.active {
            color: #fca311;
            background: none;
            border-bottom: 3px solid #fca311;
        }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
                        <a class="nav-link fw-semibold menu-link" href="{{ url('/#beranda') }}">
                            Beranda
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link" href="{{ url('/#tentang') }}">
                            Tentang Sistem
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link" href="{{ url('/#fitur') }}">
                            Fitur
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link active" href="{{ url('/rekap-korwil') }}">
                            Korwil
                            <span class="active-line"></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold menu-link" href="{{ url('/#kontak') }}">
                            Kontak
                            <span class="active-line"></span>
                        </a>
                    </li>
                </ul>

                <!-- Login / Dashboard -->
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

    <!-- ======================= HERO SECTION ======================= -->
    <section class="hero-section py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h1 class="display-4 fw-bold mb-3 animate-fadeInUp">Rekap Data Koordinator Wilayah</h1>
                    <p class="lead opacity-75 mb-4 animate-fadeInUp">Informasi lengkap data sekolah, tenaga pendidik, dan absensi seluruh wilayah</p>
                    <div class="d-flex justify-content-center gap-3 animate-fadeInUp">
                        <div class="px-4 py-2 bg-white bg-opacity-10 rounded-pill">
                            <i class="fas fa-school me-2"></i> {{ number_format($totalSekolah) }} Sekolah
                        </div>
                        <div class="px-4 py-2 bg-white bg-opacity-10 rounded-pill">
                            <i class="fas fa-chalkboard-user me-2"></i> {{ number_format($totalGuru) }} Guru
                        </div>
                        <div class="px-4 py-2 bg-white bg-opacity-10 rounded-pill">
                            <i class="fas fa-user-graduate me-2"></i> {{ number_format($totalSiswa) }} Siswa
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================= STATISTIK UTAMA ======================= -->
    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon bg-primary bg-opacity-10 mx-auto mb-3">
                        <i class="fas fa-school fs-2 text-primary"></i>
                    </div>
                    <h2 class="fw-bold text-primary mb-0">{{ number_format($totalSekolah) }}</h2>
                    <p class="text-muted mb-0">Total Sekolah</p>
                    <div class="mt-2">
                        <small class="text-success">Aktif: {{ $sekolahAktif }}</small> |
                        <small class="text-danger">Nonaktif: {{ $sekolahNonaktif }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon bg-success bg-opacity-10 mx-auto mb-3">
                        <i class="fas fa-chalkboard-user fs-2 text-success"></i>
                    </div>
                    <h2 class="fw-bold text-success mb-0">{{ number_format($totalGuru) }}</h2>
                    <p class="text-muted mb-0">Total Guru</p>
                    <div class="mt-2">
                        <small class="text-success">Aktif: {{ number_format($guruAktif) }}</small> |
                        <small class="text-warning">Pensiun: {{ number_format($guruPensiun) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon bg-info bg-opacity-10 mx-auto mb-3">
                        <i class="fas fa-user-graduate fs-2 text-info"></i>
                    </div>
                    <h2 class="fw-bold text-info mb-0">{{ number_format($totalSiswa) }}</h2>
                    <p class="text-muted mb-0">Total Siswa</p>
                    <div class="mt-2">
                        <small class="text-success">Aktif: {{ number_format($siswaAktif) }}</small> |
                        <small class="text-info">Lulus: {{ number_format($siswaLulus) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card p-4 text-center">
                    <div class="stat-icon bg-warning bg-opacity-10 mx-auto mb-3">
                        <i class="fas fa-chart-line fs-2 text-warning"></i>
                    </div>
                    <h2 class="fw-bold text-warning mb-0">{{ number_format($totalAbsensi) }}</h2>
                    <p class="text-muted mb-0">Total Absensi</p>
                    <div class="mt-2">
                        <small>Periode: {{ $periodeAktif->tahun_ajaran ?? '-' }} - Sem {{ $periodeAktif->semester ?? '-' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= DETAIL KEHADIRAN ======================= -->
    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="stat-icon bg-success bg-opacity-10">
                            <i class="fas fa-users fs-2 text-success"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Absensi Siswa</h4>
                    </div>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-success mb-0">{{ number_format($siswaHadir) }}</h3>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-info mb-0">{{ number_format($siswaSakit) }}</h3>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-warning mb-0">{{ number_format($siswaIzin) }}</h3>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-danger mb-0">{{ number_format($siswaAlpha) }}</h3>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="stat-card p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="stat-icon bg-info bg-opacity-10">
                            <i class="fas fa-chalkboard-user fs-2 text-info"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Absensi Guru</h4>
                    </div>
                    <div class="row text-center">
                        <div class="col-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-success mb-0">{{ number_format($guruHadir) }}</h3>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-warning mb-0">{{ number_format($guruIzin) }}</h3>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-danger mb-0">{{ number_format($guruAlpha) }}</h3>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= TAB MENU ======================= -->
    <div class="container mt-5">
        <ul class="nav nav-tabs-custom justify-content-center mb-4" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="korwil-tab" data-bs-toggle="tab" data-bs-target="#korwil" type="button" role="tab">
                    <i class="fas fa-diagram-3 me-2"></i> Data Korwil
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="guru-tab" data-bs-toggle="tab" data-bs-target="#guru" type="button" role="tab">
                    <i class="fas fa-chalkboard-user me-2"></i> Data Guru
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="siswa-tab" data-bs-toggle="tab" data-bs-target="#siswa" type="button" role="tab">
                    <i class="fas fa-user-graduate me-2"></i> Data Siswa
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB KORWIL -->
            <div class="tab-pane fade show active" id="korwil" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Wilayah</th>
                                <th>Nama Korwil</th>
                                <th>Wilayah Kerja</th>
                                <th class="text-center">Sekolah</th>
                                <th class="text-center">Guru</th>
                                <th class="text-center">Siswa</th>
                                <th class="text-center">Absensi</th>
                                <th class="text-center">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($korwilList as $index => $korwil)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="badge-korwil">{{ $korwil->kode_wilayah }}</span></td>
                                <td><strong>{{ $korwil->nama_korwil }}</strong></td>
                                <td>{{ Str::limit($korwil->wilayah_kerja, 50) }}</td>
                                <td class="text-center">{{ number_format($korwil->jumlah_sekolah) }}</td>
                                <td class="text-center">{{ number_format($korwil->jumlah_guru) }}</td>
                                <td class="text-center">{{ number_format($korwil->jumlah_siswa) }}</td>
                                <td class="text-center">{{ number_format($korwil->total_absensi) }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <div class="progress-custom flex-grow-1" style="width: 100px;">
                                            <div class="progress-bar-custom" style="width: {{ $korwil->persen_kehadiran }}%; height: 8px; border-radius: 10px;"></div>
                                        </div>
                                        <span class="fw-semibold {{ $korwil->persen_kehadiran >= 80 ? 'text-success' : ($korwil->persen_kehadiran >= 60 ? 'text-warning' : 'text-danger') }}">
                                            {{ $korwil->persen_kehadiran }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data korwil
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB GURU -->
            <div class="tab-pane fade" id="guru" role="tabpanel">
                <div class="search-box mb-4">
                    <form method="GET" action="{{ url('/rekap-korwil') }}" class="row g-3">
                        <input type="hidden" name="tab" value="guru">
                        <div class="col-md-5">
                            <input type="text" name="search_guru" class="form-control" placeholder="Cari nama guru, bidang studi, atau sekolah..." value="{{ request('search_guru') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="filter_korwil" class="form-select">
                                <option value="">Semua Wilayah</option>
                                @foreach($korwilOptions as $option)
                                    <option value="{{ $option->kode_wilayah }}" {{ request('filter_korwil') == $option->kode_wilayah ? 'selected' : '' }}>
                                        {{ $option->kode_wilayah }} - {{ $option->nama_korwil }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Jenis Kelamin</th>
                                <th>Pendidikan</th>
                                <th>Bidang Studi</th>
                                <th>Sekolah</th>
                                <th>Kecamatan</th>
                                <th>Korwil</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guruList as $index => $guru)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $guru->nama_guru }}</strong></td>
                                <td>{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $guru->pendidikan_terakhir }}</td>
                                <td>{{ $guru->bidang_studi }}</td>
                                <td>{{ $guru->nama_sekolah }}</td>
                                <td>{{ $guru->kecamatan }}</td>
                                <td><span class="badge-korwil">{{ $guru->kode_wilayah ?? '-' }}</span></td>
                                <td class="text-center">
                                    @if($guru->status == 'aktif')
                                        <span class="badge bg-success-soft px-3 py-1 rounded-pill">Aktif</span>
                                    @else
                                        <span class="badge bg-danger-soft px-3 py-1 rounded-pill">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data guru
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB SISWA -->
            <div class="tab-pane fade" id="siswa" role="tabpanel">
                <div class="search-box mb-4">
                    <form method="GET" action="{{ url('/rekap-korwil') }}" class="row g-3">
                        <input type="hidden" name="tab" value="siswa">
                        <div class="col-md-5">
                            <input type="text" name="search_siswa" class="form-control" placeholder="Cari nama siswa, kelas, atau sekolah..." value="{{ request('search_siswa') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="filter_korwil" class="form-select">
                                <option value="">Semua Wilayah</option>
                                @foreach($korwilOptions as $option)
                                    <option value="{{ $option->kode_wilayah }}" {{ request('filter_korwil') == $option->kode_wilayah ? 'selected' : '' }}>
                                        {{ $option->kode_wilayah }} - {{ $option->nama_korwil }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th>Kelas</th>
                                <th>Sekolah</th>
                                <th>Kecamatan</th>
                                <th>Korwil</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswaList as $index => $siswa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                                <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $siswa->kelas }}</td>
                                <td>{{ $siswa->nama_sekolah }}</td>
                                <td>{{ $siswa->kecamatan }}</td>
                                <td><span class="badge-korwil">{{ $siswa->kode_wilayah ?? '-' }}</span></td>
                                <td class="text-center">
                                    @if($siswa->status == 'aktif')
                                        <span class="badge bg-success-soft px-3 py-1 rounded-pill">Aktif</span>
                                    @elseif($siswa->status == 'lulus')
                                        <span class="badge bg-info-soft px-3 py-1 rounded-pill">Lulus</span>
                                    @else
                                        <span class="badge bg-danger-soft px-3 py-1 rounded-pill">{{ ucfirst($siswa->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data siswa
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================= FOOTER ======================= -->
    <footer class="footer py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 opacity-75">&copy; {{ date('Y') }} SIMAGU - Sistem Monitoring Absensi Guru. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aktifkan tab berdasarkan parameter URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');

            if (tabParam === 'guru') {
                const guruTab = document.getElementById('guru-tab');
                if (guruTab) {
                    const tab = new bootstrap.Tab(guruTab);
                    tab.show();
                }
            } else if (tabParam === 'siswa') {
                const siswaTab = document.getElementById('siswa-tab');
                if (siswaTab) {
                    const tab = new bootstrap.Tab(siswaTab);
                    tab.show();
                }
            }

            // Set active menu
            const menuLinks = document.querySelectorAll(".menu-link");
            const currentPath = window.location.pathname;

            menuLinks.forEach(link => {
                link.classList.remove("active");
                const href = link.getAttribute("href");
                if (currentPath === '/rekap-korwil' && href === '/rekap-korwil') {
                    link.classList.add("active");
                }
            });
        });

        // Style tambahan untuk badge
        const style = document.createElement('style');
        style.textContent = `
            .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
            .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
            .bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
