<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\TryoutController as AdminTryout;
use App\Http\Controllers\Admin\QuestionController as AdminQuestion;
use App\Http\Controllers\Admin\UserController as AdminUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Tryout;
use App\Models\Exam;

// --- PUBLIK ---
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// --- DASHBOARD REDIRECTOR ---
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->is_admin) return redirect()->route('admin.dashboard');

    return Inertia::render('Dashboard', [
        'stats' => [
            'twk' => (int) round(Exam::where('user_id', $user->id)->avg('score_twk') ?? 0),
            'tiu' => (int) round(Exam::where('user_id', $user->id)->avg('score_tiu') ?? 0),
            'tkp' => (int) round(Exam::where('user_id', $user->id)->avg('score_tkp') ?? 0),
        ],
        'totalExams' => Exam::where('user_id', $user->id)->where('status', 'completed')->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// --- AREA PESERTA (CPNS NUSANTARA) ---
Route::middleware('auth')->group(function () {
    
    // Profile Akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Marketplace & Leaderboard
    Route::get('/marketplace', function() {
        return Inertia::render('Marketplace/Index', [
            'tryouts' => Tryout::where('is_published', true)->withCount('questions')->get()
        ]);
    })->name('marketplace.index');

    // Analisis Progres
    Route::get('/analysis', [ExamController::class, 'analysis'])->name('exam.analysis');

    // Fitur Ujian CAT
    Route::controller(ExamController::class)->group(function () {
        Route::get('/tryout/{tryout}/wait', 'waitingRoom')->name('tryout.wait');
        Route::get('/tryout/{tryout}/leaderboard', 'leaderboard')->name('tryout.leaderboard');
        
        Route::post('/exam/start', 'start')->name('exam.start');
        Route::get('/exam/{exam}', 'show')->name('exam.show');
        Route::post('/exam/{exam}/submit', 'submit')->name('exam.submit');
        Route::get('/exam/{exam}/result', 'result')->name('exam.result');
        Route::get('/exam/{exam}/review', 'review')->name('exam.review');
        Route::get('/exam/{exam}/certificate', 'certificate')->name('exam.certificate');
        Route::get('/exam-history', 'history')->name('exam.history');
    });
});

// --- AREA ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('tryouts', AdminTryout::class);
    Route::resource('users', AdminUser::class);
    
    // Management Soal
    Route::get('tryouts/{tryout}/questions', [AdminQuestion::class, 'manage'])->name('questions.manage');
    Route::get('tryouts/{tryout}/questions/create', [AdminQuestion::class, 'create'])->name('questions.create');
    Route::post('tryouts/{tryout}/questions', [AdminQuestion::class, 'store'])->name('questions.store');
    Route::get('questions/{question}/edit', [AdminQuestion::class, 'edit'])->name('questions.edit');
    Route::patch('questions/{question}', [AdminQuestion::class, 'update'])->name('questions.update');
    Route::delete('questions/{question}', [AdminQuestion::class, 'destroy'])->name('questions.destroy');

    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';