<?php

namespace App\Http\Controllers;

use App\Models\DetailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller untuk mengelola detail event.
 *
 * Superadmin dapat membuat, melihat, mengubah, dan menghapus event dari halaman ini.
 */
class DetailEventController extends Controller
{
    /**
     * Tampilkan daftar event.
     */
    public function index()
    {
        $events = DetailEvent::orderBy('pendaftaran_mulai', 'desc')->get();
        return view('event.index', compact('events'));
    }

    /**
     * Tampilkan form untuk membuat event baru.
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Simpan event baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan_aktif' => 'required|string|max:255',
            'waktu_pelaksanaan_mulai' => 'nullable|date',
            'waktu_pelaksanaan_selesai' => 'nullable|date|after_or_equal:waktu_pelaksanaan_mulai',
            'tempat_pelaksanaan' => 'nullable|string|max:255',
            'pendaftaran_mulai' => 'nullable|date',
            'pendaftaran_selesai' => 'nullable|date|after_or_equal:pendaftaran_mulai',
            'verif1_mulai' => 'nullable|date',
            'verif1_selesai' => 'nullable|date|after_or_equal:verif1_mulai',
            'verif2_mulai' => 'nullable|date',
            'verif2_selesai' => 'nullable|date|after_or_equal:verif2_mulai',
            'sanggah_mulai' => 'nullable|date',
            'sanggah_selesai' => 'nullable|date|after_or_equal:sanggah_mulai',
            'pengumuman_verifikasi' => 'nullable|date',
            'technical_meeting' => 'nullable|date',
            'logo_path' => 'nullable|string|max:255',
        ]);

        // Generate a unique slug from the event name
        $baseSlug = Str::slug($validated['nama_kegiatan_aktif']);
        $slug = $baseSlug;
        $counter = 1;
        while (DetailEvent::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        DetailEvent::create(array_merge($validated, ['slug' => $slug]));

        return redirect()->route('home')->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit event.
     */
    public function edit(DetailEvent $event)
    {
        return view('event.edit', compact('event'));
    }

    /**
     * Perbarui event di database.
     */
    public function update(Request $request, DetailEvent $event)
    {
        $validated = $request->validate([
            'nama_kegiatan_aktif' => 'required|string|max:255',
            'waktu_pelaksanaan_mulai' => 'nullable|date',
            'waktu_pelaksanaan_selesai' => 'nullable|date|after_or_equal:waktu_pelaksanaan_mulai',
            'tempat_pelaksanaan' => 'nullable|string|max:255',
            'pendaftaran_mulai' => 'nullable|date',
            'pendaftaran_selesai' => 'nullable|date|after_or_equal:pendaftaran_mulai',
            'verif1_mulai' => 'nullable|date',
            'verif1_selesai' => 'nullable|date|after_or_equal:verif1_mulai',
            'verif2_mulai' => 'nullable|date',
            'verif2_selesai' => 'nullable|date|after_or_equal:verif2_mulai',
            'sanggah_mulai' => 'nullable|date',
            'sanggah_selesai' => 'nullable|date|after_or_equal:sanggah_mulai',
            'pengumuman_verifikasi' => 'nullable|date',
            'technical_meeting' => 'nullable|date',
            'logo_path' => 'nullable|string|max:255',
        ]);

        $event->update($validated);

        return redirect()->route('event.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Hapus event dari database.
     */
    public function destroy(DetailEvent $event)
    {
        $event->delete();
        return redirect()->route('event.index')->with('success', 'Event berhasil dihapus.');
    }
}