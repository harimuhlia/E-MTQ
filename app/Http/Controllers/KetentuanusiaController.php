<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Ketentuanusia;
use App\Models\Nomorcabang;
use Illuminate\Http\Request;

class KetentuanUsiaController extends Controller
{
    public function index()
    {
        $ketentuanUsias = KetentuanUsia::with('cabang', 'golongan')->get();
        return view('ketentuan_usia.index', compact('ketentuanUsias'));
    }

    public function create()
    {
        $cabangs = Nomorcabang::all();
        return view('ketentuan_usia.create', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'batas_usia' => 'required|integer|min:0',
        ]);

        KetentuanUsia::create($request->all());
        return redirect()->route('ketentuanusia.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function show($slug)
    {
        $ketentuanUsia = KetentuanUsia::where('slug', $slug)->firstOrFail();
        return view('ketentuanusia.show', compact('ketentuanUsia'));
    }

    public function edit($slug)
    {
        $ketentuanUsia = KetentuanUsia::where('slug', $slug)->firstOrFail();
        $cabangs = Nomorcabang::all();
        $golongans = Golongan::where('cabang_id', $ketentuanUsia->cabang_id)->get();

        return view('ketentuanusia.edit', compact('ketentuanUsia', 'cabangs', 'golongans'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'batas_usia' => 'required|integer|min:0',
        ]);

        $ketentuanUsia = KetentuanUsia::where('slug', $slug)->firstOrFail();
        $ketentuanUsia->update($request->all());

        return redirect()->route('ketentuanusia.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($slug)
    {
        $ketentuanUsia = KetentuanUsia::where('slug', $slug)->firstOrFail();
        $ketentuanUsia->delete();

        return redirect()->route('ketentuanusia.index')->with('success', 'Data berhasil dihapus');
    }
}
