<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\MonitoringController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// PUBLIC ROUTES
Route::get('/', fn() => Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
]))->name('welcome');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

// PROTECTED ROUTES
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // EXAM SYSTEM (USER)
    Route::controller(ExamController::class)->prefix('exam')->name('exam.')->group(function () {
        Route::get('/start', 'start')->name('start');
        Route::get('/{exam}', 'show')->name('show'); // Halaman pengerjaan soal
        Route::post('/{exam}/submit', 'submit')->name('submit');
        Route::post('/{exam}/violation', 'logViolation')->name('violation'); // Anti-Cheat API
        Route::get('/{exam}/result', 'result')->name('result');
        Route::get('/{exam}/review', 'review')->name('review');
    });

    Route::post('/marketplace/buy/{tryout}', [MarketplaceController::class, 'buy'])->name('marketplace.buy');

    // ADMIN SYSTEM
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('tryouts', TryoutController::class);
        Route::resource('questions', QuestionController::class);
        Route::resource('announcements', AnnouncementController::class);
        
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::patch('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');

        // MONITORING LIVE
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::post('/monitoring/{exam}/force-stop', [MonitoringController::class, 'forceStop'])->name('monitoring.force-stop');

        // Rute Tambahan: Kelola Soal di dalam Tryout
        Route::get('/tryouts/{tryout}/questions', [QuestionController::class, 'manage'])->name('tryouts.questions');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

require __DIR__.'/auth.php';