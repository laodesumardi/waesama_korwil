<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Sekolah;
use App\Models\Korwil;
use App\Models\PeriodeAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Data untuk filter
        $sekolahList = Sekolah::where('status', 'aktif')->get();
        $korwilList = Korwil::with('user')->get();
        $periodeList = PeriodeAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // Filter query
        $query = Absensi::with(['sekolah', 'sekolah.korwil', 'periode']);

        if ($request->filled('id_periode')) {
            $query->where('id_periode', $request->id_periode);
        }

        if ($request->filled('id_korwil')) {
            $query->whereHas('sekolah', function ($q) use ($request) {
                $q->where('id_korwil', $request->id_korwil);
            });
        }

        if ($request->filled('id_sekolah')) {
            $query->where('id_sekolah', $request->id_sekolah);
        }

        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // Data absensi
        $absensi = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        // Rekap data
        $rekap = [
            'total_data' => $query->count(),
            'total_hadir' => $query->sum('jumlah_hadir'),
            'total_sakit' => $query->sum('jumlah_sakit'),
            'total_izin' => $query->sum('jumlah_izin'),
            'total_alpha' => $query->sum('jumlah_alpha'),
            'total_siswa' => $query->sum('total_siswa'),
            'rata_hadir' => $query->count() > 0 ? round($query->sum('jumlah_hadir') / $query->count(), 1) : 0,
            'persen_kehadiran' => $query->sum('total_siswa') > 0
                ? round(($query->sum('jumlah_hadir') / $query->sum('total_siswa')) * 100, 1)
                : 0,
        ];

        // Grafik data per tanggal
        $chartData = $this->getChartData($query, $request);

        // Data per sekolah
        $perSekolah = $this->getPerSekolah($query, $request);

        // Data per korwil
        $perKorwil = $this->getPerKorwil($query, $request);

        $statusOptions = ['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'];

        return view('admin.laporan.index', compact(
            'absensi',
            'sekolahList',
            'korwilList',
            'periodeList',
            'rekap',
            'chartData',
            'perSekolah',
            'perKorwil',
            'statusOptions'
        ));
    }

    private function getChartData($query, $request)
    {
        // Clone query untuk grafik
        $chartQuery = clone $query;

        $startDate = $request->filled('tanggal_mulai') ? $request->tanggal_mulai : now()->subDays(30)->format('Y-m-d');
        $endDate = $request->filled('tanggal_selesai') ? $request->tanggal_selesai : now()->format('Y-m-d');

        $data = $chartQuery->whereBetween('tanggal', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(jumlah_hadir) as total_hadir'),
                DB::raw('SUM(jumlah_sakit) as total_sakit'),
                DB::raw('SUM(jumlah_izin) as total_izin'),
                DB::raw('SUM(jumlah_alpha) as total_alpha')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return [
            'labels' => $data->pluck('tanggal')->map(function ($item) {
                return date('d/m', strtotime($item));
            }),
            'hadir' => $data->pluck('total_hadir'),
            'sakit' => $data->pluck('total_sakit'),
            'izin' => $data->pluck('total_izin'),
            'alpha' => $data->pluck('total_alpha'),
        ];
    }

    private function getPerSekolah($query, $request)
    {
        $cloneQuery = clone $query;

        return $cloneQuery->select(
            'id_sekolah',
            DB::raw('MAX(sekolah.nama_sekolah) as nama_sekolah'),
            DB::raw('COUNT(*) as jumlah_absensi'),
            DB::raw('SUM(jumlah_hadir) as total_hadir'),
            DB::raw('SUM(jumlah_sakit) as total_sakit'),
            DB::raw('SUM(jumlah_izin) as total_izin'),
            DB::raw('SUM(jumlah_alpha) as total_alpha'),
            DB::raw('SUM(total_siswa) as total_siswa')
        )
            ->join('sekolah', 'absensi.id_sekolah', '=', 'sekolah.id')
            ->groupBy('id_sekolah')
            ->orderBy('total_hadir', 'desc')
            ->limit(10)
            ->get();
    }

    private function getPerKorwil($query, $request)
    {
        $cloneQuery = clone $query;

        return $cloneQuery->select(
            'sekolah.id_korwil',
            DB::raw('MAX(korwil.nama_korwil) as nama_korwil'),
            DB::raw('COUNT(*) as jumlah_absensi'),
            DB::raw('SUM(jumlah_hadir) as total_hadir'),
            DB::raw('SUM(jumlah_sakit) as total_sakit'),
            DB::raw('SUM(jumlah_izin) as total_izin'),
            DB::raw('SUM(jumlah_alpha) as total_alpha'),
            DB::raw('SUM(total_siswa) as total_siswa')
        )
            ->join('sekolah', 'absensi.id_sekolah', '=', 'sekolah.id')
            ->join('korwil', 'sekolah.id_korwil', '=', 'korwil.id')
            ->whereNotNull('sekolah.id_korwil')
            ->groupBy('sekolah.id_korwil')
            ->orderBy('total_hadir', 'desc')
            ->get();
    }

    public function exportPdf(Request $request)
    {
        // Implementasi export PDF (gunakan Barryvdh\DomPDF)
        // Sementara redirect dengan pesan
        return redirect()->back()->with('info', 'Fitur export PDF sedang dalam pengembangan.');
    }

    public function exportExcel(Request $request)
    {
        // Implementasi export Excel (gunakan Maatwebsite\Excel)
        // Sementara redirect dengan pesan
        return redirect()->back()->with('info', 'Fitur export Excel sedang dalam pengembangan.');
    }
}
