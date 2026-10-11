<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show'])->middleware('admin');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/stock-in', [TransactionController::class, 'stockInForm'])->name('transactions.stock-in');
    Route::post('transactions/stock-in', [TransactionController::class, 'stockIn']);
    Route::get('transactions/stock-out', [TransactionController::class, 'stockOutForm'])->name('transactions.stock-out');
    Route::post('transactions/stock-out', [TransactionController::class, 'stockOut']);
});
