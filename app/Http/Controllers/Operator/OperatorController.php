<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\PeriodeAjaran;
use App\Models\Siswa;
use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk Log

class OperatorController extends Controller
{
    public function index()
    {
        // Ambil sekolah yang ditugaskan ke operator ini
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        if (!$assignment) {
            return view('operator.no-assignment');
        }

        $sekolah = Sekolah::find($assignment->target_id);

        // Periode aktif
        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        // Statistik
        $statistik = [
            // Statistik Siswa
            'total_siswa' => Siswa::where('id_sekolah', $sekolah->id)->count(),
            'siswa_aktif' => Siswa::where('id_sekolah', $sekolah->id)->where('status', 'aktif')->count(),
            'siswa_lulus' => Siswa::where('id_sekolah', $sekolah->id)->where('status', 'lulus')->count(),

            // Statistik Guru
            'total_guru' => Guru::where('id_sekolah', $sekolah->id)->count(),
            'guru_aktif' => Guru::where('id_sekolah', $sekolah->id)->where('status', 'aktif')->count(),

            // Statistik Absensi
            'total_absensi' => Absensi::where('id_sekolah', $sekolah->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
            'total_hadir' => Absensi::where('id_sekolah', $sekolah->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('jumlah_hadir'),
            'rata_hadir' => Absensi::where('id_sekolah', $sekolah->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->avg('jumlah_hadir') ?? 0,
        ];

        // Absensi terbaru
        $absensiTerbaru = Absensi::where('id_sekolah', $sekolah->id)
            ->with('periode')
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        // Data untuk chart 7 hari terakhir
        $chartData = $this->getChartData($sekolah->id);

        return view('operator.dashboard.index', compact('sekolah', 'periodeAktif', 'statistik', 'absensiTerbaru', 'chartData'));
    }

    private function getChartData($sekolahId)
    {
        $data = Absensi::where('id_sekolah', $sekolahId)
            ->whereDate('tanggal', '>=', now()->subDays(7))
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

            $hadir[] = $absensi ? $absensi->jumlah_hadir : 0;
            $sakit[] = $absensi ? $absensi->jumlah_sakit : 0;
            $izin[] = $absensi ? $absensi->jumlah_izin : 0;
            $alpha[] = $absensi ? $absensi->jumlah_alpha : 0;
        }

        return [
            'labels' => $labels,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
        ];
    }

    public function absensiCreate()
    {
        // Debug: cek apakah method ini dipanggil
        Log::info('absensiCreate method called');

        // Ambil sekolah yang ditugaskan ke operator ini
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        if (!$assignment) {
            Log::warning('No assignment found for user: ' . Auth::id());
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $sekolah = Sekolah::find($assignment->target_id);

        Log::info('Sekolah: ', ['sekolah' => $sekolah]);

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Data sekolah tidak ditemukan.');
        }

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        Log::info('Periode aktif: ', ['periode' => $periodeAktif]);

        if (!$periodeAktif) {
            return redirect()->route('operator.dashboard')->with('error', 'Belum ada periode ajaran aktif. Silakan hubungi admin.');
        }

        // Cek apakah sudah input hari ini
        $sudahInput = Absensi::where('id_sekolah', $sekolah->id)
            ->where('id_periode', $periodeAktif->id)
            ->whereDate('tanggal', now())
            ->exists();

        return view('operator.absensi.create', compact('sekolah', 'periodeAktif', 'sudahInput'));
    }

    public function absensiStore(Request $request)
    {
        // Debug: cek apakah request sampai ke sini
        Log::info('Absensi store called', $request->all());

        $request->validate([
            'jumlah_hadir' => 'required|integer|min:0',
            'jumlah_sakit' => 'required|integer|min:0',
            'jumlah_izin' => 'required|integer|min:0',
            'jumlah_alpha' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        if (!$assignment) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Belum ada periode ajaran aktif.');
        }

        // Cek duplikasi
        $exists = Absensi::where('id_sekolah', $assignment->target_id)
            ->where('id_periode', $periodeAktif->id)
            ->whereDate('tanggal', now())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah menginput absensi hari ini.');
        }

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('absensi', 'public');
        }

        $totalSiswa = $request->jumlah_hadir + $request->jumlah_sakit + $request->jumlah_izin + $request->jumlah_alpha;

        try {
            $absensi = Absensi::create([
                'id_sekolah' => $assignment->target_id,
                'id_periode' => $periodeAktif->id,
                'tanggal' => now(),
                'jumlah_hadir' => $request->jumlah_hadir,
                'jumlah_sakit' => $request->jumlah_sakit,
                'jumlah_izin' => $request->jumlah_izin,
                'jumlah_alpha' => $request->jumlah_alpha,
                'total_siswa' => $totalSiswa,
                'keterangan' => $request->keterangan,
                'foto' => $fotoPath,
                'diinput_oleh' => Auth::id(),
                'status_validasi' => 'pending',
            ]);

            Log::info('Absensi created successfully', ['id' => $absensi->id]);

            return redirect()->route('operator.absensi.history')
                ->with('success', 'Data absensi berhasil dikirim dan menunggu validasi.');
        } catch (\Exception $e) {
            Log::error('Error saving absensi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function absensiHistory(Request $request)
    {
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        if (!$assignment) {
            return view('operator.no-assignment');
        }

        $query = Absensi::where('id_sekolah', $assignment->target_id)
            ->with('periode', 'validator');

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status_validasi', $request->status);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $tahunList = range(date('Y') - 2, date('Y'));
        $statusOptions = ['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'];

        return view('operator.absensi.history', compact('absensi', 'bulanList', 'tahunList', 'statusOptions'));
    }

    public function absensiShow($id)
    {
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        if (!$assignment) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $absensi = Absensi::with(['sekolah', 'periode', 'inputer', 'validator'])
            ->where('id_sekolah', $assignment->target_id)
            ->findOrFail($id);

        return view('operator.absensi.show', compact('absensi'));
    }

    public function profile()
    {
        $user = Auth::user();
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->with('target')
            ->first();

        $sekolah = $assignment ? $assignment->target : null;

        return view('operator.profile.index', compact('user', 'sekolah'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update name dan email
        $user->name = $request->name;
        $user->email = $request->email;

        // Upload foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $fotoPath = $request->file('foto')->store('users', 'public');
            $user->foto = $fotoPath;
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('operator.profile')->with('success', 'Profil berhasil diperbarui.');
    }



    public function absensiSiswa()
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        if (!$periodeAktif) {
            return redirect()->route('operator.dashboard')->with('error', 'Belum ada periode ajaran aktif.');
        }

        // Ambil semua siswa
        $siswaList = Siswa::where('id_sekolah', $sekolah->id)
            ->where('status', 'aktif')
            ->orderBy('kelas')
            ->orderBy('nama_siswa')
            ->get();

        // Cek apakah sudah input hari ini
        $sudahInput = Absensi::where('id_sekolah', $sekolah->id)
            ->where('id_periode', $periodeAktif->id)
            ->where('jenis_absensi', 'siswa')
            ->whereDate('tanggal', now())
            ->exists();

        return view('operator.absensi.siswa', compact('sekolah', 'periodeAktif', 'siswaList', 'sudahInput'));
    }

    public function absensiSiswaStore(Request $request)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Belum ada periode ajaran aktif.');
        }

        // Validasi
        $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek duplikasi
        $exists = Absensi::where('id_sekolah', $sekolah->id)
            ->where('id_periode', $periodeAktif->id)
            ->where('jenis_absensi', 'siswa')
            ->whereDate('tanggal', now())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah menginput absensi siswa hari ini.');
        }

        // Hitung statistik
        $kehadiran = $request->kehadiran;
        $jumlahHadir = count(array_keys($kehadiran, 'hadir'));
        $jumlahSakit = count(array_keys($kehadiran, 'sakit'));
        $jumlahIzin = count(array_keys($kehadiran, 'izin'));
        $jumlahAlpha = count(array_keys($kehadiran, 'alpha'));
        $totalSiswa = count($kehadiran);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('absensi', 'public');
        }

        // Simpan detail absensi
        $detailAbsensi = [];
        foreach ($kehadiran as $siswaId => $status) {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                $detailAbsensi[] = [
                    'siswa_id' => $siswaId,
                    'nama_siswa' => $siswa->nama_siswa,
                    'kelas' => $siswa->kelas,
                    'status' => $status,
                ];
            }
        }

        Absensi::create([
            'id_sekolah' => $sekolah->id,
            'id_periode' => $periodeAktif->id,
            'jenis_absensi' => 'siswa',
            'tanggal' => now(),
            'jumlah_hadir' => $jumlahHadir,
            'jumlah_sakit' => $jumlahSakit,
            'jumlah_izin' => $jumlahIzin,
            'jumlah_alpha' => $jumlahAlpha,
            'total_siswa' => $totalSiswa,
            'keterangan' => $request->keterangan,
            'detail_absensi' => $detailAbsensi,
            'foto' => $fotoPath,
            'diinput_oleh' => Auth::id(),
            'status_validasi' => 'pending',
        ]);

        return redirect()->route('operator.absensi.history')
            ->with('success', 'Absensi siswa berhasil dikirim dan menunggu validasi.');
    }

