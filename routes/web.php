<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Rute untuk Halaman Utama (akan mengarah ke login jika belum login)
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Autentikasi
Route::group(['middleware' => 'guest'], function () {
    // Halaman Login (127.0.0.1:8000/login)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Halaman Registrasi/Signup
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');
});

// Rute yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard (halaman setelah login)
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});