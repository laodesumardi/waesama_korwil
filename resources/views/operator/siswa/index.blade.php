@extends('layouts.app')

@section('content')
<div class="siswa-index">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0 fw-semibold">
                <i class="bi bi-people-fill me-2 text-warning"></i>
                Data Siswa
            </h3>
            <p class="text-muted small mb-0 mt-1">Kelola data siswa di sekolah {{ $sekolah->nama_sekolah }}</p>
        </div>
        <a href="{{ route('operator.siswa.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-circle me-1"></i> Tambah Siswa
        </a>
    </div>

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('operator.siswa.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, NISN, NIS..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kelas</label>
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
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

    {{-- Tabel Siswa --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3" style="width: 60px">#</th>
                            <th class="py-3">NISN</th>
                            <th class="py-3">NIS</th>
                            <th class="py-3">Nama Siswa</th>
                            <th class="py-3">Kelas</th>
                            <th class="py-3">Jenis Kelamin</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center pe-3" style="width: 120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswa as $index => $item)
                        <tr>
                            <td class="ps-3">{{ $siswa->firstItem() + $index }}</td>
                            <td><span class="fw-mono">{{ $item->nisn }}</span></td>
                            <td>{{ $item->nis }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <strong class="small">{{ $item->nama_siswa }}</strong>
                                </div>
                            </td>
                            <td><span class="badge bg-info-soft px-2 py-1">{{ $item->kelas }}</span></td>
                            <td>{{ $item->jenis_kelamin_label }}</td>
                            <td class="text-center">
                                @if($item->status == 'aktif')
                                    <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1 small"></i> Aktif
                                    </span>
                                @elseif($item->status == 'lulus')
                                    <span class="badge bg-primary-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-trophy-fill me-1 small"></i> Lulus
                                    </span>
                                @elseif($item->status == 'pindah')
                                    <span class="badge bg-warning-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-arrow-right-circle me-1 small"></i> Pindah
                                    </span>
                                @else
                                    <span class="badge bg-danger-soft px-3 py-1 rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1 small"></i> Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group">
                                    <a href="{{ route('operator.siswa.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('operator.siswa.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
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
                                                <p>Apakah Anda yakin ingin menghapus siswa <strong>{{ $item->nama_siswa }}</strong>?</p>
                                                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('operator.siswa.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                </form>
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
                                Belum ada data siswa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            {{ $siswa->links() }}
        </div>
    </div>
</div>

<style scoped>
.siswa-index { width: 100%; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); color: #997404; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
.btn-group { gap: 5px; }
.btn-group .btn { border-radius: 6px; }
</style>
@endsection
