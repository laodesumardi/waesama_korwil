@extends('layouts.app')

@section('content')
<div class="sekolah-index">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="bi bi-building-fill me-2 text-warning"></i>
                Manajemen Sekolah
            </h3>
            <p class="text-muted small">Kelola data sekolah binaan</p>
        </div>
        <a href="{{ route('admin.sekolah.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-circle me-1"></i> Tambah
        </a>
    </div>

    {{-- Statistik --}}
    <div class="mb-4 row g-3">
        <div class="col-md-3 col-6">
            <div class="border-0 card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Total Sekolah</span>
                            <h3 class="mb-0 fw-bold">{{ number_format($sekolahs->total()) }}</h3>
                        </div>
                        <i class="opacity-50 bi bi-building fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="border-0 card bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Aktif</span>
                            <h3 class="mb-0 fw-bold text-success">{{ number_format($sekolahs->where('status', 'aktif')->count()) }}</h3>
                        </div>
                        <i class="opacity-50 bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="border-0 card bg-danger bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Nonaktif</span>
                            <h3 class="mb-0 fw-bold text-danger">{{ number_format($sekolahs->where('status', 'nonaktif')->count()) }}</h3>
                        </div>
                        <i class="opacity-50 bi bi-x-circle fs-2 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="border-0 card bg-info bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted small">Korwil</span>
                            <h3 class="mb-0 fw-bold text-info">{{ number_format($korwilList->count()) }}</h3>
                        </div>
                        <i class="opacity-50 bi bi-diagram-3 fs-2 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="mb-4 border-0 shadow-sm card">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="border-0 form-control bg-light"
                           placeholder="Cari sekolah..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="id_korwil" class="border-0 form-select bg-light">
                        <option value="">Semua Korwil</option>
                        @foreach($korwilList as $korwil)
                            <option value="{{ $korwil->id }}" {{ request('id_korwil') == $korwil->id ? 'selected' : '' }}>
                                {{ $korwil->nama_korwil }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="border-0 form-select bg-light">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="gap-2 d-flex">
                        <button class="btn btn-dark w-100"><i class="bi bi-search"></i></button>
                        <a href="{{ route('admin.sekolah.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-repeat"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="border-0 shadow-sm card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle table-hover">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th class="py-3 ps-3">#</th>
                        <th>NPSN</th>
                        <th>Nama Sekolah</th>
                        <th>Kepala Sekolah</th>
                        <th>Korwil</th>
                        <th>Kecamatan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sekolahs as $index => $sekolah)
                    <tr>
                        <td class="ps-3">{{ $sekolahs->firstItem() + $index }}</td>
                        <td><span class="badge bg-light text-dark">{{ $sekolah->npsn }}</span></td>
                        <td>
                            <div class="gap-2 d-flex align-items-center">
                                <i class="bi bi-building text-primary"></i>
                                <div>
                                    <div class="fw-semibold">{{ $sekolah->nama_sekolah }}</div>
                                    <div class="small text-muted">{{ $sekolah->kelurahan }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $sekolah->nama_kepala_sekolah ?? '-' }}</div>
                            @if($sekolah->no_telp_kepala_sekolah)
                                <small class="text-muted">{{ $sekolah->no_telp_kepala_sekolah }}</small>
                            @endif
                        </td>
                        <td>{{ $sekolah->korwil->nama_korwil ?? '-' }}</td>
                        <td>{{ $sekolah->kecamatan }}</td>
                        <td class="text-center">
                            @if($sekolah->status == 'aktif')
                                <span class="px-3 py-1 badge bg-success bg-opacity-10 text-success">Aktif</span>
                            @else
                                <span class="px-3 py-1 badge bg-danger bg-opacity-10 text-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.sekolah.show', $sekolah->id) }}" class="btn btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.sekolah.edit', $sekolah->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sekolah->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Hapus --}}
                    <div class="modal fade" id="deleteModal{{ $sekolah->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="p-4 text-center modal-body">
                                    <i class="mb-3 bi bi-exclamation-triangle fs-1 text-warning d-block"></i>
                                    <p class="mb-0">Hapus <strong>{{ $sekolah->nama_sekolah }}</strong>?</p>
                                    @if($sekolah->absensi->count() > 0)
                                        <div class="mt-3 mb-0 alert alert-warning small">Masih punya data absensi</div>
                                    @else
                                        <div class="mt-3">
                                            <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.sekolah.destroy', $sekolah->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="py-5 text-center text-muted">
                            <i class="mb-2 bi bi-inbox fs-2 d-block"></i>
                            <span>Belum ada data</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pb-3 bg-transparent border-0 card-footer">
            {{ $sekolahs->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<style scoped>
.sekolah-index .table td {
    padding: 12px 8px;
    vertical-align: middle;
}
.sekolah-index .table th {
    padding: 12px 8px;
}
.btn-group .btn {
    padding: 4px 8px;
    font-size: 12px;
}
</style>
@endsection
