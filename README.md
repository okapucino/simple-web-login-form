# Sistem Login dan Registrasi Web Sederhana

Aplikasi web sederhana untuk manajemen login dan registrasi mahasiswa dengan antarmuka yang modern dan responsif. Proyek ini dibuat menggunakan PHP, MySQL, HTML5, dan CSS3.

## 📋 Daftar Isi
- [Deskripsi](#deskripsi)
- [Fitur Utama](#fitur-utama)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Penggunaan](#penggunaan)
- [Struktur File](#struktur-file)
- [Validasi Data](#validasi-data)
- [Catatan Penting](#catatan-penting)

## 🎯 Deskripsi

Sistem Login dan Registrasi Web Sederhana adalah aplikasi berbasis web yang memungkinkan mahasiswa untuk melakukan registrasi dan login ke dalam sistem. Aplikasi ini dilengkapi dengan:

- Halaman login yang aman
- Halaman registrasi dengan validasi data lengkap
- Form untuk pengisian data profil mahasiswa
- Database MySQL untuk penyimpanan data
- Antarmuka yang user-friendly dan responsif
- Sistem upload foto profil

## ✨ Fitur Utama

### 1. **Halaman Login**
   - Input username dan password
   - Validasi kredensial pengguna
   - Desain modern dengan gradient background
   - Responsive untuk semua ukuran layar

### 2. **Halaman Registrasi/Profil Mahasiswa**
   - Input data pribadi (nama, NIM, email, tanggal lahir)
   - Pilihan jenis kelamin
   - Input alamat
   - Pilihan program studi
   - Pilihan jenis mahasiswa (Beasiswa/Reguler)
   - Validasi jenis UKT berdasarkan jenis mahasiswa
   - Upload foto profil (JPEG/PNG)
   - Validasi lengkap untuk setiap field

### 3. **Desain Responsif**
   - Glassmorphism design pattern
   - Responsive layout untuk desktop, tablet, dan mobile
   - Animasi smooth dan transisi
   - Shadow dan blur effects modern

### 4. **Validasi Data**
   - Validasi nama (hanya huruf dan spasi)
   - Validasi NIM (hanya angka)
   - Validasi email (format valid)
   - Validasi tanggal lahir
   - Validasi ukuran dan format file gambar
   - Error handling yang baik

## 💻 Persyaratan Sistem

- **Web Server**: Apache (XAMPP/LAMPP)
- **PHP**: Versi 7.4 atau lebih tinggi
- **MySQL**: Versi 5.7 atau lebih tinggi
- **Browser**: Modern browser yang support ES6 (Chrome, Firefox, Safari, Edge)
- **Font**: Poppins (dari Google Fonts)

## 🚀 Instalasi

### 1. Persiapan Direktori
```bash
# Copy folder proyek ke htdocs (XAMPP) atau public_html
cp -r simple-web-login-form /path/to/xampp/htdocs/
```

### 2. Aktifkan Apache dan MySQL
```bash
# Untuk XAMPP di Windows:
# - Buka XAMPP Control Panel
# - Klik "Start" pada Apache dan MySQL

# Atau gunakan command line:
cd C:\xampp
apache_start.bat
mysql_start.bat
```

### 3. Akses phpMyAdmin
- Buka browser dan kunjungi: `http://localhost/phpmyadmin`
- Login dengan credentials default (username: root, password: kosong)

## 🗄️ Konfigurasi Database

### 1. Buat Database
```sql
CREATE DATABASE profile CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Konfigurasi Connection
File `loginweb/connection.php` akan membuat tabel secara otomatis:

```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "profile";

$conn = mysqli_connect($host, $username, $password, $database);
?>
```

### 3. Tabel Otomatis
Aplikasi akan membuat tabel `mahasiswa` secara otomatis dengan struktur:

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | INT AUTO_INCREMENT PRIMARY KEY | ID unik mahasiswa |
| nama | VARCHAR(100) | Nama lengkap |
| nim | VARCHAR(20) | Nomor Induk Mahasiswa |
| email | VARCHAR(100) | Email mahasiswa |
| tgl_lahir | DATE | Tanggal lahir |
| gender | VARCHAR(20) | Jenis kelamin |
| alamat | TEXT | Alamat lengkap |
| prodi | VARCHAR(50) | Program studi |
| jenis_mahasiswa | VARCHAR(20) | Beasiswa/Reguler |
| ukt | VARCHAR(50) | Jenis UKT |
| foto_profil | VARCHAR(255) | Path foto profil |

## 📖 Penggunaan

### 1. Akses Aplikasi
```
http://localhost/simple-web-login-form/loginweb/login.html
```

### 2. Halaman Login
- Masukkan username dan password
- Klik tombol "Masuk"
- Jika belum memiliki akun, klik "Daftar sekarang"

### 3. Halaman Registrasi
- Isi semua field yang diperlukan
- Pilih opsi yang sesuai untuk jenis kelamin, program studi, dan jenis mahasiswa
- Jika dipilih "Mahasiswa Reguler", pilih jenis UKT
- Upload foto profil (JPEG/PNG, max 2MB)
- Klik tombol "Daftar"

## 📁 Struktur File

```
simple-web-login-form/
├── README.md                    # Dokumentasi proyek
├── data.php                     # File untuk menampilkan data (jika ada)
└── loginweb/
    ├── connection.php           # Koneksi database
    ├── login.html               # Halaman login (HTML)
    ├── register.php             # Halaman registrasi (PHP + HTML)
    ├── style.css                # Stylesheet utama
    └── uploads/                 # Folder untuk menyimpan foto profil (create manually)
```

## ✅ Validasi Data

### Validasi Nama
- Hanya huruf (a-z, A-Z) dan spasi yang diizinkan
- Menggunakan regex: `/^[a-zA-Z-' ]*$/`
- Error: "Hanya huruf dan spasi yang diizinkan."

### Validasi NIM
- Harus berupa angka
- Menggunakan function: `is_numeric()`
- Error: "NIM harus berupa angka."

### Validasi Email
- Harus format email yang valid
- Menggunakan function: `filter_var($email, FILTER_VALIDATE_EMAIL)`
- Error: "Format email tidak valid."

### Validasi Tanggal Lahir
- Field wajib diisi
- Error: "Tanggal lahir wajib diisi."

### Validasi Gender
- Field wajib dipilih
- Error: "Gender wajib dipilih."

### Validasi Alamat
- Field wajib diisi
- Error: "Alamat wajib diisi."

### Validasi Program Studi
- Field wajib dipilih
- Error: "Program studi wajib dipilih."

### Validasi Jenis Mahasiswa & UKT
- Jenis mahasiswa wajib dipilih
- Jika "Beasiswa": UKT otomatis menjadi "Gratis"
- Jika "Reguler": Harus memilih jenis UKT
- Error: "Jenis mahasiswa wajib dipilih" atau "Jenis UKT wajib dipilih"

### Validasi Foto Profil
- Wajib diupload
- Format yang diizinkan: JPEG, PNG, JPG
- Ukuran maksimal: 2MB
- Error: "Foto profil wajib diupload."

## 🔐 Catatan Penting

### 1. Keamanan
- Semua input dibersihkan menggunakan `test_input()` function
- Implementasi trim, stripslashes, dan htmlspecialchars
- Gunakan prepared statements untuk query production

### 2. Folder Upload
- Buat folder `uploads/` di dalam folder `loginweb/`
- Berikan permission 755 untuk folder tersebut
- Pastikan folder writable

### 3. Bahasa
- Antarmuka menggunakan Bahasa Indonesia
- Set charset UTF-8 untuk support karakter Indonesia

### 4. Database Connection
- Default menggunakan localhost dengan user root tanpa password
- Sesuaikan di `connection.php` jika konfigurasi berbeda

### 5. Error Handling
- Semua error ditampilkan di halaman
- Jangan gunakan di production tanpa hardening security

### 6. CSS Framework
- Menggunakan custom CSS (no framework)
- Font menggunakan Google Fonts "Poppins"
- Responsive design menggunakan media queries

## 🛠️ Troubleshooting

### Koneksi Database Gagal
- Pastikan MySQL running
- Cek konfigurasi di `connection.php`
- Pastikan database `profile` sudah dibuat

### Tabel Tidak Terbuat Otomatis
- Cek permission folder database
- Cek error log MySQL
- Buat tabel secara manual melalui phpMyAdmin

### Foto Tidak Bisa Diupload
- Pastikan folder `uploads/` sudah dibuat
- Cek permission folder (harus 755 atau lebih)
- Cek ukuran file (max 2MB)
- Cek format file (hanya JPEG/PNG)

### Styling Tidak Muncul
- Clear browser cache
- Cek path ke style.css benar
- Pastikan file style.css tidak ada error

## 📝 Lisensi

Proyek ini merupakan assignment/tugas akademik.

## 👨‍💻 Author

Dibuat untuk keperluan tugas pembelajaran web development.
Oleh **okapucino** - Oka Putra