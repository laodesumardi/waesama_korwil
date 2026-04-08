<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Korwil;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class KorwilController extends Controller
{
    public function index(Request $request)
    {
        $query = Korwil::with(['user', 'sekolah']);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_korwil', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_wilayah', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->whereHas('user', function ($q) {
                    $q->where('is_active', true);
                });
            } elseif ($request->status == 'nonaktif') {
                $query->whereHas('user', function ($q) {
                    $q->where('is_active', false);
                });
            }
        }

        $korwils = $query->latest()->paginate(10)->withQueryString();

        return view('admin.korwil.index', compact('korwils'));
    }

    public function create()
    {
        // Ambil user yang belum memiliki korwil dan role-nya korwil
        $existingUsers = User::where('role', 'korwil')
            ->whereDoesntHave('korwil')
            ->get();

        return view('admin.korwil.create', compact('existingUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Jika pilih user existing
            'user_id' => 'nullable|exists:users,id|unique:korwil,user_id',
            // Jika buat user baru
            'new_user_name' => 'required_without:user_id|string|max:50',
            'new_user_email' => 'required_without:user_id|email|unique:users,email',
            'new_user_password' => 'required_without:user_id|string|min:6|confirmed',
            // Data korwil
            'kode_wilayah' => 'required|string|max:20|unique:korwil,kode_wilayah',
            'nama_korwil' => 'required|string|max:100',
            'wilayah_kerja' => 'required|string',
            'no_sk' => 'nullable|string|max:100',
        ]);

        $userId = null;

        // Jika memilih user existing
        if ($request->filled('user_id')) {
            $userId = $request->user_id;
        }
        // Jika membuat user baru
        elseif ($request->filled('new_user_name')) {
            $user = User::create([
                'name' => $request->new_user_name,
                'email' => $request->new_user_email,
                'password' => Hash::make($request->new_user_password),
                'role' => 'korwil',
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        // Create Korwil
        $korwil = Korwil::create([
            'user_id' => $userId,
            'kode_wilayah' => $request->kode_wilayah,
            'nama_korwil' => $request->nama_korwil,
            'wilayah_kerja' => $request->wilayah_kerja,
            'no_sk' => $request->no_sk,
        ]);

        // Update user assignment
        UserAssignment::updateOrCreate(
            ['user_id' => $userId, 'target_type' => 'korwil'],
            ['target_id' => $korwil->id]
        );

        return redirect()->route('admin.korwil.index')
            ->with('success', 'Korwil berhasil ditambahkan.');
    }

    public function show($id)
    {
        $korwil = Korwil::with(['user', 'sekolah'])->findOrFail($id);
        $sekolahList = Sekolah::where('id_korwil', $id)->paginate(10);

        return view('admin.korwil.show', compact('korwil', 'sekolahList'));
    }

    public function edit($id)
    {
        $korwil = Korwil::findOrFail($id);
        $existingUsers = User::where('role', 'korwil')
            ->where(function ($q) use ($korwil) {
                $q->whereDoesntHave('korwil')
                    ->orWhere('id', $korwil->user_id);
            })
            ->get();

        return view('admin.korwil.edit', compact('korwil', 'existingUsers'));
    }

    public function update(Request $request, $id)
    {
        $korwil = Korwil::findOrFail($id);

        $request->validate([
            'user_id' => ['required', 'exists:users,id', Rule::unique('korwil', 'user_id')->ignore($korwil->id)],
            'kode_wilayah' => ['required', 'string', 'max:20', Rule::unique('korwil', 'kode_wilayah')->ignore($korwil->id)],
            'nama_korwil' => 'required|string|max:100',
            'wilayah_kerja' => 'required|string',
            'no_sk' => 'nullable|string|max:100',
        ]);

        // Update korwil
        $korwil->update([
            'user_id' => $request->user_id,
            'kode_wilayah' => $request->kode_wilayah,
            'nama_korwil' => $request->nama_korwil,
            'wilayah_kerja' => $request->wilayah_kerja,
            'no_sk' => $request->no_sk,
        ]);

        // Update user assignment
        UserAssignment::updateOrCreate(
            ['user_id' => $request->user_id, 'target_type' => 'korwil'],
            ['target_id' => $korwil->id]
        );

        return redirect()->route('admin.korwil.index')
            ->with('success', 'Korwil berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $korwil = Korwil::findOrFail($id);

        // Cek apakah korwil memiliki sekolah
        if ($korwil->sekolah()->count() > 0) {
            return redirect()->route('admin.korwil.index')
                ->with('error', 'Korwil tidak dapat dihapus karena masih memiliki data sekolah.');
        }

        // Hapus assignment
        UserAssignment::where('user_id', $korwil->user_id)
            ->where('target_type', 'korwil')
            ->delete();

        $korwil->delete();

        return redirect()->route('admin.korwil.index')
            ->with('success', 'Korwil berhasil dihapus.');
    }
}
