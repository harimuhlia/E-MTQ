<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Golongan;
use App\Models\KetentuanUsia;
use Illuminate\Http\Request;

class KetentuanUsiaController extends Controller
{
    // Menampilkan semua data ketentuan usia
    public function index()
    {
        $ketentuanUsia = KetentuanUsia::with(['cabang', 'golongan'])->get();
        return view('ketentuan_usia.index', compact('ketentuanUsia'));
    }

    // Menampilkan form untuk membuat ketentuan usia baru
    public function create()
    {
        $cabangs = Cabang::all(); // Ambil semua data cabang
        return view('ketentuan_usia.create', compact('cabangs'));
    }

    // Menyimpan data ketentuan usia baru
    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'min_usia' => 'required',
            'max_usia' => 'required',
        ]);

        KetentuanUsia::create($request->all());

        return redirect()->route('ketentuanusia.index')->with('success', 'Ketentuan Usia berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit ketentuan usia
    // public function edit(KetentuanUsia $ketentuanUsia)
    // {
    //     $cabangs = Cabang::all();
    //     return view('ketentuan_usia.edit', compact('ketentuanUsia', 'cabangs'));
    // }
    public function edit(KetentuanUsia $ket_usia)
{
    $cabangs = Cabang::all();
    $golongans = Golongan::where('cabang_id', $ket_usia->cabang_id)->get();

    return view('ketentuan_usia.edit', compact('ket_usia', 'cabangs', 'golongans'));
}

    // Mengupdate data ketentuan usia
    public function update(Request $request, KetentuanUsia $ket_usia)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'min_usia' => 'required|integer',
            'max_usia' => 'required|integer',
        ]);

        $ket_usia->update($request->all());

        return redirect()->route('ketentuan_usia.index')->with('success', 'Ketentuan Usia berhasil diupdate');
    }

    // Menghapus ketentuan usia
    public function destroy(KetentuanUsia $ketentuanUsia)
    {
        $ketentuanUsia->delete();
        return redirect()->route('ketentuan_usia.index')->with('success', 'Ketentuan Usia berhasil dihapus');
    }

    // Menampilkan golongan berdasarkan cabang yang dipilih
    public function getGolonganByCabang($cabangId)
    {
        $golongans = Golongan::where('cabang_id', $cabangId)->get();
        return response()->json($golongans);
    }
}
