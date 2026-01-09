<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    // 1. Daftar Semua User
    public function index(Request $request)
    {
        $users = User::where('is_admin', false) // Hanya user biasa
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search'])
        ]);
    }

    // 2. Lihat Detail & Riwayat Ujian User
    public function show(User $user)
    {
        $exams = Exam::with('tryout')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'exams' => $exams
        ]);
    }

    // 3. Update Data User
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            // Password opsional jika ingin admin bisa reset password
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('message', 'Data user berhasil diperbarui.');
    }

    // 4. Hapus User
    public function destroy(User $user)
    {
        // Opsional: Hapus juga riwayat ujiannya jika perlu
        $user->delete();
        return redirect()->route('admin.users.index')->with('message', 'User berhasil dihapus.');
    }
}