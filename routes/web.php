<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\InfrastructureController;
use App\Http\Controllers\BreakdownLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Models\Entity;
use App\Models\Infrastructure;
use App\Models\BreakdownLog;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik / Landing Page (Portal Utama)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    // Ambil data untuk katalog utama
    $infrastructures = Infrastructure::all();

    $entities = Entity::with('infrastructures')->get();

    // Ambil log insiden aktif (yang belum sembuh/resolved)
    $breakdowns = BreakdownLog::with(['infrastructure' => fn($q) => $q->withTrashed()->with('entity')])
        ->where('repair_status', '!=', 'resolved')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('welcome', compact('infrastructures', 'entities', 'breakdowns'));
})->name('home');

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

        // 2. Fitur Analytics & Export
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/export/process', [ExportController::class, 'process'])->name('export.process');

        // 3. Resource Operasional (Akses Semua Level)
        Route::resource('infrastructures', InfrastructureController::class);
        Route::resource('breakdowns', BreakdownLogController::class);
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
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // 4. Manajemen Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
