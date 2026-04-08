@extends('layouts.app')

@section('content')
<div class="absensi-history">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-clock-history me-2 text-warning"></i>
            Riwayat Absensi
        </h3>
        <a href="{{ route('operator.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('operator.absensi.history') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Jenis Absensi</label>
                    <select name="jenis_absensi" class="form-select">
                        <option value="">Semua</option>
                        <option value="siswa" {{ request('jenis_absensi') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ request('jenis_absensi') == 'guru' ? 'selected' : '' }}>Guru</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">Semua</option>
                        @foreach($bulanList as $key => $bulan)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status Validasi</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        @foreach($statusOptions as $key => $status)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3" style="width: 60px">#</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Jenis</th>
                            <th class="py-3">Periode</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">Sakit</th>
                            <th class="py-3 text-center">Izin</th>
                            <th class="py-3 text-center">Alpha</th>
                            <th class="py-3 text-center">Total</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 pe-3 text-center">Aksi</th>
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
                            <td>{{ $item->periode->tahun_ajaran ?? '-' }} - Sem {{ $item->periode->semester ?? '-' }}</td>
                            <td class="text-center"><span class="fw-semibold text-success">{{ number_format($item->jumlah_hadir) }}</span></td>
                            <td class="text-center">{{ number_format($item->jumlah_sakit) }}</td>
                            <td class="text-center">{{ number_format($item->jumlah_izin) }}</td>
                            <td class="text-center">{{ number_format($item->jumlah_alpha) }}</td>
                            <td class="text-center"><strong>{{ number_format($item->total_siswa) }}</strong></td>
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
                                <a href="{{ route('operator.absensi.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data absensi
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
.absensi-history {
    width: 100%;
}
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); color: #997404; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
