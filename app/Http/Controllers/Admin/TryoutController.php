<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TryoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Tryouts/Index', [
            'tryouts' => Tryout::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,false|numeric|min:0',
        ]);

        Tryout::create($validated);

        return back()->with('message', 'Paket Tryout berhasil dibuat!');
    }

    public function update(Request $request, Tryout $tryout)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'is_free' => 'required|boolean',
            'price' => 'required_if:is_free,false|numeric|min:0',
        ]);

        $tryout->update($validated);

        return back()->with('message', 'Paket Tryout diperbarui!');
    }

    public function destroy(Tryout $tryout)
    {
        $tryout->delete();
        return back()->with('message', 'Paket Tryout dihapus!');
    }
}