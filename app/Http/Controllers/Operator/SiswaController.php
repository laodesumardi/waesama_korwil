<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    private function getSekolahTugas()
    {
        $assignment = UserAssignment::where('user_id', Auth::id())
            ->where('target_type', 'sekolah')
            ->first();

        return $assignment ? Sekolah::find($assignment->target_id) : null;
    }

    public function index(Request $request)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $query = Siswa::where('id_sekolah', $sekolah->id);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                    ->orWhere('nisn', 'like', '%' . $request->search . '%')
                    ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswa = $query->orderBy('kelas')->orderBy('nama_siswa')->paginate(15)->withQueryString();

        // Data untuk filter
        $kelasList = Siswa::where('id_sekolah', $sekolah->id)->distinct()->pluck('kelas');
        $statusOptions = ['aktif', 'pindah', 'keluar', 'lulus'];

        return view('operator.siswa.index', compact('siswa', 'sekolah', 'kelasList', 'statusOptions'));
    }

    public function create()
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        return view('operator.siswa.create', compact('sekolah'));
    }

    public function store(Request $request)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $request->validate([
            'nisn' => 'required|string|max:20|unique:siswa,nisn',
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'kelas' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:aktif,pindah,keluar,lulus',
            'keterangan' => 'nullable|string',
        ]);

        Siswa::create([
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_sekolah' => $sekolah->id,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('operator.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $siswa = Siswa::where('id_sekolah', $sekolah->id)->findOrFail($id);
        return view('operator.siswa.show', compact('siswa', 'sekolah'));
    }

    public function edit($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $siswa = Siswa::where('id_sekolah', $sekolah->id)->findOrFail($id);
        return view('operator.siswa.edit', compact('siswa', 'sekolah'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $siswa = Siswa::where('id_sekolah', $sekolah->id)->findOrFail($id);

        $request->validate([
            'nisn' => ['required', 'string', 'max:20', Rule::unique('siswa')->ignore($siswa->id)],
            'nis' => ['required', 'string', 'max:20', Rule::unique('siswa')->ignore($siswa->id)],
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'kelas' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'status' => 'required|in:aktif,pindah,keluar,lulus',
            'keterangan' => 'nullable|string',
        ]);

        $siswa->update($request->all());

        return redirect()->route('operator.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $siswa = Siswa::where('id_sekolah', $sekolah->id)->findOrFail($id);
        $siswa->delete();

        return redirect()->route('operator.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $siswa = Siswa::where('id_sekolah', $sekolah->id)->findOrFail($id);

        $newStatus = match ($siswa->status) {
            'aktif' => 'keluar',
            'keluar' => 'aktif',
            'pindah' => 'aktif',
            'lulus' => 'aktif',
            default => 'aktif'
        };

        $siswa->update(['status' => $newStatus]);

        return redirect()->route('operator.siswa.index')
            ->with('success', 'Status siswa berhasil diubah.');
    }
}
