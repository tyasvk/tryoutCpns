<?php

use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Models\Exam;
use App\Models\Tryout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| CPNS Nusantara - Sistem Manajemen Rute Terpadu 2026
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN PUBLIK ---
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// --- 2. REDIRECTOR DASHBOARD (LOGIKA BENTO DASHBOARD) ---
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    return Inertia::render('Dashboard', [
        'stats' => [
            'twk' => (int) round(Exam::where('user_id', $user->id)->avg('score_twk') ?? 0),
            'tiu' => (int) round(Exam::where('user_id', $user->id)->avg('score_tiu') ?? 0),
            'tkp' => (int) round(Exam::where('user_id', $user->id)->avg('score_tkp') ?? 0),
        ],
        'totalExams' => Exam::where('user_id', $user->id)->where('status', 'completed')->count(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// --- 3. AREA PESERTA (SIMULASI CAT & WAITING ROOM) ---
Route::middleware('auth')->group(function () {
    
    // Waiting Room & Marketplace
    Route::get('/tryout/{tryout}/wait', [ExamController::class, 'waitingRoom'])->name('tryout.wait');
    Route::get('/marketplace', function() {
        return Inertia::render('Marketplace/Index', [
            'tryouts' => Tryout::where('is_published', true)->withCount('questions')->get()
        ]);
    })->name('marketplace.index');

    // Proses Ujian & Scoring
    Route::post('/exam/start', [ExamController::class, 'start'])->name('exam.start');
    Route::get('/exam/{exam}', [ExamController::class, 'show'])->name('exam.show');
    Route::post('/exam/{exam}/submit', [ExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exam/{exam}/result', [ExamController::class, 'result'])->name('exam.result');
    Route::get('/exam/{exam}/review', [ExamController::class, 'review'])->name('exam.review');
    Route::get('/exam-history', [ExamController::class, 'history'])->name('exam.history');

    // Profil Peserta
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- 4. AREA ADMIN (MODERN BENTO PANEL) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Overview Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Tryout (Paket Berbayar & TO Akbar)
    Route::resource('tryouts', TryoutController::class);

    // Manajemen Soal (Questions Management)
    Route::controller(QuestionController::class)->group(function () {
        // Index & Create
        Route::get('tryouts/{tryout}/questions', 'manage')->name('questions.manage');
        Route::get('tryouts/{tryout}/questions/create', 'create')->name('questions.create');
        Route::post('tryouts/{tryout}/questions', 'store')->name('questions.store');
        
        // Edit & Update (Fix Ziggy Error)
        Route::get('questions/{question}/edit', 'edit')->name('questions.edit');
        Route::patch('questions/{question}', 'update')->name('questions.update');
        
        // Preview & Action
        Route::get('questions/{question}/preview', 'preview')->name('questions.preview');
        Route::delete('questions/{question}', 'destroy')->name('questions.destroy');

        // Fitur Import Massal
        Route::get('tryouts/{tryout}/import', 'showImport')->name('questions.showImport');
        Route::post('tryouts/{tryout}/import', 'import')->name('questions.import');
        Route::get('questions/template', 'downloadTemplate')->name('questions.template');
    });

    // Manajemen Pengguna
    Route::resource('users', UserController::class);
});

// --- 5. AUTHENTICATION (BREEZE/JETSTREAM) ---
require __DIR__.'/auth.php';