    public function absensiGuru()
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        if (!$periodeAktif) {
            return redirect()->route('operator.dashboard')->with('error', 'Belum ada periode ajaran aktif.');
        }

        // Ambil semua guru
        $guruList = Guru::where('id_sekolah', $sekolah->id)
            ->where('status', 'aktif')
            ->orderBy('nama_guru')
            ->get();

        // Cek apakah sudah input hari ini
        $sudahInput = Absensi::where('id_sekolah', $sekolah->id)
            ->where('id_periode', $periodeAktif->id)
            ->where('jenis_absensi', 'guru')
            ->whereDate('tanggal', now())
            ->exists();

        return view('operator.absensi.guru', compact('sekolah', 'periodeAktif', 'guruList', 'sudahInput'));
    }

    public function absensiGuruStore(Request $request)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $periodeAktif = PeriodeAjaran::where('is_active', true)->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Belum ada periode ajaran aktif.');
        }

        // Validasi
        $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'in:hadir,izin,alpha',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek duplikasi
        $exists = Absensi::where('id_sekolah', $sekolah->id)
            ->where('id_periode', $periodeAktif->id)
            ->where('jenis_absensi', 'guru')
            ->whereDate('tanggal', now())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah menginput absensi guru hari ini.');
        }

        // Hitung statistik
        $kehadiran = $request->kehadiran;
        $jumlahHadir = count(array_keys($kehadiran, 'hadir'));
        $jumlahIzin = count(array_keys($kehadiran, 'izin'));
        $jumlahAlpha = count(array_keys($kehadiran, 'alpha'));
        $totalGuru = count($kehadiran);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('absensi', 'public');
        }

        // Simpan detail absensi
        $detailAbsensi = [];
        foreach ($kehadiran as $guruId => $status) {
            $guru = Guru::find($guruId);
            if ($guru) {
                $detailAbsensi[] = [
                    'guru_id' => $guruId,
                    'nama_guru' => $guru->nama_guru,
                    'bidang_studi' => $guru->bidang_studi,
                    'status' => $status,
                ];
            }
        }

        Absensi::create([
            'id_sekolah' => $sekolah->id,
            'id_periode' => $periodeAktif->id,
            'jenis_absensi' => 'guru',
            'tanggal' => now(),
            'jumlah_hadir' => $jumlahHadir,
            'jumlah_sakit' => 0,
            'jumlah_izin' => $jumlahIzin,
            'jumlah_alpha' => $jumlahAlpha,
            'total_siswa' => $totalGuru,
            'keterangan' => $request->keterangan,
            'detail_absensi' => $detailAbsensi,
            'foto' => $fotoPath,
            'diinput_oleh' => Auth::id(),
            'status_validasi' => 'pending',
        ]);

        return redirect()->route('operator.absensi.history')
            ->with('success', 'Absensi guru berhasil dikirim dan menunggu validasi.');
    }

    private function getSekolahTugas()
    {
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        return $assignment ? Sekolah::find($assignment->target_id) : null;
    }
}
