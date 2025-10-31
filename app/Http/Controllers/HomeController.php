<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DetailEvent;
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
        /**
         * Tampilkan halaman dashboard.
         *
         * Halaman ini memiliki dua mode: jika tidak ada event yang dipilih, tampilkan daftar
         * semua event yang tersedia dengan opsi filter berdasarkan tahun. Jika sebuah event
         * dipilih (tersimpan dalam session), tampilkan statistik pendaftar dan jadwal
         * terkait event tersebut.
         */
        $request = request();
        // Ambil parameter tahun dari query string atau dari session.
        // Perbaikan filter tahun: jika parameter year dikirim (meski kosong), atur ulang sesi.
        // Saat opsi "Semua Tahun" dipilih (value kosong atau null), hapus sesi agar menampilkan semua event.
        if ($request->has('year')) {
            $yearInput = $request->query('year');
            if ($yearInput) {
                session(['selected_year' => $yearInput]);
                $year = $yearInput;
            } else {
                // kosongkan filter tahun jika memilih Semua Tahun
                session()->forget('selected_year');
                $year = null;
            }
        } else {
            $year = session('selected_year');
        }

        // Ambil seluruh event, diurutkan berdasarkan tanggal pendaftaran mulai
        $events = DetailEvent::orderBy('pendaftaran_mulai', 'desc')->get();

        // Buat daftar tahun unik dari tanggal pendaftaran mulai atau tanggal pelaksanaan
        $years = $events->map(function ($event) {
            $date = $event->pendaftaran_mulai ?? $event->waktu_pelaksanaan_mulai;
            return $date ? $date->format('Y') : null;
        })->filter()->unique()->sort()->values();

        // Filter event berdasarkan tahun yang dipilih (jika ada)
        $filteredEvents = $events;
        if ($year) {
            $filteredEvents = $events->filter(function ($event) use ($year) {
                $date = $event->pendaftaran_mulai ?? $event->waktu_pelaksanaan_mulai;
                return $date && $date->format('Y') == $year;
            });
        }

        // Ambil event terpilih dari session, jika ada
        $selectedId = session('selected_event_id');
        $selectedEvent = null;
        $metrics = [];
        $eventParticipants = [];
        if ($selectedId) {
            $selectedEvent = DetailEvent::find($selectedId);
            if ($selectedEvent) {
                // Hitung statistik pendaftar per status verifikasi
                $metrics['total'] = EventParticipant::where('detail_event_id', $selectedId)->count();
                $metrics['verified'] = EventParticipant::where('detail_event_id', $selectedId)
                    ->where('status_verifikasi', 'verified')
                    ->count();
                $metrics['pending'] = EventParticipant::where('detail_event_id', $selectedId)
                    ->where('status_verifikasi', 'pending')
                    ->count();
                $metrics['rejected'] = EventParticipant::where('detail_event_id', $selectedId)
                    ->where('status_verifikasi', 'rejected')
                    ->count();
                // Ambil daftar peserta untuk event terpilih beserta relasi yang diperlukan
                $eventParticipants = EventParticipant::where('detail_event_id', $selectedId)
                    ->with(['user.desa', 'cabang', 'golongan'])
                    ->get();
            }
        }

        return view('home', [
            'events' => $events,
            'years' => $years,
            'year' => $year,
            'filteredEvents' => $filteredEvents,
            'selectedEvent' => $selectedEvent,
            'metrics' => $metrics,
            'eventParticipants' => $eventParticipants,
        ]);
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
        $event = DetailEvent::where('slug', $slug)->firstOrFail();
        session(['selected_event_id' => $event->id]);
        return redirect()->route('home');
    }
}
