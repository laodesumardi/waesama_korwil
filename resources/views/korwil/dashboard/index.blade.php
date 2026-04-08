@extends('layouts.app')

@section('content')
<div class="korwil-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-diagram-3-fill me-2 text-warning"></i>
                Dashboard Korwil
            </h3>
            <p class="text-muted small mb-0 mt-1">
                Selamat datang, {{ $korwil->nama_korwil }}
            </p>
        </div>
        <div class="text-muted small bg-white px-3 py-2 rounded shadow-sm">
            <i class="bi bi-hash me-1"></i> Kode Wilayah: {{ $korwil->kode_wilayah }}
        </div>
    </div>

    {{-- Info Wilayah --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-1">Wilayah Kerja: {{ $korwil->wilayah_kerja }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-building me-1"></i> {{ $totalSekolah }} Sekolah Binaan |
                        <i class="bi bi-people-fill me-1 ms-2"></i> {{ number_format($totalSiswa) }} Siswa |
                        <i class="bi bi-person-badge-fill me-1 ms-2"></i> {{ number_format($totalGuru) }} Guru
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if($periodeAktif)
                        <span class="badge bg-success-soft px-3 py-2 rounded-pill">
                            <i class="bi bi-calendar-check me-1"></i>
                            Periode Aktif: {{ $periodeAktif->tahun_ajaran }} - Sem {{ $periodeAktif->semester }}
                        </span>
                    @else
                        <span class="badge bg-warning-soft px-3 py-2 rounded-pill">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Belum ada periode aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Sekolah --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Sekolah</span>
                            <h3 class="mb-0 mt-1 fw-bold text-primary">{{ number_format($totalSekolah) }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-building-fill fs-4 text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small">
                            <span>Aktif: <strong class="text-success">{{ $sekolahAktif }}</strong></span>
                            <span>Nonaktif: <strong class="text-danger">{{ $sekolahNonaktif }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Siswa</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">{{ number_format($totalSiswa) }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-people-fill fs-4 text-success"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small">
                            <span>Aktif: <strong class="text-success">{{ number_format($siswaAktif) }}</strong></span>
                            <span>Lulus: <strong class="text-info">{{ number_format($siswaLulus) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Guru</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">{{ number_format($totalGuru) }}</h3>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-person-badge-fill fs-4 text-info"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="d-flex justify-content-between small">
                            <span>Aktif: <strong class="text-success">{{ number_format($guruAktif) }}</strong></span>
                            <span>Pensiun: <strong class="text-warning">{{ number_format($guruPensiun) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Absensi --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h4 class="text-primary mb-0">{{ number_format($totalAbsensi) }}</h4>
                    <small class="text-muted">Total Absensi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h4 class="text-success mb-0">{{ number_format($totalHadir) }}</h4>
                    <small class="text-muted">Total Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h4 class="text-info mb-0">{{ number_format($totalSakit) }}</h4>
                    <small class="text-muted">Total Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h4 class="text-warning mb-0">{{ number_format($totalIzin) }}</h4>
                    <small class="text-muted">Total Izin</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik per Jenis Absensi --}}
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-people-fill me-2 text-primary"></i>
                        Absensi Siswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-success mb-0">{{ number_format($statAbsensi['siswa']['hadir']) }}</h4>
                                <small>Hadir</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-info mb-0">{{ number_format($statAbsensi['siswa']['sakit']) }}</h4>
                                <small>Sakit</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-warning mb-0">{{ number_format($statAbsensi['siswa']['izin']) }}</h4>
                                <small>Izin</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-danger mb-0">{{ number_format($statAbsensi['siswa']['alpha']) }}</h4>
                                <small>Alpha</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">Total Data: {{ number_format($statAbsensi['siswa']['total']) }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-person-badge-fill me-2 text-info"></i>
                        Absensi Guru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-success mb-0">{{ number_format($statAbsensi['guru']['hadir']) }}</h4>
                                <small>Hadir</small>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-warning mb-0">{{ number_format($statAbsensi['guru']['izin']) }}</h4>
                                <small>Izin</small>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="text-danger mb-0">{{ number_format($statAbsensi['guru']['alpha']) }}</h4>
                                <small>Alpha</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">Total Data: {{ number_format($statAbsensi['guru']['total']) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Absensi --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-graph-up me-2 text-warning"></i>
                        Grafik Absensi 7 Hari Terakhir
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Sekolah Binaan dengan Anchor --}}
    <div id="sekolah" class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-building me-2 text-warning"></i>
                            Daftar Sekolah Binaan
                        </h5>
                        <div class="d-flex gap-2">
                            <form method="GET" action="{{ url()->current() }}#sekolah" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari sekolah..." value="{{ request('search') }}" style="width: 200px;">
                                <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-dark">
                                    <i class="bi bi-search"></i>
                                </button>
                                <a href="{{ url()->current() }}#sekolah" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-3 ps-3">#</th>
                                    <th class="py-3">NPSN</th>
                                    <th class="py-3">Nama Sekolah</th>
                                    <th class="py-3">Kecamatan</th>
                                    <th class="py-3 text-center">Siswa</th>
                                    <th class="py-3 text-center">Guru</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 pe-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sekolahList as $index => $item)
                                <tr>
                                    <td class="ps-3">{{ $sekolahList->firstItem() + $index }}</td>
                                    <td class="small">{{ $item->npsn }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-building text-primary"></i>
                                            <strong class="small">{{ $item->nama_sekolah }}</strong>
                                        </div>
                                    </td>
                                    <td class="small">{{ $item->kecamatan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-soft px-2 py-1">{{ number_format($item->siswa_count) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-soft px-2 py-1">{{ number_format($item->guru_count) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status == 'aktif')
                                            <span class="badge bg-success-soft px-2 py-1">Aktif</span>
                                        @else
                                            <span class="badge bg-danger-soft px-2 py-1">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        <a href="{{ route('korwil.sekolah.detail', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada sekolah binaan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    {{ $sekolahList->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);

    const ctx = document.getElementById('trendChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: chartData.hadir,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6
                },
                {
                    label: 'Sakit',
                    data: chartData.sakit,
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6
                },
                {
                    label: 'Izin',
                    data: chartData.izin,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6
                },
                {
                    label: 'Alpha',
                    data: chartData.alpha,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, usePointStyle: true, font: { size: 11 } } },
                tooltip: { backgroundColor: '#1a1a1a', titleColor: '#fff', bodyColor: '#ddd', padding: 10, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e9ecef' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Check if URL has #sekolah anchor, then scroll to it
    if (window.location.hash === '#sekolah') {
        const element = document.getElementById('sekolah');
        if (element) {
            setTimeout(() => {
                element.scrollIntoView({ behavior: 'smooth' });
            }, 500);
        }
    }
});
</script>

<style scoped>
.korwil-dashboard { width: 100%; }
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); color: #997404; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
