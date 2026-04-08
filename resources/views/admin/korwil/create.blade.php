@extends('layouts.app')

@section('content')
<div class="korwil-form">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-semibold">
            <i class="bi bi-plus-circle-fill me-2 text-warning"></i>
            Tambah Korwil
        </h3>
        <a href="{{ route('admin.korwil.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.korwil.store') }}" method="POST">
                @csrf

                {{-- Pilihan User --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Pilihan User</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="user_option" id="option_existing" value="existing" checked>
                        <label class="form-check-label fw-semibold" for="option_existing">
                            Pilih User Existing (Korwil)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="user_option" id="option_new" value="new">
                        <label class="form-check-label fw-semibold" for="option_new">
                            Buat User Baru (Role Korwil)
                        </label>
                    </div>
                </div>

                {{-- Section User Existing --}}
                <div id="section_existing" class="mb-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih User Korwil <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select">
                                    <option value="">-- Pilih User --</option>
                                    @foreach($existingUsers as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($existingUsers->isEmpty())
                                    <small class="text-warning d-block mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Belum ada user dengan role Korwil. Silakan buat user baru.
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section User Baru --}}
                <div id="section_new" style="display: none;" class="mb-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Data User Baru</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="new_user_name" class="form-control" placeholder="Contoh: Drs. Ahmad Yani, M.Pd">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="new_user_email" class="form-control" placeholder="email@example.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="new_user_password" class="form-control" placeholder="Minimal 6 karakter">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="new_user_password_confirmation" class="form-control" placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Korwil --}}
                <div class="mb-4">
                    <h6 class="fw-semibold mb-3">Data Korwil</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode Wilayah <span class="text-danger">*</span></label>
                            <input type="text" name="kode_wilayah" class="form-control @error('kode_wilayah') is-invalid @enderror" value="{{ old('kode_wilayah') }}" placeholder="Contoh: KW-001" required>
                            @error('kode_wilayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Korwil <span class="text-danger">*</span></label>
                            <input type="text" name="nama_korwil" class="form-control @error('nama_korwil') is-invalid @enderror" value="{{ old('nama_korwil') }}" required>
                            @error('nama_korwil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nomor SK</label>
                            <input type="text" name="no_sk" class="form-control @error('no_sk') is-invalid @enderror" value="{{ old('no_sk') }}" placeholder="Nomor Surat Keputusan">
                            @error('no_sk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Wilayah Kerja <span class="text-danger">*</span></label>
                            <textarea name="wilayah_kerja" class="form-control @error('wilayah_kerja') is-invalid @enderror" rows="4" placeholder="Deskripsi wilayah kerja..." required>{{ old('wilayah_kerja') }}</textarea>
                            @error('wilayah_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.korwil.index') }}" class="btn btn-secondary">Batal</a>
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
    const optionExisting = document.getElementById('option_existing');
    const optionNew = document.getElementById('option_new');
    const sectionExisting = document.getElementById('section_existing');
    const sectionNew = document.getElementById('section_new');

    // Input fields untuk validasi
    const userIdSelect = document.querySelector('select[name="user_id"]');
    const newUserName = document.querySelector('input[name="new_user_name"]');
    const newUserEmail = document.querySelector('input[name="new_user_email"]');
    const newUserPassword = document.querySelector('input[name="new_user_password"]');
    const newUserPasswordConfirmation = document.querySelector('input[name="new_user_password_confirmation"]');

    function toggleSections() {
        if (optionExisting.checked) {
            sectionExisting.style.display = 'block';
            sectionNew.style.display = 'none';

            // Enable/disable required attributes
            userIdSelect.required = true;
            newUserName.required = false;
            newUserEmail.required = false;
            newUserPassword.required = false;
            newUserPasswordConfirmation.required = false;
        } else {
            sectionExisting.style.display = 'none';
            sectionNew.style.display = 'block';

            userIdSelect.required = false;
            newUserName.required = true;
            newUserEmail.required = true;
            newUserPassword.required = true;
            newUserPasswordConfirmation.required = true;
        }
    }

    optionExisting.addEventListener('change', toggleSections);
    optionNew.addEventListener('change', toggleSections);

    // Initial call
    toggleSections();
});
</script>

<style scoped>
.korwil-form {
    width: 100%;
}

.card.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection
