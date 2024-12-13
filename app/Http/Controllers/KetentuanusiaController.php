<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use App\Models\Ketentuanusia;
use App\Models\Nomorcabang;
use Illuminate\Http\Request;

class KetentuanusiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ketentuanusias = Ketentuanusia::with(['cabang', 'golongan'])->get();
        return view('ketentuan_usia.index', compact('ketentuanusias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cabangs = Nomorcabang::all();
        $golongans = Golongan::all();

        return view('ketentuan_usia.create', compact('cabangs', 'golongans'));
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
            'usia_minimal' => 'required|string',
            'usia_maksimal' => 'required|string',
            'cabang_id' => 'required|exists:nomorcabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
        ]);

        $slug = Ketentuanusia::generateUniqueSlug($request->usia_minimal);

        Ketentuanusia::create([
            'usia_minimal'  => $request->usia_minimal,
            'usia_maksimal' => $request->usia_maksimal,
            'cabang_id'     => $request->cabang_id,
            'golongan_id'   => $request->golongan_id,
            'slug'          => $slug,
        ]);
        return redirect()->route('ketentuanusia.index')->with('success', 'Ketentuan usia berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
