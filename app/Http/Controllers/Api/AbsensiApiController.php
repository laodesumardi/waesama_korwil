<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;

class AbsensiApiController extends Controller
{
    public function index()
    {
        return response()->json(Absensi::all());
    }
}
