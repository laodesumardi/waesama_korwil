@extends('layouts.app')

@section('content')
<div class="validasi-index">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-check2-circle me-2 text-warning"></i>
                Validasi Absensi
            </h3>
            <p class="text-muted small mb-0 mt-1">Validasi data absensi siswa dan guru yang masuk dari sekolah</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.validasi.laporan') }}" class="btn btn-outline-info">
                <i class="bi bi-file-text me-1"></i> Laporan
            </a>
            <a href="{{ route('admin.validasi.export') }}" class="btn btn-outline-success">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- Statistik Card --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase">Total</span>
                            <h3 class="mb-0 mt-1 fw-bold">{{ number_format($stats['total']) }}</h3>
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
                            <span class="text-muted small text-uppercase">Pending</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">{{ number_format($stats['pending']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-clock-history fs-4 text-warning"></i>
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
                            <span class="text-muted small text-uppercase">Disetujui</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">{{ number_format($stats['disetujui']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-check-circle fs-4 text-success"></i>
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
                            <span class="text-muted small text-uppercase">Ditolak</span>
                            <h3 class="mb-0 mt-1 fw-bold text-danger">{{ number_format($stats['ditolak']) }}</h3>
                        </div>
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-x-circle fs-4 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.validasi.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama sekolah / NPSN..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Jenis Absensi</label>
                    <select name="jenis_absensi" class="form-select">
                        <option value="">Semua</option>
                        <option value="siswa" {{ request('jenis_absensi') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ request('jenis_absensi') == 'guru' ? 'selected' : '' }}>Guru</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.validasi.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-repeat"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Action --}}
    @if($absensi->where('status_validasi', 'pending')->count() > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form id="bulkForm" action="{{ route('admin.validasi.bulk-approve') }}" method="POST">
                @csrf
                <input type="hidden" name="ids" id="bulkIds">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectAll">
                        <label class="form-check-label small" for="selectAll">Pilih Semua</label>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success" id="bulkApproveBtn" disabled>
                        <i class="bi bi-check2-all me-1"></i> Setujui Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Tabel Absensi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3" style="width: 40px">
                                <input type="checkbox" class="form-check-input rowCheckAll" id="selectAllRows" style="display: none;">
                            </th>
                            <th class="py-3" style="width: 60px">#</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Jenis</th>
                            <th class="py-3">Sekolah</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">Sakit</th>
                            <th class="py-3 text-center">Izin</th>
                            <th class="py-3 text-center">Alpha</th>
                            <th class="py-3 text-center">Total</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center pe-3" style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $item)
                        <tr>
                            <td class="ps-3">
                                @if($item->status_validasi == 'pending')
                                    <input type="checkbox" class="form-check-input rowCheck" value="{{ $item->id }}">
                                @endif
                            </td>
                            <td>{{ $absensi->firstItem() + $index }}</td>
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
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-building text-muted"></i>
                                    <div>
                                        <div class="small fw-semibold">{{ $item->sekolah->nama_sekolah ?? '-' }}</div>
                                        <div class="text-muted" style="font-size: 11px">{{ $item->sekolah->npsn ?? '-' }}</div>
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
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.validasi.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($item->status_validasi == 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-success" title="Setujui" data-bs-toggle="modal" data-bs-target="#approveModal{{ $item->id }}">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Tolak" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @endif
                                </div>

                                {{-- Modal Approve --}}
                                <div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Setujui</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p>Yakin ingin menyetujui absensi <strong>{{ $item->jenis_absensi == 'siswa' ? 'Siswa' : 'Guru' }}</strong> dari <strong>{{ $item->sekolah->nama_sekolah ?? '-' }}</strong> tanggal <strong>{{ $item->tanggal->format('d/m/Y') }}</strong>?</p>
                                                <div class="alert alert-info mt-2">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>Rekap:</strong> Hadir: {{ $item->jumlah_hadir }},
                                                    @if($item->jenis_absensi == 'siswa') Sakit: {{ $item->jumlah_sakit }}, @endif
                                                    Izin: {{ $item->jumlah_izin }}, Alpha: {{ $item->jumlah_alpha }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.validasi.approve', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Reject --}}
                                <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.validasi.reject', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Absensi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p>Tolak absensi <strong>{{ $item->jenis_absensi == 'siswa' ? 'Siswa' : 'Guru' }}</strong> dari <strong>{{ $item->sekolah->nama_sekolah ?? '-' }}</strong> tanggal <strong>{{ $item->tanggal->format('d/m/Y') }}</strong></p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Berikan alasan penolakan..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-5 text-muted">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const rowChecks = document.querySelectorAll('.rowCheck');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkIdsInput = document.getElementById('bulkIds');

    function updateBulkButton() {
        const checked = document.querySelectorAll('.rowCheck:checked');
        bulkApproveBtn.disabled = checked.length === 0;

        const ids = Array.from(checked).map(cb => cb.value);
        bulkIdsInput.value = JSON.stringify(ids);
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowChecks.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkButton();
        });
    }

    rowChecks.forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });

    updateBulkButton();
});
</script>

<style scoped>
.validasi-index {
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

.btn-group {
    gap: 5px;
}

.btn-group .btn {
    border-radius: 6px;
}
</style>
@endsection
