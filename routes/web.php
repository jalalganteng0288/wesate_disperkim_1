<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Import semua Controller Admin kita
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserPageController;
use App\Http\Controllers\Admin\PengaduanPageController;
use App\Http\Controllers\Admin\BeritaPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama, langsung arahkan ke halaman login
Route::get('/', function () {
    return redirect('/login');
});

// Route bawaan dari Breeze untuk /dashboard.
// Kita modifikasi agar secara otomatis mengarahkan pengguna ke panel admin kita.
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =====================================================================
// == GRUP ROUTE UNTUK SEMUA HALAMAN ADMIN PANEL ==
// =====================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Route untuk halaman utama admin /admin/dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk semua fungsi CRUD (index, create, store, edit, update, destroy)
    Route::resource('users', UserPageController::class);
    Route::resource('pengaduan', PengaduanPageController::class);
    Route::resource('berita', BeritaPageController::class);

});
// =====================================================================


// Route untuk profil pengguna (bawaan dari Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Baris ini memuat semua route untuk otentikasi (login, register, logout, dll.)
require __DIR__.'/auth.php';

