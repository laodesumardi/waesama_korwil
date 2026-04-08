@extends('layouts.app')

@section('content')
<div class="guru-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-person-badge me-2 text-warning"></i>
            Detail Guru
        </h3>
        <div>
            <a href="{{ route('operator.guru.edit', $guru->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('operator.guru.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-person-badge fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $guru->nama_guru }}</h4>
                    <span class="badge bg-info-soft px-3 py-1 rounded-pill">{{ $guru->bidang_studi }}</span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted d-block">Status</small>
                            @if($guru->status == 'aktif')
                                <span class="badge bg-success-soft px-3 py-1 rounded-pill">Aktif</span>
                            @elseif($guru->status == 'pensiun')
                                <span class="badge bg-warning-soft px-3 py-1 rounded-pill">Pensiun</span>
                            @else
                                <span class="badge bg-danger-soft px-3 py-1 rounded-pill">{{ ucfirst($guru->status) }}</span>
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
                            <label class="text-muted small text-uppercase">NIP</label>
                            <p class="fw-semibold mb-0">{{ $guru->nip }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">NUPTK</label>
                            <p class="mb-0">{{ $guru->nuptk ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nama Lengkap</label>
                            <p class="mb-0">{{ $guru->nama_guru }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Jenis Kelamin</label>
                            <p class="mb-0">{{ $guru->jenis_kelamin_label }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Tempat, Tanggal Lahir</label>
                            <p class="mb-0">{{ $guru->tempat_lahir }}, {{ $guru->tanggal_lahir->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Pendidikan Terakhir</label>
                            <p class="mb-0">{{ $guru->pendidikan_terakhir }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Bidang Studi</label>
                            <p class="mb-0">{{ $guru->bidang_studi }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Email</label>
                            <p class="mb-0">{{ $guru->email ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">No. HP</label>
                            <p class="mb-0">{{ $guru->no_hp ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small text-uppercase">Alamat</label>
                            <p class="mb-0">{{ $guru->alamat ?? '-' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small text-uppercase">Keterangan</label>
                            <p class="mb-0">{{ $guru->keterangan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style scoped>
.guru-detail { width: 100%; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); color: #997404; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
