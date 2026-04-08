<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('operator.dashboard.index', [
            'totalAbsensi' => Absensi::where('diinput_oleh', $user->id)->count()
        ]);
    }
}
