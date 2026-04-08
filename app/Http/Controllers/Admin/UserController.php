<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Korwil;
use App\Models\UserAssignment;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['creator', 'korwil', 'assignments']);

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter status aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'aktif');
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Data untuk filter
        $roles = ['admin_dinas', 'operator_sekolah', 'korwil'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = ['admin_dinas', 'operator_sekolah', 'korwil'];
        $korwilList = Korwil::with('user')->get();
        $sekolahList = Sekolah::with('korwil')->where('status', 'aktif')->get();

        return view('admin.users.create', compact('roles', 'korwilList', 'sekolahList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin_dinas,operator_sekolah,korwil',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
            'assignment_sekolah_id' => 'required_if:role,operator_sekolah|nullable|exists:sekolah,id',
            'assignment_korwil_id' => 'required_if:role,korwil|nullable|exists:korwil,id',
        ]);

        // Upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => $fotoPath,
            'role' => $request->role,
            'created_by' => Auth::id(),
            'is_active' => $request->has('is_active') && $request->is_active == '1' ? true : false, // Perbaikan ini
        ]);

        // Handle assignment berdasarkan role
        if ($request->role == 'operator_sekolah' && $request->assignment_sekolah_id) {
            UserAssignment::create([
                'user_id' => $user->id,
                'target_type' => 'sekolah',
                'target_id' => $request->assignment_sekolah_id,
            ]);
        }

        if ($request->role == 'korwil') {
            // Update atau create korwil
            Korwil::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'kode_wilayah' => $request->kode_wilayah ?? 'KW-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'nama_korwil' => $request->name,
                    'wilayah_kerja' => $request->wilayah_kerja ?? '',
                    'no_sk' => $request->no_sk ?? '',
                ]
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::with(['creator', 'korwil', 'assignments'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with(['assignments', 'korwil'])->findOrFail($id);
        $roles = ['admin_dinas', 'operator_sekolah', 'korwil'];
        $korwilList = Korwil::with('user')->get();
        $sekolahList = Sekolah::with('korwil')->where('status', 'aktif')->get();

        // Get current assignments
        $currentAssignment = $user->assignments->first();
        $currentSekolahId = null;
        $currentKorwilId = null;

        if ($currentAssignment) {
            if ($currentAssignment->target_type == 'sekolah') {
                $currentSekolahId = $currentAssignment->target_id;
            } elseif ($currentAssignment->target_type == 'korwil') {
                $currentKorwilId = $currentAssignment->target_id;
            }
        }

        return view('admin.users.edit', compact('user', 'roles', 'korwilList', 'sekolahList', 'currentSekolahId', 'currentKorwilId'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin_dinas,operator_sekolah,korwil',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
            'assignment_sekolah_id' => 'required_if:role,operator_sekolah|nullable|exists:sekolah,id',
            'assignment_korwil_id' => 'required_if:role,korwil|nullable|exists:korwil,id',
        ]);

        // Update foto
        $fotoPath = $user->foto;
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'foto' => $fotoPath,
            'role' => $request->role,
            'is_active' => $request->has('is_active') && $request->is_active == '1' ? true : false, // Perbaikan ini
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Update assignment untuk operator sekolah
        if ($request->role == 'operator_sekolah') {
            UserAssignment::where('user_id', $user->id)->delete();
            if ($request->assignment_sekolah_id) {
                UserAssignment::create([
                    'user_id' => $user->id,
                    'target_type' => 'sekolah',
                    'target_id' => $request->assignment_sekolah_id,
                ]);
            }
        }
        // Update untuk korwil
        elseif ($request->role == 'korwil') {
            Korwil::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'kode_wilayah' => $request->kode_wilayah ?? 'KW-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                    'nama_korwil' => $request->name,
                    'wilayah_kerja' => $request->wilayah_kerja ?? '',
                    'no_sk' => $request->no_sk ?? '',
                ]
            );
        } else {
            // Jika role berubah dari korwil ke yang lain, hapus data korwil
            if ($user->korwil) {
                $user->korwil->delete();
            }
            UserAssignment::where('user_id', $user->id)->delete();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Cek apakah user memiliki relasi data
        if ($user->korwil && $user->korwil->sekolah()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih memiliki data sekolah terkait.');
        }

        if ($user->assignments()->exists()) {
            $user->assignments()->delete();
        }

        if ($user->korwil) {
            $user->korwil->delete();
        }

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users.index')
            ->with('success', "User berhasil {$status}.");
    }

    public function updateAvatar(Request $request)
    {
        // Buat manager dengan driver GD secara eksplisit
        $manager = new ImageManager(new Driver());

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            // Baca gambar, resize, dan simpan
            $image = $manager->read($file);
            $image->resize(300, 300);
            $image->save(public_path('uploads/avatars/' . $filename));

            // Simpan nama file ke database...
        }
    }
}
