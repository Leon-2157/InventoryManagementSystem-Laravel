# Inventory Management System

A web-based inventory and stock management application built with Laravel 13, Eloquent ORM, and Bootstrap 5.

## Overview

Inventory Management System memfasilitasi pelacakan menyeluruh atas barang, pemasok, kategori, dan pergerakan stok. Dirancang untuk operasional ritel dan pergudangan dengan aturan bisnis yang ketat, mencakup pemotongan stok aman konkurensi (database transactions), pencegahan stok minus, serta pembagian hak akses berbasis peran (Role-Based Access Control).

## Arsitektur MVC Proyek

Aplikasi ini mengimplementasikan pola arsitektur **Model-View-Controller (MVC)** dari Laravel untuk memisahkan tanggung jawab (separation of concerns):

1. **Model (`app/Models/`)**
   - Mengelola entitas data, relasi tabel via Eloquent ORM, serta aturan bisnis inti.
   - **`User`**: Mengelola otentikasi akun, integrasi dengan skema otentikasi native lama (`password_hash`), dan penentuan peran (`role`).
   - **`UserProfile`**: Menyimpan detail profil pengguna dengan relasi `1-to-1` ke User.
   - **`Product`**: Menyimpan data barang, harga, kuantitas stok saat ini, batas minimum stok, relasi kategori, serta metode domain seperti `isLowStock()`.
   - **`Category`**: Pengelompokan produk dengan relasi `1-to-many` ke produk.
   - **`Supplier` & `ProductSupplier`**: Mengelola daftar pemasok dan relasi `many-to-many` dengan produk beserta riwayat harga pasokan.
   - **`StockMovement`**: Buku kas pergerakan stok (Stock In dan Stock Out) lengkap dengan referensi audit pengguna dan alasan transaksi.

2. **View (`resources/views/`)**
   - Menangani presentasi antarmuka dan interaksi pengguna berbasis template engine Blade.
   - Terstruktur secara modular:
     - `layouts/`: Kerangka layout utama (`app.blade.php`, `auth.blade.php`) dan navigasi responsif.
     - `components/`: Komponen UI reusable (alert notifikasi, badge status stok, modal konfirmasi).
     - Modul fitur: Tampilan form dan data tabel untuk `auth/`, `dashboard/`, `products/`, `suppliers/`, `stock/`, dan `users/`.
   - Antarmuka dibangun menggunakan Bootstrap 5 dan Bootstrap Icons tanpa dependensi framework JavaScript yang rumit.

3. **Controller (`app/Http/Controllers/`)**
   - Menangani siklus request HTTP, menghubungkan input pengguna dengan Model, dan menyajikan View respons.
   - **`AuthController`**: Menangani proses login, validasi session, dan logout.
   - **`DashboardController`**: Mengagregasi metrik ringkasan (total produk, stok rendah, ringkasan transaksi).
   - **`ProductController`**: Operasi CRUD produk lengkap dengan pencarian, filter kategori, dan pagination.
   - **`SupplierController`**: Operasi CRUD pemasok dan keterkaitan produk pasokan.
   - **`StockController`**: Memproses mutasi stok keluar/masuk di dalam transaksi database atomik (`DB::transaction`) untuk mencegah *race condition*.
   - **`UserController`**: Manajemen pengguna yang dibatasi khusus peran Administrator.
   - Validasi input didelegasikan ke kelas Form Request (`app/Http/Requests/`) untuk menjaga controller tetap ramping dan bersih.

---

## Prerequisites

- **Opsi Manual & Laragon / XAMPP**:
  - PHP >= 8.4
  - Composer >= 2.0
  - MySQL >= 8.0 atau MariaDB >= 10.5
- **Opsi Podman**:
  - Podman >= 4.0
  - Podman Compose (`podman-compose`)

---

## Quick Start & Panduan Instalasi

Pilih salah satu metode instalasi yang sesuai dengan lingkungan pengembangan Anda:

### Cara 1: Instalasi Manual CLI

