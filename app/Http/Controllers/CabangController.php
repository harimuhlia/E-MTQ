<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    // Menampilkan semua data cabang
    public function index()
    {
        // Muat relasi tahun event untuk menampilkan nama tahun pada tabel
        $cabang = Cabang::with('tahunevent')->get();
        return view('cabang.index', compact('cabang'));
    }

    // Menampilkan form untuk membuat cabang baru
    public function create()
    {
        // Ambil seluruh tahun event untuk pemilihan event saat membuat cabang
        $tahunevents = \App\Models\Tahunevent::all();
        return view('cabang.create', compact('tahunevents'));
    }

    // Menyimpan data cabang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahunevent_id' => 'required|exists:tahunevents,id',
        ]);

        // Hanya input field yang relevan
        Cabang::create([
            'nama' => $request->nama,
            'tahunevent_id' => $request->tahunevent_id,
        ]);

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
        $tahunevents = \App\Models\Tahunevent::all();
        return view('cabang.edit', compact('cabang', 'tahunevents'));
    }

    // Mengupdate data cabang
    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahunevent_id' => 'required|exists:tahunevents,id',
        ]);

        $cabang->update([
            'nama' => $request->nama,
            'tahunevent_id' => $request->tahunevent_id,
        ]);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil diupdate');
    }

    // Menghapus cabang
    public function destroy(Cabang $cabang)
    {
        $cabang->delete();
        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil dihapus');
    }
}
