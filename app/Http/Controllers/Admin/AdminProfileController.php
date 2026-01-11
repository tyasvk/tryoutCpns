<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan halaman profil admin.
     */
    public function index()
    {
        return Inertia::render('Admin/Profile/Index', [
            // Kirim data tambahan (Statistik Dummy)
            'stats' => [
                'login_count' => 150, 
                'last_ip' => request()->ip(),
                'joined_at' => Auth::user()->created_at->format('d M Y'),
            ],
        ]);
    }

    /**
     * Memproses update data profil admin.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', // Opsional, hanya jika ingin ganti
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // 2. Update Foto (Jika ada upload baru)
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (agar storage tidak penuh)
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        // 3. Update Data Teks
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // 4. Update Password (Hanya jika diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Redirect kembali dengan notifikasi (Inertia otomatis handle flash message)
        return back()->with('success', 'Profil admin berhasil diperbarui.');
    }
}