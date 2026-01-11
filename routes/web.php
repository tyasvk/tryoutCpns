<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Landing Page (Perbaikan Error "View not found")
// Menggunakan Inertia::render untuk memanggil file resources/js/Pages/Welcome.vue
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 2. Dashboard Redirect Logic
// Mengarahkan user ke dashboard yang sesuai berdasarkan Role saat login
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| GROUP: ADMIN
|--------------------------------------------------------------------------
| Middleware: auth (Login) & role:admin (Cek Role)
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard'); // Pastikan file Admin/Dashboard.vue ada
    })->name('dashboard');

    // Profile Admin (Inertia)
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
    // Gunakan POST untuk update data + file upload di Inertia (Form method spoofing)
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

});


/*
|--------------------------------------------------------------------------
| GROUP: USER (PESERTA)
|--------------------------------------------------------------------------
| Middleware: auth (Login) & role:user (Cek Role)
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', function () {
        return Inertia::render('User/Dashboard'); // Pastikan file User/Dashboard.vue ada
    })->name('dashboard');

    // Profile User (Inertia)
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');

});

// 3. Auth Routes (Bawaan Laravel Breeze/Inertia)
// File ini menghandle login, register, logout, reset password
require __DIR__.'/auth.php';