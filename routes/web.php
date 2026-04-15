<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\InfrastructureController;
use App\Http\Controllers\BreakdownLogController;
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
    $infrastructures = Infrastructure::all();

    $entities = Entity::with('infrastructures')->get()->map(function($entity) {
        $entity->Infrastructure_stats_by_cat = [
            'equipment' => $entity->infrastructures->where('category', 'equipment')->groupBy('type')->map(fn($items) => [
                'available' => $items->where('status', 'available')->count(),
                'breakdown' => $items->where('status', 'breakdown')->count(),
            ]),
            'facility' => $entity->infrastructures->where('category', 'facility')->groupBy('type')->map(fn($items) => [
                'available' => $items->where('status', 'available')->count(),
                'breakdown' => $items->where('status', 'breakdown')->count(),
            ]),
            'utility' => $entity->infrastructures->where('category', 'utility')->groupBy('type')->map(fn($items) => [
                'available' => $items->where('status', 'available')->count(),
                'breakdown' => $items->where('status', 'breakdown')->count(),
            ]),
        ];
        return $entity;
    });

    $breakdowns = BreakdownLog::with('infrastructure.entity')
        ->where('repair_status', '!=', 'resolved')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('welcome', compact('infrastructures', 'entities', 'breakdowns'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Rute Admin & Auth (Hanya Bisa Diakses Setelah Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Rute Dashboard Utama Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Rute CRUD Master Data (Diberi prefix /admin/...)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('entities', EntityController::class);
        Route::resource('infrastructures', InfrastructureController::class);
        Route::resource('breakdowns', BreakdownLogController::class);
    });

    // 3. Rute untuk manajemen profil (Bawaan Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat rute autentikasi bawaan (login, logout, forgot password)
require __DIR__.'/auth.php';
