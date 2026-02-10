<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MilkCollectionController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Milk Collections
    Route::resource('milk', MilkCollectionController::class);

    // Production
    Route::resource('production', ProductionController::class);

    // Distribution
    Route::resource('distribution', DistributionController::class);

    // Debts
    Route::resource('debts', DebtController::class);
    Route::post('/debts/{debt}/payment', [DebtController::class, 'addPayment'])->name('debts.payment');

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
});
