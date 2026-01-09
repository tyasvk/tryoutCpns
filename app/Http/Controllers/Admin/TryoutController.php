<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TryoutController extends Controller
{
    /**
     * Menampilkan daftar semua paket tryout.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Tryouts/Index', [
            // withCount('questions') sangat penting agar data questions_count muncul di Vue
            'tryouts' => Tryout::withCount('questions')->latest()->get()
        ]);
    }

    /**
     * Menampilkan form pembuatan paket baru.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Tryouts/Create');
    }

    /**
     * Menyimpan paket tryout baru ke database.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:free,paid,grand_to',
        'duration' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'price' => 'required_if:type,paid,grand_to|integer|min:0',
        'start_at' => 'required_if:type,grand_to|nullable|date',
        'end_at' => 'required_if:type,grand_to|nullable|date|after:start_at',
        'is_published' => 'boolean',
    ]);

    Tryout::create($validated);

    return redirect()->route('admin.tryouts.index')
        ->with('message', 'Paket Tryout berhasil dibuat!');
}

    /**
     * Menampilkan form edit paket.
     */
    public function edit(Tryout $tryout): Response
    {
        return Inertia::render('Admin/Tryouts/Edit', [
            'tryout' => $tryout
        ]);
    }

    /**
     * Memperbarui data paket tryout.
     */
    public function update(Request $request, Tryout $tryout)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'type' => 'required|in:free,paid,grand',
            'is_published' => 'required|boolean',
        ]);

        $tryout->update($validated);

        return redirect()->route('admin.tryouts.index')
            ->with('message', 'Data paket berhasil diperbarui!');
    }

    /**
     * Menghapus paket tryout.
     */
    public function destroy(Tryout $tryout)
    {
        $tryout->delete();

        return redirect()->route('admin.tryouts.index')
            ->with('message', 'Paket berhasil dihapus dari sistem.');
    }
}