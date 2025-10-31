<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk mengelola peserta.
 *
 * Operator (admin_desa) dapat menambahkan peserta baru. Superadmin (administrator)
 * juga dapat menambahkan peserta jika diperlukan. Semua peran dapat melihat daftar
 * peserta. Verifikasi peserta dilakukan oleh superadmin, operator (untuk peserta
 * dari desa mereka), atau oleh peserta itu sendiri.
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
     */
    public function create()
    {
        $user = auth()->user();
        // Hanya operator desa atau superadmin yang boleh menambah peserta
        if (!in_array($user->role, ['admin_desa', 'administrator'])) {
            abort(403);
        }
        return view('peserta.create');
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
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|size:16|unique:users,nik',
            'tanggal_lahir' => 'required|date',
        ]);
        // Desa peserta mengikuti desa operator atau default null untuk superadmin
        $desaId = $user->role === 'admin_desa' ? $user->desa_id : ($request->desa_id ?? null);
        // Buat user baru dengan peran peserta
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'peserta',
            'desa_id' => $desaId,
            'nik' => $validated['nik'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'terms' => 'accepted',
            'status_verifikasi' => 'pending',
        ]);
        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Verifikasi peserta.
     *
     * Superadmin dapat memverifikasi siapa saja. Operator desa dapat memverifikasi
     * peserta yang berasal dari desanya. Peserta dapat memverifikasi dirinya sendiri.
     */
    public function verify($id)
    {
        $currentUser = auth()->user();
        $target = User::where('role', 'peserta')->findOrFail($id);
        // Periksa izin verifikasi
        $allowed = false;
        if ($currentUser->role === 'administrator') {
            $allowed = true;
        } elseif ($currentUser->role === 'admin_desa' && $target->desa_id === $currentUser->desa_id) {
            $allowed = true;
        } elseif ($currentUser->role === 'peserta' && $currentUser->id === $target->id) {
            $allowed = true;
        }
        if (!$allowed) {
            abort(403);
        }
        $target->status_verifikasi = 'verified';
        $target->save();
        return redirect()->back()->with('success', 'Data peserta berhasil diverifikasi.');
    }
}