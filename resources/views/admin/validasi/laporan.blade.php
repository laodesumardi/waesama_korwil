@extends('layouts.app')

@section('content')
<div class="laporan-absensi">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-file-text-fill me-2 text-warning"></i>
                Laporan Absensi
            </h3>
            <p class="text-muted small mb-0 mt-1">Rekap dan laporan data absensi siswa dan guru</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.validasi.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('admin.validasi.export', request()->query()) }}" class="btn btn-success">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.validasi.laporan') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Jenis Absensi</label>
                    <select name="jenis_absensi" class="form-select">
                        <option value="">Semua</option>
                        <option value="siswa" {{ request('jenis_absensi') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ request('jenis_absensi') == 'guru' ? 'selected' : '' }}>Guru</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Periode</label>
                    <select name="id_periode" class="form-select">
                        <option value="">Semua Periode</option>
                        @foreach($periodeList as $periode)
                            <option value="{{ $periode->id }}" {{ request('id_periode') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->tahun_ajaran }} - Sem {{ $periode->semester }}
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
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status Validasi</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                    <a href="{{ route('admin.validasi.laporan') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-repeat"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Rekap Data --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-2">
                        <i class="bi bi-database fs-4 text-primary"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-primary">{{ number_format($rekap['jumlah_data']) }}</h3>
                    <small class="text-muted">Total Data</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-2">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-success">{{ number_format($rekap['total_hadir']) }}</h3>
                    <small class="text-muted">Total Hadir</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex p-3 mb-2">
                        <i class="bi bi-thermometer-half fs-4 text-info"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-info">{{ number_format($rekap['total_sakit']) }}</h3>
                    <small class="text-muted">Total Sakit</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex p-3 mb-2">
                        <i class="bi bi-envelope fs-4 text-warning"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-warning">{{ number_format($rekap['total_izin']) }}</h3>
                    <small class="text-muted">Total Izin</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Detail --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-pie-chart me-2 text-warning"></i>
                Detail Rekap
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Total Alpha</td>
                                <td class="fw-bold">{{ number_format($rekap['total_alpha']) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Siswa/Guru</td>
                                <td class="fw-bold">{{ number_format($rekap['total_siswa']) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Rata-rata Hadir per Hari</td>
                                <td class="fw-bold">{{ number_format($rekap['rata_hadir'], 1) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Persentase Kehadiran</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: {{ $rekap['persen_kehadiran'] }}%"></div>
                                        </div>
                                        <span class="fw-bold">{{ $rekap['persen_kehadiran'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Data Pending</td>
                                <td class="fw-bold text-warning">{{ number_format($rekap['pending'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Data Disetujui</td>
                                <td class="fw-bold text-success">{{ number_format($rekap['disetujui'] ?? 0) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Data Absensi --}}
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
                            <th class="py-3 ps-3" style="width: 60px">#</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Jenis</th>
                            <th class="py-3">NPSN</th>
                            <th class="py-3">Nama Sekolah</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">Sakit</th>
                            <th class="py-3 text-center">Izin</th>
                            <th class="py-3 text-center">Alpha</th>
                            <th class="py-3 text-center">Total</th>
                            <th class="py-3 pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $item)
                        <tr>
                            <td class="ps-3">{{ $absensi->firstItem() + $index }}</td>
                            <td class="small">{{ $item->tanggal->format('d/m/Y') }}</td>
                            <td>
                                @if($item->jenis_absensi == 'siswa')
                                    <span class="badge bg-primary-soft px-2 py-1">
                                        <i class="bi bi-people-fill me-1"></i> Siswa
                                    </span>
                                @else
                                    <span class="badge bg-info-soft px-2 py-1">
                                        <i class="bi bi-person-badge-fill me-1"></i> Guru
                                    </span>
                                @endif
                            </td>
                            <td class="small">{{ $item->sekolah->npsn ?? '-' }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-building text-muted"></i>
                                    <div>
                                        <div class="small fw-semibold">{{ $item->sekolah->nama_sekolah ?? '-' }}</div>
                                        <div class="text-muted" style="font-size: 10px">{{ $item->sekolah->kecamatan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><span class="fw-semibold text-success">{{ number_format($item->jumlah_hadir) }}</span></td>
                            <td class="text-center">
                                @if($item->jenis_absensi == 'siswa')
                                    {{ number_format($item->jumlah_sakit) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($item->jumlah_izin) }}</td>
                            <td class="text-center">{{ number_format($item->jumlah_alpha) }}</td>
                            <td class="text-center"><strong>{{ number_format($item->total_siswa) }}</strong></td>
                            <td>{!! $item->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            {{ $absensi->links() }}
        </div>
    </div>
</div>

<style scoped>
.laporan-absensi {
    width: 100%;
}

.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
    color: #997404;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}
</style>
@endsection
