<?php

namespace App\Http\Controllers;

use App\Models\Tahunevent;
use Illuminate\Http\Request;

class TahuneventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahunevents = Tahunevent::all();
        return view("tahun_event.index", compact('tahunevents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tahun_event.create');
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
            'tahun_event' => 'required|integer',
        ]);

        $slug = Tahunevent::generateSlug($request->tahun_event);

        Tahunevent::create([
            'tahun_event' => $request->tahun_event,
            'slug' => $slug,
        ]);
        return redirect()->route('tahunevent.index')->with('success', 'Tahun Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $tahunevents = Tahunevent::where('slug', $slug)->firstOrFail();
        return view('tahun_event.show', compact('tahunevents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $tahunevents = Tahunevent::where('slug', $slug)->firstOrFail();
        return view('tahun_event.edit', compact('tahunevents'));
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
            'tahun_event' => 'required|integer',
        ]);

        $tahunevents = Tahunevent::where('slug', $slug)->firstOrFail();
        $slug = Tahunevent::generateSlug($request->tahun_event);

        $tahunevents->update([
            'tahun_event' => $request->tahun_event,
            'slug' => $slug,
        ]);

        return redirect()->route('tahunevent.index')->with('success', 'Data berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $tahunevents = Tahunevent::where('slug', $slug)->firstOrFail();
        $tahunevents->delete();

        return redirect()->route('tahunevent.index')->with('success', 'Data berhasil dihapus!');
    }

}
