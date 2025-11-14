# Retail POS (CodeIgniter 4)

Sistem Point of Sale (POS) berbasis web untuk retail dengan dukungan multi-outlet, manajemen user/role, master barang, promo diskon, transaksi POS, dan pelaporan. Seluruh deskripsi di bawah dirangkum dari kode pada proyek ini.

---

## Ringkasan Proyek

- Framework: CodeIgniter 4 (PHP 8.1+)
- Arsitektur: MVC (Controllers, Models, Views) + Filters (middleware)
- Otorisasi: Role-based access control (RBAC) menggunakan session + filter
- Modul utama:
  - Autentikasi & Session
  - Dashboard per peran (Admin/Manajer/Kasir)
  - Admin: User, Outlet, Barang, Promo, Report
  - Kasir: POS (scan/cari barang, hitung diskon, simpan transaksi, cetak struk)
  - Manajer: Laporan outlet

---

## Teknologi yang Digunakan

- Bahasa/Runtime: PHP 8.1+
- Framework: CodeIgniter 4 (codeigniter4/framework)
- Database: MySQL/MariaDB (diatur via `app/Config/Database.php` atau `.env`)
- Session & Filter: CI4 Session + Filter (`AuthFilter`, `RoleFilter`)
- View: Templating bawaan CodeIgniter 4 (Views)
- Logging: CI4 Logger ke `writable/logs`
- Build CSS: Tersedia `postcss.config.js` untuk Tailwind CSS & Autoprefixer (opsional; tidak ada package.json di repo ini)

### Dependensi Composer

Dari `composer.json`:

- require:
  - codeigniter4/framework
- require-dev:
  - phpunit/phpunit
  - fakerphp/faker
  - mikey179/vfsstream

---

## Peran Pengguna (RBAC)

- AD (Admin Pusat)
  - Akses penuh modul admin: User, Outlet, Barang, Promo, Report
  - Akses dashboard
- MG (Manajer Outlet)
  - Akses laporan outlet sendiri dan dashboard
- KS (Kasir)
  - Akses modul POS (kasir) dan cetak struk

Role dibaca dari session `kd_role` dan difilter melalui `RoleFilter` pada grup rute.

---

## Fitur Utama (berdasarkan kode)

1. Autentikasi & Session

- Controller: `Auth`
- Login via email/password, verifikasi `password_hash`
- Simpan data user, role, outlet di session
- Logout menghapus session

2. Dashboard

- Controller: `DashboardController`
- Statistik berbeda sesuai role (Admin/Manajer/Kasir)

3. Manajemen User (Admin)

- Controller: `Admin\UserController`
- Model: `UserModel`, `RoleModel`, `OutletModel`
- CRUD user, validasi, toggle aktif/nonaktif, ganti password
- Filter/paginasi pengguna, statistik pengguna
- Validasi keterkaitan role–outlet (manajer/kasir wajib punya outlet)

4. Manajemen Outlet (Admin)

- Controller: `Admin\OutletController`
- Model: `OutletModel`
- CRUD outlet, toggle aktif/nonaktif, hitung jumlah user per outlet
- Cegah hapus jika outlet punya transaksi atau user

5. Manajemen Barang (Admin)

- Controller: `Admin\BarangController`
- Model: `BarangModel` (tabel `masterbarang`)
- CRUD barang (kode produk `PCode` manual), barcode 1–3, harga jual/beli, status aktif (T/F), FlagReady (Y/N)
- Pencarian (kode/nama/barcode), toggle status & ready
- Cegah hapus jika sudah bertransaksi atau sedang ikut promo aktif
- Hitung profit/margin sederhana

6. Manajemen Promo (Admin)

- Controller: `Admin\PromoController`
- Model: `PromoModel` (header), `PromoDetailModel` (detail)
- Promo aktif berdasar tanggal, jam, hari, dan outlet
- Jenis diskon per item: Persentase (P) atau Rupiah (R)
- Tambah/hapus item promo, pratinjau perhitungan diskon, statistik promo

7. POS Kasir (Kasir)

- Controller: `Kasir\POSController`
- Model: `TransaksiModel`, `BarangModel`, `PromoModel`, `OutletModel`
- Cari produk (kode/nama/barcode), terapkan promo valid secara otomatis
- Hitung subtotal, total diskon, grand total pada keranjang
- Metode pembayaran yang tercatat: Tunai, Kartu Kredit, Kartu Debit, GoPay, Voucher
- Simpan transaksi header+detail dalam satu transaksi database
- Penomoran struk otomatis: format `YYMMDD-XXXXX` per outlet (dengan lock tabel)
- Cetak struk via view khusus, riwayat transaksi harian, void transaksi

8. Report (Admin/Manajer)

- Rute tersedia untuk ringkasan dan detail penjualan (lihat bagian Rute)
- Agregasi penjualan per outlet, item terlaris, metode pembayaran, dsb. (via `TransaksiModel`)

---

## Skema Data (ringkas dari Model)

Catatan: Nama kolom diambil dari `protected $allowedFields` dan query pada model.

