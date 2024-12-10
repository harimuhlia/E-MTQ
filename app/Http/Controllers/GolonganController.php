<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $golongans = Golongan::all();
        return view("golongan.index", compact('golongans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('golongan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_golongan' => 'required',
            'nama_golongan' => 'required',
        ]);

        $slug = Golongan::generateSlug($request->nama_golongan);

        Golongan::create([
            'kode_golongan' => $request->kode_golongan,
            'nama_golongan' => $request->nama_golongan,
            'slug' => $slug,
        ]);
        return redirect()->route('golongan.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $golongans = Golongan::where('slug', $slug)->firstOrFail();
        return view('golongan.show', compact('golongans'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $golongans = Golongan::where('slug', $slug)->firstOrFail();
        return view('golongan.edit', compact('golongans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'kode_golongan' => 'required',
            'nama_golongan' => 'required',
        ]);

        $golongans = Golongan::where('slug', $slug)->firstOrFail();
        $slug = Golongan::generateSlug($request->nama_golongan);

        $golongans->update([
            'kode_golongan' => $request->kode_golongan,
            'nama_golongan' => $request->nama_golongan,
            'slug' => $slug,
        ]);

        return redirect()->route('golongan.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $golongans = Golongan::where('slug', $slug)->firstOrFail();
        $golongans->delete();

        return redirect()->route('golongan.index')->with('success', 'Data berhasil dihapus!');
    }
}
