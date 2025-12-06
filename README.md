<p align="center">
    <a href="https://github.com/Anggertha-Afif-Fathiika-Yatin/coffouria" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<h1 align="center">SISTEM MANAJEMEN PRODUK COFFOURIA</h1>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10.x Badge">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+ Badge">
    <img src="https://img.shields.io/badge/Database-MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL Badge">
    <img src="https://img.shields.io/badge/Status-Production%20Ready-28a745?style=for-the-badge" alt="Status Badge">
</p>

***

## ☕ 1. Tentang Proyek

**Sistem Manajemen Produk Coffouria** adalah aplikasi web yang dikembangkan menggunakan **Laravel** untuk mengotomatisasi manajemen inventaris dan pelaporan penjualan harian pada *coffee shop*.

Aplikasi ini didesain untuk menyederhanakan alur kerja operasional dengan menyediakan fitur-fitur penting seperti **otomatisasi kode produk**, **pembaruan stok cerdas**, dan **laporan profitabilitas** yang akurat. 

***

## ✨ 2. Fitur Utama (Highlights)

| Ikon | Fitur | Deskripsi |
| :--- | :--- | :--- |
| **🔐** | **Modul Autentikasi** | Implementasi Login & Registrasi yang aman untuk pengguna sistem (Lihat `AuthController.php`). |
| **📦** | **Manajemen Produk (CRUD)** | Mengelola data produk secara lengkap (Lihat `ProductController.php`). |
| **🆔** | **Otomatisasi Kode Produk** | Kode produk dibuat otomatis berdasarkan kategori (Contoh: `HOT001`, `CLD002`) (Lihat `Product.php`). |
| **🔄** | **Smart Stock Management** | Stok diperbarui otomatis setelah pencatatan penjualan. Produk dinonaktifkan jika stok $\le 0$ (Lihat `ProductController.php`). |
| **💰** | **Laporan Profit Harian** | Pencatatan transaksi dan perhitungan otomatis **Profit** dan **Margin (%)** (Lihat `ReportController.php`). |
| **📊** | **Dashboard Analisis** | Menyajikan ringkasan visual performa bulanan dan produk terlaris (Lihat `DashboardController.php`). |
| **⬇️** | **Ekspor Data CSV** | Unduh laporan penjualan rinci dalam format CSV (didukung oleh `Maatwebsite/Laravel-Excel`) (Lihat `ReportsExport.php`). |

***

## 🛠️ 3. Persyaratan Teknis (Environment)

Untuk menjalankan proyek ini, pastikan Anda telah menginstal dan mengkonfigurasi:

| Kategori | Kebutuhan | Versi Minimum |
| :--- | :--- | :--- |
| **Core Framework** | Laravel Framework | $\ge 10.x$ |
| **Bahasa Pemrograman** | PHP | $\ge 8.2$ |
| **Database** | MySQL / MariaDB | $\ge 5.7$ |
| **Dependency Manager**| Composer | - |
| **Ekstensi PHP Wajib** | `pdo`, `mbstring`, `openssl`, `json`, `bcmath` | |

***

## 💻 4. Panduan Instalasi Lokal

Ikuti langkah-langkah *command line* ini untuk menyiapkan dan menjalankan proyek:

1.  **Kloning Repositori:**
    ```bash
    git clone [https://github.com/your-repo/coffouria.git](https://github.com/your-repo/coffouria.git)
    cd coffouria
    ```

2.  **Instalasi Dependensi:**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment:**
    ```bash
    cp .env.example .env
    ```
    ⚠️ **SETUP DB:** Edit file `.env` dan masukkan kredensial *database* Anda.

4.  **Setup Kunci Aplikasi & Database:**
    ```bash
    php artisan key:generate
    php artisan migrate --force
    php artisan storage:link
    ```

5.  **Jalankan Aplikasi:**
    ```bash
    php artisan serve
    ```
    Akses aplikasi Anda di: `http://127.0.0.1:8000/login`

***

## 🌐 5. Panduan Deployment (Produksi)

Proses *deployment* di server produksi dilakukan menggunakan metode **Git Pull** yang cepat:

```bash
# 1. Masuk ke direktori proyek di server
cd /var/www/coffouria

# 2. Tarik (Pull) kode terbaru dari branch utama
git pull origin main

# 3. Instal/Optimasi dependensi
composer install --no-dev --optimize-autoloader

# 4. Jalankan migrasi jika ada perubahan skema database
php artisan migrate --force

# 5. Bersihkan dan cache konfigurasi/view Laravel
php artisan optimize
