<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// ROUTE UMUM (Semua yang login bisa akses)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================
    // BLOK 1: KHUSUS ADMIN SAJA
    // ====================================================
    Route::middleware(['admin'])->group(function () {
        // Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
    });

    // ====================================================
    // BLOK 2: KHUSUS USER SAJA
    // ====================================================
    Route::middleware(['user'])->group(function () {
        Route::resource('wallets', WalletController::class);
        Route::resource('transactions', TransactionController::class);
        Route::resource('transfers', TransferController::class);
    });
});

require __DIR__ . '/auth.php';
