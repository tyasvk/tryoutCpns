<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    /**
     * Menampilkan halaman profil peserta.
     */
    public function index()
    {
        return Inertia::render('User/Profile/Index', [
            // Data Riwayat Tryout (Dummy/Hardcode sementara)
            // Nanti bisa diganti dengan: $user->tryouts()->get()
            'history' => [
                [
                    'id' => 1, 
                    'name' => 'Tryout SKD CPNS Akbar 2025', 
                    'score' => 450, 
                    'date' => '10 Jan 2025'
                ],
                [
                    'id' => 2, 
                    'name' => 'Latihan Soal TIU', 
                    'score' => 100, 
                    'date' => '09 Jan 2025'
                ],
                [
                    'id' => 3, 
                    'name' => 'Tryout TWK Harian', 
                    'score' => 85, 
                    'date' => '05 Jan 2025'
                ],
            ]
        ]);
    }

    /**
     * Memproses update data profil peserta.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input (Termasuk no hp dan alamat)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15', // Kolom tambahan user
            'address' => 'nullable|string|max:500', // Kolom tambahan user
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Update Foto
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        // 3. Update Data Diri
        $user->name = $validated['name'];
        
        // Pastikan kolom 'phone' dan 'address' ada di tabel users (migration)
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];

        // 4. Update Password
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Data profil berhasil disimpan.');
    }
}