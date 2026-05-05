# Portfolio Website — PHP Native + MySQL

## Cara Instalasi

### 1. Persyaratan
- PHP 8.0+
- MySQL 5.7+ atau MariaDB
- Web server: Apache / Nginx / XAMPP / Laragon

### 2. Setup Database
1. Buka phpMyAdmin atau MySQL CLI
2. Jalankan file `database.sql`:
   ```
   mysql -u root -p < database.sql
   ```
   Atau copy-paste isi file ke phpMyAdmin.

### 3. Konfigurasi
Edit file `includes/config.php`, sesuaikan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // username MySQL Anda
define('DB_PASS', '');          // password MySQL Anda
define('DB_NAME', 'portfolio_db');
```

### 4. Upload ke Server
Letakkan semua file di folder root web server (misal: `htdocs/portfolio/` di XAMPP).

### 5. Akses Website
- **Portfolio**: `http://localhost/portfolio/`
- **Admin Panel**: `http://localhost/portfolio/admin/`
- **Login default**: 
  - Email: `admin@portfolio.com`
  - Password: `admin123`

---

## Struktur Folder
```
portfolio/
├── index.php               # Halaman utama portfolio
├── database.sql            # Script inisialisasi database
├── includes/
│   └── config.php          # Konfigurasi DB & helper functions
├── admin/
│   ├── login.php           # Halaman login admin
│   ├── logout.php          # Logout
│   ├── index.php           # Dashboard
│   ├── site-setup.php      # Pengaturan situs (single row)
│   ├── about-me.php        # Tentang saya (single row)
│   ├── pengalaman.php      # CRUD Pengalaman
│   ├── perkerjaan.php      # CRUD Perkerjaan/Proyek
│   ├── sertifikat.php      # CRUD Sertifikat
│   ├── galery.php          # Upload/delete Galeri
│   ├── users.php           # CRUD Users
│   ├── header.php          # Layout header admin
│   └── footer.php          # Layout footer admin
└── uploads/                # Folder hasil upload gambar (auto-created)
    ├── profile/
    ├── gallery/
    ├── experience/
    ├── work/
    ├── certificate/
    └── thumbnail/
```

## Fitur
- ✅ Login admin dengan session
- ✅ Site Setup (judul, footer, sub-sections)
- ✅ About Me (profil, foto, kontak)
- ✅ CRUD Pengalaman
- ✅ CRUD Perkerjaan/Proyek (+ link)
- ✅ CRUD Sertifikat
- ✅ Upload multi-foto Galeri
- ✅ CRUD Users (dengan password hashing)
- ✅ Upload & hapus gambar otomatis
- ✅ Frontend otomatis terhubung ke database
