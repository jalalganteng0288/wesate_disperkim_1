<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Import semua Controller Admin kita agar lebih rapi
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserPageController;
use App\Http\Controllers\Admin\PengaduanPageController;
use App\Http\Controllers\Admin\BeritaPageController;
use App\Http\Controllers\Admin\InfrastructureReportPageController;
use App\Http\Controllers\Admin\HousingUnitPageController;
use App\Http\Controllers\Admin\AnnouncementPageController;
use App\Http\Controllers\Admin\MediaPageController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\WorkOrderPageController;
use App\Http\Controllers\Admin\SettingPageController; // Tambahkan ini di bagian atas
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\KecamatanController; // <-- INI PERBAIKANNYA
// <-- Tambahkan ini


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
    Route::patch('pengaduan/{pengaduan}/status', [PengaduanPageController::class, 'updateStatus'])->name('pengaduan.updateStatus');

    Route::resource('berita', BeritaPageController::class);

    Route::resource('infrastruktur', InfrastructureReportPageController::class)
        ->parameters(['infrastruktur' => 'infrastructureReport']) // <-- TAMBAHKAN BARIS INI
        ->only(['index', 'show']);

    Route::patch('infrastruktur/{infrastructureReport}/status', [InfrastructureReportPageController::class, 'updateStatus'])->name('infrastruktur.updateStatus');

    Route::resource('perumahan', HousingUnitPageController::class);

    Route::resource('pengumuman', AnnouncementPageController::class);

    Route::get('media', [MediaPageController::class, 'index'])->name('media.index');
    Route::post('media', [MediaPageController::class, 'store'])->name('media.store');
    Route::delete('media/{media}', [MediaPageController::class, 'destroy'])->name('media.destroy');

    Route::get('peta', [MapController::class, 'index'])->name('map.index');
    Route::get('peta/lokasi', [MapController::class, 'locations'])->name('map.locations');

    Route::resource('penugasan', WorkOrderPageController::class);
    Route::get('reports/complaints/pdf', [App\Http\Controllers\Admin\ReportController::class, 'exportComplaintsPDF'])->name('reports.complaints.pdf');
    Route::get('reports/complaints/csv', [App\Http\Controllers\Admin\ReportController::class, 'exportComplaintsCSV'])->name('reports.complaints.csv');
    Route::post('penugasan/{penugasan}/notify', [WorkOrderPageController::class, 'sendNotification'])->name('penugasan.notify');
    Route::get('pengaturan', [SettingPageController::class, 'index'])->name('pengaturan.index');
    Route::patch('pengaturan/profile', [SettingPageController::class, 'updateProfile'])->name('pengaturan.updateProfile'); // <-- Rute untuk update profil
    Route::patch('pengaturan/password', [SettingPageController::class, 'updatePassword'])->name('pengaturan.updatePassword'); // <-- Rute untuk update password
    Route::delete('pengaturan/profile-photo', [SettingPageController::class, 'deleteProfilePhoto'])->name('pengaturan.deleteProfilePhoto');
    Route::patch('pengaturan/umum', [SettingPageController::class, 'updateGeneral'])->name('pengaturan.updateGeneral');
    Route::delete('pengaturan/app-logo', [SettingPageController::class, 'deleteAppLogo'])->name('pengaturan.deleteAppLogo');
    Route::patch('pengaturan/notifikasi', [SettingPageController::class, 'updateNotifications'])->name('pengaturan.updateNotifications');
    Route::patch('pengaturan/tampilan', [SettingPageController::class, 'updateAppearance'])->name('pengaturan.updateAppearance');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::get('kecamatan', [\App\Http\Controllers\Admin\KecamatanController::class, 'index'])->name('kecamatan.index');

});
// =====================================================================


// Route untuk profil pengguna (bawaan dari Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Baris ini memuat semua route untuk otentikasi (login, register, logout, dll.)
require __DIR__ . '/auth.php';
