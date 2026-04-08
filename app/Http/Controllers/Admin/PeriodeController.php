<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeriodeController extends Controller
{
    public function index(Request $request)
    {
        $query = PeriodeAjaran::query();

        // Search
        if ($request->filled('search')) {
            $query->where('tahun_ajaran', 'like', '%' . $request->search . '%');
        }

        // Filter semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter status aktif
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        $periode = $query->latest()->paginate(10)->withQueryString();

        $semesterOptions = [1 => 'Ganjil', 2 => 'Genap'];

        return view('admin.periode.index', compact('periode', 'semesterOptions'));
    }

    public function create()
    {
        $semesterOptions = [1 => 'Ganjil', 2 => 'Genap'];
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        // Generate tahun ajaran options
        $tahunAjaranOptions = [];
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahunAjaranOptions[] = $year . '/' . ($year + 1);
        }

        return view('admin.periode.create', compact('semesterOptions', 'tahunAjaranOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string|max:20|unique:periode_ajaran,tahun_ajaran',
            'semester' => 'required|in:1,2',
            'is_active' => 'boolean',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $periode = PeriodeAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'is_active' => $request->boolean('is_active', false),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        $message = 'Periode ajaran berhasil ditambahkan.';
        if ($periode->is_active) {
            $message .= ' Periode ini ditetapkan sebagai periode aktif.';
        }

        return redirect()->route('admin.periode.index')
            ->with('success', $message);
    }

    public function show($id)
    {
        $periode = PeriodeAjaran::with(['absensi' => function ($q) {
            $q->latest()->limit(10);
        }])->findOrFail($id);

        // Statistik absensi dalam periode ini
        $totalAbsensi = $periode->absensi()->count();
        $totalHadir = $periode->absensi()->sum('jumlah_hadir');
        $totalSakit = $periode->absensi()->sum('jumlah_sakit');
        $totalIzin = $periode->absensi()->sum('jumlah_izin');
        $totalAlpha = $periode->absensi()->sum('jumlah_alpha');

        return view('admin.periode.show', compact('periode', 'totalAbsensi', 'totalHadir', 'totalSakit', 'totalIzin', 'totalAlpha'));
    }

    public function edit($id)
    {
        $periode = PeriodeAjaran::findOrFail($id);
        $semesterOptions = [1 => 'Ganjil', 2 => 'Genap'];
        $currentYear = date('Y');

        // Generate tahun ajaran options
        $tahunAjaranOptions = [];
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahunAjaranOptions[] = $year . '/' . ($year + 1);
        }

        return view('admin.periode.edit', compact('periode', 'semesterOptions', 'tahunAjaranOptions'));
    }

    public function update(Request $request, $id)
    {
        $periode = PeriodeAjaran::findOrFail($id);

        $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20', Rule::unique('periode_ajaran', 'tahun_ajaran')->ignore($periode->id)],
            'semester' => 'required|in:1,2',
            'is_active' => 'boolean',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $periode->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester,
            'is_active' => $request->boolean('is_active', false),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        $message = 'Periode ajaran berhasil diperbarui.';
        if ($periode->is_active) {
            $message .= ' Periode ini ditetapkan sebagai periode aktif.';
        }

        return redirect()->route('admin.periode.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $periode = PeriodeAjaran::findOrFail($id);

        // Cek apakah periode memiliki data absensi
        if ($periode->absensi()->count() > 0) {
            return redirect()->route('admin.periode.index')
                ->with('error', 'Periode ajaran tidak dapat dihapus karena masih memiliki data absensi.');
        }

        $periode->delete();

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode ajaran berhasil dihapus.');
    }

    public function setActive($id)
    {
        $periode = PeriodeAjaran::findOrFail($id);

        // Set semua periode menjadi tidak aktif
        PeriodeAjaran::where('is_active', true)->update(['is_active' => false]);

        // Set periode ini menjadi aktif
        $periode->update(['is_active' => true]);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode ajaran "' . $periode->label . '" ditetapkan sebagai periode aktif.');
    }
}
