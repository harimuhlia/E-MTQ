<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use App\Models\DetailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk mengelola peserta per event (tabel pivot event_participants).
 *
 * Fungsionalitas yang disediakan:
 * - Form dan penyimpanan berkas verifikasi oleh peserta (upload dokumen).
 * - Verifikasi berkas oleh superadmin atau operator desa.
 * - Penolakan berkas beserta catatan alasan.
 *
 * Aturan otorisasi:
 * - Administrator (superadmin) dapat memverifikasi atau menolak siapa saja.
 * - Operator desa dapat memverifikasi atau menolak peserta yang berasal dari desa mereka sendiri.
 * - Peserta hanya dapat mengunggah berkas verifikasi untuk dirinya sendiri.
 */
class EventParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar peserta untuk event terpilih dan aksi verifikasi.
     *
     * Halaman ini menggantikan navigasi "Verifikasi Pendaftar". Semua peran
     * dapat mengaksesnya setelah memilih event. Peserta hanya melihat
     * dirinya sendiri dan dapat mengunggah berkas. Administrator melihat
     * semua peserta, sedangkan admin_desa melihat peserta dari desanya.
     * Aksi (upload, verifikasi, tolak) dibatasi berdasarkan peran dan
     * status event, mirip dengan logika di dashboard home.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $currentUser = auth()->user();
        // Pastikan event dipilih
        $eventId = session('selected_event_id');
        if (!$eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu untuk melihat peserta.');
        }
        $selectedEvent = DetailEvent::find($eventId);
        if (! $selectedEvent) {
            return redirect()->route('home')->with('error', 'Event tidak ditemukan.');
        }
        $selectedEventStatus = $selectedEvent->status();
        // Ambil peserta sesuai peran
        $query = EventParticipant::where('detail_event_id', $eventId)
            ->with(['user.desa', 'cabang', 'golongan']);
        if ($currentUser->role === 'admin_desa') {
            $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('desa_id', $currentUser->desa_id);
            });
        } elseif ($currentUser->role === 'peserta') {
            $query->where('user_id', $currentUser->id);
        }
        $eventParticipants = $query->get();
        return view('event_participant.index', [
            'eventParticipants' => $eventParticipants,
            'selectedEvent' => $selectedEvent,
            'selectedEventStatus' => $selectedEventStatus,
            'currentUser' => $currentUser,
        ]);
    }

    /**
     * Tampilkan form upload berkas verifikasi untuk peserta tertentu.
     *
     * Hanya peserta yang bersangkutan yang boleh mengunggah berkasnya sendiri.
     *
     * @param  int  $id  ID event_participant
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function uploadForm($id)
    {
        $participant = EventParticipant::with('user')->findOrFail($id);
        $currentUser = auth()->user();
        // Tentukan apakah user berhak untuk mengunggah berkas untuk peserta ini.
        // Peserta hanya boleh mengunggah berkas dirinya sendiri.
        // Admin desa boleh mengunggah berkas untuk peserta dari desanya.
        // Administrator boleh mengunggah berkas siapa saja.
        $allowed = false;
        if ($currentUser->role === 'peserta') {
            // Peserta hanya untuk dirinya sendiri
            if ($participant->user_id === $currentUser->id) {
                $allowed = true;
            }
        } elseif ($currentUser->role === 'admin_desa') {
            // Admin desa hanya untuk peserta dari desanya sendiri
            if ($participant->user && $participant->user->desa_id === $currentUser->desa_id) {
                $allowed = true;
            }
        } elseif ($currentUser->role === 'administrator') {
            $allowed = true;
        }
        if (! $allowed) {
            abort(403);
        }
        // Cek status event: pengunggahan hanya diizinkan ketika event aktif
        if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, tidak dapat mengunggah berkas.');
        }
        return view('event_participant.upload', compact('participant'));
    }

    /**
     * Proses upload berkas verifikasi.
     *
     * Validasi file, simpan di storage publik, lalu ubah status menjadi
     * sedang_diverifikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  ID event_participant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request, $id)
    {
        $participant = EventParticipant::findOrFail($id);
        $currentUser = auth()->user();
        // Tentukan apakah user berhak untuk mengunggah berkas.
        $allowed = false;
        if ($currentUser->role === 'peserta') {
            if ($participant->user_id === $currentUser->id) {
                $allowed = true;
            }
        } elseif ($currentUser->role === 'admin_desa') {
            if ($participant->user && $participant->user->desa_id === $currentUser->desa_id) {
                $allowed = true;
            }
        } elseif ($currentUser->role === 'administrator') {
            $allowed = true;
        }
        if (! $allowed) {
            abort(403);
        }
        // Cek status event: hanya dapat mengunggah ketika event aktif
        if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, tidak dapat mengunggah berkas.');
        }
        // Validasi berkas; semua dokumen optional, tetapi setidaknya satu harus ada
        $validated = $request->validate([
            'kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        // Pastikan ada berkas yang diunggah. Harus unggah minimal satu dokumen/foto
        if (!$request->hasFile('kk') && !$request->hasFile('akta') && !$request->hasFile('ktp') && !$request->hasFile('photo')) {
            return redirect()->back()->with('error', 'Anda harus mengunggah setidaknya satu berkas atau foto.');
        }
        // Simpan berkas dan update path jika diupload
        if ($request->hasFile('kk')) {
            $path = $request->file('kk')->store('verification', 'public');
            $participant->kk_path = $path;
        }
        if ($request->hasFile('akta')) {
            $path = $request->file('akta')->store('verification', 'public');
            $participant->akta_path = $path;
        }
        if ($request->hasFile('ktp')) {
            $path = $request->file('ktp')->store('verification', 'public');
            $participant->ktp_path = $path;
        }
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('verification', 'public');
            $participant->photo_path = $path;
        }
        // Set status menjadi sedang_diverifikasi
        $participant->status_verifikasi = 'sedang_diverifikasi';
        $participant->save();
        return redirect()->route('home')->with('success', 'Berkas berhasil diunggah, menunggu verifikasi.');
    }

    /**
     * Tampilkan form permintaan perubahan data oleh peserta.
     *
     * Peserta hanya dapat mengajukan permintaan perbaikan sebelum verifikasi
     * berhasil. Form ini memungkinkan peserta memasukkan pesan permintaan yang
     * disimpan dalam kolom request_message pada tabel event_participants.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function requestChangeForm($id)
    {
        $participant = EventParticipant::with('user')->findOrFail($id);
        $currentUser = auth()->user();
        // Peserta dapat mengajukan permintaan perubahan untuk dirinya sendiri
        if ($currentUser->role === 'peserta') {
            if ($participant->user_id !== $currentUser->id) {
                abort(403);
            }
            // Hanya boleh meminta perubahan jika event aktif
            if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
                return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, permintaan perubahan tidak dapat diajukan.');
            }
            // Tidak boleh lagi meminta perubahan jika sudah diverifikasi berhasil
            if ($participant->status_verifikasi === 'verifikasi_berhasil') {
                return redirect()->back()->with('error', 'Data sudah diverifikasi, permintaan perubahan tidak dapat diajukan.');
            }
            return view('event_participant.request', compact('participant'));
        }
        // Admin desa dapat meminta peserta untuk memperbaiki data/berkas sebelum verifikasi final
        if ($currentUser->role === 'admin_desa') {
            // Pastikan peserta berasal dari desa yang sama
            if (! $participant->user || $participant->user->desa_id !== $currentUser->desa_id) {
                abort(403);
            }
            // Event harus aktif untuk operator desa
            if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
                return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, permintaan perubahan tidak dapat diajukan.');
            }
            // Tidak boleh meminta perubahan jika peserta sudah diverifikasi berhasil
            if ($participant->status_verifikasi === 'verifikasi_berhasil') {
                return redirect()->back()->with('error', 'Data sudah diverifikasi, permintaan perubahan tidak dapat diajukan.');
            }
            return view('event_participant.request', compact('participant'));
        }
        // Other roles are not allowed
        abort(403);
    }

    /**
     * Simpan permintaan perubahan data yang diajukan oleh peserta.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestChange(Request $request, $id)
    {
        $participant = EventParticipant::with('user')->findOrFail($id);
        $currentUser = auth()->user();
        // Peserta mengajukan permintaan perubahan untuk dirinya sendiri
        if ($currentUser->role === 'peserta') {
            if ($participant->user_id !== $currentUser->id) {
                abort(403);
            }
            // Hanya boleh meminta perubahan jika event aktif
            if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
                return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, permintaan perubahan tidak dapat diajukan.');
            }
            if ($participant->status_verifikasi === 'verifikasi_berhasil') {
                return redirect()->back()->with('error', 'Data sudah diverifikasi, permintaan perubahan tidak dapat diajukan.');
            }
            $validated = $request->validate([
                'message' => 'required|string',
            ]);
            $participant->request_message = $validated['message'];
            $participant->save();
            return redirect()->route('home')->with('success', 'Permintaan perbaikan data telah dikirim.');
        }
        // Admin desa meminta peserta untuk memperbaiki data/berkas
        if ($currentUser->role === 'admin_desa') {
            // Pastikan peserta berasal dari desa yang sama
            if (! $participant->user || $participant->user->desa_id !== $currentUser->desa_id) {
                abort(403);
            }
            // Event harus aktif untuk operator desa
            if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
                return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, permintaan perubahan tidak dapat diajukan.');
            }
            // Hanya boleh meminta perubahan jika belum diverifikasi berhasil
            if ($participant->status_verifikasi === 'verifikasi_berhasil') {
                return redirect()->back()->with('error', 'Data sudah diverifikasi, permintaan perubahan tidak dapat diajukan.');
            }
            $validated = $request->validate([
                'message' => 'required|string',
            ]);
            // Simpan pesan permintaan perubahan di kolom request_message
            $participant->request_message = $validated['message'];
            $participant->save();
            return redirect()->route('event-participant.index')->with('success', 'Permintaan perubahan data telah dikirim ke peserta.');
        }
        // Other roles
        abort(403);
    }

    /**
     * Verifikasi berkas peserta.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($id)
    {
        $currentUser = auth()->user();
        $participant = EventParticipant::with('user')->findOrFail($id);
        // Hanya superadmin (administrator) yang dapat memverifikasi.
        if ($currentUser->role !== 'administrator') {
            abort(403);
        }
        // Administrator dapat memverifikasi tanpa terikat status event, namun jika event tidak aktif,
        // tampilkan pesan agar konsisten dengan logika lain.
        if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
            return redirect()->back()->with('error', 'Event ini tidak sedang berlangsung, verifikasi tidak diizinkan.');
        }
        // Hanya dapat diverifikasi jika sedang dalam status sedang_diverifikasi
        if ($participant->status_verifikasi !== 'sedang_diverifikasi') {
            return redirect()->back()->with('error', 'Berkas peserta belum dapat diverifikasi.');
        }
        $participant->status_verifikasi = 'verifikasi_berhasil';
        $participant->catatan_verifikasi = null;
        $participant->save();
        return redirect()->back()->with('success', 'Berkas peserta telah diverifikasi.');
    }

    /**
     * Tolak berkas peserta dengan catatan alasan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $currentUser = auth()->user();
        $participant = EventParticipant::with('user')->findOrFail($id);
        // Hanya superadmin (administrator) yang dapat menolak peserta.
        if ($currentUser->role !== 'administrator') {
            abort(403);
        }
        // Jika event tidak aktif, tampilkan pesan untuk konsistensi
        if ($participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
            return redirect()->back()->with('error', 'Event ini tidak sedang berlangsung, penolakan tidak diizinkan.');
        }
        // Hanya dapat ditolak jika sedang dalam status sedang_diverifikasi
        if ($participant->status_verifikasi !== 'sedang_diverifikasi') {
            return redirect()->back()->with('error', 'Berkas peserta belum dapat ditolak.');
        }
        $request->validate([
            'reason' => 'required|string',
        ]);
        $participant->status_verifikasi = 'verifikasi_gagal';
        $participant->catatan_verifikasi = $request->reason;
        $participant->save();
        return redirect()->back()->with('success', 'Berkas peserta ditolak.');
    }

    /**
     * Tampilkan halaman verifikasi peserta untuk admin dan operator desa.
     *
     * Halaman ini menampilkan data peserta, dokumen yang diunggah, serta tombol
     * untuk memverifikasi atau menolak. Hanya administrator dan admin desa
     * (pemilik desa) yang dapat mengakses halaman ini.
     *
     * @param int $id ID event_participant
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function verifyForm($id)
    {
        $currentUser = auth()->user();
        $participant = EventParticipant::with(['user.desa', 'cabang', 'golongan', 'detailEvent'])->findOrFail($id);
        // Cek hak akses: hanya administrator atau operator desa pemilik desa
        $allowed = false;
        if ($currentUser->role === 'administrator') {
            $allowed = true;
        } elseif ($currentUser->role === 'admin_desa' && $participant->user && $participant->user->desa_id === $currentUser->desa_id) {
            $allowed = true;
        }
        if (! $allowed) {
            abort(403);
        }
        // Cek status event untuk operator desa: hanya bisa memproses saat event aktif
        if ($currentUser->role !== 'administrator' && $participant->detailEvent && $participant->detailEvent->status() !== 'Aktif') {
            return redirect()->route('event-participant.index')->with('error', 'Event ini tidak sedang berlangsung, verifikasi tidak diizinkan.');
        }
        // Tampilkan halaman verifikasi. Halaman ini menyediakan tombol verifikasi (POST)
        // dan penolakan (POST) yang diarahkan ke route event-participant.verify dan event-participant.reject.
        return view('event_participant.verify', [
            'participant' => $participant,
            'currentUser' => $currentUser,
        ]);
    }
}