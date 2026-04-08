@extends('layouts.app')

@section('content')
<div class="korwil-form">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-pencil-square me-2 text-warning"></i>
            Edit Korwil
        </h3>
        <a href="{{ route('admin.korwil.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.korwil.update', $korwil->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">User Login <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">Pilih User</option>
                            @foreach($existingUsers as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $korwil->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">User dengan role Korwil</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kode Wilayah <span class="text-danger">*</span></label>
                        <input type="text" name="kode_wilayah" class="form-control @error('kode_wilayah') is-invalid @enderror" value="{{ old('kode_wilayah', $korwil->kode_wilayah) }}" required>
                        @error('kode_wilayah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Korwil <span class="text-danger">*</span></label>
                        <input type="text" name="nama_korwil" class="form-control @error('nama_korwil') is-invalid @enderror" value="{{ old('nama_korwil', $korwil->nama_korwil) }}" required>
                        @error('nama_korwil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nomor SK</label>
                        <input type="text" name="no_sk" class="form-control @error('no_sk') is-invalid @enderror" value="{{ old('no_sk', $korwil->no_sk) }}">
                        @error('no_sk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Wilayah Kerja <span class="text-danger">*</span></label>
                        <textarea name="wilayah_kerja" class="form-control @error('wilayah_kerja') is-invalid @enderror" rows="4" required>{{ old('wilayah_kerja', $korwil->wilayah_kerja) }}</textarea>
                        @error('wilayah_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.korwil.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style scoped>
.korwil-form {
    width: 100%;
}
</style>
@endsection
