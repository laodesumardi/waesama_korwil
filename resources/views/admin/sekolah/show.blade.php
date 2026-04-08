@extends('layouts.app')

@section('content')
<div class="sekolah-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-building-fill me-2 text-warning"></i>
            Detail Sekolah
        </h3>
        <div>
            <a href="{{ route('admin.sekolah.edit', $sekolah->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.sekolah.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-building fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $sekolah->nama_sekolah }}</h4>
                    <span class="badge bg-info-soft px-3 py-1 rounded-pill">
                        <i class="bi bi-qr-code me-1"></i> {{ $sekolah->npsn }}
                    </span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted d-block">Status</small>
                            @if($sekolah->status == 'aktif')
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
                            <small class="text-muted d-block">Total Absensi</small>
                            <span class="badge bg-primary-soft px-3 py-1 rounded-pill">
                                <i class="bi bi-calendar-check me-1"></i> {{ $totalAbsensi }} Kali
                            </span>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">Rata-rata Hadir</small>
                            <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                <i class="bi bi-graph-up me-1"></i> {{ number_format($rataHadir, 1) }} Siswa
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
                            <label class="text-muted small text-uppercase">NPSN</label>
                            <p class="fw-semibold mb-0">{{ $sekolah->npsn }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nama Sekolah</label>
                            <p class="fw-semibold mb-0">{{ $sekolah->nama_sekolah }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Korwil</label>
                            <p class="mb-0">
                                @if($sekolah->korwil)
                                    <strong>{{ $sekolah->korwil->nama_korwil }}</strong><br>
                                    <small class="text-muted">Kode: {{ $sekolah->korwil->kode_wilayah }}</small>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Kecamatan</label>
                            <p class="mb-0">{{ $sekolah->kecamatan }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Kelurahan</label>
                            <p class="mb-0">{{ $sekolah->kelurahan }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="text-muted small text-uppercase">Alamat</label>
                            <p class="mb-0">{{ $sekolah->alamat }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Dibuat Pada</label>
                            <p class="mb-0">{{ $sekolah->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Terakhir Update</label>
                            <p class="mb-0">{{ $sekolah->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    @if($sekolah->absensi->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-check me-2 text-warning"></i>
                Riwayat Absensi Terbaru
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3">Tanggal</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">Sakit</th>
                            <th class="py-3 text-center">Izin</th>
                            <th class="py-3 text-center">Alpha</th>
                            <th class="py-3 pe-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sekolah->absensi->take(10) as $absensi)
                        <tr>
                            <td class="ps-3">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</td>
                            <td class="text-center"><span class="fw-semibold text-success">{{ number_format($absensi->jumlah_hadir) }}</span></td>
                            <td class="text-center">{{ number_format($absensi->jumlah_sakit) }}</td>
                            <td class="text-center">{{ number_format($absensi->jumlah_izin) }}</td>
                            <td class="text-center">{{ number_format($absensi->jumlah_alpha) }}</td>
                            <td class="text-center pe-3">
                                @php
                                    $statusClass = match($absensi->status_validasi ?? 'pending') {
                                        'disetujui' => 'success',
                                        'ditolak' => 'danger',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}-soft px-3 py-1 rounded-pill small">
                                    {{ ucfirst($absensi->status_validasi ?? 'Pending') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<style scoped>
.sekolah-detail {
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
</style>
@endsection
