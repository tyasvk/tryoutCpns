<?php

namespace App\Http\Controllers\Admin; // Perhatikan namespace-nya ada \Admin

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Question;

class DashboardController extends Controller
{
    public function index()
    {
        // Ini me-render tampilan yang berbeda jika Anda memisahkan file Vue-nya
        // Misalnya: resources/js/Pages/Admin/Dashboard.vue
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => User::count(),
                'total_admin' => User::where('is_admin', true)->count(),
                'total_questions' => Question::count(),
            ],
            // Mengambil 10 user terbaru
            'users' => User::latest()->take(10)->get()
        ]);
    }
}