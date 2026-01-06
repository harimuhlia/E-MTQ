<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Golongan;
use App\Models\Desa;
use App\Models\EventParticipant;
use App\Models\DetailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk mengelola peserta (user dengan role 'peserta').
 *
 * Operator desa (admin_desa) dan superadmin (administrator) dapat menambahkan
 * peserta baru untuk event yang sedang dipilih. Setiap peserta harus memilih
 * cabang dan golongan pada saat pendaftaran. Peserta ditempatkan pada desa
 * operator secara otomatis, sedangkan superadmin dapat memilih desa.
 *
 * Semua peran dapat melihat daftar peserta. Verifikasi dilakukan melalui
 * EventParticipantController, sehingga controller ini tidak lagi memiliki
 * method verifikasi.
 */
class PesertaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar semua peserta.
     */
    public function index()
    {
        // Ambil semua user dengan role peserta beserta desa mereka
        $pesertas = User::where('role', 'peserta')->with('desa')->get();
        $currentUser = auth()->user();
        return view('peserta.index', compact('pesertas', 'currentUser'));
    }

    /**
     * Tampilkan form untuk membuat peserta baru.
     *
     * Operator desa dan superadmin dapat mengakses halaman ini. Pastikan
     * sebuah event telah dipilih terlebih dahulu agar cabang dan golongan
     * tersedia.
     */
    public function create()
    {
        $user = auth()->user();
        // Hanya operator desa atau superadmin yang boleh menambah peserta.
        $userRole = trim(strtolower($user->role));
        if (! in_array($userRole, ['admin_desa', 'administrator'])) {
            abort(403);
        }
        // Pastikan event terpilih
        $eventId = session('selected_event_id');
        if (!$eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah peserta.');
        }
        // Pastikan event masih aktif untuk pendaftaran jika user bukan administrator
        $event = \App\Models\DetailEvent::find($eventId);
        if ($user->role !== 'administrator' && $event && $event->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang membuka pendaftaran.');
        }
        // Ambil cabang untuk event terpilih
        $cabangs = Cabang::where('detail_event_id', $eventId)->get();
        // Jika superadmin, ambil daftar desa untuk pilihan
        $desas = [];
        if ($user->role === 'administrator') {
            $desas = Desa::all();
        }
        return view('peserta.create', compact('cabangs', 'desas'));
    }

    /**
     * Simpan peserta baru ke database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $userRole = trim(strtolower($user->role));
        if (! in_array($userRole, ['admin_desa', 'administrator'])) {
            abort(403);
        }
        // Pastikan event terpilih
        $eventId = session('selected_event_id');
        if (!$eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah peserta.');
        }
        // Pastikan event masih aktif untuk pendaftaran jika user bukan administrator
        $event = \App\Models\DetailEvent::find($eventId);
        if ($user->role !== 'administrator' && $event && $event->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang membuka pendaftaran.');
        }
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|size:16|unique:users,nik',
            'tanggal_lahir' => 'required|date',
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'desa_id' => 'nullable|exists:desas,id',
        ]);
        // Desa peserta mengikuti desa operator; untuk superadmin ambil dari input jika tersedia
        $desaId = $user->role === 'admin_desa' ? $user->desa_id : ($validated['desa_id'] ?? null);
        // Buat user baru dengan peran peserta
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'peserta',
            'desa_id' => $desaId,
            'nik' => $validated['nik'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'terms' => 'accepted',
        ]);
        // Buat entry di tabel pivot event_participants dengan status default belum_verifikasi
        EventParticipant::create([
            'user_id' => $newUser->id,
            'detail_event_id' => $eventId,
            'cabang_id' => $validated['cabang_id'],
            'golongan_id' => $validated['golongan_id'],
            'status_verifikasi' => 'belum_verifikasi',
            'catatan_verifikasi' => null,
            'request_message' => null,
        ]);
        return redirect()->route('home')->with('success', 'Peserta berhasil didaftarkan.');
    }

    /**
     * Show the form for editing a participant (user with role 'peserta') for the selected event.
     *
     * Only superadmin or operator of the same desa may edit a participant. Editing is
     * prohibited once the participant's verification status is verifikasi_berhasil. The
     * event must be selected in the session in order to determine available cabang and
     * golongan choices.
     *
     * @param  \App\Models\User  $peserta  The participant to edit
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(User $peserta)
    {
        $currentUser = auth()->user();
        // Only administrator or admin_desa may edit
        $role = trim(strtolower($currentUser->role));
        if (! in_array($role, ['administrator', 'admin_desa'])) {
            abort(403);
        }
        // Ensure the user being edited is a participant
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        // Event must be selected
        $eventId = session('selected_event_id');
        if (! $eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum mengedit peserta.');
        }
        // Retrieve the event participant record for this event
        $eventParticipant = EventParticipant::where('detail_event_id', $eventId)
            ->where('user_id', $peserta->id)
            ->first();
        if (! $eventParticipant) {
            return redirect()->route('home')->with('error', 'Peserta tidak terdaftar pada event ini.');
        }
        // Disallow editing after verification success
        if ($eventParticipant->status_verifikasi === 'verifikasi_berhasil') {
            return redirect()->route('home')->with('error', 'Data peserta tidak dapat diubah setelah verifikasi berhasil.');
        }
        // Cegah operator mengedit jika event tidak aktif
        $event = DetailEvent::find($eventId);
        if ($currentUser->role !== 'administrator' && $event && $event->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, data peserta tidak dapat diubah.');
        }
        // Operator may only edit participants from their own desa
        if ($currentUser->role === 'admin_desa' && $peserta->desa_id != $currentUser->desa_id) {
            abort(403);
        }
        // Prepare options for cabang and desa
        $cabangs = Cabang::where('detail_event_id', $eventId)->get();
        $desas = [];
        if ($currentUser->role === 'administrator') {
            $desas = Desa::all();
        }
        // Pass current user role to view for conditional display
        $currentUserRole = $currentUser->role;
        return view('peserta.edit', compact('peserta', 'eventParticipant', 'cabangs', 'desas', 'currentUserRole'));
    }

    /**
     * Update a participant's data for the selected event.
     *
     * Similar authorization rules apply as in the edit method. Only administrator or
     * admin_desa may update, and only before verification is successful. Superadmin may
     * change the participant's desa, while operators cannot. Any pending request message
     * will be cleared upon successful update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $peserta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $peserta)
    {
        $currentUser = auth()->user();
        $role = trim(strtolower($currentUser->role));
        if (! in_array($role, ['administrator', 'admin_desa'])) {
            abort(403);
        }
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        $eventId = session('selected_event_id');
        if (! $eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum memperbarui peserta.');
        }
        $eventParticipant = EventParticipant::where('detail_event_id', $eventId)
            ->where('user_id', $peserta->id)
            ->first();
        if (! $eventParticipant) {
            return redirect()->route('home')->with('error', 'Peserta tidak terdaftar pada event ini.');
        }
        if ($eventParticipant->status_verifikasi === 'verifikasi_berhasil') {
            return redirect()->route('home')->with('error', 'Data peserta tidak dapat diubah setelah verifikasi berhasil.');
        }
        // Cegah operator memperbarui jika event tidak aktif
        $event = DetailEvent::find($eventId);
        if ($currentUser->role !== 'administrator' && $event && $event->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang berlangsung, data peserta tidak dapat diubah.');
        }
        if ($currentUser->role === 'admin_desa' && $peserta->desa_id != $currentUser->desa_id) {
            abort(403);
        }
        // Validation rules: email & NIK unique except for current user
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $peserta->id,
            'password' => 'nullable|string|min:6|confirmed',
            'nik' => 'required|string|size:16|unique:users,nik,' . $peserta->id,
            'tanggal_lahir' => 'required|date',
            'cabang_id' => 'required|exists:cabangs,id',
            'golongan_id' => 'required|exists:golongans,id',
            'desa_id' => 'nullable|exists:desas,id',
        ]);
        // Update user fields
        $peserta->name = $validated['name'];
        $peserta->email = $validated['email'];
        $peserta->nik = $validated['nik'];
        $peserta->tanggal_lahir = $validated['tanggal_lahir'];
        // Allow desa change only for administrator
        if ($currentUser->role === 'administrator') {
            $peserta->desa_id = $validated['desa_id'] ?? $peserta->desa_id;
        }
        if (! empty($validated['password'])) {
            $peserta->password = Hash::make($validated['password']);
        }
        $peserta->save();
        // Update pivot data
        $eventParticipant->cabang_id = $validated['cabang_id'];
        $eventParticipant->golongan_id = $validated['golongan_id'];
        // Clear any existing request message since data has been updated
        $eventParticipant->request_message = null;
        $eventParticipant->save();
        return redirect()->route('home')->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Remove a participant from the selected event.
     *
     * This method does not delete the user record entirely—only their registration
     * within the selected event. The operation is allowed only to administrators
     * and admin_desa of the same desa, provided the participant has not been
     * successfully verified. After deletion, the participant may still exist
     * for other events.
     *
     * @param  \App\Models\User  $peserta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $peserta)
    {
        $currentUser = auth()->user();
        $role = trim(strtolower($currentUser->role));
        if (! in_array($role, ['administrator', 'admin_desa'])) {
            abort(403);
        }
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        $eventId = session('selected_event_id');
        if (! $eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menghapus peserta.');
        }
        $eventParticipant = EventParticipant::where('detail_event_id', $eventId)
            ->where('user_id', $peserta->id)
            ->first();
        if (! $eventParticipant) {
            return redirect()->back()->with('error', 'Peserta tidak terdaftar pada event ini.');
        }
        // Do not allow deletion after verification success
        if ($eventParticipant->status_verifikasi === 'verifikasi_berhasil') {
            return redirect()->back()->with('error', 'Peserta telah diverifikasi, tidak dapat dihapus.');
        }
        // Cegah operator menghapus jika event tidak aktif
        $event = DetailEvent::find($eventId);
        if ($currentUser->role !== 'administrator' && $event && $event->status() !== 'Aktif') {
            return redirect()->back()->with('error', 'Event ini tidak sedang berlangsung, peserta tidak dapat dihapus.');
        }
        if ($currentUser->role === 'admin_desa' && $peserta->desa_id != $currentUser->desa_id) {
            abort(403);
        }
        $eventParticipant->delete();
        return redirect()->back()->with('success', 'Peserta berhasil dihapus dari event.');
    }
}