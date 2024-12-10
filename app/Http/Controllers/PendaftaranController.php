<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Nomorcabang;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function create()
    {
        $nomorcabangs = Nomorcabang::all();
        return view('pendaftaran.create', compact('nomorcabangs'));
    }

    public function getGolonganByCabang(Request $request)
    {
        $golongans = Golongan::where('nomorcabang_id', $request->nomorcabang_id)->get();
        return response()->json($golongans);
    }
}
