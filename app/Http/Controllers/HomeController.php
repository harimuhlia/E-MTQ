<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Tahunevent;
use App\Models\EventParticipant;

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
        // Ambil seluruh event untuk ditampilkan di dashboard
        $events = Tahunevent::orderBy('tahun_event', 'desc')->get();
        $selectedId = session('selected_event_id');
        $selectedEvent = null;
        $metrics = [];

        if ($selectedId) {
            // Jika ada event terpilih dalam sesi, muat detailnya
            $selectedEvent = Tahunevent::with('detail')->find($selectedId);
            if ($selectedEvent) {
                // Hitung statistik verifikasi pendaftar menggunakan tabel pivot event_participants
                $metrics['total'] = EventParticipant::where('tahunevent_id', $selectedId)->count();
                $metrics['verified'] = EventParticipant::where('tahunevent_id', $selectedId)->where('status_verifikasi', 'verified')->count();
                $metrics['pending'] = EventParticipant::where('tahunevent_id', $selectedId)->where('status_verifikasi', 'pending')->count();
                $metrics['rejected'] = EventParticipant::where('tahunevent_id', $selectedId)->where('status_verifikasi', 'rejected')->count();
            }
        }

        return view('home', compact('events', 'selectedEvent', 'metrics'));
    }

    /**
     * Pilih event berdasarkan ID dan simpan ke session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selectEvent($id)
    {
        // Jika ID 0 diberikan, artinya reset pilihan event
        if ($id == 0 || $id === '0') {
            session()->forget('selected_event_id');
        } else {
            session(['selected_event_id' => $id]);
        }
        return redirect()->route('home');
    }

    /**
     * Pilih event berdasarkan slug dan simpan ke session.
     *
     * @param  string $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function event($slug)
    {
        $event = Tahunevent::where('slug', $slug)->firstOrFail();
        session(['selected_event_id' => $event->id]);
        return redirect()->route('home');
    }
}
