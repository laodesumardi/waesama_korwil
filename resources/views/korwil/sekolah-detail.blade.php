@extends('layouts.app')

@section('content')
<div class="sekolah-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-building-fill me-2 text-warning"></i>
            Detail Sekolah
        </h3>
        <a href="{{ route('korwil.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-3">
                        <i class="bi bi-building fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $sekolah->nama_sekolah }}</h4>
                    <p class="text-muted small">NPSN: {{ $sekolah->npsn }}</p>
                    <hr>
                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted">Status</small><br>
                            @if($sekolah->status == 'aktif')
                                <span class="badge bg-success-soft">Aktif</span>
                            @else
                                <span class="badge bg-danger-soft">Nonaktif</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Kecamatan</small>
                            <p class="mb-0">{{ $sekolah->kecamatan }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Kelurahan</small>
                            <p class="mb-0">{{ $sekolah->kelurahan }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-graph-up me-2 text-warning"></i>
                        Statistik Sekolah
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="text-primary mb-0">{{ number_format($totalSiswa) }}</h3>
                                <small class="text-muted">Total Siswa</small>
                                <div class="mt-1">
                                    <small>Aktif: <strong class="text-success">{{ number_format($siswaAktif) }}</strong></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="text-info mb-0">{{ number_format($totalGuru) }}</h3>
                                <small class="text-muted">Total Guru</small>
                                <div class="mt-1">
                                    <small>Aktif: <strong class="text-success">{{ number_format($guruAktif) }}</strong></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6 text-center">
                            <h5 class="text-success">{{ number_format($statAbsensi['hadir']) }}</h5>
                            <small class="text-muted">Total Hadir</small>
                        </div>
                        <div class="col-6 text-center">
                            <h5 class="text-danger">{{ number_format($statAbsensi['alpha']) }}</h5>
                            <small class="text-muted">Total Alpha</small>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">Periode: {{ $periodeAktif->tahun_ajaran ?? '-' }} - Sem {{ $periodeAktif->semester ?? '-' }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style scoped>
.sekolah-detail { width: 100%; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