- roles

  - id, KdRole, nama_role, created_at, updated_at

- users

  - id, role_id, nama, email, password, outlet_id, is_active, created_at, updated_at

- outlets

  - id, KdStore, nama_outlet, alamat, telepon, is_active, created_at, updated_at

- masterbarang

  - PCode (PK, manual), NamaLengkap, NamaStruk, NamaInitial, SatuanSt,
    Barcode1, Barcode2, Barcode3,
    Harga1c (jual), Harga1b (beli), HargaBeli, HPP,
    Status (T/F), FlagReady (Y/N), Jenis, JenisBarang,
    KdDivisi, KdKategori, KdBrand,
    AddDate, EditDate (diatur manual via callback)

- discountheader (promo header)

  - NoTrans (PK), TglTrans, Ketentuan, TglAwal, TglAkhir,
    jam_mulai, jam_selesai, hari_berlaku (angka 1–7 dipisah koma),
    Minimum, Status (1/0), exclude_promo, berlaku, outlet_id

- discountdetail (promo detail)

  - NoTrans, PCode, Jenis (P/R), Nilai

- transaksi_header

  - NoKassa, Gudang, NoStruk, Tanggal, Waktu, Kasir, KdStore,
    TotalItem, TotalNilaiPem, TotalNilai, TotalBayar, Kembali,
    Point, Tunai, KKredit, KDebit, GoPay, Voucher, VoucherTravel,
    Discount, BankDebet, EDCBankDebet, BankKredit, EDCBankKredit,
    Status, KdCustomer, Ttl_Charge, DPP, TAX, KdMeja, userdisc,
    KdMember, NoCard, NamaCard, nilaidisc, statuskomisi, statuskomisi_khusus

- transaksi_detail (dipakai via query di model)
  - NoKassa, Gudang, NoStruk, Tanggal, Waktu, Kasir, KdStore,
    PCode, Qty, Harga, Ketentuan1, Disc1, Jenis1, Netto, Hpp, Status

---

## Alur Utama POS (ringkas)

1. Login kasir → session berisi `kd_role=KS`, `outlet_id`, `kdstore`, dll.
2. Buka `/kasir/pos` → tampil info outlet, kasir, NoKassa (dari user id)
3. Scan/ketik produk → ambil barang dari `masterbarang` + cek promo aktif via `PromoModel::calculateDiscount`
4. Tambah ke cart → hitung subtotal, diskon, grand total
5. Pembayaran → simpan ke `transaksi_header` + `transaksi_detail` (atomic, dengan duplicate check)
6. Cetak struk → view cetak, tersedia riwayat transaksi dan void transaksi

---

## Rute Utama (dari `app/Config/Routes.php`)

- Publik

  - `/` → `Auth::index`
  - `/login` (GET), `/auth/login` (POST), `/auth/logout` (GET)

- Dashboard (perlu login)

  - `/dashboard` → `DashboardController::index` (filter: `auth`)

- Admin (filter: `role:AD`)

  - `/admin/user`, `/admin/outlet`, `/admin/barang`, `/admin/promo`
  - Report: `/admin/report`, `/admin/report/sales-summary`, `/admin/report/sales-detail`, dll.

- Manajer (filter: `role:MG`)

  - `/manajer/laporan-outlet`, detail, export

- Kasir (filter: `role:KS`)
  - `/kasir/pos`
  - AJAX POS: `search-product`, `get-product-list`, `calculate-cart`, `save-transaction`

---

## Persyaratan Sistem

- PHP 8.1+
- Ekstensi umum: intl, mbstring, json, mysqli/mysqlnd, xml
- Web server (Apache/Nginx) dengan document root ke folder `public/`
- MySQL/MariaDB
- Composer 2+

---

## Instalasi & Menjalankan

1. Install dependensi PHP

```powershell
# Windows PowerShell
composer install
```

2. Duplikasi file env → .env

```powershell
Copy-Item env .env
```

3. Konfigurasi `.env`

- Set `CI_ENVIRONMENT = development`
- Set `app.baseURL = 'http://localhost:8080/'`
- Isi kredensial database pada bagian `database.default.*`

4. Siapkan database

- Buat database kosong (charset utf8mb4)
- Jika migrasi tersedia pada folder `app/Database/Migrations/`, jalankan:

```powershell
php spark migrate
```

5. Jalankan server pengembangan

```powershell
php spark serve
```

Akses: http://localhost:8080

---

## Catatan Penting

- Penomoran `NoStruk` memakai format hari-ini `YYMMDD-XXXXX`, dihitung per outlet dengan lock tabel untuk mencegah duplikasi.
- Validasi peran–outlet: Manajer dan Kasir wajib memiliki `outlet_id`.
- Penghapusan data memiliki guard: mis. barang yang pernah bertransaksi/tengah promo tidak bisa dihapus.
- Log aplikasi tersimpan di `writable/logs`.

---

## Testing

- PHPUnit tersedia via `require-dev`. Jalankan:

```powershell
vendor\bin\phpunit
```

---

## Lisensi

MIT License (lihat berkas `LICENSE`).
