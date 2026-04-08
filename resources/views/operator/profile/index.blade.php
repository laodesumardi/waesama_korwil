@extends('layouts.app')

@section('content')
<div class="profile-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-person-circle me-2 text-warning"></i>
            Profil Saya
        </h3>
        <a href="{{ route('operator.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body py-4">
                    @if($user->foto)
                        <img src="{{ Storage::url($user->foto) }}" class="rounded-circle img-thumbnail mb-3" width="120" height="120" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                            <i class="bi bi-person fs-1 text-secondary"></i>
                        </div>
                    @endif
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small">{{ $user->email }}</p>
                    <span class="badge bg-primary-soft px-3 py-1 rounded-pill">
                        <i class="bi bi-person-badge me-1"></i> Operator Sekolah
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 pt-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-pencil-square me-2 text-warning"></i>
                        Edit Profil
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('operator.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Foto Profil</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sekolah Tugas</label>
                            <input type="text" class="form-control" value="{{ $sekolah->nama_sekolah ?? '-' }}" disabled>
                        </div>

                        @if($sekolah)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">NPSN</label>
                            <input type="text" class="form-control" value="{{ $sekolah->npsn ?? '-' }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Sekolah</label>
                            <textarea class="form-control" rows="2" disabled>{{ $sekolah->alamat ?? '-' }}</textarea>
                        </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style scoped>
.profile-page {
    width: 100%;
}
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
</style>
@endsection
