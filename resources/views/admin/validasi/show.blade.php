@extends('layouts.app')

@section('content')
<div class="validasi-detail">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-file-text-fill me-2 text-warning"></i>
            Detail Absensi {{ ucfirst($absensi->jenis_absensi) }}
        </h3>
        <div>
            @if($absensi->status_validasi == 'pending')
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="bi bi-check2-circle me-1"></i> Setujui
                </button>
                <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-1"></i> Tolak
                </button>
            @endif
            <a href="{{ route('admin.validasi.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-building me-2 text-warning"></i>
                        Informasi Sekolah
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Nama Sekolah</label>
                        <p class="fw-semibold mb-0">{{ $absensi->sekolah->nama_sekolah ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">NPSN</label>
                        <p class="mb-0">{{ $absensi->sekolah->npsn ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Korwil</label>
                        <p class="mb-0">{{ $absensi->sekolah->korwil->nama_korwil ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Alamat</label>
                        <p class="mb-0">{{ $absensi->sekolah->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-calendar3 me-2 text-warning"></i>
                        Informasi Absensi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Tanggal Absensi</label>
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
                        <div>{!! $absensi->status_badge !!}</div>
                    </div>
                    @if($absensi->status_validasi != 'pending')
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Divalidasi Oleh</label>
                        <p class="mb-0">{{ $absensi->validator->name ?? '-' }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="text-muted small text-uppercase">Diinput Oleh</label>
                        <p class="mb-0">{{ $absensi->inputer->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Absensi --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-table me-2 text-warning"></i>
                Rekap Kehadiran
            </h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="p-4 bg-success bg-opacity-10 rounded">
                        <h2 class="text-success mb-0">{{ number_format($absensi->jumlah_hadir) }}</h2>
                        <small class="text-muted">Hadir</small>
                    </div>
                </div>
                @if($absensi->jenis_absensi == 'siswa')
                <div class="col-md-3 mb-3">
                    <div class="p-4 bg-info bg-opacity-10 rounded">
                        <h2 class="text-info mb-0">{{ number_format($absensi->jumlah_sakit) }}</h2>
                        <small class="text-muted">Sakit</small>
                    </div>
                </div>
                @endif
                <div class="col-md-3 mb-3">
                    <div class="p-4 bg-warning bg-opacity-10 rounded">
                        <h2 class="text-warning mb-0">{{ number_format($absensi->jumlah_izin) }}</h2>
                        <small class="text-muted">Izin</small>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-4 bg-danger bg-opacity-10 rounded">
                        <h2 class="text-danger mb-0">{{ number_format($absensi->jumlah_alpha) }}</h2>
                        <small class="text-muted">Alpha</small>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <strong>Total {{ $absensi->jenis_absensi == 'siswa' ? 'Siswa' : 'Guru' }}: {{ number_format($absensi->total_siswa) }}</strong>
            </div>
        </div>
    </div>

    {{-- Detail Per Siswa/Guru --}}
    @if($absensi->detail_absensi && count($absensi->detail_absensi) > 0)
    <div class="card border-0 shadow-sm mb-4">
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
                            <th class="py-3 pe-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi->detail_absensi as $index => $detail)
                        <tr>
                            <td class="ps-3">{{ $loop->iteration }}</td>
                            @if($absensi->jenis_absensi == 'siswa')
                                <td class="small">{{ $detail['siswa_id'] ?? $detail['nisn'] ?? '-' }}</td>
                                <td><strong>{{ $detail['nama_siswa'] ?? '-' }}</strong></td>
                                <td>{{ $detail['kelas'] ?? '-' }}</td>
                            @else
                                <td class="small">{{ $detail['guru_id'] ?? $detail['nip'] ?? '-' }}</td>
                                <td><strong>{{ $detail['nama_guru'] ?? '-' }}</strong></td>
                                <td>{{ $detail['bidang_studi'] ?? '-' }}</td>
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
                            <td class="pe-3">{{ $detail['keterangan'] ?? '-' }}</td>
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
    @endif

    @if($absensi->keterangan)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom-0 pt-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-chat-text me-2 text-warning"></i>
                Keterangan Tambahan
            </h5>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $absensi->keterangan }}</p>
        </div>
    </div>
    @endif

    @if($absensi->foto)
    <div class="card border-0 shadow-sm">
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

{{-- Modal Approve --}}
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Setujui</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <p>Yakin ingin menyetujui absensi <strong>{{ $absensi->jenis_absensi == 'siswa' ? 'Siswa' : 'Guru' }}</strong> dari <strong>{{ $absensi->sekolah->nama_sekolah ?? '-' }}</strong> tanggal <strong>{{ $absensi->tanggal->format('d/m/Y') }}</strong>?</p>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Rekap Kehadiran:</strong><br>
                    Hadir: {{ number_format($absensi->jumlah_hadir) }} |
                    @if($absensi->jenis_absensi == 'siswa') Sakit: {{ number_format($absensi->jumlah_sakit) }} | @endif
                    Izin: {{ number_format($absensi->jumlah_izin) }} |
                    Alpha: {{ number_format($absensi->jumlah_alpha) }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.validasi.approve', $absensi->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Ya, Setujui</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Reject --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.validasi.reject', $absensi->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <p>Tolak absensi <strong>{{ $absensi->jenis_absensi == 'siswa' ? 'Siswa' : 'Guru' }}</strong> dari <strong>{{ $absensi->sekolah->nama_sekolah ?? '-' }}</strong> tanggal <strong>{{ $absensi->tanggal->format('d/m/Y') }}</strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style scoped>
.validasi-detail {
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

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}
</style>
@endsection
