<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GobogController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenjualanAdminController;
use App\Http\Controllers\PenjualanTenanController;
use App\Http\Controllers\ReturGobogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    Route::resource('gobog', GobogController::class)->names([
        'index' => 'gobog.index',
        'create' => 'gobog.create',
        'store' => 'gobog.store',
        'show' => 'gobog.show',
        'edit' => 'gobog.edit',
        'update' => 'gobog.update',
        'destroy' => 'gobog.destroy',
    ]);

    Route::get('/rekap-penjualan', [PenjualanAdminController::class, 'index'])->name('rekap-penjualan.index');
    Route::post('/rekap-penjualan/scan', [PenjualanAdminController::class, 'scan'])->name('rekap-penjualan.scan');
    Route::post('/rekap-penjualan', [PenjualanAdminController::class, 'store'])->name('rekap-penjualan.store');

    Route::get('/rekap-pengembalian', [ReturGobogController::class, 'index'])->name('rekap-pengembalian.index');
    Route::post('/rekap-pengembalian/scan', [ReturGobogController::class, 'scan'])->name('rekap-pengembalian.scan');
    Route::post('/rekap-pengembalian', [ReturGobogController::class, 'store'])->name('rekap-pengembalian.store');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::resource('users', UserController::class)->except(['show'])->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);
});

Route::prefix('penjual')->name('penjual.')->middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/dashboard', fn () => view('penjual.dashboard'))->name('dashboard');

    Route::get('/cek-keaslian', [PenjualanTenanController::class, 'cekIndex'])->name('cek-keaslian.index');
    Route::post('/cek-keaslian/scan', [PenjualanTenanController::class, 'cekScan'])->name('cek-keaslian.scan');

    Route::get('/scan-penjualan', [PenjualanTenanController::class, 'index'])->name('scan-penjualan.index');
    Route::post('/scan-penjualan/scan', [PenjualanTenanController::class, 'scan'])->name('scan-penjualan.scan');
    Route::post('/scan-penjualan/store', [PenjualanTenanController::class, 'store'])->name('scan-penjualan.store');
});
