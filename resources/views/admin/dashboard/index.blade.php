@extends('layouts.app')

@section('content')
{{-- Langsung konten tanpa wrapper tambahan --}}
<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-speedometer2 me-2 text-warning"></i>
            Dashboard Admin
        </h3>
        <div class="text-muted small bg-white px-3 py-2 rounded shadow-sm">
            <i class="bi bi-calendar3 me-1"></i>
            {{ now()->format('l, d F Y') }}
        </div>
    </div>

    {{-- CARD STATISTIK --}}
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 small text-uppercase">Total Users</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalUsers ?? 0) }}</h2>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-people-fill text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 small text-uppercase">Total Sekolah</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalSekolah ?? 0) }}</h2>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="bi bi-building-fill text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 small text-uppercase">Total Korwil</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalKorwil ?? 0) }}</h2>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="bi bi-diagram-3-fill text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2 small text-uppercase">Absensi Hari Ini</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalAbsensi ?? 0) }}</h2>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="bi bi-check-circle-fill text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK TREN ABSENSI --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3 pb-0">
                    <strong class="fw-semibold">
                        <i class="bi bi-graph-up me-2 text-warning"></i>
                        Tren Absensi 7 Hari Terakhir
                    </strong>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DATA ABSENSI TERBARU --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3 pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <strong class="fw-semibold">
                            <i class="bi bi-table me-2 text-warning"></i>
                            Data Absensi Terbaru
                        </strong>
                        <a href="{{ url('/admin/laporan') }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-eye me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-3 ps-3">Tanggal</th>
                                    <th class="py-3">Sekolah</th>
                                    <th class="py-3 text-center">Hadir</th>
                                    <th class="py-3 text-center">Sakit</th>
                                    <th class="py-3 text-center">Izin</th>
                                    <th class="py-3 text-center">Alpha</th>
                                    <th class="py-3 pe-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestAbsensi ?? [] as $item)
                                <tr>
                                    <td class="ps-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-building text-muted small"></i>
                                            {{ $item->sekolah->nama_sekolah ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="fw-semibold text-success">{{ number_format($item->jumlah_hadir ?? 0) }}</span></td>
                                    <td class="text-center">{{ number_format($item->jumlah_sakit ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_izin ?? 0) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_alpha ?? 0) }}</td>
                                    <td class="text-center pe-3">
                                        @php
                                            $statusMap = [
                                                'disetujui' => ['class' => 'success', 'text' => 'Disetujui'],
                                                'ditolak' => ['class' => 'danger', 'text' => 'Ditolak'],
                                                'pending' => ['class' => 'warning', 'text' => 'Pending']
                                            ];
                                            $statusKey = $item->status_validasi ?? 'pending';
                                            $status = $statusMap[$statusKey] ?? $statusMap['pending'];
                                        @endphp
                                        <span class="badge bg-{{ $status['class'] }}-soft px-3 py-1 rounded-pill small">
                                            {{ $status['text'] }}
                                        </span>
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
    // Data untuk chart dari controller
    var chartLabels = @json($chartData['labels'] ?? []);
    var chartHadir = @json($chartData['hadir'] ?? []);
    var chartSakit = @json($chartData['sakit'] ?? []);
    var chartIzin = @json($chartData['izin'] ?? []);
    var chartAlpha = @json($chartData['alpha'] ?? []);

    var ctx = document.getElementById('trendChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Hadir',
                    data: chartHadir,
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
                    data: chartSakit,
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#0dcaf0',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Izin',
                    data: chartIzin,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Alpha',
                    data: chartAlpha,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#dc3545',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        usePointStyle: true,
                        font: { size: 11 },
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor: '#fff',
                    bodyColor: '#ddd',
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e9ecef',
                        drawBorder: false
                    },
                    ticks: { stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
});
</script>

<style scoped>
/* Style khusus untuk konten dashboard */
.dashboard-content {
    width: 100%;
}

.stat-card {
    transition: all 0.2s ease;
    border-radius: 12px;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 24px;
}

/* Badge Soft Colors */
.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
    color: #997404;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.25rem;
}

.table td, .table th {
    padding: 0.85rem;
    font-size: 0.875rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 193, 7, 0.05);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-icon {
        width: 40px;
        height: 40px;
    }

    .stat-icon i {
        font-size: 20px;
    }

    .table td, .table th {
        padding: 0.6rem;
        font-size: 0.75rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    h6 {
        font-size: 0.7rem;
    }
}
</style>
@endsection
