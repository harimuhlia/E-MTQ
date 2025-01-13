<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    // Menampilkan semua data cabang
    public function index()
    {
        $cabang = Cabang::all();
        return view('cabang.index', compact('cabang'));
    }

    // Menampilkan form untuk membuat cabang baru
    public function create()
    {
        return view('cabang.create');
    }

    // Menyimpan data cabang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Cabang::create($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan');
    }

    // Menampilkan detail cabang
    public function show(Cabang $cabang)
    {
        return view('cabang.show', compact('cabang'));
    }

    // Menampilkan form untuk mengedit cabang
    public function edit(Cabang $cabang)
    {
        return view('cabang.edit', compact('cabang'));
    }

    // Mengupdate data cabang
    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $cabang->update($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diupdate');
    }

    // Menghapus cabang
    public function destroy(Cabang $cabang)
    {
        $cabang->delete();
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus');
    }
}
