@extends('layouts.app')

@section('content')
<div class="absensi-form">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-plus-circle-fill me-2 text-warning"></i>
            Input Absensi
        </h3>
        <a href="{{ route('operator.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if(isset($sudahInput) && $sudahInput)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Anda sudah menginput absensi hari ini. Input hanya dapat dilakukan satu kali per hari.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('operator.absensi.history') }}" class="btn btn-primary">
                                Lihat Riwayat Absensi
                            </a>
                        </div>
                    @else
                        <form action="{{ route('operator.absensi.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal</label>
                                    <input type="text" class="form-control" value="{{ now()->format('d/m/Y') }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Sekolah</label>
                                    <input type="text" class="form-control" value="{{ $sekolah->nama_sekolah ?? '-' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Periode</label>
                                    <input type="text" class="form-control" value="{{ $periodeAktif->tahun_ajaran ?? '-' }} - Semester {{ $periodeAktif->semester ?? '-' }}" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Total Siswa</label>
                                    <div class="input-group">
                                        <input type="text" id="total_siswa_display" class="form-control" readonly style="background-color: #e9ecef;" value="0">
                                        <span class="input-group-text bg-warning text-dark">Otomatis</span>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="fw-semibold mb-3">Data Kehadiran</h6>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-semibold text-success">Hadir <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_hadir" id="jumlah_hadir" class="form-control" value="0" required min="0">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-semibold text-info">Sakit</label>
                                    <input type="number" name="jumlah_sakit" id="jumlah_sakit" class="form-control" value="0" min="0">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-semibold text-warning">Izin</label>
                                    <input type="number" name="jumlah_izin" id="jumlah_izin" class="form-control" value="0" min="0">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-semibold text-danger">Alpha</label>
                                    <input type="number" name="jumlah_alpha" id="jumlah_alpha" class="form-control" value="0" min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan jika ada..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Foto Dokumentasi</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('operator.dashboard') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-save me-1"></i> Kirim
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hadir = document.getElementById('jumlah_hadir');
    const sakit = document.getElementById('jumlah_sakit');
    const izin = document.getElementById('jumlah_izin');
    const alpha = document.getElementById('jumlah_alpha');
    const totalDisplay = document.getElementById('total_siswa_display');

    function updateTotal() {
        const total = (parseInt(hadir.value) || 0) +
                     (parseInt(sakit.value) || 0) +
                     (parseInt(izin.value) || 0) +
                     (parseInt(alpha.value) || 0);
        totalDisplay.value = total;
    }

    if (hadir) hadir.addEventListener('input', updateTotal);
    if (sakit) sakit.addEventListener('input', updateTotal);
    if (izin) izin.addEventListener('input', updateTotal);
    if (alpha) alpha.addEventListener('input', updateTotal);

    updateTotal();
});
</script>

<style scoped>
.absensi-form {
    width: 100%;
}
</style>
@endsection
