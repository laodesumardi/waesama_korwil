@extends('layouts.app')

@section('content')
<div class="absensi-siswa">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-people-fill me-2 text-warning"></i>
            Absensi Siswa
        </h3>
        <a href="{{ route('operator.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($sudahInput)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Anda sudah menginput absensi siswa hari ini. Input hanya dapat dilakukan satu kali per hari.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('operator.absensi.history') }}" class="btn btn-primary">
                                Lihat Riwayat Absensi
                            </a>
                        </div>
                    @else
                        <form action="{{ route('operator.absensi.siswa.store') }}" method="POST" enctype="multipart/form-data" id="absensiForm">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="info-box bg-light p-3 rounded">
                                        <small class="text-muted">Tanggal</small>
                                        <h5 class="mb-0">{{ now()->format('d/m/Y') }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-light p-3 rounded">
                                        <small class="text-muted">Sekolah</small>
                                        <h5 class="mb-0">{{ $sekolah->nama_sekolah }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-light p-3 rounded">
                                        <small class="text-muted">Periode</small>
                                        <h5 class="mb-0">{{ $periodeAktif->tahun_ajaran }} - Sem {{ $periodeAktif->semester }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px">#</th>
                                            <th>NISN</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th style="width: 300px">Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($siswaList as $index => $siswa)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $siswa->nisn }}</td>
                                            <td>{{ $siswa->nama_siswa }}</td>
                                            <td>{{ $siswa->kelas }}</td>
                                            <td>
                                                <div class="btn-group w-100" role="group">
                                                    <input type="radio"
                                                           class="btn-check"
                                                           name="kehadiran[{{ $siswa->id }}]"
                                                           id="hadir_{{ $siswa->id }}"
                                                           value="hadir"
                                                           autocomplete="off"
                                                           checked>
                                                    <label class="btn btn-outline-success" for="hadir_{{ $siswa->id }}">
                                                        <i class="bi bi-check-circle"></i> Hadir
                                                    </label>

                                                    <input type="radio"
                                                           class="btn-check"
                                                           name="kehadiran[{{ $siswa->id }}]"
                                                           id="sakit_{{ $siswa->id }}"
                                                           value="sakit"
                                                           autocomplete="off">
                                                    <label class="btn btn-outline-info" for="sakit_{{ $siswa->id }}">
                                                        <i class="bi bi-thermometer-half"></i> Sakit
                                                    </label>

                                                    <input type="radio"
                                                           class="btn-check"
                                                           name="kehadiran[{{ $siswa->id }}]"
                                                           id="izin_{{ $siswa->id }}"
                                                           value="izin"
                                                           autocomplete="off">
                                                    <label class="btn btn-outline-warning" for="izin_{{ $siswa->id }}">
                                                        <i class="bi bi-envelope"></i> Izin
                                                    </label>

                                                    <input type="radio"
                                                           class="btn-check"
                                                           name="kehadiran[{{ $siswa->id }}]"
                                                           id="alpha_{{ $siswa->id }}"
                                                           value="alpha"
                                                           autocomplete="off">
                                                    <label class="btn btn-outline-danger" for="alpha_{{ $siswa->id }}">
                                                        <i class="bi bi-x-circle"></i> Alpha
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Foto Dokumentasi</label>
                                    <div id="camera-container" class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div id="camera-preview" class="border rounded bg-light text-center p-3" style="min-height: 300px;">
                                                    <i class="bi bi-camera fs-1 text-muted"></i>
                                                    <p class="text-muted mt-2">Preview foto akan muncul di sini</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-grid gap-2">
                                                    <button type="button" id="start-camera" class="btn btn-primary">
                                                        <i class="bi bi-camera-video"></i> Buka Kamera
                                                    </button>
                                                    <button type="button" id="capture-photo" class="btn btn-warning" disabled>
                                                        <i class="bi bi-camera"></i> Ambil Foto
                                                    </button>
                                                    <input type="file" name="foto" id="foto-file" class="form-control" accept="image/*" style="display: none;">
                                                    <button type="button" id="upload-file" class="btn btn-secondary">
                                                        <i class="bi bi-image"></i> Upload dari File
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">Ambil foto menggunakan kamera atau upload dari file</small>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('operator.dashboard') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-save me-1"></i> Simpan Absensi
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
    let stream = null;
    const video = document.createElement('video');
    const canvas = document.createElement('canvas');
    const cameraPreview = document.getElementById('camera-preview');
    const startCameraBtn = document.getElementById('start-camera');
    const capturePhotoBtn = document.getElementById('capture-photo');
    const uploadFileBtn = document.getElementById('upload-file');
    const fotoFile = document.getElementById('foto-file');

    video.style.width = '100%';
    video.style.maxHeight = '280px';
    video.style.objectFit = 'cover';

    startCameraBtn.addEventListener('click', async function() {
        try {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.play();

            cameraPreview.innerHTML = '';
            cameraPreview.appendChild(video);

            capturePhotoBtn.disabled = false;
            startCameraBtn.textContent = 'Ganti Kamera';
        } catch (err) {
            alert('Tidak dapat mengakses kamera: ' + err.message);
        }
    });

    capturePhotoBtn.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(function(blob) {
            const file = new File([blob], "foto_absensi.jpg", { type: "image/jpeg" });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fotoFile.files = dataTransfer.files;

            // Tampilkan preview
            const img = document.createElement('img');
            img.src = URL.createObjectURL(blob);
            img.style.width = '100%';
            img.style.maxHeight = '280px';
            img.style.objectFit = 'cover';
            cameraPreview.innerHTML = '';
            cameraPreview.appendChild(img);

            // Stop camera
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            capturePhotoBtn.disabled = true;
            startCameraBtn.textContent = 'Buka Kamera';
        }, 'image/jpeg', 0.9);
    });

    uploadFileBtn.addEventListener('click', function() {
        fotoFile.click();
    });

    fotoFile.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.style.width = '100%';
                img.style.maxHeight = '280px';
                img.style.objectFit = 'cover';
                cameraPreview.innerHTML = '';
                cameraPreview.appendChild(img);

                // Stop camera jika aktif
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                    capturePhotoBtn.disabled = true;
                    startCameraBtn.textContent = 'Buka Kamera';
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
});
</script>

<style scoped>
.absensi-siswa { width: 100%; }
.info-box { background-color: #f8f9fa; }
.btn-group .btn { padding: 8px 12px; font-size: 13px; }
.btn-check:checked + .btn-outline-success { background-color: #198754; color: white; }
.btn-check:checked + .btn-outline-info { background-color: #0dcaf0; color: white; }
.btn-check:checked + .btn-outline-warning { background-color: #ffc107; color: white; }
.btn-check:checked + .btn-outline-danger { background-color: #dc3545; color: white; }
</style>
@endsection
