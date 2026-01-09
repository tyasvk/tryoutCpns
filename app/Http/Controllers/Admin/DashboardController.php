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
    return Inertia::render('Admin/Dashboard', [
        'stats' => [
            'total_users'   => \App\Models\User::where('is_admin', false)->count(),
            'total_tryouts' => \App\Models\Tryout::count(),
            'total_exams'   => \App\Models\Exam::count(),
            'passed_today'  => \App\Models\Exam::where('is_passed', true)
                                ->whereDate('completed_at', now())->count(),
        ],
        'recent_exams' => \App\Models\Exam::with(['user', 'tryout'])
                            ->latest()
                            ->take(5)
                            ->get()
    ]);
}
}