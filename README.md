# Inventory & Sales Management System

Aplikasi web untuk mengelola inventaris dan penjualan usaha distribusi kecil.
Stok produk terupdate otomatis setiap ada transaksi pembelian atau penjualan.

Dibangun dengan Laravel dan MySQL/MariaDB. Tampilan memakai CSS polos
(tanpa build step JavaScript).

## Fitur

- Login dengan hak akses berbeda (admin dan staff)
- Data master: kategori, produk (SKU unik), supplier
- Transaksi pembelian multi-item: stok bertambah otomatis, nomor transaksi
  unik format PR-YYYYMMDD-XXXX
- Transaksi penjualan multi-item: stok berkurang otomatis, nomor transaksi
  unik format SL-YYYYMMDD-XXXX
- Pembatalan transaksi oleh admin (stok dipulihkan)
- Dashboard: kartu ringkasan, grafik penjualan 7 hari, produk terlaris,
  transaksi terbaru
- Laporan stok rendah (batas bisa diubah) dan laporan transaksi dengan
  filter tanggal
- Kelola user (admin): tambah, ubah, hapus, atur role
- Pencarian produk, filter kategori, pagination

## Syarat

- PHP 8.2+
- Composer
- MySQL 8 / MariaDB

## Cara instalasi

```bash
git clone https://github.com/DarulQutni-Q/inventorySalesRaravel.git
cd inventorySalesRaravel
composer install
cp .env.example .env
php artisan key:generate
```

Atur koneksi database di `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sales
DB_USERNAME=root
DB_PASSWORD=<redacted>
```

Lanjutkan:

```bash
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000 di browser.

## Akun demo

| Role  | Email              | Password |
|-------|--------------------|----------|
| Admin | admin@inventory.test | password |
| Staff | staff@inventory.test | password |

## Aturan bisnis

- SKU produk harus unik.
- Penjualan tidak bisa melebihi stok yang tersedia. Kalau satu item kurang,
  seluruh transaksi ditolak tanpa perubahan data.
- Total dan subtotal transaksi selalu dihitung di server dari harga di
  database, bukan dari input browser.
- Header transaksi, item, dan perubahan stok disimpan dalam satu database
  transaction. Kalau satu item gagal, semuanya batal (rollback).
- Baris produk dikunci (`lockForUpdate`) selama transaksi penjualan berjalan
  supaya dua penjualan bersamaan tidak menjual stok yang sama.
- Produk yang sudah pernah ditransaksikan tidak bisa dihapus (foreign key
  restrict); hapus kategori/supplier yang dipakai produk juga ditolak.
- Hanya admin yang bisa mengubah data master, membatalkan transaksi, dan
  mengelola user.

## Keterbatasan

- Satu gudang (belum mendukung multi-warehouse).
- Belum ada payment gateway.
- Laporan masih ringkas (ringkasan dan filter tanggal).
