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

        // Data Guru per Wilayah (tanpa data pribadi)
        $guruList = Guru::select(
            'guru.id',
            'guru.nama_guru',
            'guru.jenis_kelamin',
            'guru.pendidikan_terakhir',
            'guru.bidang_studi',
            'guru.status',
            'sekolah.nama_sekolah',
            'sekolah.kecamatan',
            'korwil.nama_korwil',
            'korwil.kode_wilayah'
        )
            ->join('sekolah', 'guru.id_sekolah', '=', 'sekolah.id')
            ->leftJoin('korwil', 'sekolah.id_korwil', '=', 'korwil.id')
            ->where('guru.status', 'aktif')
            ->orderBy('korwil.nama_korwil')
            ->orderBy('guru.nama_guru')
            ->get();

        // Data Siswa per Wilayah (tanpa data pribadi)
        $siswaList = Siswa::select(
            'siswa.id',
            'siswa.nama_siswa',
            'siswa.jenis_kelamin',
            'siswa.kelas',
            'siswa.status',
            'sekolah.nama_sekolah',
            'sekolah.kecamatan',
            'korwil.nama_korwil',
            'korwil.kode_wilayah'
        )
            ->join('sekolah', 'siswa.id_sekolah', '=', 'sekolah.id')
            ->leftJoin('korwil', 'sekolah.id_korwil', '=', 'korwil.id')
            ->where('siswa.status', 'aktif')
            ->orderBy('korwil.nama_korwil')
            ->orderBy('siswa.kelas')
            ->orderBy('siswa.nama_siswa')
            ->get();

        // Filter untuk guru dan siswa
        $searchGuru = $request->input('search_guru');
        $searchSiswa = $request->input('search_siswa');
        $filterKorwil = $request->input('filter_korwil');

        if ($searchGuru) {
            $guruList = $guruList->filter(function ($guru) use ($searchGuru) {
                return stripos($guru->nama_guru, $searchGuru) !== false ||
                    stripos($guru->bidang_studi, $searchGuru) !== false ||
                    stripos($guru->nama_sekolah, $searchGuru) !== false;
            });
        }

        if ($searchSiswa) {
            $siswaList = $siswaList->filter(function ($siswa) use ($searchSiswa) {
                return stripos($siswa->nama_siswa, $searchSiswa) !== false ||
                    stripos($siswa->kelas, $searchSiswa) !== false ||
                    stripos($siswa->nama_sekolah, $searchSiswa) !== false;
            });
        }

        if ($filterKorwil) {
            $guruList = $guruList->filter(function ($guru) use ($filterKorwil) {
                return $guru->kode_wilayah == $filterKorwil;
            });
            $siswaList = $siswaList->filter(function ($siswa) use ($filterKorwil) {
                return $siswa->kode_wilayah == $filterKorwil;
            });
        }

        // Data untuk filter dropdown
        $korwilOptions = Korwil::select('kode_wilayah', 'nama_korwil')->get();

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
            'guruList',
            'siswaList',
            'korwilOptions'
        ));
    }
}
