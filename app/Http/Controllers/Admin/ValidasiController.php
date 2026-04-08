<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Sekolah;
use App\Models\PeriodeAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with(['sekolah', 'periode', 'inputer', 'validator']);

        // Filter jenis absensi
        if ($request->filled('jenis_absensi')) {
            $query->where('jenis_absensi', $request->jenis_absensi);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        // Filter sekolah
        if ($request->filled('id_sekolah')) {
            $query->where('id_sekolah', $request->id_sekolah);
        }

        // Filter periode
        if ($request->filled('id_periode')) {
            $query->where('id_periode', $request->id_periode);
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        // Search
        if ($request->filled('search')) {
            $query->whereHas('sekolah', function ($q) use ($request) {
                $q->where('nama_sekolah', 'like', '%' . $request->search . '%')
                    ->orWhere('npsn', 'like', '%' . $request->search . '%');
            });
        }

        $absensi = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        // Data untuk filter dropdown
        $sekolahList = Sekolah::where('status', 'aktif')->get();
        $periodeList = PeriodeAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $statusOptions = [
            'pending' => 'Pending',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak'
        ];

        // Statistik
        $stats = [
            'total' => Absensi::count(),
            'pending' => Absensi::where('status_validasi', 'pending')->count(),
            'disetujui' => Absensi::where('status_validasi', 'disetujui')->count(),
            'ditolak' => Absensi::where('status_validasi', 'ditolak')->count(),
        ];

        return view('admin.validasi.index', compact('absensi', 'sekolahList', 'periodeList', 'statusOptions', 'stats'));
    }

    public function show($id)
    {
        $absensi = Absensi::with(['sekolah', 'sekolah.korwil', 'periode', 'inputer', 'validator'])
            ->findOrFail($id);

        return view('admin.validasi.show', compact('absensi'));
    }

    public function approve($id)
    {
        $absensi = Absensi::findOrFail($id);

        DB::beginTransaction();
        try {
            $absensi->update([
                'status_validasi' => 'disetujui',
                'divalidasi_oleh' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('admin.validasi.index')
                ->with('success', 'Absensi berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menyetujui absensi.');
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|min:10',
        ]);

        $absensi = Absensi::findOrFail($id);

        DB::beginTransaction();
        try {
            $absensi->update([
                'status_validasi' => 'ditolak',
                'divalidasi_oleh' => Auth::id(),
                'keterangan' => $request->alasan_tolak,
            ]);

            DB::commit();
            return redirect()->route('admin.validasi.index')
                ->with('success', 'Absensi ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menolak absensi.');
        }
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:absensi,id',
        ]);

        $count = Absensi::whereIn('id', $request->ids)
            ->where('status_validasi', 'pending')
            ->update([
                'status_validasi' => 'disetujui',
                'divalidasi_oleh' => Auth::id(),
            ]);

        return redirect()->route('admin.validasi.index')
            ->with('success', "$count data absensi berhasil disetujui.");
    }

    public function laporan(Request $request)
    {
        $query = Absensi::with(['sekolah', 'periode']);

        // Filter jenis absensi
        if ($request->filled('jenis_absensi')) {
            $query->where('jenis_absensi', $request->jenis_absensi);
        }

        // Filter periode
        if ($request->filled('id_periode')) {
            $query->where('id_periode', $request->id_periode);
        }

        // Filter sekolah
        if ($request->filled('id_sekolah')) {
            $query->where('id_sekolah', $request->id_sekolah);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->paginate(20)->withQueryString();

        // Data untuk filter
        $sekolahList = Sekolah::where('status', 'aktif')->get();
        $periodeList = PeriodeAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // Rekap data
        $rekap = [
            'jumlah_data' => $query->count(),
            'total_hadir' => $query->sum('jumlah_hadir'),
            'total_sakit' => $query->sum('jumlah_sakit'),
            'total_izin' => $query->sum('jumlah_izin'),
            'total_alpha' => $query->sum('jumlah_alpha'),
            'total_siswa' => $query->sum('total_siswa'),
            'rata_hadir' => $query->count() > 0 ? round($query->sum('jumlah_hadir') / $query->count(), 1) : 0,
            'persen_kehadiran' => $query->sum('total_siswa') > 0
                ? round(($query->sum('jumlah_hadir') / $query->sum('total_siswa')) * 100, 1)
                : 0,
            'pending' => (clone $query)->where('status_validasi', 'pending')->count(),
            'disetujui' => (clone $query)->where('status_validasi', 'disetujui')->count(),
            'ditolak' => (clone $query)->where('status_validasi', 'ditolak')->count(),
        ];

        return view('admin.validasi.laporan', compact('absensi', 'sekolahList', 'periodeList', 'rekap'));
    }

    public function export(Request $request)
    {
        $query = Absensi::with(['sekolah', 'periode']);

        if ($request->filled('id_periode')) {
            $query->where('id_periode', $request->id_periode);
        }
        if ($request->filled('id_sekolah')) {
            $query->where('id_sekolah', $request->id_sekolah);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->get();

        // Generate CSV
        $filename = "laporan_absensi_" . date('Ymd_His') . ".csv";
        $handle = fopen('php://temp', 'w+');

        // Header CSV
        fputcsv($handle, [
            'No',
            'Tanggal',
            'NPSN',
            'Nama Sekolah',
            'Hadir',
            'Sakit',
            'Izin',
            'Alpha',
            'Total Siswa',
            'Status Validasi',
            'Petugas Input',
            'Validator'
        ]);

        foreach ($absensi as $index => $item) {
            fputcsv($handle, [
                $index + 1,
                $item->tanggal->format('d/m/Y'),
                $item->sekolah->npsn ?? '-',
                $item->sekolah->nama_sekolah ?? '-',
                $item->jumlah_hadir,
                $item->jumlah_sakit,
                $item->jumlah_izin,
                $item->jumlah_alpha,
                $item->total_siswa,
                $item->status_validasi,
                $item->inputer->name ?? '-',
                $item->validator->name ?? '-',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
