@extends('layouts.app')

@section('content')
<div class="korwil-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-person-badge me-2 text-warning"></i>
            Detail Korwil
        </h3>
        <div>
            <a href="{{ route('admin.korwil.edit', $korwil->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.korwil.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-person-badge fs-1 text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ $korwil->nama_korwil }}</h4>
                    <span class="badge bg-info-soft px-3 py-1 rounded-pill">
                        <i class="bi bi-hash me-1"></i> {{ $korwil->kode_wilayah }}
                    </span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted d-block">Status User</small>
                            @if($korwil->user && $korwil->user->is_active)
                                <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Nonaktif
                                </span>
                            @endif
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">Jumlah Sekolah</small>
                            <span class="badge bg-primary-soft px-3 py-1 rounded-pill">
                                <i class="bi bi-building me-1"></i> {{ $korwil->sekolah->count() }} Sekolah
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2 text-warning"></i>
                        Informasi Lengkap
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nama Lengkap</label>
                            <p class="fw-semibold mb-0">{{ $korwil->nama_korwil }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Kode Wilayah</label>
                            <p class="fw-semibold mb-0">{{ $korwil->kode_wilayah }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">User Login</label>
                            <p class="mb-0">
                                @if($korwil->user)
                                    <strong>{{ $korwil->user->name }}</strong><br>
                                    <small class="text-muted">{{ $korwil->user->email }}</small>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nomor SK</label>
                            <p class="mb-0">{{ $korwil->no_sk ?? '-' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="text-muted small text-uppercase">Wilayah Kerja</label>
                            <p class="mb-0">{{ $korwil->wilayah_kerja }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Dibuat Pada</label>
                            <p class="mb-0">{{ $korwil->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Terakhir Update</label>
                            <p class="mb-0">{{ $korwil->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Sekolah --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-building me-2 text-warning"></i>
                Daftar Sekolah Binaan
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3">NPSN</th>
                            <th class="py-3">Nama Sekolah</th>
                            <th class="py-3">Alamat</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 pe-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sekolahList as $sekolah)
                        <tr>
                            <td class="ps-3">{{ $sekolah->npsn }}</td>
                            <td>{{ $sekolah->nama_sekolah }}</td>
                            <td>{{ $sekolah->kecamatan }}, {{ $sekolah->kelurahan }}</td>
                            <td class="text-center">
                                @if($sekolah->status == 'aktif')
                                    <span class="badge bg-success-soft px-3 py-1 rounded-pill">Aktif</span>
                                @else
                                    <span class="badge bg-danger-soft px-3 py-1 rounded-pill">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('admin.sekolah.show', $sekolah->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada sekolah binaan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            {{ $sekolahList->links() }}
        </div>
    </div>
</div>

<style scoped>
.korwil-detail {
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

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
    color: #997404;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}
</style>
@endsection
