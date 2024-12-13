<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Nomorcabang;
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
        $golongans = Golongan::with('nomorcabang')->get();
        return view('golongan.index', compact('golongans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nomorcabangs = Nomorcabang::all();
        return view('golongan.create', compact('nomorcabangs'));
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
            'nama_golongan' => ['required', 'string', 'max:255', 'unique:golongans'],
            'nomorcabang_id' => 'required',
        ]);

        $slug = Golongan::generateSlug($request->nama_golongan);

        Golongan::create([
            'nama_golongan' => $request->nama_golongan,
            'nomorcabang_id' => $request->nomorcabang_id,
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
        $nomorcabangs = Nomorcabang::all();
        return view('golongan.edit', compact('golongans', 'nomorcabangs'));
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
            'nama_golongan' => ['required', 'string', 'max:255', 'unique:golongans'],
            'nomorcabang_id' => 'required',
        ]);

        $golongans = Golongan::where('slug', $slug)->firstOrFail();
        $slug = Golongan::generateSlug($request->nama_golongan);

        $golongans->update([
            'nama_golongan' => $request->nama_golongan,
            'nomorcabang_id' => $request->nomorcabang_id,
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
