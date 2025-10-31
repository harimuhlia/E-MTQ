<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Golongan;
use App\Models\Desa;
use App\Models\EventParticipant;
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
        // Hanya operator desa atau superadmin yang boleh menambah peserta
        if (!in_array($user->role, ['admin_desa', 'administrator'])) {
            abort(403);
        }
        // Pastikan event terpilih
        $eventId = session('selected_event_id');
        if (!$eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah peserta.');
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
        if (!in_array($user->role, ['admin_desa', 'administrator'])) {
            abort(403);
        }
        // Pastikan event terpilih
        $eventId = session('selected_event_id');
        if (!$eventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum menambah peserta.');
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
}