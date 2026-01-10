<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $query->withCount('exams')->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only(['search']),
            'stats' => [
                'total_users' => User::count(),
                'total_admins' => User::where('is_admin', true)->count(),
                'new_today' => User::whereDate('created_at', today())->count(),
            ]
        ]);
    }

    // PERBAIKAN: Menambahkan simbol $ pada $user
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_admin' => 'required|boolean',
        ]);

        $user->update($data);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui');
    }

    // PERBAIKAN: Menambahkan simbol $ pada $user
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}