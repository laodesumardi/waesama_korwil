<?php

namespace App\Http\Controllers\Korwil;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Korwil;
use App\Models\PeriodeAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function getKorwilData()
    {
        $user = Auth::user();
        $korwil = Korwil::where('user_id', $user->id)->first();

        if (!$korwil) {
            return null;
        }

        return $korwil;
    }

    private function getSekolahIds()
    {
        $korwil = $this->getKorwilData();

        if (!$korwil) {
            return [];
        }

        return Sekolah::where('id_korwil', $korwil->id)
            ->where('status', 'aktif')
            ->pluck('id')
            ->toArray();
    }

    public function index(Request $request)
    {
        $korwil = $this->getKorwilData();

        if (!$korwil) {
            return view('korwil.no-assignment');
        }

        $sekolahIds = $this->getSekolahIds();
        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        // Statistik Sekolah
        $totalSekolah = Sekolah::where('id_korwil', $korwil->id)->count();
        $sekolahAktif = Sekolah::where('id_korwil', $korwil->id)->where('status', 'aktif')->count();
        $sekolahNonaktif = $totalSekolah - $sekolahAktif;

        // Statistik Siswa
        $totalSiswa = Siswa::whereIn('id_sekolah', $sekolahIds)->count();
        $siswaAktif = Siswa::whereIn('id_sekolah', $sekolahIds)->where('status', 'aktif')->count();
        $siswaLulus = Siswa::whereIn('id_sekolah', $sekolahIds)->where('status', 'lulus')->count();

        // Statistik Guru
        $totalGuru = Guru::whereIn('id_sekolah', $sekolahIds)->count();
        $guruAktif = Guru::whereIn('id_sekolah', $sekolahIds)->where('status', 'aktif')->count();
        $guruPensiun = Guru::whereIn('id_sekolah', $sekolahIds)->where('status', 'pensiun')->count();

        // Statistik Absensi
        $absensiQuery = Absensi::whereIn('id_sekolah', $sekolahIds);

        if ($periodeAktif) {
            $absensiQuery->where('id_periode', $periodeAktif->id);
        }

        $totalAbsensi = $absensiQuery->count();
        $totalHadir = $absensiQuery->sum('jumlah_hadir');
        $totalSakit = $absensiQuery->sum('jumlah_sakit');
        $totalIzin = $absensiQuery->sum('jumlah_izin');
        $totalAlpha = $absensiQuery->sum('jumlah_alpha');

        // Statistik per jenis absensi
        $absensiSiswa = Absensi::whereIn('id_sekolah', $sekolahIds)->where('jenis_absensi', 'siswa');
        $absensiGuru = Absensi::whereIn('id_sekolah', $sekolahIds)->where('jenis_absensi', 'guru');

        if ($periodeAktif) {
            $absensiSiswa->where('id_periode', $periodeAktif->id);
            $absensiGuru->where('id_periode', $periodeAktif->id);
        }

        $statAbsensi = [
            'siswa' => [
                'total' => $absensiSiswa->count(),
                'hadir' => $absensiSiswa->sum('jumlah_hadir'),
                'sakit' => $absensiSiswa->sum('jumlah_sakit'),
                'izin' => $absensiSiswa->sum('jumlah_izin'),
                'alpha' => $absensiSiswa->sum('jumlah_alpha'),
            ],
            'guru' => [
                'total' => $absensiGuru->count(),
                'hadir' => $absensiGuru->sum('jumlah_hadir'),
                'izin' => $absensiGuru->sum('jumlah_izin'),
                'alpha' => $absensiGuru->sum('jumlah_alpha'),
            ]
        ];

        // Data Sekolah Binaan dengan pagination dan filter
        $sekolahQuery = Sekolah::where('id_korwil', $korwil->id);

        // Search
        if ($request->filled('search')) {
            $sekolahQuery->where(function ($q) use ($request) {
                $q->where('nama_sekolah', 'like', '%' . $request->search . '%')
                    ->orWhere('npsn', 'like', '%' . $request->search . '%')
                    ->orWhere('kecamatan', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $sekolahQuery->where('status', $request->status);
        }

        $sekolahList = $sekolahQuery->paginate(10)->withQueryString();

        // Hitung manual jumlah siswa dan guru per sekolah
        foreach ($sekolahList as $sekolah) {
            $sekolah->siswa_count = Siswa::where('id_sekolah', $sekolah->id)
                ->where('status', 'aktif')
                ->count();
            $sekolah->guru_count = Guru::where('id_sekolah', $sekolah->id)
                ->where('status', 'aktif')
                ->count();
        }

        // Grafik Absensi 7 Hari Terakhir
        $chartData = $this->getChartData($sekolahIds);

        return view('korwil.dashboard.index', compact(
            'korwil',
            'periodeAktif',
            'totalSekolah',
            'sekolahAktif',
            'sekolahNonaktif',
            'totalSiswa',
            'siswaAktif',
            'siswaLulus',
            'totalGuru',
            'guruAktif',
            'guruPensiun',
            'totalAbsensi',
            'totalHadir',
            'totalSakit',
            'totalIzin',
            'totalAlpha',
            'statAbsensi',
            'sekolahList',
            'chartData'
        ));
    }

    private function getChartData($sekolahIds)
    {
        $data = Absensi::whereIn('id_sekolah', $sekolahIds)
            ->whereDate('tanggal', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(jumlah_hadir) as total_hadir'),
                DB::raw('SUM(jumlah_sakit) as total_sakit'),
                DB::raw('SUM(jumlah_izin) as total_izin'),
                DB::raw('SUM(jumlah_alpha) as total_alpha')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal');

        $labels = [];
        $hadir = [];
        $sakit = [];
        $izin = [];
        $alpha = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');

            $absensi = $data->get($date);

            $hadir[] = $absensi ? $absensi->total_hadir : 0;
            $sakit[] = $absensi ? $absensi->total_sakit : 0;
            $izin[] = $absensi ? $absensi->total_izin : 0;
            $alpha[] = $absensi ? $absensi->total_alpha : 0;
        }

        return compact('labels', 'hadir', 'sakit', 'izin', 'alpha');
    }

    public function sekolahDetail($id)
    {
        $korwil = $this->getKorwilData();

        if (!$korwil) {
            return redirect()->route('korwil.dashboard')->with('error', 'Data korwil tidak ditemukan.');
        }

        $sekolah = Sekolah::where('id_korwil', $korwil->id)->findOrFail($id);

        $totalSiswa = Siswa::where('id_sekolah', $sekolah->id)->count();
        $siswaAktif = Siswa::where('id_sekolah', $sekolah->id)->where('status', 'aktif')->count();
        $totalGuru = Guru::where('id_sekolah', $sekolah->id)->count();
        $guruAktif = Guru::where('id_sekolah', $sekolah->id)->where('status', 'aktif')->count();

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();
        $absensiQuery = Absensi::where('id_sekolah', $sekolah->id);

        if ($periodeAktif) {
            $absensiQuery->where('id_periode', $periodeAktif->id);
        }

        $statAbsensi = [
            'total' => $absensiQuery->count(),
            'hadir' => $absensiQuery->sum('jumlah_hadir'),
            'sakit' => $absensiQuery->sum('jumlah_sakit'),
            'izin' => $absensiQuery->sum('jumlah_izin'),
            'alpha' => $absensiQuery->sum('jumlah_alpha'),
        ];

        return view('korwil.sekolah-detail', compact('sekolah', 'totalSiswa', 'siswaAktif', 'totalGuru', 'guruAktif', 'statAbsensi', 'periodeAktif'));
    }
}
