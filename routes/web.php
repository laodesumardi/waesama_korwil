<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingKorwilController;

// CONTROLLER ADMIN
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KorwilController;
use App\Http\Controllers\Admin\SekolahController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Admin\LaporanController;

// CONTROLLER OPERATOR
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Operator\SiswaController;
use App\Http\Controllers\Operator\GuruController;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;

// CONTROLLER KORWIL
use App\Http\Controllers\Korwil\DashboardController as KorwilDashboard;

// ======================
// ROOT (AUTO REDIRECT ROLE)
// ======================
Route::get('/', function () {
    if (!Auth::check()) {
        return view('welcome');
    }

    return match (Auth::user()->role) {
        'admin_dinas' => redirect('/admin'),
        'operator_sekolah' => redirect('/operator'),
        'korwil' => redirect('/korwil'),
        default => redirect('/login')
    };
})->name('home');

// ======================
// LANDING PAGE KORWIL (PUBLIC)
// ======================
Route::get('/rekap-korwil', [LandingKorwilController::class, 'index'])->name('rekap.korwil');

// ======================
// PROFILE
// ======================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ======================
// ADMIN ROUTES
// ======================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Manajemen User
    Route::resource('users', UserController::class);
    Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Manajemen Korwil
    Route::resource('korwil', KorwilController::class);

    // Manajemen Sekolah
    Route::resource('sekolah', SekolahController::class);
    Route::patch('sekolah/{id}/toggle-status', [SekolahController::class, 'toggleStatus'])->name('sekolah.toggle-status');

    // Manajemen Periode
    Route::resource('periode', PeriodeController::class);
    Route::patch('periode/{id}/set-active', [PeriodeController::class, 'setActive'])->name('periode.set-active');

    // Validasi Absensi
    Route::get('validasi', [ValidasiController::class, 'index'])->name('validasi.index');
    Route::get('validasi/{id}', [ValidasiController::class, 'show'])->name('validasi.show');
    Route::post('validasi/{id}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');
    Route::post('validasi/{id}/reject', [ValidasiController::class, 'reject'])->name('validasi.reject');
    Route::post('validasi/bulk/approve', [ValidasiController::class, 'bulkApprove'])->name('validasi.bulk-approve');
    Route::get('validasi/laporan/report', [ValidasiController::class, 'laporan'])->name('validasi.laporan');
    Route::get('validasi/export/csv', [ValidasiController::class, 'export'])->name('validasi.export');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
});

// ======================
// OPERATOR ROUTES
// ======================
Route::prefix('operator')->name('operator.')->middleware(['auth', 'operator'])->group(function () {
    // Dashboard
    Route::get('/', [OperatorController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [OperatorController::class, 'profile'])->name('profile');
    Route::post('/profile', [OperatorController::class, 'profileUpdate'])->name('profile.update');

    // Data Siswa
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/', [SiswaController::class, 'index'])->name('index');
        Route::get('/create', [SiswaController::class, 'create'])->name('create');
        Route::post('/', [SiswaController::class, 'store'])->name('store');
        Route::get('/{siswa}', [SiswaController::class, 'show'])->name('show');
        Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('edit');
        Route::put('/{siswa}', [SiswaController::class, 'update'])->name('update');
        Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('destroy');
        Route::patch('/{siswa}/toggle-status', [SiswaController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Data Guru
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('index');
        Route::get('/create', [GuruController::class, 'create'])->name('create');
        Route::post('/', [GuruController::class, 'store'])->name('store');
        Route::get('/{guru}', [GuruController::class, 'show'])->name('show');
        Route::get('/{guru}/edit', [GuruController::class, 'edit'])->name('edit');
        Route::put('/{guru}', [GuruController::class, 'update'])->name('update');
        Route::delete('/{guru}', [GuruController::class, 'destroy'])->name('destroy');
        Route::patch('/{guru}/toggle-status', [GuruController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Absensi
    Route::prefix('absensi')->name('absensi.')->group(function () {
        // Absensi Siswa
        Route::get('/siswa', [OperatorController::class, 'absensiSiswa'])->name('siswa');
        Route::post('/siswa/store', [OperatorController::class, 'absensiSiswaStore'])->name('siswa.store');

        // Absensi Guru
        Route::get('/guru', [OperatorController::class, 'absensiGuru'])->name('guru');
        Route::post('/guru/store', [OperatorController::class, 'absensiGuruStore'])->name('guru.store');

        // Riwayat Absensi
        Route::get('/history', [OperatorController::class, 'absensiHistory'])->name('history');
        Route::get('/{id}', [OperatorController::class, 'absensiShow'])->name('show');
    });
});

// ======================
// KORWIL ROUTES (DASHBOARD)
// ======================
Route::prefix('korwil')->name('korwil.')->middleware(['auth', 'korwil'])->group(function () {
    Route::get('/', [KorwilDashboard::class, 'index'])->name('dashboard');
    Route::get('/sekolah/{id}', [KorwilDashboard::class, 'sekolahDetail'])->name('sekolah.detail');
});

require __DIR__ . '/auth.php';
