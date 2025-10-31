<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
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
        // Pastikan hanya peserta pemilik yang boleh mengunggah berkasnya
        if ($currentUser->role !== 'peserta' || $participant->user_id !== $currentUser->id) {
            abort(403);
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
        // Pastikan user adalah peserta tersebut
        if ($currentUser->role !== 'peserta' || $participant->user_id !== $currentUser->id) {
            abort(403);
        }
        // Validasi berkas; semua dokumen optional, tetapi setidaknya satu harus ada
        $validated = $request->validate([
            'kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        if (!$request->hasFile('kk') && !$request->hasFile('akta') && !$request->hasFile('ktp')) {
            return redirect()->back()->with('error', 'Anda harus mengunggah setidaknya satu berkas.');
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
        // Set status menjadi sedang_diverifikasi
        $participant->status_verifikasi = 'sedang_diverifikasi';
        $participant->save();
        return redirect()->route('home')->with('success', 'Berkas berhasil diunggah, menunggu verifikasi.');
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
        // Tentukan apakah user memiliki hak verifikasi
        $allowed = false;
        if ($currentUser->role === 'administrator') {
            $allowed = true;
        } elseif ($currentUser->role === 'admin_desa' && $participant->user && $participant->user->desa_id === $currentUser->desa_id) {
            $allowed = true;
        }
        if (!$allowed) {
            abort(403);
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
        // Periksa hak menolak: superadmin atau operator desa pemilik
        $allowed = false;
        if ($currentUser->role === 'administrator') {
            $allowed = true;
        } elseif ($currentUser->role === 'admin_desa' && $participant->user && $participant->user->desa_id === $currentUser->desa_id) {
            $allowed = true;
        }
        if (!$allowed) {
            abort(403);
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
}