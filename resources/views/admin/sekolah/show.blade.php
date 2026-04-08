@extends('layouts.app')

@section('content')
<div class="sekolah-detail">
    <div class="mb-4 d-flex justify-content-between align-items-center">
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
        <div class="mb-4 col-md-4">
            <div class="border-0 shadow-sm card">
                <div class="text-center card-body">
                    <div class="mx-auto mb-3 rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="bi bi-building fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $sekolah->nama_sekolah }}</h4>
                    <span class="px-3 py-1 badge bg-info-soft rounded-pill">
                        <i class="bi bi-qr-code me-1"></i> {{ $sekolah->npsn }}
                    </span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted d-block">Status</small>
                            @if($sekolah->status == 'aktif')
                                <span class="px-3 py-1 badge bg-success-soft rounded-pill">Aktif</span>
                            @else
                                <span class="px-3 py-1 badge bg-danger-soft rounded-pill">Nonaktif</span>
                            @endif
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">Kepala Sekolah</small>
                            <p class="mb-0 fw-semibold">{{ $sekolah->nama_kepala_sekolah ?? '-' }}</p>
                            <small class="text-muted">{{ $sekolah->no_telp_kepala_sekolah ?? '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4 col-md-8">
            <div class="border-0 shadow-sm card">
                <div class="pt-3 bg-transparent card-header border-bottom-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2 text-warning"></i>
                        Informasi Lengkap
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">NPSN</label>
                            <p class="mb-0 fw-semibold">{{ $sekolah->npsn }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Nama Sekolah</label>
                            <p class="mb-0 fw-semibold">{{ $sekolah->nama_sekolah }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
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

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Kecamatan</label>
                            <p class="mb-0">{{ $sekolah->kecamatan }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Kelurahan</label>
                            <p class="mb-0">{{ $sekolah->kelurahan }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Status</label>
                            <p class="mb-0">{{ ucfirst($sekolah->status) }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Nama Kepala Sekolah</label>
                            <p class="mb-0">{{ $sekolah->nama_kepala_sekolah ?? '-' }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">No. Telepon Kepala Sekolah</label>
                            <p class="mb-0">{{ $sekolah->no_telp_kepala_sekolah ?? '-' }}</p>
                        </div>

                        <div class="mb-3 col-12">
                            <label class="text-muted small text-uppercase">Alamat</label>
                            <p class="mb-0">{{ $sekolah->alamat }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Dibuat Pada</label>
                            <p class="mb-0">{{ $sekolah->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="text-muted small text-uppercase">Terakhir Update</label>
                            <p class="mb-0">{{ $sekolah->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik --}}
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="pt-3 bg-transparent card-header border-bottom-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-people-fill me-2 text-warning"></i>
                        Data Siswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center row">
                        <div class="col-6">
                            <h3 class="mb-0 text-primary">{{ number_format($sekolah->siswa->count()) }}</h3>
                            <small class="text-muted">Total Siswa</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0 text-success">{{ number_format($sekolah->siswa->where('status', 'aktif')->count()) }}</h3>
                            <small class="text-muted">Siswa Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="pt-3 bg-transparent card-header border-bottom-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-person-badge-fill me-2 text-warning"></i>
                        Data Guru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center row">
                        <div class="col-6">
                            <h3 class="mb-0 text-primary">{{ number_format($sekolah->guru->count()) }}</h3>
                            <small class="text-muted">Total Guru</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0 text-success">{{ number_format($sekolah->guru->where('status', 'aktif')->count()) }}</h3>
                            <small class="text-muted">Guru Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    @if($sekolah->absensi->count() > 0)
    <div class="border-0 shadow-sm card">
        <div class="pt-3 bg-transparent card-header border-bottom-0">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-calendar-check me-2 text-warning"></i>
                Riwayat Absensi Terbaru
            </h5>
        </div>
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3">Tanggal</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">Sakit</th>
                            <th class="py-3 text-center">Izin</th>
                            <th class="py-3 text-center">Alpha</th>
                            <th class="py-3 text-center pe-3">Status</th>
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
                            <td class="text-center pe-3">{!! $absensi->status_badge !!}</td>
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
.sekolah-detail { width: 100%; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
