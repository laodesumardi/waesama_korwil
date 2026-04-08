@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="users-index">
        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div>
                        <h3 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-people-fill me-2 text-warning"></i>
                            Manajemen User
                        </h3>
                        <p class="text-muted mb-0 mt-1">
                            <i class="bi bi-info-circle me-1"></i>
                            Kelola data user, operator, dan korwil
                        </p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-warning shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-funnel me-2 text-warning"></i>
                            Filter Data
                        </h6>
                    </div>
                    <div class="card-body pt-0">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pencarian</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Role</label>
                                <select name="role" class="form-select">
                                    <option value="">Semua Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-dark w-100">
                                    <i class="bi bi-search me-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Users --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-table me-2 text-warning"></i>
                                Daftar User
                            </h6>
                            <span class="badge bg-warning rounded-pill">{{ $users->total() }} Total</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-3" style="width: 70px">No</th>
                                        <th class="py-3">User</th>
                                        <th class="py-3">Email</th>
                                        <th class="py-3">Role</th>
                                        <th class="py-3">Assignment</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="py-3 text-center pe-3" style="width: 140px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                    <tr class="border-bottom">
                                        <td class="ps-3 fw-semibold fs-5">{{ $users->firstItem() + $index }}</td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                {{-- FOTO KOTAK --}}
                                                @if($user->foto)
                                                    <img src="{{ Storage::url($user->foto) }}"
                                                         class="square-photo"
                                                         width="55" height="55"
                                                         style="object-fit: cover; border-radius: 12px;">
                                                @else
                                                    <div class="square-photo-placeholder d-flex align-items-center justify-content-center"
                                                         style="width: 55px; height: 55px; background-color: #f8f9fa; border-radius: 12px; border: 1px solid #dee2e6;">
                                                        <i class="bi bi-person fs-3 text-secondary"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold fs-6">{{ $user->name }}</div>
                                                    <div class="text-muted small">
                                                        <i class="bi bi-person-badge me-1"></i>
                                                        Dibuat oleh: {{ $user->creator->name ?? 'System' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-envelope text-muted"></i>
                                                <span class="">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $roleBadge = [
                                                    'admin_dinas' => ['class' => 'primary', 'icon' => 'shield'],
                                                    'operator_sekolah' => ['class' => 'info', 'icon' => 'building'],
                                                    'korwil' => ['class' => 'warning', 'icon' => 'diagram-3']
                                                ];
                                                $role = $roleBadge[$user->role] ?? ['class' => 'secondary', 'icon' => 'person'];
                                            @endphp
                                            <span class="badge bg-{{ $role['class'] }}-soft px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-{{ $role['icon'] }} me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @if($user->role == 'operator_sekolah' && $user->assignments->first())
                                                @php $sekolah = \App\Models\Sekolah::find($user->assignments->first()->target_id); @endphp
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-building fs-5 text-warning"></i>
                                                    <div>
                                                        <div class="fw-semibold">{{ $sekolah->nama_sekolah ?? '-' }}</div>
                                                        <div class="text-muted small">NPSN: {{ $sekolah->npsn ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            @elseif($user->role == 'korwil' && $user->korwil)
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-diagram-3 fs-5 text-info"></i>
                                                    <div>
                                                        <div class="fw-semibold">{{ $user->korwil->nama_korwil ?? '-' }}</div>
                                                        <div class="text-muted small">Kode: {{ $user->korwil->kode_wilayah ?? '-' }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3">
                                            @if($user->is_active)
                                                <span class="badge bg-success-soft px-3 py-2 rounded-pill fs-6">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-danger-soft px-3 py-2 rounded-pill fs-6">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3 py-3">
                                            <div class="btn-group gap-2">
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                   class="btn btn-sm btn-outline-secondary"
                                                   title="Detail">
                                                    <i class="bi bi-eye fs-6"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit">
                                                    <i class="bi bi-pencil fs-6"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $user->id }}">
                                                    <i class="bi bi-trash fs-6"></i>
                                                </button>
                                            </div>

                                            {{-- Modal Delete --}}
                                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div class="mb-3">
                                                                <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                                                                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                                                                </div>
                                                            </div>
                                                            <p class="mb-1 fs-6">Apakah Anda yakin ingin menghapus user</p>
                                                            <p class="fw-bold text-danger fs-5 mb-0">{{ $user->name }}</p>
                                                            <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                                                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                                                <i class="bi bi-x-circle me-1"></i> Batal
                                                            </button>
                                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger px-4">
                                                                    <i class="bi bi-trash me-1"></i> Ya, Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                <p class="mb-0 fs-5">Belum ada data user</p>
                                                <small>Silakan tambah user baru</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                        <div class="d-flex justify-content-end mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.users-index {
    width: 100%;
}

/* Square Photo Styling - KOTAK */
.square-photo {
    border-radius: 12px !important;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.square-photo-placeholder {
    border-radius: 12px !important;
}

/* Badge Soft Colors */
.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.12);
    color: #0d6efd;
}

.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.12);
    color: #0dcaf0;
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.12);
    color: #b28100;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.12);
    color: #198754;
}

.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.12);
    color: #dc3545;
}

/* Button Group */
.btn-group {
    gap: 8px;
}

.btn-group .btn {
    border-radius: 8px !important;
    padding: 6px 12px;
}

/* Table Styling - Lebih Besar & Jelas */
.table {
    font-size: 0.95rem;
}

.table thead th {
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
    background-color: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
}

.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: #fff9e6;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

/* Card Styling */
.card {
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.25rem;
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 8px;
    padding: 0.6rem 0.75rem;
    font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
}

.input-group-text {
    border-radius: 8px 0 0 8px;
}

/* Badge */
.badge {
    font-weight: 500;
    font-size: 0.8rem;
}

/* Modal */
.modal-content {
    border-radius: 16px;
    border: none;
}

/* Pagination */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 8px !important;
    margin: 0 3px;
    border: none;
    color: #6c757d;
    font-weight: 500;
}

.page-item.active .page-link {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.page-link:hover {
    background-color: #ffc107;
    color: #000;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .square-photo, .square-photo-placeholder {
        width: 45px !important;
        height: 45px !important;
    }

    .table {
        font-size: 0.85rem;
    }

    .btn-group {
        gap: 4px;
    }

    .btn-group .btn {
        padding: 4px 8px;
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.4rem 0.6rem !important;
    }
}
</style>
@endsection
