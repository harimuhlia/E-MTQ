<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola peserta per event (pivot event_participants).
 *
 * Saat ini hanya digunakan untuk memverifikasi pendaftar berdasarkan hak akses.
 */
class EventParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Verifikasi pendaftar event.
     *
     * Aturan:
     * - Administrator (superadmin) dapat memverifikasi siapa saja.
     * - Operator desa dapat memverifikasi pendaftar yang berasal dari desa mereka sendiri.
     * - Peserta dapat memverifikasi dirinya sendiri.
     *
     * @param  int  $id  ID baris pivot event_participants
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
        } elseif ($currentUser->role === 'peserta' && $participant->user && $participant->user->id === $currentUser->id) {
            $allowed = true;
        }

        if (!$allowed) {
            abort(403);
        }

        // Update status verifikasi pada pivot event_participants
        $participant->status_verifikasi = 'verified';
        $participant->save();

        return redirect()->back()->with('success', 'Data peserta berhasil diverifikasi.');
    }
}