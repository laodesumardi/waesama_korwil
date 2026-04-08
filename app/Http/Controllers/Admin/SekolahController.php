<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Korwil;
use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::with(['korwil', 'korwil.user']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_sekolah', 'like', '%' . $request->search . '%')
                    ->orWhere('npsn', 'like', '%' . $request->search . '%')
                    ->orWhere('kecamatan', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter korwil
        if ($request->filled('id_korwil')) {
            $query->where('id_korwil', $request->id_korwil);
        }

        $sekolahs = $query->latest()->paginate(10)->withQueryString();

        // Untuk filter dropdown
        $korwilList = Korwil::with('user')->get();
        $statusOptions = ['aktif', 'nonaktif'];

        return view('admin.sekolah.index', compact('sekolahs', 'korwilList', 'statusOptions'));
    }

    public function create()
    {
        $korwilList = Korwil::with('user')->get();
        $statusOptions = ['aktif', 'nonaktif'];

        return view('admin.sekolah.create', compact('korwilList', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npsn' => 'required|string|max:20|unique:sekolah,npsn',
            'nama_sekolah' => 'required|string|max:200',
            'alamat' => 'required|string',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'id_korwil' => 'required|exists:korwil,id',
            'status' => 'required|in:aktif,nonaktif',
            'nama_kepala_sekolah' => 'nullable|string|max:100',      // Tambahkan
            'no_telp_kepala_sekolah' => 'nullable|string|max:20',    // Tambahkan
        ]);

        $sekolah = Sekolah::create($request->all());

        return redirect()->route('admin.sekolah.index')
            ->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sekolah = Sekolah::with(['korwil', 'korwil.user', 'absensi' => function ($q) {
            $q->latest()->limit(10);
        }])->findOrFail($id);

        // Statistik absensi
        $totalAbsensi = $sekolah->absensi()->count();
        $rataHadir = $sekolah->absensi()->avg('jumlah_hadir') ?? 0;

        return view('admin.sekolah.show', compact('sekolah', 'totalAbsensi', 'rataHadir'));
    }

    public function edit($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $korwilList = Korwil::with('user')->get();
        $statusOptions = ['aktif', 'nonaktif'];

        return view('admin.sekolah.edit', compact('sekolah', 'korwilList', 'statusOptions'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $request->validate([
            'npsn' => ['required', 'string', 'max:20', Rule::unique('sekolah', 'npsn')->ignore($sekolah->id)],
            'nama_sekolah' => 'required|string|max:200',
            'alamat' => 'required|string',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'id_korwil' => 'required|exists:korwil,id',
            'status' => 'required|in:aktif,nonaktif',
            'nama_kepala_sekolah' => 'nullable|string|max:100',      // Tambahkan
            'no_telp_kepala_sekolah' => 'nullable|string|max:20',
        ]);

        $sekolah->update($request->all());

        return redirect()->route('admin.sekolah.index')
            ->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);

        // Cek apakah sekolah memiliki data absensi
        if ($sekolah->absensi()->count() > 0) {
            return redirect()->route('admin.sekolah.index')
                ->with('error', 'Sekolah tidak dapat dihapus karena masih memiliki data absensi.');
        }

        // Hapus assignment yang terkait
        UserAssignment::where('target_type', 'sekolah')
            ->where('target_id', $sekolah->id)
            ->delete();

        $sekolah->delete();

        return redirect()->route('admin.sekolah.index')
            ->with('success', 'Sekolah berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $newStatus = $sekolah->status == 'aktif' ? 'nonaktif' : 'aktif';
        $sekolah->update(['status' => $newStatus]);

        $statusText = $newStatus == 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.sekolah.index')
            ->with('success', "Sekolah berhasil {$statusText}.");
    }
}
