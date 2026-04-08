@extends('layouts.app')

@section('content')
<div class="sekolah-index">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-building-fill me-2 text-warning"></i>
                Manajemen Sekolah
            </h3>
            <p class="text-muted small mb-0 mt-1">Kelola data sekolah binaan</p>
        </div>
        <a href="{{ route('admin.sekolah.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-circle me-1"></i> Tambah Sekolah
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sekolah.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama sekolah, NPSN, kecamatan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Korwil</label>
                    <select name="id_korwil" class="form-select">
                        <option value="">Semua Korwil</option>
                        @foreach($korwilList as $korwil)
                            <option value="{{ $korwil->id }}" {{ request('id_korwil') == $korwil->id ? 'selected' : '' }}>
                                {{ $korwil->nama_korwil }} ({{ $korwil->kode_wilayah }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Sekolah --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3" style="width: 60px">#</th>
                            <th class="py-3">NPSN</th>
                            <th class="py-3">Nama Sekolah</th>
                            <th class="py-3">Korwil</th>
                            <th class="py-3">Kecamatan</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center pe-3" style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sekolahs as $index => $sekolah)
                        <tr>
                            <td class="ps-3">{{ $sekolahs->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-info-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-qr-code me-1"></i> {{ $sekolah->npsn }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-building text-primary"></i>
                                    </div>
                                    <div>
                                        <strong class="small">{{ $sekolah->nama_sekolah }}</strong>
                                        <div class="text-muted small">{{ $sekolah->kelurahan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($sekolah->korwil)
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-person-badge text-muted small"></i>
                                        <span class="small">{{ $sekolah->korwil->nama_korwil }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>{{ $sekolah->kecamatan }}</td>
                            <td class="text-center">
                                @if($sekolah->status == 'aktif')
                                    <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1 small"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1 small"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.sekolah.show', $sekolah->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.sekolah.edit', $sekolah->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sekolah->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Modal Delete --}}
                                <div class="modal fade" id="deleteModal{{ $sekolah->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p>Apakah Anda yakin ingin menghapus sekolah <strong>{{ $sekolah->nama_sekolah }}</strong>?</p>
                                                @if($sekolah->absensi->count() > 0)
                                                    <div class="alert alert-warning">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                                        Sekolah ini memiliki {{ $sekolah->absensi->count() }} data absensi. Tidak dapat dihapus.
                                                    </div>
                                                @else
                                                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                @if($sekolah->absensi->count() == 0)
                                                    <form action="{{ route('admin.sekolah.destroy', $sekolah->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data sekolah
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            {{ $sekolahs->links() }}
        </div>
    </div>
</div>

<style scoped>
.sekolah-index {
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
