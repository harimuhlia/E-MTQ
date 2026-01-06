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
        $user = auth()->user();
        // Filter announcements by the selected event. If no event is selected,
        // return an empty paginator so that no announcements are shown. This
        // prevents announcements created for one event from appearing in other
        // events as reported by the user.
        $selectedEventId = session('selected_event_id');
        if ($selectedEventId) {
            $announcements = Announcement::where('detail_event_id', $selectedEventId)
                ->with('user')
                ->latest()
                ->paginate(10);
        } else {
            // Create an empty collection with pagination to avoid errors in the view
            $announcements = Announcement::whereRaw('1 = 0')->paginate(10);
        }
        return view('announcements.index', compact('announcements', 'user'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        // Only superadmin can create
        $this->authorizeAdmin();
        // Require an event to be selected before creating an announcement
        $selectedEventId = session('selected_event_id');
        if (! $selectedEventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum membuat pengumuman.');
        }
        $event = \App\Models\DetailEvent::find($selectedEventId);
        return view('announcements.create', compact('event'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        // Ensure an event is selected; announcements are tied to an event
        $selectedEventId = session('selected_event_id');
        if (! $selectedEventId) {
            return redirect()->route('home')->with('error', 'Pilih event terlebih dahulu sebelum membuat pengumuman.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'detail_event_id' => $selectedEventId,
        ]);
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        // Only display announcement if it belongs to the selected event (if any)
        $selectedEventId = session('selected_event_id');
        if ($selectedEventId && $announcement->detail_event_id != $selectedEventId) {
            // If an announcement is requested outside of its event context, deny access
            abort(403);
        }
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