@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="user-detail">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div>
                        <h3 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-person-badge me-2 text-warning"></i>

                        </h3>
                        <p class="text-muted small mb-0 mt-1">
                            <i class="bi bi-info-circle me-1"></i>
                            Informasi lengkap data user
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning me-2 shadow-sm">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Card --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-4">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-person-vcard me-2 text-warning"></i>
                            Profil User
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            {{-- Foto Profile - SQUARE --}}
                            <div class="col-md-3 text-center mb-4">
                                @if($user->foto)
                                    <div class="photo-container mb-3">
                                        <img src="{{ Storage::url($user->foto) }}"
                                             class="square-photo img-thumbnail"
                                             style="width: 180px; height: 180px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="photo-placeholder d-flex flex-column align-items-center justify-content-center mx-auto mb-3"
                                         style="width: 180px; height: 180px; background-color: #f8f9fa; border-radius: 16px; border: 2px dashed #dee2e6;">
                                        <i class="bi bi-person fs-1 text-secondary"></i>
                                        <span class="small text-muted mt-2">No Photo</span>
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-warning-subtle px-3 py-1 rounded-pill">
                                        <i class="bi bi-camera me-1 small"></i>
                                        Foto Profil
                                    </span>
                                </div>
                            </div>

                            {{-- Informasi User --}}
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            <i class="bi bi-person me-1"></i> Nama Lengkap
                                        </label>
                                        <p class="fw-semibold mb-0 fs-5">{{ $user->name }}</p>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            <i class="bi bi-envelope me-1"></i> Email
                                        </label>
                                        <p class="fw-semibold mb-0">
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                {{ $user->email }}
                                            </a>
                                        </p>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            <i class="bi bi-tag me-1"></i> Role
                                        </label>
                                        <div>
                                            @php
                                                $roleConfig = [
                                                    'admin_dinas' => ['class' => 'primary', 'icon' => 'shield', 'text' => 'Admin Dinas'],
                                                    'operator_sekolah' => ['class' => 'info', 'icon' => 'building', 'text' => 'Operator Sekolah'],
                                                    'korwil' => ['class' => 'warning', 'icon' => 'diagram-3', 'text' => 'Korwil']
                                                ];
                                                $config = $roleConfig[$user->role] ?? ['class' => 'secondary', 'icon' => 'person', 'text' => ucfirst(str_replace('_', ' ', $user->role))];
                                            @endphp
                                            <span class="badge bg-{{ $config['class'] }}-soft px-3 py-2 rounded-pill">
                                                <i class="bi bi-{{ $config['icon'] }} me-1"></i>
                                                {{ $config['text'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="text-muted small text-uppercase fw-semibold mb-1">
                                            <i class="bi bi-check-circle me-1"></i> Status
                                        </label>
                                        <div>
                                            @if($user->is_active)
                                                <span class="badge bg-success-soft px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle-fill me-1 small"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-danger-soft px-3 py-2 rounded-pill">
                                                    <i class="bi bi-x-circle-fill me-1 small"></i> Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Assignment Sekolah --}}
                                    @if($user->role == 'operator_sekolah' && $user->assignments->first())
                                        @php $sekolah = \App\Models\Sekolah::find($user->assignments->first()->target_id); @endphp
                                        <div class="col-md-12 mb-4">
                                            <label class="text-muted small text-uppercase fw-semibold mb-2">
                                                <i class="bi bi-building me-1"></i> Assignment Sekolah
                                            </label>
                                            <div class="bg-light rounded-3 p-3">
                                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                                    <div class="bg-white p-3 rounded-3 text-center shadow-sm">
                                                        <i class="bi bi-building fs-1 text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $sekolah->nama_sekolah ?? '-' }}</h6>
                                                        <div class="small text-muted">
                                                            <span class="me-3"><i class="bi bi-hash me-1"></i> NPSN: {{ $sekolah->npsn ?? '-' }}</span>
                                                            <span><i class="bi bi-geo-alt me-1"></i> {{ $sekolah->alamat ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Data Korwil --}}
                                    @if($user->role == 'korwil' && $user->korwil)
                                        <div class="col-md-12 mb-4">
                                            <label class="text-muted small text-uppercase fw-semibold mb-2">
                                                <i class="bi bi-diagram-3 me-1"></i> Data Korwil
                                            </label>
                                            <div class="bg-light rounded-3 p-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">Nama Korwil</small>
                                                            <strong>{{ $user->korwil->nama_korwil }}</strong>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Kode Wilayah</small>
                                                            <strong>{{ $user->korwil->kode_wilayah }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block">No. SK</small>
                                                            <strong>{{ $user->korwil->no_sk ?? '-' }}</strong>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Tanggal SK</small>
                                                            <strong>{{ $user->korwil->tanggal_sk ? \Carbon\Carbon::parse($user->korwil->tanggal_sk)->format('d/m/Y') : '-' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Informasi Sistem --}}
                                    <div class="col-12">
                                        <hr class="my-2">
                                        <div class="row mt-3">
                                            <div class="col-md-4 mb-3">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                    <i class="bi bi-person-plus me-1"></i> Dibuat Oleh
                                                </label>
                                                <p class="mb-0">
                                                    <i class="bi bi-person-circle me-1"></i>
                                                    {{ $user->creator->name ?? 'System' }}
                                                </p>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                    <i class="bi bi-calendar-plus me-1"></i> Dibuat Pada
                                                </label>
                                                <p class="mb-0">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                    <i class="bi bi-calendar-check me-1"></i> Terakhir Update
                                                </label>
                                                <p class="mb-0">
                                                    <i class="bi bi-clock-history me-1"></i>
                                                    {{ $user->updated_at->format('d/m/Y H:i') }}
                                                </p>
                                            </div>

                                            @if($user->last_login)
                                            <div class="col-md-4 mb-3">
                                                <label class="text-muted small text-uppercase fw-semibold mb-1">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i> Terakhir Login
                                                </label>
                                                <p class="mb-0">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($user->last_login)->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-detail {
    width: 100%;
}

/* Square Photo Styling */
.square-photo {
    border-radius: 16px !important;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

.img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 16px;
}

.photo-placeholder {
    transition: all 0.2s;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
}

/* Card Styling */
.card {
    border-radius: 20px;
    overflow: hidden;
}

.card-header {
    padding: 1.25rem 1.5rem 0 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

/* Badge Soft Colors (mempertahankan warna asli) */
.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
    color: #997404;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1);
    color: #997404;
}

/* Label Styling */
label {
    letter-spacing: 0.5px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Info Box */
.bg-light {
    background-color: #f8f9fa !important;
}

/* Tombol tetap warna asli */
.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-warning:hover {
    background-color: #ffca2c;
    border-color: #ffc720;
    color: #000;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }

    .square-photo {
        width: 120px !important;
        height: 120px !important;
    }

    .photo-placeholder {
        width: 120px !important;
        height: 120px !important;
    }

    .fs-5 {
        font-size: 1rem !important;
    }
}
</style>
@endsection
