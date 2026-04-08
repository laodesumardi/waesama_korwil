@extends('layouts.app')

@section('content')
<div class="sekolah-form">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-pencil-square me-2 text-warning"></i>
            Edit Sekolah
        </h3>
        <a href="{{ route('admin.sekolah.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="card-body">
            <form action="{{ route('admin.sekolah.update', $sekolah->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">NPSN <span class="text-danger">*</span></label>
                        <input type="text" name="npsn" class="form-control @error('npsn') is-invalid @enderror" value="{{ old('npsn', $sekolah->npsn) }}" required>
                        @error('npsn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror" value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}" required>
                        @error('nama_sekolah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Korwil <span class="text-danger">*</span></label>
                        <select name="id_korwil" class="form-select @error('id_korwil') is-invalid @enderror" required>
                            <option value="">Pilih Korwil</option>
                            @foreach($korwilList as $korwil)
                                <option value="{{ $korwil->id }}" {{ old('id_korwil', $sekolah->id_korwil) == $korwil->id ? 'selected' : '' }}>
                                    {{ $korwil->nama_korwil }} ({{ $korwil->kode_wilayah }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_korwil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status', $sekolah->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $sekolah->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Kelurahan <span class="text-danger">*</span></label>
                        <input type="text" name="kelurahan" class="form-control @error('kelurahan') is-invalid @enderror" value="{{ old('kelurahan', $sekolah->kelurahan) }}" required>
                        @error('kelurahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                        <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" value="{{ old('kecamatan', $sekolah->kecamatan) }}" required>
                        @error('kecamatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_kepala_sekolah" class="form-control @error('nama_kepala_sekolah') is-invalid @enderror" value="{{ old('nama_kepala_sekolah', $sekolah->nama_kepala_sekolah) }}" placeholder="Contoh: Drs. Ahmad Yani, M.Pd">
                        @error('nama_kepala_sekolah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">No. Telepon Kepala Sekolah</label>
                        <input type="text" name="no_telp_kepala_sekolah" class="form-control @error('no_telp_kepala_sekolah') is-invalid @enderror" value="{{ old('no_telp_kepala_sekolah', $sekolah->no_telp_kepala_sekolah) }}" placeholder="Contoh: 081234567890">
                        @error('no_telp_kepala_sekolah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $sekolah->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="gap-2 d-flex justify-content-end">
                    <a href="{{ route('admin.sekolah.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style scoped>
.sekolah-form {
    width: 100%;
}
</style>
@endsection
