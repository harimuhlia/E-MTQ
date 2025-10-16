<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Golongan;
use App\Models\KetentuanUsia;
use Illuminate\Http\Request;

class KetentuanUsiaController extends Controller
{
    public function index()
    {
        $ketentuanusias = KetentuanUsia::with(['cabang', 'golongan'])->get();
        return view('ketentuan_usia.index', compact('ketentuanusias'));
    }

    public function create()
    {
        $cabangs = Cabang::all();
        return view('ketentuan_usia.create', compact('cabangs'));
    }

    public function show(KetentuanUsia $ketentuanusium)
    {
        return view('ketentuan_usia.show', compact('ketentuanusium'));
    }

    public function edit(KetentuanUsia $ketentuanusium)
    {
        $cabangs = Cabang::all();
        $golongans = Golongan::where('cabang_id', $ketentuanusium->cabang_id)->get();
        return view('ketentuan_usia.edit', compact('ketentuanusium', 'cabangs', 'golongans'));
    }

    public function store(Request $request)
{
    $request->validate([
        'cabang_id' => 'required|exists:cabangs,id',
        'golongan_id' => 'required|exists:golongans,id',
        'min_usia' => 'required|date',
        'max_usia' => 'required|date|after_or_equal:min_usia',
    ]);

    // 🔍 Cek apakah kombinasi cabang_id + golongan_id sudah ada
    $exists = KetentuanUsia::where('cabang_id', $request->cabang_id)
        ->where('golongan_id', $request->golongan_id)
        ->exists();

    if ($exists) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['golongan_id' => 'Data dengan Cabang dan Golongan ini sudah ada!']);
    }

    KetentuanUsia::create([
        'cabang_id' => $request->cabang_id,
        'golongan_id' => $request->golongan_id,
        'min_usia' => $request->min_usia,
        'max_usia' => $request->max_usia,
    ]);

    return redirect()->route('ketentuanusia.index')->with('success', 'Data Ketentuan Usia berhasil ditambahkan.');
    }


    public function update(Request $request, KetentuanUsia $ketentuanusium)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'min_usia' => 'required|date',
            'max_usia' => 'required|date|after_or_equal:min_usia',
        ]);

        // 🔍 Cek duplikasi, tapi abaikan diri sendiri (pakai where id !=)
        $exists = KetentuanUsia::where('cabang_id', $request->cabang_id)
            ->where('golongan_id', $request->golongan_id)
            ->where('id', '!=', $ketentuanusium->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['golongan_id' => 'Data dengan Cabang dan Golongan ini sudah ada!']);
        }

        $ketentuanusium->update([
            'cabang_id' => $request->cabang_id,
            'golongan_id' => $request->golongan_id,
            'min_usia' => $request->min_usia,
            'max_usia' => $request->max_usia,
        ]);

        return redirect()->route('ketentuanusia.index')->with('success', 'Data Ketentuan Usia berhasil diupdate.');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'cabang_id' => 'required|exists:cabangs,id',
    //         'golongan_id' => 'required|exists:golongans,id',
    //         'min_usia' => 'required|date',
    //         'max_usia' => 'required|date|after_or_equal:min_usia',
    //     ]);

    //     KetentuanUsia::create($request->all());

    //     return redirect()->route('ketentuanusia.index')->with('success', 'Data Ketentuan Usia berhasil ditambahkan.');
    // }

    // public function update(Request $request, KetentuanUsia $ketentuanusium)
    // {
    //     $request->validate([
    //         'cabang_id' => 'required|exists:cabangs,id',
    //         'golongan_id' => 'required|exists:golongans,id',
    //         'min_usia' => 'required|date',
    //         'max_usia' => 'required|date|after_or_equal:min_usia',
    //     ]);

    //     $ketentuanusium->update($request->all());

    //     return redirect()->route('ketentuanusia.index')->with('success', 'Data Ketentuan Usia berhasil diupdate.');
    // }

    public function destroy(KetentuanUsia $ketentuanusium)
    {
        $ketentuanusium->delete();
        return redirect()->route('ketentuanusia.index')->with('success', 'Data Ketentuan Usia berhasil dihapus.');
    }

    // untuk AJAX
    public function getGolonganByCabang($cabang_id)
    {
        $golongans = Golongan::where('cabang_id', $cabang_id)->pluck('nama', 'id');
        return response()->json($golongans);
    }
}
