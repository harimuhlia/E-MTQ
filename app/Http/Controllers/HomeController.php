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
        $eventParticipants = collect();
        if ($selectedId) {
            $selectedEvent = DetailEvent::find($selectedId);
            if ($selectedEvent) {
                // Ambil seluruh peserta terdaftar untuk event terpilih
                $allParticipants = EventParticipant::where('detail_event_id', $selectedId)
                    ->with(['user.desa', 'cabang', 'golongan'])
                    ->get();
                $currentUser = auth()->user();
                // Filter peserta berdasarkan peran: admin_desa hanya melihat peserta dari desanya, peserta melihat dirinya sendiri
                if ($currentUser && $currentUser->role === 'admin_desa') {
                    $eventParticipants = $allParticipants->filter(function ($p) use ($currentUser) {
                        return $p->user && $p->user->desa_id === $currentUser->desa_id;
                    });
                } elseif ($currentUser && $currentUser->role === 'peserta') {
                    $eventParticipants = $allParticipants->filter(function ($p) use ($currentUser) {
                        return $p->user_id === $currentUser->id;
                    });
                } else {
                    // administrator sees all participants
                    $eventParticipants = $allParticipants;
                }
                // Hitung statistik berdasarkan peserta yang dapat dilihat
                $metrics['total'] = $eventParticipants->count();
                $metrics['belum_verifikasi'] = $eventParticipants->where('status_verifikasi', 'belum_verifikasi')->count();
                $metrics['sedang_diverifikasi'] = $eventParticipants->where('status_verifikasi', 'sedang_diverifikasi')->count();
                $metrics['verifikasi_gagal'] = $eventParticipants->where('status_verifikasi', 'verifikasi_gagal')->count();
                $metrics['verifikasi_berhasil'] = $eventParticipants->where('status_verifikasi', 'verifikasi_berhasil')->count();
            }
        }

        // Determine status of selected event for view logic
        $selectedEventStatus = $selectedEvent ? $selectedEvent->status() : null;
        return view('home', [
            'events' => $events,
            'years' => $years,
            'year' => $year,
            'filteredEvents' => $filteredEvents,
            'selectedEvent' => $selectedEvent,
            'metrics' => $metrics,
            'eventParticipants' => $eventParticipants,
            'selectedEventStatus' => $selectedEventStatus,
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
