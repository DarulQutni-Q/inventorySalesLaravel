# Inventory & Sales Management System

Aplikasi web untuk mengelola inventaris dan penjualan usaha distribusi kecil.
Stok produk terupdate otomatis setiap ada transaksi pembelian atau penjualan.

Dibangun dengan Laravel dan MySQL.

## Fitur

- Login dengan hak akses berbeda (admin dan staff)
- Data master: kategori, produk (SKU unik), supplier
- Transaksi pembelian: stok bertambah otomatis
- Transaksi penjualan: stok berkurang otomatis, transaksi ditolak kalau stok kurang
- Pembatalan transaksi oleh admin (stok dipulihkan)
- Dashboard ringkasan penjualan dan daftar produk stok rendah
- Pencarian produk, filter tanggal transaksi, pagination

## Syarat

- PHP 8.2+
- Composer
- MySQL 8 / MariaDB
- Node.js dan npm (untuk build asset)

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
DB_PASSWORD=
```

Lanjutkan:

```bash
php artisan migrate --seed
npm install
npm run build
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
- Penjualan tidak bisa melebihi stok yang tersedia.
- Total transaksi selalu dihitung di server, bukan dari input browser.
- Header transaksi, item, dan perubahan stok disimpan dalam satu database transaction. Kalau satu item gagal, semuanya batal.
- Produk yang sudah pernah ditransaksikan tidak bisa dihapus permanen.

## Keterbatasan

- Satu gudang (belum mendukung multi-warehouse).
- Belum ada payment gateway.
- Laporan masih ringkas (ringkasan dan filter tanggal).
