<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MasterWalletController;

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
        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);

        // PERBAIKAN: Dipindah ke blok Admin dan 'create' diaktifkan kembali (hanya except 'show')
        Route::resource('master-wallets', MasterWalletController::class)->except(['show']);
    });

    // ====================================================
    // BLOK 2: KHUSUS USER SAJA
    // ====================================================
    Route::middleware(['user'])->group(function () {
        Route::resource('wallets', WalletController::class);
        Route::resource('transactions', TransactionController::class);
        Route::resource('transfers', TransferController::class);
        Route::resource('debts', DebtController::class);
        Route::get('/debts/{debt}/settle', [DebtController::class, 'settle'])->name('debts.settle');
        Route::resource('budgets', BudgetController::class)->except(['show', 'edit', 'update']);

        Route::resource('recurring-transactions', RecurringTransactionController::class);
        Route::patch('recurring-transactions/{recurringTransaction}/toggle', [RecurringTransactionController::class, 'toggle'])->name('recurring-transactions.toggle');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    });
});

require __DIR__ . '/auth.php';
