@extends('layouts.app')

@section('content')
<div class="absensi-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-file-text-fill me-2 text-warning"></i>
            Detail Absensi {{ ucfirst($absensi->jenis_absensi) }}
        </h3>
        <a href="{{ route('operator.absensi.history') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2 text-warning"></i>
                        Informasi Absensi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Tanggal</label>
                        <p class="fw-semibold mb-0">{{ $absensi->tanggal->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Jenis Absensi</label>
                        <div>
                            @if($absensi->jenis_absensi == 'siswa')
                                <span class="badge bg-primary-soft px-3 py-1">
                                    <i class="bi bi-people-fill me-1"></i> Absensi Siswa
                                </span>
                            @else
                                <span class="badge bg-info-soft px-3 py-1">
                                    <i class="bi bi-person-badge-fill me-1"></i> Absensi Guru
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Periode Ajaran</label>
                        <p class="mb-0">{{ $absensi->periode->tahun_ajaran ?? '-' }} - Semester {{ $absensi->periode->semester ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Status Validasi</label>
                        <div>
                            @if($absensi->status_validasi == 'pending')
                                <span class="badge bg-warning-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-clock-history me-1"></i> Pending
                                </span>
                            @elseif($absensi->status_validasi == 'disetujui')
                                <span class="badge bg-success-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                </span>
                            @else
                                <span class="badge bg-danger-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($absensi->validator)
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Divalidasi Oleh</label>
                        <p class="mb-0">{{ $absensi->validator->name }}</p>
                    </div>
                    @endif
                    @if($absensi->keterangan)
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Keterangan</label>
                        <p class="mb-0">{{ $absensi->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-table me-2 text-warning"></i>
                        Rekap Kehadiran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h3 class="text-success mb-0">{{ number_format($absensi->jumlah_hadir) }}</h3>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        @if($absensi->jenis_absensi == 'siswa')
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-info bg-opacity-10 rounded">
                                <h3 class="text-info mb-0">{{ number_format($absensi->jumlah_sakit) }}</h3>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                        @endif
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h3 class="text-warning mb-0">{{ number_format($absensi->jumlah_izin) }}</h3>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h3 class="text-danger mb-0">{{ number_format($absensi->jumlah_alpha) }}</h3>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <strong>Total {{ $absensi->jenis_absensi == 'siswa' ? 'Siswa' : 'Guru' }}: {{ number_format($absensi->total_siswa) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Per Siswa/Guru --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2 text-warning"></i>
                Detail Kehadiran {{ ucfirst($absensi->jenis_absensi) }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th class="py-3 ps-3">#</th>
                            @if($absensi->jenis_absensi == 'siswa')
                                <th class="py-3">NISN</th>
                                <th class="py-3">Nama Siswa</th>
                                <th class="py-3">Kelas</th>
                            @else
                                <th class="py-3">NIP</th>
                                <th class="py-3">Nama Guru</th>
                                <th class="py-3">Bidang Studi</th>
                            @endif
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 pe-3 text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi->detail_absensi ?? [] as $index => $detail)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            @if($absensi->jenis_absensi == 'siswa')
                                <td>{{ $detail['siswa_id'] ?? $detail['nisn'] ?? '-' }}</td>
                                <td><strong>{{ $detail['nama_siswa'] }}</strong></td>
                                <td>{{ $detail['kelas'] }}</td>
                            @else
                                <td>{{ $detail['guru_id'] ?? $detail['nip'] ?? '-' }}</td>
                                <td><strong>{{ $detail['nama_guru'] }}</strong></td>
                                <td>{{ $detail['bidang_studi'] }}</td>
                            @endif
                            <td class="text-center">
                                @php
                                    $statusClass = match($detail['status']) {
                                        'hadir' => 'success',
                                        'sakit' => 'info',
                                        'izin' => 'warning',
                                        'alpha' => 'danger',
                                        default => 'secondary'
                                    };
                                    $statusIcon = match($detail['status']) {
                                        'hadir' => 'check-circle',
                                        'sakit' => 'thermometer-half',
                                        'izin' => 'envelope',
                                        'alpha' => 'x-circle',
                                        default => 'question-circle'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}-soft px-3 py-1 rounded-pill">
                                    <i class="bi bi-{{ $statusIcon }} me-1"></i> {{ ucfirst($detail['status']) }}
                                </span>
                            </td>
                            <td class="text-center pe-3">{{ $detail['keterangan'] ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data detail
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($absensi->foto)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-image me-2 text-warning"></i>
                Dokumentasi
            </h5>
        </div>
        <div class="card-body text-center">
            <img src="{{ Storage::url($absensi->foto) }}" class="img-fluid rounded" style="max-height: 400px;" alt="Dokumentasi Absensi">
        </div>
    </div>
    @endif
</div>

<style scoped>
.absensi-detail {
    width: 100%;
}
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0; }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); color: #997404; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); color: #198754; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endsection
