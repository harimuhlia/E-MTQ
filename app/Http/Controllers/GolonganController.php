<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    // Menampilkan semua data golongan untuk event terpilih
    public function index()
    {
        $selectedId = session('selected_event_id');
        if ($selectedId) {
            // Ambil golongan yang terkait dengan cabang dari event terpilih
            $golongan = Golongan::whereHas('cabang', function ($query) use ($selectedId) {
                $query->where('detail_event_id', $selectedId);
            })->with('cabang')->get();
        } else {
            $golongan = collect();
        }
        return view('golongan.index', compact('golongan'));
    }

    // Menampilkan form untuk membuat golongan baru
    public function create()
    {
        $selectedId = session('selected_event_id');
        if (!$selectedId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah golongan.');
        }
        // Ambil cabang-cabang yang terkait dengan event terpilih
        $cabangs = Cabang::where('detail_event_id', $selectedId)->get();
        return view('golongan.create', compact('cabangs'));
    }

    // Menyimpan data golongan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'cabang_id' => 'required|exists:cabangs,id',
            'max_usia' => 'nullable|integer|min:0',
        ]);

        Golongan::create([
            'nama' => $request->nama,
            'cabang_id' => $request->cabang_id,
            'max_usia' => $request->max_usia,
        ]);

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
        $selectedId = session('selected_event_id');
        // Ambil cabang-cabang yang terkait dengan event terpilih
        $cabangs = Cabang::where('detail_event_id', $selectedId)->get();
        return view('golongan.edit', compact('golongan', 'cabangs'));
    }

    // Mengupdate data golongan
    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'cabang_id' => 'required|exists:cabangs,id',
            'max_usia' => 'nullable|integer|min:0',
        ]);

        $golongan->update([
            'nama' => $request->nama,
            'cabang_id' => $request->cabang_id,
            'max_usia' => $request->max_usia,
        ]);

        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil diupdate');
    }

    // Menghapus golongan
    public function destroy(Golongan $golongan)
    {
        $golongan->delete();
        return redirect()->route('golongan.index')->with('success', 'Golongan berhasil dihapus');
    }

    /**
     * Kembalikan daftar golongan berdasarkan cabang yang diberikan dalam format JSON.
     *
     * Digunakan untuk AJAX ketika memilih cabang pada form pendaftaran peserta.
     *
     * @param  int  $cabang_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGolonganByCabang($cabang_id)
    {
        $golongans = Golongan::where('cabang_id', $cabang_id)
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();
        return response()->json($golongans);
    }
}