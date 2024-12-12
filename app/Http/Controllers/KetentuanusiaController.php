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
        $ketentuanusias = Ketentuanusia::all();
        return view('ketentuan_usia.index', compact('ketentuanusias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $nomorcabangs = Nomorcabang::all();
        return view('ketentuan_usia.create', compact('nomorcabangs'));
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
            'usia_minimal' => 'required|date',
            'usia_maksimal' => 'required|date|after_or_equal:usia_minimal',
        ]);

        // Menyimpan data tanggal
        Ketentuanusia::create($request->all());

        return redirect()->route('ketentuanusia.index')->with('success', 'Data berhasil ditambahkan');
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
