@extends('layouts.app')

@section('content')
<div class="periode-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-calendar3-fill me-2 text-warning"></i>
            Detail Periode Ajaran
        </h3>
        <div>
            <a href="{{ route('admin.periode.edit', $periode->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                        <i class="bi bi-calendar-week fs-1 text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $periode->tahun_ajaran }}</h4>
                    <span class="badge bg-info-soft px-3 py-1 rounded-pill">
                        <i class="bi bi-book-half me-1"></i> Semester {{ $periode->semester }} ({{ $periode->semester_label }})
                    </span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted d-block">Status Periode</small>
                            @php
                                $statusClass = match($periode->status_periode) {
                                    'Berjalan' => 'success',
                                    'Aktif (Belum Mulai)' => 'warning',
                                    'Selesai' => 'secondary',
                                    default => 'danger'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}-soft px-3 py-1 rounded-pill">
                                <i class="bi {{ $periode->status_periode == 'Berjalan' ? 'bi-play-circle-fill' : ($periode->status_periode == 'Selesai' ? 'bi-check-circle-fill' : 'bi-pause-circle-fill') }} me-1"></i>
                                {{ $periode->status_periode }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">Status Aktif</small>
                            @if($periode->is_active)
                                <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>

                        <div class="mb-2">
                            <small class="text-muted d-block">Total Absensi</small>
                            <span class="badge bg-primary-soft px-3 py-1 rounded-pill">
                                <i class="bi bi-calendar-check me-1"></i> {{ $totalAbsensi }} Kali
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
                            <label class="text-muted small text-uppercase">Tahun Ajaran</label>
                            <p class="fw-semibold mb-0">{{ $periode->tahun_ajaran }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Semester</label>
                            <p class="mb-0">Semester {{ $periode->semester }} ({{ $periode->semester_label }})</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Tanggal Mulai</label>
                            <p class="mb-0">{{ $periode->tanggal_mulai->format('d/m/Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Tanggal Selesai</label>
                            <p class="mb-0">{{ $periode->tanggal_selesai->format('d/m/Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Dibuat Pada</label>
                            <p class="mb-0">{{ $periode->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Terakhir Update</label>
                            <p class="mb-0">{{ $periode->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Absensi --}}
    @if($totalAbsensi > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-graph-up me-2 text-warning"></i>
                        Statistik Absensi Periode Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-success mb-0">{{ number_format($totalHadir) }}</h3>
                                <small class="text-muted">Total Hadir</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-info mb-0">{{ number_format($totalSakit) }}</h3>
                                <small class="text-muted">Total Sakit</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-warning mb-0">{{ number_format($totalIzin) }}</h3>
                                <small class="text-muted">Total Izin</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3 class="text-danger mb-0">{{ number_format($totalAlpha) }}</h3>
                                <small class="text-muted">Total Alpha</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Absensi --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-warning"></i>
                Daftar Absensi Periode Ini
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3">Tanggal</th>
                            <th class="py-3">Sekolah</th>
                            <th class="py-3 text-center">Hadir</th>
                            <th class="py-3 text-center">Sakit</th>
                            <th class="py-3 text-center">Izin</th>
                            <th class="py-3 text-center pe-3">Alpha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periode->absensi->take(10) as $absensi)
                        <tr>
                            <td class="ps-3">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $absensi->sekolah->nama_sekolah ?? '-' }}</td>
                            <td class="text-center">{{ number_format($absensi->jumlah_hadir) }}</td>
                            <td class="text-center">{{ number_format($absensi->jumlah_sakit) }}</td>
                            <td class="text-center">{{ number_format($absensi->jumlah_izin) }}</td>
                            <td class="text-center pe-3">{{ number_format($absensi->jumlah_alpha) }}</td>
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
.periode-detail {
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
</style>
@endsection
