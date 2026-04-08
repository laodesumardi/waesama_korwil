@extends('layouts.app')

@section('content')
<div class="periode-index">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-calendar3-fill me-2 text-warning"></i>
                Manajemen Periode Ajaran
            </h3>
            <p class="text-muted small mb-0 mt-1">Kelola periode ajaran dan semester</p>
        </div>
        <a href="{{ route('admin.periode.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-circle me-1"></i> Tambah Periode
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.periode.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Tahun ajaran..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="">Semua Semester</option>
                        <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Ganjil</option>
                        <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status Aktif</label>
                    <select name="is_active" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
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

    {{-- Tabel Periode --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3" style="width: 60px">#</th>
                            <th class="py-3">Tahun Ajaran</th>
                            <th class="py-3">Semester</th>
                            <th class="py-3">Tanggal Mulai</th>
                            <th class="py-3">Tanggal Selesai</th>
                            <th class="py-3">Status Periode</th>
                            <th class="py-3 text-center">Aktif</th>
                            <th class="py-3 text-center pe-3" style="width: 130px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periode as $index => $item)
                        <tr>
                            <td class="ps-3">{{ $periode->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-calendar text-primary"></i>
                                    </div>
                                    <strong class="small">{{ $item->tahun_ajaran }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-book-half me-1"></i> Semester {{ $item->semester }} ({{ $item->semester_label }})
                                </span>
                            </td>
                            <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $statusClass = match($item->status_periode) {
                                        'Berjalan' => 'success',
                                        'Aktif (Belum Mulai)' => 'warning',
                                        'Selesai' => 'secondary',
                                        default => 'danger'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}-soft px-3 py-1 rounded-pill">
                                    <i class="bi {{ $item->status_periode == 'Berjalan' ? 'bi-play-circle-fill' : ($item->status_periode == 'Selesai' ? 'bi-check-circle-fill' : 'bi-pause-circle-fill') }} me-1"></i>
                                    {{ $item->status_periode }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1 small"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1 small"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('admin.periode.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.periode.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(!$item->is_active)

                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Modal Delete --}}
                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p>Apakah Anda yakin ingin menghapus periode ajaran <strong>{{ $item->tahun_ajaran }} - Semester {{ $item->semester }}</strong>?</p>
                                                @if($item->absensi->count() > 0)
                                                    <div class="alert alert-warning">
                                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                                        Periode ini memiliki {{ $item->absensi->count() }} data absensi. Tidak dapat dihapus.
                                                    </div>
                                                @else
                                                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                @if($item->absensi->count() == 0)
                                                    <form action="{{ route('admin.periode.destroy', $item->id) }}" method="POST">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data periode ajaran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            {{ $periode->links() }}
        </div>
    </div>
</div>

<style scoped>
.periode-index {
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

.bg-secondary-soft {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.btn-group {
    gap: 5px;
}

.btn-group .btn {
    border-radius: 6px;
}
</style>
@endsection
