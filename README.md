# UdinGalleries

UdinGalleries adalah platform toko online berbasis Laravel yang berfokus pada penjualan karya seni digital maupun fisik. Proyek ini dirancang untuk menjadi pondasi sistem e-commerce sederhana namun fleksibel untuk produk kreatif.

## ✨ Fitur Utama
- Manajemen produk (CRUD lengkap)
- Fitur keranjang belanja
- Pengaturan jumlah dan harga produk secara dinamis
- Proses checkout sederhana
- Autentikasi pengguna (login dan register)
- Integrasi tampilan dengan Blade & TailwindCSS
- Struktur backend modular dan mudah dikembangkan

## 🧩 Teknologi yang Digunakan
- **Laravel 11+**
- **PHP 8+**
- **Composer**
- **MySQL**
- **TailwindCSS**

## 🏗️ Struktur Direktori
```
app/             → Logika utama aplikasi (controller, model, service)
bootstrap/       → File bootstrap awal Laravel
config/          → Konfigurasi aplikasi (database, mail, session, dll)
database/        → Migrasi dan seeder database
public/          → File publik (index.php, assets, dll)
resources/       → View Blade, file Tailwind, dan aset front-end
routes/          → Web dan API routes
storage/         → Log dan file yang dihasilkan sistem
tests/           → Unit dan feature test
```

## ⚙️ Cara Instalasi (Local Setup)
1. Clone repositori:
   ```bash
   git clone https://github.com/fazallsaid/udingalleries.git
   ```
2. Masuk ke direktori proyek:
   ```bash
   cd udingalleries
   ```
3. Install dependensi PHP:
   ```bash
   composer install
   ```
4. Salin file environment:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Atur koneksi database di file `.env`
   Contoh:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=udingalleries
   DB_USERNAME=root
   DB_PASSWORD=
   ```
7. Jalankan migrasi:
   ```bash
   php artisan migrate --seed
   ```
8. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

Akses aplikasi di: [http://localhost:8000](http://localhost:8000)

## 🌐 Versi Online
Proyek ini juga telah di-deploy di:
👉 [https://udingallery.com](https://udingallery.com)

## 🧠 Kontribusi
1. Fork repositori ini
2. Buat branch baru: `git checkout -b fitur-baru`
3. Commit perubahan: `git commit -m "Menambah fitur X"`
4. Push branch: `git push origin fitur-baru`
5. Ajukan pull request

## 📄 Lisensi
Proyek ini menggunakan lisensi **MIT**, yang berarti bebas digunakan, dimodifikasi, dan dikembangkan dengan mencantumkan atribusi kepada pengembang asli.
