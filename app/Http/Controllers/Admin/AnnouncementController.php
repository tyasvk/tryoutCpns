<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => Announcement::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        Announcement::create($validated);

        return back()->with('message', 'Pengumuman berhasil dipublikasikan!');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $announcement->update($validated);

        return back()->with('message', 'Pengumuman diperbarui!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('message', 'Pengumuman dihapus!');
    }
}