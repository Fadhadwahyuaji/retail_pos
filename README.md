# 🛒 Retail POS System

Sistem Point of Sale (POS) berbasis web untuk mengelola transaksi penjualan retail dengan fitur multi-outlet, manajemen produk, promo, dan pelaporan yang lengkap.

## 📋 Daftar Isi

- [Tentang Proyek](#tentang-proyek)
- [Tech Stack](#tech-stack)
- [Fitur Utama](#fitur-utama)
- [Role & Hak Akses](#role--hak-akses)
- [Database Schema](#database-schema)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Struktur Project](#struktur-project)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

---

## 🎯 Tentang Proyek

Retail POS System adalah aplikasi web yang dirancang untuk membantu dalam mengelola transaksi penjualan di berbagai outlet. Sistem ini dilengkapi dengan fitur manajemen multi-outlet, kontrol stok barang, sistem promo/diskon, dan pelaporan penjualan yang komprehensif.

### Target Pengguna

- **Admin Pusat**: Mengelola seluruh sistem promo, outlet, user, produk, dan laporan Transaksi
- **Manajer Outlet**: Melihat laporan penjualan outlet yang dikelola
- **Kasir**: Melakukan transaksi penjualan di POS

---

## 🛠 Tech Stack

### Backend

- **Framework**: [CodeIgniter 4](https://codeigniter.com/) (PHP Framework)
- **PHP Version**: 8.1 atau lebih tinggi
- **Database**: MySQL
- **ORM**: CodeIgniter 4 Query Builder & Models
- **Authentication**: Session-based authentication dengan filter

### Frontend

- **CSS Framework**: Tailwind CSS (utility-first CSS framework)
- **JavaScript**: Vanilla JavaScript / jQuery (untuk interaktivitas)
- **Template Engine**: CodeIgniter 4 View Parser
- **Build Tool**: PostCSS dengan Autoprefixer

### Development Tools

- **Dependency Manager**: Composer (PHP)
- **Testing**: -
- **Faker**: FakerPHP untuk data dummy
- **Version Control**: Git

### Server Requirements

- PHP 8.1+
- MySQL 5.7+
- Apache/Nginx Web Server
- mod_rewrite enabled (Apache)
- PHP Extensions:
  - `intl`
  - `mbstring`
  - `json`
  - `mysqlnd` / `mysqli`
  - `xml`

---

## ✨ Fitur Utama

### 🔐 Authentication & Authorization

- Login dengan email dan password
- Role-based access control (RBAC)
- Session management
- Password encryption dengan bcrypt
- Logout functionality

### 👥 Manajemen User

- CRUD User (Create, Read, Update, Delete)
- Assign role ke user (Admin, Manajer, Kasir)
- Assign outlet untuk Manajer dan Kasir
- Toggle status aktif/non-aktif user
- Change password functionality
- Filter user berdasarkan outlet
- User statistics

### 🏪 Manajemen Outlet

- CRUD Outlet
- Informasi detail outlet (nama, alamat, telepon, dll)
- Toggle status aktif/non-aktif outlet
- Relasi outlet dengan user (Manajer & Kasir)

### 📦 Manajemen Barang

- CRUD Barang dengan kode barang unik
- Informasi lengkap produk (nama, harga, stok, kategori)
- Barcode support
- Status ready/not ready untuk penjualan
- Search barang (by nama, kode, atau barcode)
- Toggle status aktif/non-aktif
- Detail view untuk setiap produk

### 🎁 Manajemen Promo

- CRUD Promo dengan berbagai jenis diskon
- Jenis promo:
  - **Diskon Nominal**: Potongan harga tetap
  - **Diskon Persentase**: Potongan dalam persen
- Periode promo (tanggal mulai dan berakhir)
- Assign produk ke promo
- Kalkulasi otomatis diskon
- Toggle status aktif/non-aktif promo

### 💰 Point of Sale (POS)

- Interface kasir yang user-friendly
- input barcode atau pilih katalog produk
- Tambah/kurangi quantity item
- Kalkulasi otomatis total belanja
- Aplikasi promo otomatis
- Split payment atau cash payment

### 📊 Laporan & Report

#### Untuk Admin Pusat (AD)

- **Dashboard**: Overview penjualan seluruh outlet
- **Sales Summary**: Ringkasan penjualan per outlet
- **Sales Detail**: Detail transaksi per periode
- **Transaction Detail**: Detail item dalam transaksi
- Export laporan ke Excel/CSV
- Filter by date range
- Filter by outlet

#### Untuk Manajer Outlet (MG)

- Laporan penjualan outlet yang dikelola
- Detail transaksi outlet
- Export laporan outlet
- Filter by date range

### 🔍 Fitur Tambahan

- Responsive design (mobile-friendly)
- Real-time search & filtering
- AJAX untuk operasi cepat tanpa reload
- Validation form yang komprehensif
- Error handling & logging
- Database migration & seeding
- Soft deletes (optional)

---

## 👤 Role & Hak Akses

Sistem ini menggunakan 3 role utama dengan hierarki hak akses:

### 1. Admin Pusat (AD)

**Kode Role**: `AD`

**Hak Akses**:

- ✅ Full access ke semua fitur sistem
- ✅ Manajemen User (CRUD, change password, toggle status)
- ✅ Manajemen Outlet (CRUD, toggle status)
- ✅ Manajemen Barang (CRUD, toggle status, search)
- ✅ Manajemen Promo (CRUD, assign items, toggle status)
- ✅ Laporan lengkap semua outlet (dashboard, sales summary, sales detail)
- ✅ Export laporan
- ✅ Akses ke semua modul (Admin, Manajer, Kasir)

**URL Access**:

- `/admin/*` - Semua fitur admin
- `/manajer/*` - Akses laporan manajer
- `/kasir/*` - Akses POS kasir

### 2. Manajer Outlet (MG)

**Kode Role**: `MG`

**Hak Akses**:

- ✅ Melihat laporan penjualan outlet yang dikelola
- ✅ Detail transaksi outlet
- ✅ Export laporan outlet
- ❌ Tidak bisa manage user, outlet, barang, atau promo
- ❌ Tidak bisa melihat laporan outlet lain

**URL Access**:

- `/manajer/*` - Laporan outlet
- `/kasir/*` - Akses POS

**Catatan**:

- Manajer wajib ter-assign ke 1 outlet spesifik
- Hanya bisa melihat data outlet sendiri

### 3. Kasir (KS)

**Kode Role**: `KS`

**Hak Akses**:

- ✅ Akses Point of Sale (POS)
- ✅ Scan/input produk
- ✅ Proses transaksi penjualan
- ❌ Tidak bisa akses admin atau laporan

**URL Access**:

- `/kasir/*` - Hanya modul POS

**Catatan**:

- Kasir wajib ter-assign ke 1 outlet spesifik
- Hanya bisa transaksi untuk outlet sendiri

---

## 🗄 Database Schema

### Tabel: `roles`

| Column     | Type              | Description          |
| ---------- | ----------------- | -------------------- |
| id         | INT(5) PK         | Primary key          |
| KdRole     | VARCHAR(5) UNIQUE | Kode role (AD/MG/KS) |
| nama_role  | VARCHAR(50)       | Nama role            |
| created_at | DATETIME          | Waktu dibuat         |
| updated_at | DATETIME          | Waktu diupdate       |

### Tabel: `outlets`

| Column      | Type         | Description        |
| ----------- | ------------ | ------------------ |
| id          | INT(5) PK    | Primary key        |
| nama_outlet | VARCHAR(100) | Nama outlet        |
| alamat      | TEXT         | Alamat lengkap     |
| telepon     | VARCHAR(20)  | Nomor telepon      |
| is_active   | TINYINT(1)   | Status aktif (0/1) |
| created_at  | DATETIME     | Waktu dibuat       |
| updated_at  | DATETIME     | Waktu diupdate     |

### Tabel: `users`

| Column     | Type                | Description                          |
| ---------- | ------------------- | ------------------------------------ |
| id         | INT(5) PK           | Primary key                          |
| role_id    | INT(5) FK           | Foreign key ke roles                 |
| nama       | VARCHAR(100)        | Nama user                            |
| email      | VARCHAR(100) UNIQUE | Email untuk login                    |
| password   | VARCHAR(255)        | Password terenkripsi                 |
| outlet_id  | INT(5) FK NULL      | Foreign key ke outlets (untuk MG/KS) |
| is_active  | TINYINT(1)          | Status aktif (0/1)                   |
| created_at | DATETIME            | Waktu dibuat                         |
| updated_at | DATETIME            | Waktu diupdate                       |

### Tabel: `masterbarang`

| Column      | Type               | Description              |
| ----------- | ------------------ | ------------------------ |
| id          | INT(5) PK          | Primary key              |
| kd_barang   | VARCHAR(20) UNIQUE | Kode barang unik         |
| nama_barang | VARCHAR(150)       | Nama produk              |
| harga       | DECIMAL(10,2)      | Harga jual               |
| stok        | INT                | Jumlah stok              |
| kategori    | VARCHAR(50)        | Kategori produk          |
| barcode     | VARCHAR(50) NULL   | Barcode produk           |
| is_ready    | TINYINT(1)         | Ready untuk dijual (0/1) |
| is_active   | TINYINT(1)         | Status aktif (0/1)       |
| created_at  | DATETIME           | Waktu dibuat             |
| updated_at  | DATETIME           | Waktu diupdate           |

### Tabel: `transaksi_header`

| Column        | Type               | Description                     |
| ------------- | ------------------ | ------------------------------- |
| id            | INT(5) PK          | Primary key                     |
| no_transaksi  | VARCHAR(50) UNIQUE | Nomor transaksi unik            |
| tanggal       | DATE               | Tanggal transaksi               |
| outlet_id     | INT(5) FK          | Outlet yang melakukan transaksi |
| user_id       | INT(5) FK          | Kasir yang input                |
| total_belanja | DECIMAL(12,2)      | Total sebelum diskon            |
| total_diskon  | DECIMAL(12,2)      | Total diskon                    |
| total_bayar   | DECIMAL(12,2)      | Total yang harus dibayar        |
| created_at    | DATETIME           | Waktu dibuat                    |
| updated_at    | DATETIME           | Waktu diupdate                  |

### Tabel: `transaksi_detail`

| Column       | Type          | Description                     |
| ------------ | ------------- | ------------------------------- |
| id           | INT(5) PK     | Primary key                     |
| transaksi_id | INT(5) FK     | Foreign key ke transaksi_header |
| barang_id    | INT(5) FK     | Produk yang dibeli              |
| qty          | INT           | Jumlah barang                   |
| harga_satuan | DECIMAL(10,2) | Harga per item                  |
| subtotal     | DECIMAL(12,2) | Total per item (qty \* harga)   |
| diskon       | DECIMAL(12,2) | Diskon per item                 |
| created_at   | DATETIME      | Waktu dibuat                    |
| updated_at   | DATETIME      | Waktu diupdate                  |

### Tabel: `discountheader`

| Column          | Type               | Description               |
| --------------- | ------------------ | ------------------------- |
| id              | INT(5) PK          | Primary key               |
| kd_promo        | VARCHAR(20) UNIQUE | Kode promo unik           |
| nama_promo      | VARCHAR(100)       | Nama promo                |
| jenis_diskon    | ENUM               | NOMINAL/PERCENTAGE/BUNDLE |
| nilai_diskon    | DECIMAL(10,2)      | Nilai diskon              |
| tanggal_mulai   | DATE               | Tanggal mulai promo       |
| tanggal_selesai | DATE               | Tanggal akhir promo       |
| is_active       | TINYINT(1)         | Status aktif (0/1)        |
| created_at      | DATETIME           | Waktu dibuat              |
| updated_at      | DATETIME           | Waktu diupdate            |

### Tabel: `discountdetail`

| Column     | Type      | Description                   |
| ---------- | --------- | ----------------------------- |
| id         | INT(5) PK | Primary key                   |
| promo_id   | INT(5) FK | Foreign key ke discountheader |
| barang_id  | INT(5) FK | Produk yang dapat promo       |
| created_at | DATETIME  | Waktu dibuat                  |
| updated_at | DATETIME  | Waktu diupdate                |

### Relasi Database

```
roles (1) ──── (N) users
outlets (1) ──── (N) users
outlets (1) ──── (N) transaksi_header
users (1) ──── (N) transaksi_header
transaksi_header (1) ──── (N) transaksi_detail
masterbarang (1) ──── (N) transaksi_detail
discountheader (1) ──── (N) discountdetail
masterbarang (1) ──── (N) discountdetail
```

---

## 💻 Persyaratan Sistem

### Server Requirements

- **OS**: Windows/Linux/MacOS
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **PHP**: 8.1 atau lebih tinggi
- **Database**: MySQL 5.7+ atau MariaDB 10.3+
- **Composer**: 2.0+

### PHP Extensions (Required)

```
- intl
- mbstring
- json
- mysqlnd atau mysqli
- xml
- curl
```

### Development Tools (Optional)

- Node.js & npm (untuk Tailwind CSS build)
- Git
- PHPUnit (untuk testing)

---

## 📥 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/your-repo/retail_pos.git
cd retail_pos
```

### 2. Install Dependencies PHP

```bash
composer install
```

### 3. Setup Environment File

Salin file `env` menjadi `.env`:

```powershell
# Windows (PowerShell)
Copy-Item env .env

# Linux/Mac
cp env .env
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```ini
#--------------------------------------------------------------------
# ENVIRONMENT
#--------------------------------------------------------------------
CI_ENVIRONMENT = development

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'http://localhost:8080/'
# app.baseURL = 'http://yourdomain.com/'

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = retail_pos
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 5. Buat Database

Login ke MySQL dan buat database baru:

```sql
CREATE DATABASE retail_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 6. Jalankan Migration

Migration akan membuat semua tabel yang diperlukan:

```bash
php spark migrate
```

Output yang diharapkan:

```
Running: 2025-11-01-182309_CreateRolesTable
Running: 2025-11-01-191456_CreateOutletsTable
Running: 2025-11-02-184608_CreateUsersTable
Running: 2025-11-02-185041_CreateMasterbarangTable
Running: 2025-11-02-185139_CreateTransaksiHeaderTable
Running: 2025-11-02-185240_CreateTransaksiDetailTable
Running: 2025-11-02-185318_CreateDiscountheaderTable
Running: 2025-11-02-185343_CreateDiscountdetailTable
```

### 7. Jalankan Seeder (Optional)

Untuk mengisi data awal (roles, admin default, dll):

```bash
php spark db:seed NamaSeederAnda
```

### 8. Setup Tailwind CSS (Optional untuk Development)

Jika Anda ingin build custom Tailwind CSS:

```bash
# Install Node.js dependencies
npm install -D tailwindcss postcss autoprefixer

# Generate tailwind config
npx tailwindcss init

# Build CSS
npm run build
```

---

## ⚙ Konfigurasi

### Konfigurasi Aplikasi

Edit `app/Config/App.php`:

```php
public string $baseURL = 'http://localhost:8080/';
public string $indexPage = '';
public string $defaultLocale = 'id'; // Bahasa Indonesia
public string $negotiateLocale = true;
public array $supportedLocales = ['id', 'en'];
```

### Konfigurasi Database

File: `app/Config/Database.php`

Atau gunakan `.env` file (recommended):

```ini
database.default.hostname = localhost
database.default.database = retail_pos
database.default.username = root
database.default.password = yourpassword
database.default.DBDriver = MySQLi
```

### Konfigurasi Session

Edit `app/Config/Session.php`:

```php
public string $driver = 'CodeIgniter\Session\Handlers\FileHandler';
public string $cookieName = 'ci_session';
public int $expiration = 7200; // 2 jam
public string $savePath = WRITEPATH . 'session';
public bool $matchIP = false;
public int $timeToUpdate = 300;
public bool $regenerateDestroy = false;
```

### Konfigurasi Routes

File utama routing: `app/Config/Routes.php`

Default route mengarah ke login page:

```php
$routes->get('/', 'Auth::index');
```

---

## 🚀 Menjalankan Aplikasi

### Development Server (Built-in PHP Server)

```bash
php spark serve
```

Aplikasi akan berjalan di: `http://localhost:8080`

### Dengan Custom Port

```bash
php spark serve --port=8081
```

### Menggunakan Apache/Nginx

1. **Apache**:

   - Set document root ke folder `public/`
   - Enable `mod_rewrite`
   - Pastikan `.htaccess` ada di folder `public/`

2. **Nginx**:
   - Set root ke folder `public/`
   - Configure nginx dengan:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/retail_pos/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
```

### Login Default

Setelah setup seeder, gunakan kredensial berikut:

**Admin Pusat:**

- Email: `admin@retailpos.com`
- Password: `admin123`

**Manajer Outlet:**

- Email: `manajer@outlet1.com`
- Password: `manajer123`

**Kasir:**

- Email: `kasir@outlet1.com`
- Password: `kasir123`

> ⚠️ **PENTING**: Segera ganti password default setelah login pertama kali!

---

## 📁 Struktur Project

```
retail_pos/
│
├── app/
│   ├── Config/              # Konfigurasi aplikasi
│   │   ├── App.php
│   │   ├── Database.php
│   │   ├── Routes.php
│   │   └── ...
│   │
│   ├── Controllers/         # Business logic
│   │   ├── Auth.php         # Authentication controller
│   │   ├── DashboardController.php
│   │   ├── Admin/           # Admin controllers
│   │   │   ├── UserController.php
│   │   │   ├── OutletController.php
│   │   │   ├── BarangController.php
│   │   │   ├── PromoController.php
│   │   │   └── ReportController.php
│   │   ├── Manajer/         # Manajer controllers
│   │   │   └── LaporanController.php
│   │   └── Kasir/           # Kasir controllers
│   │       └── POSController.php
│   │
│   ├── Models/              # Data models
│   │   ├── UserModel.php
│   │   ├── RoleModel.php
│   │   ├── OutletModel.php
│   │   ├── BarangModel.php
│   │   ├── PromoModel.php
│   │   ├── PromoDetailModel.php
│   │   └── TransaksiModel.php
│   │
│   ├── Views/               # Templates/Views
│   │   ├── layouts/         # Layout templates
│   │   ├── auth/            # Login/auth views
│   │   ├── admin/           # Admin views
│   │   ├── manajer/         # Manajer views
│   │   └── kasir/           # Kasir/POS views
│   │
│   ├── Filters/             # Middleware/Filters
│   │   ├── AuthFilter.php   # Check if logged in
│   │   └── RoleFilter.php   # Check user role
│   │
│   ├── Database/
│   │   ├── Migrations/      # Database migrations
│   │   └── Seeds/           # Database seeders
│   │
│   ├── Helpers/             # Helper functions
│   └── Libraries/           # Custom libraries
│
├── public/                  # Public assets (DocumentRoot)
│   ├── index.php           # Front controller
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   └── favicon.ico
│
├── writable/               # Writable directory
│   ├── cache/              # Cache files
│   ├── logs/               # Log files
│   ├── session/            # Session files
│   └── uploads/            # Uploaded files
│
├── tests/                  # Unit & integration tests
├── vendor/                 # Composer dependencies
├── composer.json           # PHP dependencies
├── env                     # Environment template
├── .env                    # Environment config (created by you)
├── spark                   # CLI tool
└── README.md              # This file
```

---

## 🔌 API Endpoints

### Authentication

| Method | Endpoint       | Description    | Access        |
| ------ | -------------- | -------------- | ------------- |
| GET    | `/login`       | Tampilan login | Public        |
| POST   | `/auth/login`  | Proses login   | Public        |
| GET    | `/auth/logout` | Logout         | Authenticated |

### Dashboard

| Method | Endpoint     | Description     | Access        |
| ------ | ------------ | --------------- | ------------- |
| GET    | `/dashboard` | Dashboard utama | Authenticated |

### Admin - User Management

| Method | Endpoint                           | Description                | Access     |
| ------ | ---------------------------------- | -------------------------- | ---------- |
| GET    | `/admin/user`                      | List users                 | Admin (AD) |
| GET    | `/admin/user/create`               | Form tambah user           | Admin (AD) |
| POST   | `/admin/user/store`                | Simpan user baru           | Admin (AD) |
| GET    | `/admin/user/edit/{id}`            | Form edit user             | Admin (AD) |
| POST   | `/admin/user/update/{id}`          | Update user                | Admin (AD) |
| GET    | `/admin/user/delete/{id}`          | Hapus user                 | Admin (AD) |
| GET    | `/admin/user/toggle-status/{id}`   | Toggle aktif/nonaktif      | Admin (AD) |
| GET    | `/admin/user/change-password/{id}` | Form ganti password        | Admin (AD) |
| POST   | `/admin/user/update-password/{id}` | Update password            | Admin (AD) |
| GET    | `/admin/user/get-by-outlet`        | Get users by outlet (AJAX) | Admin (AD) |

### Admin - Outlet Management

| Method | Endpoint                           | Description           | Access     |
| ------ | ---------------------------------- | --------------------- | ---------- |
| GET    | `/admin/outlet`                    | List outlets          | Admin (AD) |
| GET    | `/admin/outlet/create`             | Form tambah outlet    | Admin (AD) |
| POST   | `/admin/outlet/store`              | Simpan outlet baru    | Admin (AD) |
| GET    | `/admin/outlet/edit/{id}`          | Form edit outlet      | Admin (AD) |
| POST   | `/admin/outlet/update/{id}`        | Update outlet         | Admin (AD) |
| GET    | `/admin/outlet/delete/{id}`        | Hapus outlet          | Admin (AD) |
| GET    | `/admin/outlet/toggle-status/{id}` | Toggle aktif/nonaktif | Admin (AD) |

### Admin - Barang Management

| Method | Endpoint                           | Description            | Access     |
| ------ | ---------------------------------- | ---------------------- | ---------- |
| GET    | `/admin/barang`                    | List barang            | Admin (AD) |
| GET    | `/admin/barang/create`             | Form tambah barang     | Admin (AD) |
| POST   | `/admin/barang/store`              | Simpan barang baru     | Admin (AD) |
| GET    | `/admin/barang/detail/{kd}`        | Detail barang          | Admin (AD) |
| GET    | `/admin/barang/edit/{kd}`          | Form edit barang       | Admin (AD) |
| POST   | `/admin/barang/update/{kd}`        | Update barang          | Admin (AD) |
| GET    | `/admin/barang/delete/{kd}`        | Hapus barang           | Admin (AD) |
| GET    | `/admin/barang/toggle-status/{kd}` | Toggle aktif/nonaktif  | Admin (AD) |
| GET    | `/admin/barang/toggle-ready/{kd}`  | Toggle ready/not ready | Admin (AD) |
| GET    | `/admin/barang/search`             | Search barang (AJAX)   | Admin (AD) |
| GET    | `/admin/barang/get-by-barcode`     | Get by barcode (AJAX)  | Admin (AD) |

### Admin - Promo Management

| Method | Endpoint                          | Description           | Access     |
| ------ | --------------------------------- | --------------------- | ---------- |
| GET    | `/admin/promo`                    | List promo            | Admin (AD) |
| GET    | `/admin/promo/create`             | Form tambah promo     | Admin (AD) |
| POST   | `/admin/promo/store`              | Simpan promo baru     | Admin (AD) |
| GET    | `/admin/promo/detail/{kd}`        | Detail promo          | Admin (AD) |
| GET    | `/admin/promo/edit/{kd}`          | Form edit promo       | Admin (AD) |
| POST   | `/admin/promo/update/{kd}`        | Update promo          | Admin (AD) |
| GET    | `/admin/promo/delete/{kd}`        | Hapus promo           | Admin (AD) |
| GET    | `/admin/promo/toggle-status/{kd}` | Toggle aktif/nonaktif | Admin (AD) |
| GET    | `/admin/promo/items/{kd}`         | Manage items promo    | Admin (AD) |
| POST   | `/admin/promo/add-item`           | Tambah item ke promo  | Admin (AD) |
| POST   | `/admin/promo/remove-item`        | Hapus item dari promo | Admin (AD) |
| POST   | `/admin/promo/calculate-discount` | Kalkulasi diskon      | Admin (AD) |

### Admin - Reports

| Method | Endpoint                                       | Description         | Access     |
| ------ | ---------------------------------------------- | ------------------- | ---------- |
| GET    | `/admin/report`                                | Dashboard laporan   | Admin (AD) |
| GET    | `/admin/report/sales-summary`                  | Ringkasan penjualan | Admin (AD) |
| GET    | `/admin/report/export-summary`                 | Export summary      | Admin (AD) |
| GET    | `/admin/report/sales-detail`                   | Detail penjualan    | Admin (AD) |
| GET    | `/admin/report/export-detail`                  | Export detail       | Admin (AD) |
| GET    | `/admin/report/transaction-detail/{no}/{date}` | Detail transaksi    | Admin (AD) |

### Manajer - Reports

| Method | Endpoint                                | Description    | Access       |
| ------ | --------------------------------------- | -------------- | ------------ |
| GET    | `/manajer/laporan-outlet`               | Laporan outlet | Manajer (MG) |
| GET    | `/manajer/laporan-outlet/detail/{date}` | Detail laporan | Manajer (MG) |
| GET    | `/manajer/laporan-outlet/export`        | Export laporan | Manajer (MG) |

### Kasir - POS

| Method | Endpoint                | Description       | Access     |
| ------ | ----------------------- | ----------------- | ---------- |
| GET    | `/kasir/pos`            | POS interface     | Kasir (KS) |
| POST   | `/kasir/pos/process`    | Proses transaksi  | Kasir (KS) |
| GET    | `/kasir/pos/print/{id}` | Print receipt     | Kasir (KS) |
| GET    | `/kasir/pos/history`    | History transaksi | Kasir (KS) |

---

## 🧪 Testing

### Menjalankan Unit Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/unit/HealthTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

### Test Structure

```
tests/
├── unit/              # Unit tests
├── database/          # Database tests
└── session/           # Session tests
```

### Membuat Test Baru

```bash
php spark make:test NamaTest
```

---

## 🔧 Troubleshooting

### 1. Error 404 Page Not Found

**Problem**: Semua route selain homepage menampilkan 404

**Solution**:

- Pastikan `mod_rewrite` Apache enabled
- Check `.htaccess` di folder `public/`
- Set `$indexPage = ''` di `app/Config/App.php`

### 2. Database Connection Failed

**Problem**: Tidak bisa connect ke database

**Solution**:

- Cek konfigurasi di `.env`
- Pastikan MySQL service running
- Verify username/password database
- Cek hostname (localhost vs 127.0.0.1)

### 3. Session Tidak Tersimpan

**Problem**: Session selalu hilang, tidak bisa login

**Solution**:

- Pastikan folder `writable/session/` ada dan writable
- Check permission folder (Windows: Full Control, Linux: `chmod -R 777 writable/`)
- Clear session files: hapus isi folder `writable/session/`

### 4. Migration Error

**Problem**: Error saat run migration

**Solution**:

```bash
# Rollback migration
php spark migrate:rollback

# Refresh migration
php spark migrate:refresh

# Reset database
php spark migrate:reset
php spark migrate
```

### 5. Composer Dependencies Error

**Problem**: Error saat install dependencies

**Solution**:

```bash
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Reinstall dependencies
Remove-Item -Recurse -Force vendor/
composer install
```

### 6. Permission Denied pada Folder Writable

**Problem**: Error write ke logs, cache, session

**Solution** (Windows):

- Right-click folder `writable`
- Properties → Security
- Edit permissions
- Give "Full Control" untuk user Anda

**Solution** (Linux/Mac):

```bash
chmod -R 777 writable/
chown -R www-data:www-data writable/
```

### 7. Tailwind CSS Tidak Load

**Problem**: Style tidak muncul

**Solution**:

```bash
# Rebuild CSS
npm install
npm run build

# Atau manual compile
npx tailwindcss -i ./src/input.css -o ./public/css/output.css --watch
```

### 8. Error "Class not found"

**Problem**: CodeIgniter tidak menemukan class

**Solution**:

```bash
# Regenerate autoload
composer dump-autoload
```

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan ikuti langkah berikut:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Coding Standards

- Ikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Gunakan meaningful variable/function names
- Tambahkan comment untuk logic yang kompleks
- Write unit tests untuk fitur baru
- Update dokumentasi jika diperlukan

---

## 📄 Lisensi

Project ini dilisensikan under MIT License - lihat file [LICENSE](LICENSE) untuk detail.

---

## 👨‍💻 Author & Support

**Fadhad Wahyu Aji**

Untuk support dan pertanyaan:

- Email: fadhadwahyuaji@gmail.com
- Website: https://www.fadhadwahyuaji.com

---

## 📝 Changelog

### Version 1.0.0 (November 2025)

- ✨ Initial release
- ✅ Multi-role authentication (Admin, Manajer, Kasir)
- ✅ Multi-outlet management
- ✅ Product management dengan barcode
- ✅ Promo system (Nominal, Percentage, Bundle)
- ✅ Point of Sale (POS) interface
- ✅ Comprehensive reporting system
- ✅ Export to Excel/CSV
- ✅ Responsive design

---

## 📚 Resources

### Documentation

- [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [PHP Documentation](https://www.php.net/docs.php)

### Tutorials

- [CodeIgniter 4 CRUD Tutorial](https://codeigniter.com/user_guide/tutorial/index.html)
- [RESTful API with CodeIgniter 4](https://codeigniter.com/user_guide/libraries/restful.html)

---

## 🙏 Acknowledgments

- CodeIgniter Framework Team
- Tailwind CSS Team
- All contributors yang telah membantu project ini

---

**Happy Coding! 🚀**

Made with ❤️ by Fadhad Wahyu Aji
