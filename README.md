<h1 align="center">SISTEM MANAJEMEN PRODUK COFFEE SHOP (COFOURIA)</h1>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Status-Development-blue" alt="Development Status"></a>
  <a href="#"><img src="https://img.shields.io/badge/Built%20with-Laravel-red" alt="Framework"></a>
  <a href="#"><img src="https://img.shields.io/badge/Database-MySQL-orange" alt="Database"></a>
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/License-MIT-green" alt="License"></a>
</p>

---

## Tentang Proyek ☕

Sistem Manajemen Produk ini dirancang untuk mempermudah pengelolaan data produk, stok, kategori, dan brand pada sebuah coffee shop.  
Tujuan utama proyek ini adalah menyediakan *platform terpusat* untuk memastikan *konsistensi, akurasi, dan data produk yang up-to-date* serta meningkatkan efisiensi operasional.

Proyek ini dibangun untuk memenuhi kebutuhan bisnis tingkat tinggi, termasuk penyediaan dashboard produk, fungsionalitas CRUD untuk produk, kategori, dan brand, serta kemampuan menampilkan laporan stok dan riwayat pergerakan barang.

---

## Fitur Utama ✨

Berdasarkan Functional Requirements (FR) dan Use Case Diagram, sistem ini memiliki fungsionalitas inti berikut:

| Kebutuhan Fungsional (FR) | Deskripsi Fungsional | Stakeholder Terkait |
| :--- | :--- | :--- |
| *Manajemen Produk (CRUD)* | Memungkinkan *Admin Produk* untuk melakukan operasi Create, Read, Update, Delete (CRUD) pada data produk (nama, harga, deskripsi, gambar, kategori). | Admin Produk |
| *Manajemen Stok* | Mengelola data stok produk, termasuk melihat jumlah stok terkini dan riwayat pergerakan barang (masuk/keluar). | Admin Produk |
| *Manajemen Kategori & Brand* | Memungkinkan *Admin Produk* melakukan operasi CRUD terhadap data kategori dan brand produk. | Admin Produk |
| *Pelacakan Perubahan Data* | Mencatat dan menyimpan riwayat setiap perubahan data produk, mencakup informasi siapa yang mengubah, apa yang diubah, dan kapan. | Admin Produk |
| *Dashboard & Laporan* | Menyediakan dashboard ringkasan data produk dan laporan relevan, seperti laporan stok dan laporan penjualan sesuai periode. | Admin Produk |

---

## Komponen Teknis 💻

Proyek ini dikembangkan dengan mempertimbangkan Non Functional Requirements (NFR) dan menggunakan teknologi berikut:

| Kategori NFR | Spesifikasi Teknis |
| :--- | :--- |
| *Lingkungan Operasi* | Dapat berjalan pada web browser modern (Chrome, Firefox, Safari). |
| *Database* | Menggunakan *MySQL* untuk penyimpanan data (User, Produk, Stok, Kategori, Brand, History Log). |
| *Kinerja (Performance)* | Response time maksimal *3 detik* untuk semua operasi. Pembaruan stok atau data produk harus selesai dalam waktu kurang dari *1 detik*. |
| *Keamanan (Security)* | Semua akses harus melalui halaman login yang aman dengan otentikasi username dan password. Hanya pengguna dengan peran *Admin* yang memiliki hak untuk mengubah, menambah, atau menghapus data. |
| *Skalabilitas* | Sistem harus mampu menampung hingga *1.000 data produk* tanpa memengaruhi kinerja. |

---

## Struktur Pengguna (Aktor) 👥

Sistem ini mendukung dua peran utama:

1. **Admin Produk**
   - Memiliki hak akses penuh (full access).
   - Bertanggung jawab untuk input & update data produk.
   - Melakukan operasi CRUD pada produk, stok, kategori, dan brand.

---

## Instalasi Lokal 🚀

### Persyaratan Sistem

Pastikan Anda telah menginstal:
- PHP (versi yang kompatibel dengan Laravel)
- Composer
- XAMPP
- Visual Studio Code

### Langkah-langkah

1. Buka XAMPP, nyalakan Apache & MySQL
2. Buka CMD, pilih lokasi untuk folder projek
   ```bash
   cd C:folder/name
3. Clone projek ini
   ```bash
   git clone https://github.com/anggerasikk/coffouria.git
4. Tambahkan path folder projek
   ```bash
   cd coffouria
5. Masuk ke text editor Visual Studio Code
   ```bash
   code .
6. Install Composer
   ```bash
   composer install
7. Buat file .env
   ```bash
   cp .env.example .env
8. Uncomment baris pada .env yang terhide/tercomment dan masukkan sesuai dibawah ini
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_coffouria
   DB_USERNAME=root
   DB_PASSWORD=
9. Tambahkan APP_KEY
    ```bash
    php artisan key:generate
10. Tambahkan tabel migration lalu pilih yes
    ```bash
    php artisan migrate
11. Jalankan projek
    ```bash
    php artisan serve
12. Buka projek di url [localhost:8000](http://127.0.0.1:8000/)
