<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Korwil;
use App\Models\Absensi;
use App\Models\PeriodeAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingKorwilController extends Controller
{
    public function index(Request $request)
    {
        // Statistik Utama
        $totalSekolah = Sekolah::count();
        $sekolahAktif = Sekolah::where('status', 'aktif')->count();
        $sekolahNonaktif = $totalSekolah - $sekolahAktif;

        $totalGuru = Guru::count();
        $guruAktif = Guru::where('status', 'aktif')->count();
        $guruPensiun = Guru::where('status', 'pensiun')->count();

        $totalSiswa = Siswa::count();
        $siswaAktif = Siswa::where('status', 'aktif')->count();
        $siswaLulus = Siswa::where('status', 'lulus')->count();

        // Periode Aktif
        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        // Statistik Absensi
        $absensiQuery = Absensi::query();
        if ($periodeAktif) {
            $absensiQuery->where('id_periode', $periodeAktif->id);
        }

        $totalAbsensi = $absensiQuery->count();

        // Detail Absensi Siswa
        $absensiSiswa = Absensi::where('jenis_absensi', 'siswa');
        if ($periodeAktif) {
            $absensiSiswa->where('id_periode', $periodeAktif->id);
        }

        $siswaHadir = $absensiSiswa->sum('jumlah_hadir');
        $siswaSakit = $absensiSiswa->sum('jumlah_sakit');
        $siswaIzin = $absensiSiswa->sum('jumlah_izin');
        $siswaAlpha = $absensiSiswa->sum('jumlah_alpha');

        // Detail Absensi Guru
        $absensiGuru = Absensi::where('jenis_absensi', 'guru');
        if ($periodeAktif) {
            $absensiGuru->where('id_periode', $periodeAktif->id);
        }

        $guruHadir = $absensiGuru->sum('jumlah_hadir');
        $guruIzin = $absensiGuru->sum('jumlah_izin');
        $guruAlpha = $absensiGuru->sum('jumlah_alpha');

        // Data Korwil per Wilayah
        $korwilList = Korwil::with(['user', 'sekolah'])->get();

        foreach ($korwilList as $korwil) {
            $sekolahIds = $korwil->sekolah->pluck('id')->toArray();

            $korwil->jumlah_sekolah = $korwil->sekolah->count();
            $korwil->jumlah_guru = Guru::whereIn('id_sekolah', $sekolahIds)->count();
            $korwil->jumlah_siswa = Siswa::whereIn('id_sekolah', $sekolahIds)->count();

            // Hitung absensi per korwil
            $absensiWilayah = Absensi::whereIn('id_sekolah', $sekolahIds);
            if ($periodeAktif) {
                $absensiWilayah->where('id_periode', $periodeAktif->id);
            }

            $totalHadirWilayah = $absensiWilayah->sum('jumlah_hadir');
            $totalSiswaWilayah = $absensiWilayah->sum('total_siswa');

            $korwil->total_absensi = $absensiWilayah->count();
            $korwil->persen_kehadiran = $totalSiswaWilayah > 0
                ? round(($totalHadirWilayah / $totalSiswaWilayah) * 100, 1)
                : 0;
        }

        // Data untuk filter dropdown korwil
        $korwilOptions = Korwil::select('kode_wilayah', 'nama_korwil')->get();

        // Data Sekolah (TAB BARU)
        $sekolahQuery = Sekolah::select(
            'sekolah.id',
            'sekolah.npsn',
            'sekolah.nama_sekolah',
            'sekolah.alamat',
            'sekolah.kelurahan',
            'sekolah.kecamatan',
            'sekolah.status',
            'sekolah.nama_kepala_sekolah',
            'sekolah.no_telp_kepala_sekolah',
            'korwil.nama_korwil',
            'korwil.kode_wilayah'
        )
            ->leftJoin('korwil', 'sekolah.id_korwil', '=', 'korwil.id')
            ->orderBy('korwil.nama_korwil')
            ->orderBy('sekolah.nama_sekolah');

        // Filter untuk sekolah
        if ($request->filled('search_sekolah')) {
            $sekolahQuery->where(function ($q) use ($request) {
                $q->where('sekolah.nama_sekolah', 'like', '%' . $request->search_sekolah . '%')
                    ->orWhere('sekolah.npsn', 'like', '%' . $request->search_sekolah . '%')
                    ->orWhere('sekolah.kecamatan', 'like', '%' . $request->search_sekolah . '%');
            });
        }

        if ($request->filled('filter_korwil_sekolah')) {
            $sekolahQuery->where('korwil.kode_wilayah', $request->filter_korwil_sekolah);
        }

        if ($request->filled('filter_status_sekolah')) {
            $sekolahQuery->where('sekolah.status', $request->filter_status_sekolah);
        }

        $sekolahList = $sekolahQuery->get();

        // Data Guru
        $guruQuery = Guru::select(
            'guru.id',
            'guru.nama_guru',
            'guru.jenis_kelamin',
            'guru.pendidikan_terakhir',
            'guru.bidang_studi',
            'guru.status',
            'sekolah.nama_sekolah',
            'sekolah.kecamatan',
            'sekolah.nama_kepala_sekolah',
            'sekolah.no_telp_kepala_sekolah',
            'korwil.nama_korwil',
            'korwil.kode_wilayah'
        )
            ->join('sekolah', 'guru.id_sekolah', '=', 'sekolah.id')
            ->leftJoin('korwil', 'sekolah.id_korwil', '=', 'korwil.id')
            ->orderBy('korwil.nama_korwil')
            ->orderBy('guru.nama_guru');

        if ($request->filled('search_guru')) {
            $guruQuery->where(function ($q) use ($request) {
                $q->where('guru.nama_guru', 'like', '%' . $request->search_guru . '%')
                    ->orWhere('guru.bidang_studi', 'like', '%' . $request->search_guru . '%')
                    ->orWhere('sekolah.nama_sekolah', 'like', '%' . $request->search_guru . '%');
            });
        }

        if ($request->filled('filter_korwil')) {
            $guruQuery->where('korwil.kode_wilayah', $request->filter_korwil);
        }

        $guruList = $guruQuery->get();

        // Data Siswa
        $siswaQuery = Siswa::select(
            'siswa.id',
            'siswa.nama_siswa',
            'siswa.jenis_kelamin',
            'siswa.kelas',
            'siswa.status',
            'sekolah.nama_sekolah',
            'sekolah.kecamatan',
            'sekolah.nama_kepala_sekolah',
            'korwil.nama_korwil',
            'korwil.kode_wilayah'
        )
            ->join('sekolah', 'siswa.id_sekolah', '=', 'sekolah.id')
            ->leftJoin('korwil', 'sekolah.id_korwil', '=', 'korwil.id')
            ->orderBy('korwil.nama_korwil')
            ->orderBy('siswa.kelas')
            ->orderBy('siswa.nama_siswa');

        if ($request->filled('search_siswa')) {
            $siswaQuery->where(function ($q) use ($request) {
                $q->where('siswa.nama_siswa', 'like', '%' . $request->search_siswa . '%')
                    ->orWhere('siswa.kelas', 'like', '%' . $request->search_siswa . '%')
                    ->orWhere('sekolah.nama_sekolah', 'like', '%' . $request->search_siswa . '%');
            });
        }

        if ($request->filled('filter_korwil_siswa')) {
            $siswaQuery->where('korwil.kode_wilayah', $request->filter_korwil_siswa);
        }

        $siswaList = $siswaQuery->get();

        return view('landing.korwil', compact(
            'totalSekolah',
            'sekolahAktif',
            'sekolahNonaktif',
            'totalGuru',
            'guruAktif',
            'guruPensiun',
            'totalSiswa',
            'siswaAktif',
            'siswaLulus',
            'totalAbsensi',
            'periodeAktif',
            'siswaHadir',
            'siswaSakit',
            'siswaIzin',
            'siswaAlpha',
            'guruHadir',
            'guruIzin',
            'guruAlpha',
            'korwilList',
            'korwilOptions',
            'sekolahList',
            'guruList',
            'siswaList'
        ));
    }
}
