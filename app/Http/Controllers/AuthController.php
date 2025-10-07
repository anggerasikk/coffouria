<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Menampilkan halaman login (untuk 127.0.0.1:8000)
    public function showLogin()
    {
        if (Auth::check()) {
            // Jika user sudah login, arahkan ke dashboard/home
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Menampilkan halaman registrasi/signup
    public function showRegister()
    {
        return view('auth.register');
    }

    // Logika untuk proses registrasi/signup
    public function register(Request $request)
    {
        // Validasi data input
        $request->validate([
            'username' => 'required|string|max:255', // Asumsi 'username' adalah nama
            'email' => 'required|email|unique:users,email', // Email harus unik
            'password' => 'required|min:6',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Setelah registrasi, user diarahkan ke halaman login
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    // Logika untuk proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba otentikasi
        $credentials = $request->only('email', 'password');

        // Kita akan menggunakan kolom 'email' untuk login, seperti standar Laravel
        // Jika Anda benar-benar ingin menggunakan 'username' (bukan 'email'),
        // Anda perlu menyesuaikan validasi dan Auth::attempt, atau menambahkan kolom 'username'
        // ke tabel users dan model User. Untuk kepraktisan, kita asumsikan 'username' di form
        // adalah kolom 'email' di database, seperti pada gambar.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect setelah login
            return redirect()->intended('/dashboard');
        }

        // Gagal login
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    // Halaman setelah login (Dashboard)
    public function dashboard()
    {
        return view('dashboard');
    }

    // Logika untuk logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Kembali ke halaman login
        return redirect()->route('login');
    }
}