<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Nomorcabang;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index()
    {
        $golongan = Golongan::with('cabang')->get();
        return view('golongan.index', compact('golongan'));
    }

    public function create()
    {
        $cabang = Nomorcabang::all();
        return view('golongan.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_golongan' => 'required',
            'cabang_id' => 'required',
        ]);

        Golongan::create($request->all());
        return redirect()->route('golongan.index')->with('success', 'Golongan created successfully.');
    }

    public function edit(Golongan $golongan)
    {
        $cabang = Nomorcabang::all();
        return view('golongan.edit', compact('golongan', 'cabang'));
    }

    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'nama_golongan' => 'required',
            'cabang_id' => 'required',
        ]);

        $golongan->update($request->all());
        return redirect()->route('golongan.index')->with('success', 'Golongan updated successfully.');
    }

    public function destroy(Golongan $golongan)
    {
        $golongan->delete();
        return redirect()->route('golongan.index')->with('success', 'Golongan deleted successfully.');
    }

    public function show($slug)
    {
        $golongan = Golongan::where('slug', $slug)->with('cabang')->firstOrFail();
        return view('golongan.show', compact('golongan'));
    }
}
