<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;

// Rute untuk Halaman Utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Autentikasi
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');
});

// Rute yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD Laporan
    Route::resource('reports', ReportController::class);
    Route::get('/reports/product/{id}', [ReportController::class, 'getProductData'])->name('reports.product-data');
    
    // CRUD Produk
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

Route::get('/reports/export-summary/{month}/{year}', [ReportController::class, 'exportSummary'])
    ->name('reports.export.summary');