<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    // Menampilkan semua data cabang untuk event terpilih
    public function index()
    {
        // Ambil ID event yang dipilih dari sesi. Jika tidak ada, kembalikan koleksi kosong.
        $selectedId = session('selected_event_id');
        if ($selectedId) {
            $cabang = Cabang::where('detail_event_id', $selectedId)->get();
        } else {
            $cabang = collect();
        }
        return view('cabang.index', compact('cabang'));
    }

    // Menampilkan form untuk membuat cabang baru
    public function create()
    {
        // Pastikan event telah dipilih terlebih dahulu
        $selectedId = session('selected_event_id');
        if (!$selectedId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah cabang.');
        }
        return view('cabang.create');
    }

    // Menyimpan data cabang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $selectedId = session('selected_event_id');
        Cabang::create([
            'nama' => $request->nama,
            'detail_event_id' => $selectedId,
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
        return view('cabang.edit', compact('cabang'));
    }

    // Mengupdate data cabang
    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $cabang->update([
            'nama' => $request->nama,
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
