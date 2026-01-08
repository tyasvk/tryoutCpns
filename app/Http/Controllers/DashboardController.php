<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Question; // Pastikan model Question sudah dibuat

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard terpadu.
     * Fungsi ini WAJIB ada agar error "undefined method index" hilang.
     */
    public function index()
    {
        $user = auth()->user();

        // LOGIKA KHUSUS ADMIN
        if ($user && $user->is_admin) {
            return Inertia::render('Dashboard', [
                'isAdmin' => true,
                'stats' => [
                    'total_users' => User::count(),
                    // Menggunakan query builder agar tidak error jika tabel belum ada isinya
                    'total_questions' => Question::query()->count(), 
                    'new_users_today' => User::whereDate('created_at', now())->count(),
                ],
                'recent_users' => User::latest()->take(5)->get(),
            ]);
        }

        // LOGIKA KHUSUS USER BIASA (SISWA)
        return Inertia::render('Dashboard', [
            'isAdmin' => false,
            'userStats' => [
                'tests_taken' => 0, // Nanti diganti dengan data real dari tabel hasil ujian
                'average_score' => 0,
                'last_activity' => 'Belum ada riwayat ujian',
            ],
        ]);
    }
}