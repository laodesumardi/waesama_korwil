<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Sekolah;
use App\Models\Korwil;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Di dalam controller method dashboard Anda
    // Tambahkan method index()
    public function index()
    {
        // Data untuk card statistik
        $totalUsers = User::count();
        $totalSekolah = Sekolah::count();
        $totalKorwil = Korwil::count();
        $totalAbsensi = Absensi::count();

        // Data untuk tabel
        $latestAbsensi = Absensi::with('sekolah')->latest()->take(10)->get();

        // Data untuk ringkasan status
        $statusPending = Absensi::where('status_validasi', 'pending')->count();
        $statusDisetujui = Absensi::where('status_validasi', 'disetujui')->count();
        $statusDitolak = Absensi::where('status_validasi', 'ditolak')->count();

        // Data untuk rata-rata kehadiran
        $totalHadir = Absensi::sum('jumlah_hadir');
        $totalSiswa = Absensi::sum('total_siswa');
        $avgHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;
        $avgTidakHadir = 100 - $avgHadir;

        // Data untuk statistik hari ini
        $todayAbsensi = Absensi::whereDate('tanggal', today())->get();
        $todayHadir = $todayAbsensi->sum('jumlah_hadir');
        $todaySakit = $todayAbsensi->sum('jumlah_sakit');
        $todayIzin = $todayAbsensi->sum('jumlah_izin');
        $todayAlpha = $todayAbsensi->sum('jumlah_alpha');

        // Perhatikan: view ini mengarah ke admin.dashboard.index
        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalSekolah',
            'totalKorwil',
            'totalAbsensi',
            'latestAbsensi',
            'statusPending',
            'statusDisetujui',
            'statusDitolak',
            'avgHadir',
            'avgTidakHadir',
            'todayHadir',
            'todaySakit',
            'todayIzin',
            'todayAlpha'
        ));
    }
}
