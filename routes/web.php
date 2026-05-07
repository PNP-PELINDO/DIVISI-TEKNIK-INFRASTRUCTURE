<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\InfrastructureController;
use App\Http\Controllers\BreakdownLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Models\Entity;
use App\Models\Infrastructure;
use App\Models\BreakdownLog;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;

/*
|--------------------------------------------------------------------------
| Rute Publik / Landing Page (Portal Utama)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Rute Terproteksi (Hanya Bisa Diakses Setelah Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Kita buat grup 'admin' untuk membungkus semua fitur manajemen
    Route::prefix('admin')->name('admin.')->group(function () {

        // 1. Dashboard Utama (Sekarang namanya menjadi admin.dashboard)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 2. Fitur Export
        Route::get('/export/process', [ExportController::class, 'process'])->name('export.process');

        // 3. Resource Operasional (Akses Semua Level)
        Route::resource('infrastructures', InfrastructureController::class);
        Route::resource('breakdowns', BreakdownLogController::class);
        Route::get('breakdowns/{breakdown}/proof', [BreakdownLogController::class, 'downloadProof'])->name('breakdowns.proof');
        Route::resource('maintenance', MaintenanceScheduleController::class);

        /* --- KHUSUS AKSES SUPERADMIN (Administrator Pusat) --- */
        Route::middleware(['superadmin'])->group(function () {
            // Manajemen Bagian/Terminal
            Route::resource('entities', EntityController::class);
            // Manajemen Akun Pegawai
            Route::resource('users', UserController::class);
        });

    });

    // Alias untuk memudahkan jika ada yang mengetik /dashboard secara manual
    Route::get('/dashboard', function() {
        return redirect()->route('admin.dashboard', request()->query());
    })->name('dashboard');

    // 4. Manajemen Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
