<?php

namespace App\Http\Controllers;

use App\Models\Nomorcabang;
use Illuminate\Http\Request;

class NomorcabangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nomorcabangs = Nomorcabang::all();
        return view("cabang.index", compact('nomorcabangs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cabang.create');
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
            'kode_cabang' => ['required', 'string', 'max:255', 'unique:nomorcabangs'],
            'nama_cabang' => ['required', 'string', 'max:255', 'unique:nomorcabangs'],
        ]);

        $slug = Nomorcabang::generateSlug($request->nama_cabang);

        Nomorcabang::create([
            'kode_cabang' => $request->kode_cabang,
            'nama_cabang' => $request->nama_cabang,
            'slug' => $slug,
        ]);
        return redirect()->route('nomorcabang.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $nomorcabangs = Nomorcabang::where('slug', $slug)->firstOrFail();
        return view('cabang.show', compact('nomorcabangs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $nomorcabangs = Nomorcabang::where('slug', $slug)->firstOrFail();
        return view('cabang.edit', compact('nomorcabangs'));
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
            'kode_cabang' => ['required', 'string', 'max:255', 'unique:nomorcabangs'],
            'nama_cabang' => ['required', 'string', 'max:255', 'unique:nomorcabangs'],
        ]);

        $nomorcabangs = Nomorcabang::where('slug', $slug)->firstOrFail();
        $slug = Nomorcabang::generateSlug($request->nama_cabang);

        $nomorcabangs->update([
            'kode_cabang' => $request->kode_cabang,
            'nama_cabang' => $request->nama_cabang,
            'slug' => $slug,
        ]);

        return redirect()->route('nomorcabang.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $nomorcabangs = Nomorcabang::where('slug', $slug)->firstOrFail();
        $nomorcabangs->delete();

        return redirect()->route('nomorcabang.index')->with('success', 'Data berhasil dihapus!');
    }
}
