<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::select('id', 'name', 'email', 'is_admin', 'created_at')
                ->latest()
                ->get()
        ]);
    }

    public function toggleAdmin(User $user)
    {
        // Mencegah user mencopot akses admin dirinya sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa mengubah status admin sendiri.');
        }

        $user->update(['is_admin' => !$user->is_admin]);
        return back();
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $user->delete();
        return back();
    }
}