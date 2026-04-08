@extends('layouts.app')

@section('content')
<div class="siswa-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-person-badge me-2 text-warning"></i>
            Detail Siswa
        </h3>
        <div>
            <a href="{{ route('operator.siswa.edit', $siswa->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('operator.siswa.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-person fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $siswa->nama_siswa }}</h4>
                    <span class="badge bg-info-soft px-3 py-1 rounded-pill">{{ $siswa->kelas }}</span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted d-block">Status</small>
                            @if($siswa->status == 'aktif')
                                <span class="badge bg-success-soft px-3 py-1 rounded-pill">Aktif</span>
                            @elseif($siswa->status == 'lulus')
                                <span class="badge bg-primary-soft px-3 py-1 rounded-pill">Lulus</span>
                            @else
                                <span class="badge bg-danger-soft px-3 py-1 rounded-pill">{{ ucfirst($siswa->status) }}</span>
                            @endif
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
                            <label class="text-muted small text-uppercase">NISN</label>
                            <p class="fw-semibold mb-0">{{ $siswa->nisn }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">NIS</label>
                            <p class="fw-semibold mb-0">{{ $siswa->nis }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nama Lengkap</label>
                            <p class="mb-0">{{ $siswa->nama_siswa }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Jenis Kelamin</label>
                            <p class="mb-0">{{ $siswa->jenis_kelamin_label }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Tempat, Tanggal Lahir</label>
                            <p class="mb-0">{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Kelas</label>
                            <p class="mb-0">{{ $siswa->kelas }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">No. HP</label>
                            <p class="mb-0">{{ $siswa->no_hp ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Status</label>
                            <p class="mb-0">{{ ucfirst($siswa->status) }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small text-uppercase">Alamat</label>
                            <p class="mb-0">{{ $siswa->alamat ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small text-uppercase">Keterangan</label>
                            <p class="mb-0">{{ $siswa->keterangan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style scoped>
.siswa-detail { width: 100%; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
