<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::resource('/admin/barang', BarangController::class)
        ->names('barang')
        ->parameters(['barang' => 'barang'])
        ->except('show');
    Route::get('/admin/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/admin/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian.index');

    Route::get('/user/dashboard', [DashboardController::class, 'user'])->name('user.dashboard');
    Route::get('/user/peminjaman/{barang}/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/user/peminjaman/{barang}', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'return'])->name('peminjaman.return');
});