1. **Instalasi dan Menjalankan Database MySQL / MariaDB di Terminal**
   Pastikan service database telah terpasang dan berjalan di sistem Anda:
   - **Fedora / RHEL / CentOS**:
     ```bash
     sudo dnf install -y mariadb-server
     sudo systemctl start mariadb
     sudo systemctl enable mariadb
     ```
   - **Ubuntu / Debian**:
     ```bash
     sudo apt update && sudo apt install -y mysql-server
     sudo systemctl start mysql
     sudo systemctl enable mysql
     ```
   - **Membuat Database `inventory`**:
     ```bash
     sudo mysql -e "CREATE DATABASE IF NOT EXISTS inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
     ```

2. **Install Dependensi Proyek**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Buka file `.env` dan sesuaikan koneksi database MySQL lokal:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Siapkan Direktori Cache & Storage**
   ```bash
   mkdir -p storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

5. **Jalankan Migrasi dan Seeder**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   Buka browser di `http://127.0.0.1:8000`.

---

### Cara 2: Menggunakan Laragon atau XAMPP (Windows)

#### 1. Penempatan Folder Proyek
- **Laragon**: Letakkan folder proyek di dalam direktori root Laragon:
  ```text
  C:\laragon\www\InventoryManagementSystem-Laravel
  ```
- **XAMPP**: Letakkan folder proyek di dalam direktori `htdocs` XAMPP:
  ```text
  C:\xampp\htdocs\InventoryManagementSystem-Laravel
  ```

#### 2. Menjalankan Service Web & Database
- Buka control panel Laragon atau XAMPP.
- Klik **Start All** (atau start service **Apache** dan **MySQL**).

#### 3. Membuat Database di phpMyAdmin
- Buka browser dan akses phpMyAdmin di `http://localhost/phpmyadmin`.
- Buat database baru dengan nama `inventory`.

#### 4. Setup Dependensi dan Environment (.env)
1. Install dependensi:
   ```bash
   composer install
   ```
2. Salin environment dan generate key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Sesuaikan parameter database di file `.env`. Secara bawaan, Laragon dan XAMPP menggunakan username `root` tanpa password:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory
   DB_USERNAME=root
   DB_PASSWORD=
   ```

#### 5. Jalankan Migrasi dan Data Awal
Eksekusi migrasi tabel dan seeding akun default:
```bash
php artisan migrate:fresh --seed
```

#### 6. Menjalankan / Mengakses Aplikasi
- **Menggunakan Artisan Serve (Paling Direkomendasikan)**:
  Jalankan di terminal proyek:
  ```bash
  php artisan serve
  ```
  Akses aplikasi di browser: `http://127.0.0.1:8000`.

- **Menggunakan Virtual Host Laragon**:
  `http://InventoryManagementSystem-Laravel.test`

- **Menggunakan Apache XAMPP**:
  `http://localhost/InventoryManagementSystem-Laravel/public`

---

### Cara 3: Menjalankan Menggunakan Podman (Containerized)

1. **Konfigurasi Environment Host**
   Salin file `.env` dan generate application key (jika belum ada):
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan konfigurasi database di `.env` merujuk ke service database container:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=inventory
   DB_USERNAME=inventory_user
   DB_PASSWORD=inventory_pass
   ```

2. **Atur Izin Direktori Storage (Wajib untuk Rootless Podman)**
   ```bash
   mkdir -p storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache
   chmod -R 777 storage bootstrap/cache
   ```

3. **Build dan Jalankan Container**
   ```bash
   podman compose up -d --build
   ```

4. **Jalankan Migrasi dan Data Awal di Dalam Container**
   ```bash
   podman exec -it inventorymanagementsystem-laravel_app_1 php artisan migrate:fresh --seed
   ```

5. **Akses Aplikasi**
   Buka browser di `http://localhost:8080`.

---

## Default Credentials

| Role | Username | Password |
|---|---|---|
| Administrator | `admin` | `admin123` |
| Staff | `staff` | `staff123` |

---

## Running Tests

Jalankan rangkaian pengujian otomatis fitur (Feature Tests):
```bash
php artisan test
```
