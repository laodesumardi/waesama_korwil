@extends('layouts.app')

@section('content')
<div class="operator-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-house-door-fill me-2 text-warning"></i>
                Dashboard Operator
            </h3>
            <p class="text-muted small mb-0 mt-1">
                Selamat datang, {{ auth()->user()->name }}
            </p>
        </div>
        <div class="text-muted small bg-white px-3 py-2 rounded shadow-sm">
            <i class="bi bi-building me-1"></i>
            {{ $sekolah->nama_sekolah ?? '-' }}
        </div>
    </div>

    {{-- Info Sekolah --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-1">{{ $sekolah->nama_sekolah }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-qr-code me-1"></i> NPSN: {{ $sekolah->npsn }} |
                        <i class="bi bi-geo-alt me-1 ms-2"></i> {{ $sekolah->kecamatan }}, {{ $sekolah->kelurahan }}
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

    {{-- Statistik Cards --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Siswa</span>
                            <h3 class="mb-0 mt-1 fw-bold text-primary">{{ number_format($statistik['total_siswa']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Guru</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">{{ number_format($statistik['total_guru']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-person-badge-fill fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Absensi Bulan Ini</span>
                            <h3 class="mb-0 mt-1 fw-bold">{{ number_format($statistik['total_absensi']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-calendar-check fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Rata-rata Hadir</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">{{ number_format($statistik['rata_hadir'], 1) }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-graph-up fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Quick Access --}}
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <a href="{{ route('operator.siswa.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center h-100 hover-card">
                    <div class="card-body py-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-people-fill fs-2 text-primary"></i>
                        </div>
                        <h5 class="mb-1">Data Siswa</h5>
                        <p class="small text-muted mb-0">Kelola data siswa</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('operator.guru.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center h-100 hover-card">
                    <div class="card-body py-4">
                        <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-person-badge-fill fs-2 text-info"></i>
                        </div>
                        <h5 class="mb-1">Data Guru</h5>
                        <p class="small text-muted mb-0">Kelola data guru</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('operator.absensi.siswa') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm text-center h-100 hover-card">
                    <div class="card-body py-4">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-plus-circle-fill fs-2 text-success"></i>
                        </div>
                        <h5 class="mb-1">Input Absensi</h5>
                        <p class="small text-muted mb-0">Input absensi hari ini</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Grafik 7 Hari --}}
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

    {{-- Absensi Terbaru --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-table me-2 text-warning"></i>
                            Absensi Terbaru
                        </h5>
                        <a href="{{ route('operator.absensi.history') }}" class="btn btn-sm btn-link text-warning">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-3 ps-3">Tanggal</th>
                                    <th class="py-3 text-center">Hadir</th>
                                    <th class="py-3 text-center">Sakit</th>
                                    <th class="py-3 text-center">Izin</th>
                                    <th class="py-3 text-center">Alpha</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 pe-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absensiTerbaru as $item)
                                <tr>
                                    <td class="ps-3">{{ $item->tanggal->format('d/m/Y') }}</td>
                                    <td class="text-center"><span class="fw-semibold text-success">{{ number_format($item->jumlah_hadir) }}</span></td>
                                    <td class="text-center">{{ number_format($item->jumlah_sakit) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_izin) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_alpha) }}</td>
                                    <td>
                                        @if($item->status_validasi == 'pending')
                                            <span class="badge bg-warning-soft px-3 py-1 rounded-pill">
                                                <i class="bi bi-clock-history me-1"></i> Pending
                                            </span>
                                        @elseif($item->status_validasi == 'disetujui')
                                            <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger-soft px-3 py-1 rounded-pill">
                                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        <a href="{{ route('operator.absensi.show', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data absensi
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#198754',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
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
                legend: {
                    position: 'top',
                    labels: { boxWidth: 12, usePointStyle: true, font: { size: 11 } }
                },
                tooltip: { backgroundColor: '#1a1a1a', titleColor: '#fff', bodyColor: '#ddd', padding: 10, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e9ecef' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>

<style scoped>
.operator-dashboard {
    width: 100%;
}

.hover-card {
    transition: all 0.2s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #fff8e7 0%, #fff3d4 100%);
}

.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
</style>
@endsection
