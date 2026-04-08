@extends('layouts.app')

@section('content')
<div class="laporan-page">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-graph-up me-2 text-warning"></i>
                Laporan & Statistik
            </h3>
            <p class="text-muted small mb-0 mt-1">Rekapitulasi data absensi seluruh sekolah</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.validasi.export', request()->query()) }}" class="btn btn-success">
                <i class="bi bi-file-spreadsheet me-1"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Periode Ajaran</label>
                    <select name="id_periode" class="form-select">
                        <option value="">Semua Periode</option>
                        @foreach($periodeList as $periode)
                            <option value="{{ $periode->id }}" {{ request('id_periode') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->tahun_ajaran }} - Semester {{ $periode->semester }}
                                @if($periode->is_active) (Aktif) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Korwil</label>
                    <select name="id_korwil" class="form-select">
                        <option value="">Semua Korwil</option>
                        @foreach($korwilList as $korwil)
                            <option value="{{ $korwil->id }}" {{ request('id_korwil') == $korwil->id ? 'selected' : '' }}>
                                {{ $korwil->nama_korwil }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Sekolah</label>
                    <select name="id_sekolah" class="form-select">
                        <option value="">Semua Sekolah</option>
                        @foreach($sekolahList as $sekolah)
                            <option value="{{ $sekolah->id }}" {{ request('id_sekolah') == $sekolah->id ? 'selected' : '' }}>
                                {{ $sekolah->nama_sekolah }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status Validasi</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-repeat me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Data</span>
                            <h3 class="mb-0 mt-1 fw-bold">{{ number_format($rekap['total_data']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-calendar-check fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total Kehadiran</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">{{ number_format($rekap['total_hadir']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-person-check fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Rata-rata Hadir/Hari</span>
                            <h3 class="mb-0 mt-1 fw-bold">{{ number_format($rekap['rata_hadir']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-graph-up fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Persentase Kehadiran</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">{{ $rekap['persen_kehadiran'] }}%</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-pie-chart fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Tren --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-graph-up me-2 text-warning"></i>
                        Grafik Tren Absensi
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Per Sekolah & Per Korwil --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-building me-2 text-warning"></i>
                        Top 10 Sekolah (Tertinggi)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-2 ps-3">#</th>
                                    <th class="py-2">Sekolah</th>
                                    <th class="py-2 text-center">Absensi</th>
                                    <th class="py-2 text-center">Hadir</th>
                                    <th class="py-2 text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perSekolah as $index => $item)
                                <tr>
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td class="small">{{ $item->nama_sekolah }}</td>
                                    <td class="text-center">{{ $item->jumlah_absensi }}</td>
                                    <td class="text-center">{{ number_format($item->total_hadir) }}</td>
                                    <td class="text-center">
                                        @php $persen = $item->total_siswa > 0 ? round(($item->total_hadir / $item->total_siswa) * 100, 1) : 0; @endphp
                                        <div class="progress" style="height: 6px; width: 80px; display: inline-block;">
                                            <div class="progress-bar bg-success" style="width: {{ $persen }}%"></div>
                                        </div>
                                        <span class="ms-1 small">{{ $persen }}%</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-diagram-3 me-2 text-warning"></i>
                        Statistik Per Korwil
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr class="small">
                                    <th class="py-2 ps-3">#</th>
                                    <th class="py-2">Korwil</th>
                                    <th class="py-2 text-center">Absensi</th>
                                    <th class="py-2 text-center">Hadir</th>
                                    <th class="py-2 text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perKorwil as $index => $item)
                                <tr>
                                    <td class="ps-3">{{ $index + 1 }}</td>
                                    <td class="small">{{ $item->nama_korwil ?? '-' }}</td>
                                    <td class="text-center">{{ $item->jumlah_absensi }}</td>
                                    <td class="text-center">{{ number_format($item->total_hadir) }}</td>
                                    <td class="text-center">
                                        @php $persen = $item->total_siswa > 0 ? round(($item->total_hadir / $item->total_siswa) * 100, 1) : 0; @endphp
                                        <div class="progress" style="height: 6px; width: 80px; display: inline-block;">
                                            <div class="progress-bar bg-primary" style="width: {{ $persen }}%"></div>
                                        </div>
                                        <span class="ms-1 small">{{ $persen }}%</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{-- Detail Data Absensi --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom-0 pt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-warning"></i>
                Detail Data Absensi
            </h5>
            <span class="badge bg-light text-dark">{{ $absensi->total() }} Data</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr class="small">
                        <th class="py-3 ps-3">#</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Sekolah</th>
                        <th class="py-3">Korwil</th>
                        <th class="py-3 text-center">Hadir</th>
                        <th class="py-3 text-center">Sakit</th>
                        <th class="py-3 text-center">Izin</th>
                        <th class="py-3 text-center">Alpha</th>
                        <th class="py-3 text-center">Status Validasi</th>
                        <th class="py-3 pe-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $index => $item)
                    <tr>
                        <td class="ps-3">{{ $absensi->firstItem() + $index }}</td>
                        <td class="small">
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ $item->tanggal->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-building text-muted"></i>
                                <div>
                                    <div class="small fw-semibold">{{ $item->sekolah->nama_sekolah ?? '-' }}</div>
                                    <div class="text-muted" style="font-size: 10px">{{ $item->sekolah->npsn ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="small">
                            <i class="bi bi-diagram-3 me-1 text-muted"></i>
                            {{ $item->sekolah->korwil->nama_korwil ?? '-' }}
                        </td>
                        <td class="text-center">
                            <span class="fw-semibold text-success">
                                <i class="bi bi-check-circle-fill me-1 small"></i>
                                {{ number_format($item->jumlah_hadir) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-info">
                                <i class="bi bi-heart-pulse me-1 small"></i>
                                {{ number_format($item->jumlah_sakit) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-warning">
                                <i class="bi bi-envelope-paper me-1 small"></i>
                                {{ number_format($item->jumlah_izin) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-danger">
                                <i class="bi bi-x-circle-fill me-1 small"></i>
                                {{ number_format($item->jumlah_alpha) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php
                                $statusConfig = [
                                    'disetujui' => [
                                        'class' => 'success',
                                        'icon' => 'check-circle-fill',
                                        'text' => 'Disetujui',
                                        'bg' => 'bg-success-soft'
                                    ],
                                    'ditolak' => [
                                        'class' => 'danger',
                                        'icon' => 'x-circle-fill',
                                        'text' => 'Ditolak',
                                        'bg' => 'bg-danger-soft'
                                    ],
                                    'pending' => [
                                        'class' => 'warning',
                                        'icon' => 'clock-history',
                                        'text' => 'Menunggu Validasi',
                                        'bg' => 'bg-warning-soft'
                                    ],
                                    'revisi' => [
                                        'class' => 'info',
                                        'icon' => 'arrow-repeat',
                                        'text' => 'Perlu Revisi',
                                        'bg' => 'bg-info-soft'
                                    ]
                                ];
                                $status = $statusConfig[$item->status_validasi ?? 'pending'] ?? $statusConfig['pending'];
                            @endphp
                            <span class="badge {{ $status['bg'] }} px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                {{ $status['text'] }}
                            </span>
                        </td>
                        <td class="text-center pe-3">
                            <a href="{{ route('admin.validasi.show', $item->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               data-bs-toggle="tooltip"
                               title="Detail Validasi">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p class="mb-0">Tidak ada data absensi</p>
                            <small>Silakan input data absensi terlebih dahulu</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Menampilkan {{ $absensi->firstItem() ?? 0 }} - {{ $absensi->lastItem() ?? 0 }} dari {{ $absensi->total() }} data
            </div>
            {{ $absensi->links() }}
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
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#0dcaf0',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
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
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
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
                    grid: { color: '#e9ecef', drawBorder: false },
                    ticks: { stepSize: 1, font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 }, rotation: 45 }
                }
            }
        }
    });
});
</script>

<style scoped>
.laporan-page {
    width: 100%;
}

.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
}
.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.table td, .table th {
    padding: 0.85rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .table td, .table th {
        padding: 0.6rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection
