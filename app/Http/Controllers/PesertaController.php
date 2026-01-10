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
        $currentUser = auth()->user();
        // Jika operator desa, hanya tampilkan peserta dari desa yang sama
        if ($currentUser && $currentUser->role === 'admin_desa') {
            $pesertas = User::where('role', 'peserta')
                ->where('desa_id', $currentUser->desa_id)
                ->with('desa')
                ->get();
        } else {
            // Administrator dan peran lainnya melihat seluruh peserta
            $pesertas = User::where('role', 'peserta')->with('desa')->get();
        }
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
        // Ambil daftar desa untuk superadmin. Operator desa tidak perlu memilih desa
        // karena desa peserta otomatis mengikuti desa operator.
        $desas = [];
        if ($user->role === 'administrator') {
            $desas = Desa::all();
        }
        // Formulir pendaftaran tidak lagi menampilkan pilihan cabang dan golongan.
        // Cabang dan golongan akan dipilih pada halaman terpisah setelah peserta dibuat.
        return view('peserta.create', compact('desas'));
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
        // Validasi input dasar. Perhatikan bahwa NIK tidak lagi menggunakan rule unique
        // karena keunikan peserta ditentukan berdasarkan kombinasi NIK dan event. Rule
        // unique untuk email masih diperlukan agar tidak ada akun ganda dengan email
        // sama.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Email tidak divalidasi unik secara global karena email boleh dipakai di event lain.
            // Unik per event diperiksa secara manual di bawah.
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|size:16',
            'tanggal_lahir' => 'required|date',
            // Desa hanya relevan untuk superadmin; validasi nullable tetap diperlukan
            'desa_id' => 'nullable|exists:desas,id',
        ]);
        // Cek apakah ada peserta lain dengan NIK yang sama yang sudah terdaftar di event ini
        $existingUserIds = User::where('nik', $validated['nik'])->pluck('id');
        if ($existingUserIds->isNotEmpty()) {
            $existsInEvent = EventParticipant::where('detail_event_id', $eventId)
                ->whereIn('user_id', $existingUserIds)
                ->exists();
            if ($existsInEvent) {
                return redirect()->back()->withInput()->with('error', 'Peserta dengan NIK ini sudah terdaftar pada event ini.');
            }
        }
        // Pastikan email belum digunakan oleh peserta lain pada event ini. Email boleh sama di event berbeda,
        // tetapi tidak boleh digunakan oleh dua peserta berbeda pada event yang sama.
        $emailUserIds = User::where('email', $validated['email'])->pluck('id');
        if ($emailUserIds->isNotEmpty()) {
            $existsInEventForEmail = EventParticipant::where('detail_event_id', $eventId)
                ->whereIn('user_id', $emailUserIds)
                ->exists();
            if ($existsInEventForEmail) {
                return redirect()->back()->withInput()->with('error', 'Email ini sudah digunakan oleh peserta lain pada event ini.');
            }
        }
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
        ]);
        // Setelah peserta dibuat, tidak langsung mendaftarkan cabang/golongan.
        // Admin desa atau superadmin dapat memilih lomba melalui halaman terpisah.
        // Jika user adalah admin_desa atau administrator, arahkan ke halaman pemilihan lomba.
        if (in_array($user->role, ['admin_desa', 'administrator'])) {
            return redirect()->route('peserta.select-lomba', [$newUser->id])
                ->with('success', 'Peserta berhasil didaftarkan. Silakan pilih lomba untuk peserta.');
        }
        // Untuk peran lain (meskipun seharusnya tidak terjadi), kembali ke beranda
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
        die('debug edit peserta');
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
        // Persiapkan daftar desa hanya untuk administrator. Cabang dan golongan tidak
        // diperlukan pada form edit, karena pemilihan lomba dilakukan di halaman lain.
        $desas = [];
        if ($currentUser->role === 'administrator') {
            $desas = Desa::all();
        }
        // Pass current user role to view for conditional display
        $currentUserRole = $currentUser->role;
        
        return view('peserta.edit', compact('peserta', 'eventParticipant', 'desas', 'currentUserRole'));
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
        // Validation rules: email harus unik kecuali untuk user saat ini. NIK tidak lagi
        // di-unique-kan secara global karena keunikan ditentukan oleh kombinasi NIK
        // dan event. Namun kita cek secara manual setelah validasi untuk
        // memastikan NIK tidak bentrok dengan peserta lain pada event yang sama.
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Email tidak divalidasi unik secara global. Unik per event diperiksa manual di bawah.
            'email' => 'required|email',
            'password' => 'nullable|string|min:6|confirmed',
            'nik' => 'required|string|size:16',
            'tanggal_lahir' => 'required|date',
            'desa_id' => 'nullable|exists:desas,id',
        ]);
        // Pastikan tidak ada user lain dengan NIK yang sama yang sudah terdaftar di event ini
        $otherUserWithNik = User::where('nik', $validated['nik'])
            ->where('id', '!=', $peserta->id)
            ->first();
        if ($otherUserWithNik) {
            $registeredInEvent = EventParticipant::where('detail_event_id', $eventId)
                ->where('user_id', $otherUserWithNik->id)
                ->exists();
            if ($registeredInEvent) {
                return redirect()->back()->withInput()->with('error', 'NIK sudah digunakan oleh peserta lain pada event ini.');
            }
        }
        // Pastikan tidak ada user lain dengan email yang sama yang sudah terdaftar di event ini.
        $otherUsersWithEmail = User::where('email', $validated['email'])
            ->where('id', '!=', $peserta->id)
            ->pluck('id');
        if ($otherUsersWithEmail->isNotEmpty()) {
            $existsInEventForEmail = EventParticipant::where('detail_event_id', $eventId)
                ->whereIn('user_id', $otherUsersWithEmail)
                ->exists();
            if ($existsInEventForEmail) {
                return redirect()->back()->withInput()->with('error', 'Email sudah digunakan oleh peserta lain pada event ini.');
            }
        }
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
        // Tidak memperbarui cabang/golongan di sini karena pemilihan lomba dilakukan
        // melalui halaman khusus. Hanya perbarui data user. Apabila ada
        // request_message pada eventParticipant, hapus karena data telah diperbarui.
        if ($eventParticipant) {
            $eventParticipant->request_message = null;
            $eventParticipant->save();
        }
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

    /**
     * Tampilkan form pemilihan lomba untuk peserta.
     *
     * Halaman ini memungkinkan admin desa atau superadmin menempatkan seorang peserta
     * ke satu atau lebih cabang/golongan lomba dalam event yang sedang dipilih.
     * Peserta (role 'peserta') tidak dapat mengakses halaman ini.
     *
     * @param  \App\Models\User  $peserta
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function selectLombaForm(User $peserta)
    {
        $currentUser = auth()->user();
        // Pastikan hanya administrator atau admin_desa yang mengakses
        if (! in_array($currentUser->role, ['administrator', 'admin_desa'])) {
            abort(403);
        }
        // Pastikan yang diedit adalah peserta
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        // Pastikan event terpilih
        $eventId = session('selected_event_id');
        if (! $eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah lomba.');
        }
        $event = DetailEvent::find($eventId);
        if (! $event) {
            return redirect()->route('home')->with('error', 'Event tidak ditemukan.');
        }
        // Admin desa hanya dapat mengelola peserta dari desanya sendiri
        if ($currentUser->role === 'admin_desa' && $peserta->desa_id != $currentUser->desa_id) {
            abort(403);
        }
        // Operator desa hanya boleh menambah lomba ketika event aktif
        if ($currentUser->role === 'admin_desa' && $event->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang membuka pendaftaran.');
        }
        // Ambil daftar cabang dan golongan untuk event terpilih
        $cabangs = Cabang::where('detail_event_id', $eventId)->with('golongan')->get();
        // Ambil event_participants existing for this user for event
        $existingParticipantIds = EventParticipant::where('detail_event_id', $eventId)
            ->where('user_id', $peserta->id)
            ->pluck('golongan_id', 'cabang_id')->toArray();
        return view('peserta.select_lomba', [
            'peserta' => $peserta,
            'cabangs' => $cabangs,
            'existingParticipantIds' => $existingParticipantIds,
        ]);
    }

    /**
     * Simpan pemilihan lomba untuk peserta.
     *
     * Admin desa atau superadmin dapat memilih lebih dari satu cabang/golongan lomba.
     * Metode ini akan memvalidasi usia peserta berdasarkan ketentuan pada kombinasi
     * cabang dan golongan. Jika validasi gagal, tidak ada data yang disimpan dan
     * pengguna diarahkan kembali dengan pesan error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $peserta
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selectLomba(Request $request, User $peserta)
    {
        $currentUser = auth()->user();
        if (! in_array($currentUser->role, ['administrator', 'admin_desa'])) {
            abort(403);
        }
        if ($peserta->role !== 'peserta') {
            abort(404);
        }
        // Pastikan event terpilih
        $eventId = session('selected_event_id');
        if (! $eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah lomba.');
        }
        $event = DetailEvent::find($eventId);
        if (! $event) {
            return redirect()->route('home')->with('error', 'Event tidak ditemukan.');
        }
        // Admin desa hanya dapat mengelola peserta dari desanya sendiri
        if ($currentUser->role === 'admin_desa' && $peserta->desa_id != $currentUser->desa_id) {
            abort(403);
        }
        // Non-administrator tidak bisa menambah lomba jika event tidak aktif
        if ($currentUser->role !== 'administrator' && $event->status() !== 'Aktif') {
            return redirect()->route('home')->with('error', 'Event ini tidak sedang membuka pendaftaran.');
        }
        // Validasi input: array of selections
        $request->validate([
            'lomba' => 'required|array',
            'lomba.*' => 'required|string',
        ], [
            'lomba.required' => 'Silakan pilih setidaknya satu lomba.',
        ]);
        $selections = $request->input('lomba');
        // Prepare to collect age validation errors
        $errors = [];
        // Use Carbon for age computation
        $pesertaBirth = \Carbon\Carbon::parse($peserta->tanggal_lahir);
        $eventDate = $event->waktu_pelaksanaan_mulai ?: now();
        $age = $pesertaBirth->diffInYears($eventDate);
        foreach ($selections as $selection) {
            // Expect selection string in format cabangId-golonganId
            [$cabangId, $golonganId] = explode('-', $selection);
            // Check if already exists
            $exists = EventParticipant::where('detail_event_id', $eventId)
                ->where('user_id', $peserta->id)
                ->where('cabang_id', $cabangId)
                ->where('golongan_id', $golonganId)
                ->exists();
            if ($exists) {
                continue; // skip duplicates
            }
            // Ambil informasi golongan untuk validasi usia
            $gol = \App\Models\Golongan::find($golonganId);
            if ($gol) {
                // Validasi usia minimal jika diset (usia_min diisi dengan batas minimal tahun)
                if (!is_null($gol->usia_min) && $age < $gol->usia_min) {
                    $errors[] = 'Usia peserta kurang dari batas minimal untuk golongan ' . ($gol->nama ?? '') . '.';
                    continue;
                }
                // Validasi usia maksimal jika diset (usia_max diisi dengan batas maksimal tahun)
                // Jika usia_max tidak ada (null), fallback ke max_usia untuk kompatibilitas lama
                $maxAllowed = $gol->usia_max ?? $gol->max_usia;
                if (!is_null($maxAllowed) && $age > $maxAllowed) {
                    $errors[] = 'Usia peserta melebihi batas maksimal untuk golongan ' . ($gol->nama ?? '') . '.';
                    continue;
                }
            }
        }
        if (! empty($errors)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $errors));
        }
        // Create event_participants for each valid selection
        foreach ($selections as $selection) {
            [$cabangId, $golonganId] = explode('-', $selection);
            $exists = EventParticipant::where('detail_event_id', $eventId)
                ->where('user_id', $peserta->id)
                ->where('cabang_id', $cabangId)
                ->where('golongan_id', $golonganId)
                ->exists();
            if ($exists) {
                continue;
            }
            EventParticipant::create([
                'user_id' => $peserta->id,
                'detail_event_id' => $eventId,
                'cabang_id' => $cabangId,
                'golongan_id' => $golonganId,
                'status_verifikasi' => 'belum_verifikasi',
                'catatan_verifikasi' => null,
                'request_message' => null,
            ]);
        }
        return redirect()->route('home')->with('success', 'Lomba peserta berhasil disimpan.');
    }
}