<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    // Menampilkan semua data golongan
    public function index()
    {
        $golongan = Golongan::with('cabang')->get();
        return view('golongan.index', compact('golongan'));
    }

    // Menampilkan form untuk membuat golongan baru
    public function create()
    {
        $cabangs = Cabang::all(); // Ambil semua data cabang
        return view('golongan.create', compact('cabangs'));
    }

    // Menyimpan data golongan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'cabang_id' => 'required|exists:cabangs,id',
        ]);

        Golongan::create($request->all());

        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil ditambahkan');
    }

    // Menampilkan detail golongan
    public function show(Golongan $golongan)
    {
        return view('golongan.show', compact('golongan'));
    }

    // Menampilkan form untuk mengedit golongan
    public function edit(Golongan $golongan)
    {
        $cabangs = Cabang::all(); // Ambil semua data cabang
        return view('golongan.edit', compact('golongan', 'cabangs'));
    }

    // Mengupdate data golongan
    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'cabang_id' => 'required|exists:cabangs,id',
        ]);

        $golongan->update($request->all());

        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil diupdate');
    }

    // Menghapus golongan
    public function destroy(Golongan $golongan)
    {
        $golongan->delete();
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil dihapus');
    }
}