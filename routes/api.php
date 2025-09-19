<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\PengaduanController;
use App\Http\Controllers\Api\Admin\NewsController;
use App\Http\Controllers\Api\Admin\AnnouncementController;
use App\Http\Controllers\Api\Admin\InfrastructureReportController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\AuditLogController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\HousingUnitController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\WorkOrderController;
use App\Http\Controllers\Api\Admin\ReportController; // <-- Tambahkan ini


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Grup untuk semua route admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::apiResource('users', UserController::class);

  Route::apiResource('pengaduan', PengaduanController::class);
Route::post('pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus']);

    Route::apiResource('news', NewsController::class);
    Route::apiResource('announcements', AnnouncementController::class);
    Route::apiResource('infrastructure-reports', InfrastructureReportController::class);
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
    Route::apiResource('housing-units', HousingUnitController::class);
    Route::apiResource('media', MediaController::class)->only([
        'index',
        'store',
        'destroy'
    ]);
    Route::apiResource('work-orders', WorkOrderController::class);
    Route::get('reports/export-complaints', [ReportController::class, 'exportComplaints'])
        ->name('reports.exportComplaints');


    // <-- TAMBAHKAN INI

});
