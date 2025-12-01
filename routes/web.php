<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;


// Rute untuk Halaman Utama
// single root route kept below (redirects to reports.index)
 
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
    
    // CRUD Laporan (use string controller references to avoid undefined import)
    Route::resource('reports', 'App\Http\Controllers\ReportController')->except(['show']);
    Route::get('/reports/product/{id}', 'App\Http\Controllers\ReportController@getProductData')->name('reports.product-data');
    Route::get('/reports/export', 'App\Http\Controllers\ReportController@export')->name('reports.export');
    
    // CRUD Produk
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Route home/dashboard jika ada
Route::get('/', function () {
    return redirect()->route('reports.index');
});

