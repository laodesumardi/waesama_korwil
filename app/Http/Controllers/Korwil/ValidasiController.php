<?php

namespace App\Http\Controllers\Korwil;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    public function pending()
    {
        return view('korwil.validasi.pending', [
            'data' => Absensi::where('status_validasi', 'pending')->get()
        ]);
    }

    public function approve($id)
    {
        $absen = Absensi::findOrFail($id);

        $absen->update([
            'status_validasi' => 'disetujui',
            'divalidasi_oleh' => Auth::id()
        ]);

        return back();
    }

    public function reject($id)
    {
        $absen = Absensi::findOrFail($id);

        $absen->update([
            'status_validasi' => 'ditolak',
            'divalidasi_oleh' => Auth::id()
        ]);

        return back();
    }
}
