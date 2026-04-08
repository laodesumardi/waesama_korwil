<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\UserAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GuruController extends Controller
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

        $query = Guru::where('id_sekolah', $sekolah->id);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_guru', 'like', '%' . $request->search . '%')
                    ->orWhere('nip', 'like', '%' . $request->search . '%')
                    ->orWhere('nuptk', 'like', '%' . $request->search . '%');
            });
        }

        // Filter bidang studi
        if ($request->filled('bidang_studi')) {
            $query->where('bidang_studi', $request->bidang_studi);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $guru = $query->orderBy('nama_guru')->paginate(15)->withQueryString();

        // Data untuk filter
        $bidangStudiList = Guru::where('id_sekolah', $sekolah->id)->distinct()->pluck('bidang_studi');
        $statusOptions = ['aktif', 'nonaktif', 'pindah', 'pensiun'];

        return view('operator.guru.index', compact('guru', 'sekolah', 'bidangStudiList', 'statusOptions'));
    }

    public function create()
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        return view('operator.guru.create', compact('sekolah'));
    }

    public function store(Request $request)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $request->validate([
            'nip' => 'required|string|max:20|unique:guru,nip',
            'nuptk' => 'nullable|string|max:20|unique:guru,nuptk',
            'nama_guru' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'pendidikan_terakhir' => 'required|string|max:50',
            'bidang_studi' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|unique:guru,email',
            'status' => 'required|in:aktif,nonaktif,pindah,pensiun',
            'keterangan' => 'nullable|string',
        ]);

        Guru::create([
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'nama_guru' => $request->nama_guru,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'bidang_studi' => $request->bidang_studi,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'id_sekolah' => $sekolah->id,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('operator.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $guru = Guru::where('id_sekolah', $sekolah->id)->findOrFail($id);
        return view('operator.guru.show', compact('guru', 'sekolah'));
    }

    public function edit($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->route('operator.dashboard')->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $guru = Guru::where('id_sekolah', $sekolah->id)->findOrFail($id);
        return view('operator.guru.edit', compact('guru', 'sekolah'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $guru = Guru::where('id_sekolah', $sekolah->id)->findOrFail($id);

        $request->validate([
            'nip' => ['required', 'string', 'max:20', Rule::unique('guru')->ignore($guru->id)],
            'nuptk' => ['nullable', 'string', 'max:20', Rule::unique('guru')->ignore($guru->id)],
            'nama_guru' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'pendidikan_terakhir' => 'required|string|max:50',
            'bidang_studi' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'email' => ['nullable', 'email', Rule::unique('guru')->ignore($guru->id)],
            'status' => 'required|in:aktif,nonaktif,pindah,pensiun',
            'keterangan' => 'nullable|string',
        ]);

        $guru->update($request->all());

        return redirect()->route('operator.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $guru = Guru::where('id_sekolah', $sekolah->id)->findOrFail($id);
        $guru->delete();

        return redirect()->route('operator.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $sekolah = $this->getSekolahTugas();

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Anda tidak memiliki tugas sekolah.');
        }

        $guru = Guru::where('id_sekolah', $sekolah->id)->findOrFail($id);

        $newStatus = $guru->status == 'aktif' ? 'nonaktif' : 'aktif';
        $guru->update(['status' => $newStatus]);

        return redirect()->route('operator.guru.index')
            ->with('success', 'Status guru berhasil diubah.');
    }
}
