<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

/**
 * Controller for managing announcements (Pengumuman).
 *
 * Only users with role 'administrator' are permitted to create, edit,
 * or delete announcements. All authenticated users may view the list
 * of announcements and individual announcement pages.
 */
class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $announcements = Announcement::with('user')->latest()->paginate(10);
        $user = auth()->user();
        return view('announcements.index', compact('announcements', 'user'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        // Only superadmin can create
        $this->authorizeAdmin();
        return view('announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        $this->authorizeAdmin();
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $this->authorizeAdmin();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $announcement->update($validated);
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorizeAdmin();
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Helper: ensure the current user is an administrator.
     * If not, abort with 403.
     */
    protected function authorizeAdmin()
    {
        $user = auth()->user();
        if (! $user || $user->role !== 'administrator') {
            abort(403);
        }
    }
}