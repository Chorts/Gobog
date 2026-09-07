<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GobogController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjualanAdminController;
use App\Http\Controllers\PenjualanTenanController;
use App\Http\Controllers\ReturGobogController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('penjual.dashboard');
    }

    return redirect()->route('login');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // Kelola koin gobog (CRUD)
    Route::resource('gobog', GobogController::class)->names([
        'index' => 'gobog.index',
        'create' => 'gobog.create',
        'store' => 'gobog.store',
        'show' => 'gobog.show',
        'edit' => 'gobog.edit',
        'update' => 'gobog.update',
        'destroy' => 'gobog.destroy',
    ]);

    // Distribusi koin ke pengunjung
    Route::get('/distribusi', [PenjualanAdminController::class, 'index'])->name('distribusi.index');
    Route::post('/distribusi', [PenjualanAdminController::class, 'store'])->name('distribusi.store');

    // Retur koin dari pengunjung
    Route::get('/retur', [ReturGobogController::class, 'index'])->name('retur.index');
    Route::post('/retur/scan', [ReturGobogController::class, 'scan'])->name('retur.scan');
    Route::post('/retur', [ReturGobogController::class, 'store'])->name('retur.store');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
});

// ── Penjual ───────────────────────────────────────────────────────────────────
Route::prefix('penjual')->name('penjual.')->middleware(['auth', 'role:penjual'])->group(function () {

    Route::get('/dashboard', fn () => view('penjual.dashboard'))->name('dashboard');

    // Scan koin
    Route::get('/scan', [PenjualanTenanController::class, 'index'])->name('scan.index');
    Route::post('/scan', [PenjualanTenanController::class, 'scan'])->name('scan.scan');
    Route::post('/scan/store', [PenjualanTenanController::class, 'store'])->name('scan.store');
});
