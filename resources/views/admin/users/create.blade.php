@extends('layouts.app')

@section('content')
<div class="user-form">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-person-plus-fill me-2 text-warning"></i>
            Tambah User
        </h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Foto Profil</label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3" id="assignment_sekolah_field" style="display: none;">
                        <label class="form-label fw-semibold">Assign ke Sekolah <span class="text-danger">*</span></label>
                        <select name="assignment_sekolah_id" class="form-select">
                            <option value="">Pilih Sekolah</option>
                            @foreach($sekolahList as $sekolah)
                                <option value="{{ $sekolah->id }}" {{ old('assignment_sekolah_id') == $sekolah->id ? 'selected' : '' }}>
                                    {{ $sekolah->nama_sekolah }} ({{ $sekolah->npsn }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Assignment untuk operator sekolah</small>
                    </div>

                    <div class="col-md-6 mb-3" id="assignment_korwil_field" style="display: none;">
                        <label class="form-label fw-semibold">Assign ke Korwil <span class="text-danger">*</span></label>
                        <select name="assignment_korwil_id" class="form-select">
                            <option value="">Pilih Korwil</option>
                            @foreach($korwilList as $korwil)
                                <option value="{{ $korwil->id }}" {{ old('assignment_korwil_id') == $korwil->id ? 'selected' : '' }}>
                                    {{ $korwil->nama_korwil }} ({{ $korwil->kode_wilayah }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Assignment untuk user korwil</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Status Aktif</label>
                        </div>
                        <small class="text-muted">Nonaktifkan jika user tidak boleh login</small>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const assignmentSekolah = document.getElementById('assignment_sekolah_field');
    const assignmentKorwil = document.getElementById('assignment_korwil_field');

    function toggleAssignmentFields() {
        const role = roleSelect.value;
        assignmentSekolah.style.display = role === 'operator_sekolah' ? 'block' : 'none';
        assignmentKorwil.style.display = role === 'korwil' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleAssignmentFields);
    toggleAssignmentFields();
});
</script>

<style scoped>
.user-form {
    width: 100%;
}
</style>
@endsection
