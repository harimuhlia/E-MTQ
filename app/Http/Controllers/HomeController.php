<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Tahunevent;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $eventAktif = Tahunevent::with('detail')
            ->where('is_active', true)
            ->latest('tahun_event')
            ->first();

        return view('home', [
            'eventAktif' => $eventAktif,
        ]);
    }
}
