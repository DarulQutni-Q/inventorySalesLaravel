# CRMEdge — Inventory & Sales Management

## Overview

Laravel-based inventory and sales management system with role-based access control, atomic stock consistency, and a modern CRMEdge UI. Supports multi-item purchase (stock-in) and sale (stock-out) transactions with automatic number generation, low-stock alerts, and a transaction report dashboard.

## Tech Stack

- **Laravel 11** · PHP 8.2+ · Blade templates
- **MySQL 8** / MariaDB
- **CSS** — pure embedded design system (CRMEdge UI, no Tailwind CDN)

---

## ERD (Entity Relationship Diagram)

```
users
  id PK
  name
  email  UNIQUE
  password
  role   ENUM(admin, staff)

categories
  id PK
  name

suppliers
  id PK
  name · phone · email · address

products
  id PK
  category_id FK → categories.id
  sku  UNIQUE
  name
  purchase_price · selling_price
  stock · minimum_stock

purchases                       purchase_items
  id PK                           id PK
  purchase_number  UNIQUE         purchase_id FK → purchases.id
  supplier_id FK → suppliers.id  product_id  FK → products.id
  user_id     FK → users.id       quantity · unit_cost · subtotal
  total
  status ENUM(completed,cancelled)
  purchased_at

sales                           sale_items
  id PK                           id PK
  sale_number  UNIQUE             sale_id     FK → sales.id
  user_id FK → users.id           product_id  FK → products.id
  total                           quantity · unit_price · subtotal
  status ENUM(completed,cancelled)
  sold_at
```

---

## Features

- **Role-based access** — Admin: full access; Staff: sales only (no user management, no reports)
- **Product management** — SKU, category, stock level, minimum stock threshold, pricing
- **Purchase / stock-in** — BM-YYYYMMDD-XXXX numbering, multi-item, auto stock increment, cancellation with stock restore
- **Sales / stock-out** — SL-YYYYMMDD-XXXX numbering, multi-item, stock validated with `lockForUpdate()`, auto stock decrement, cancellation with stock restore
- **Atomic transactions** — `DB::transaction` guarantees all-or-nothing for every purchase and sale
- **Dashboard** — today's sales revenue, monthly revenue, top-selling products, recent transactions
- **Low-stock report** — configurable threshold, sorted by most critical
- **Transaction report** — date-range filter for both purchases and sales
- **Search & pagination** — products and transactions are searchable and paginated
- **Modern CRMEdge UI** — white cards, lime accent, slate-900 primary, clean sidebar

---

## Requirements

- PHP >= 8.2
- Composer
- MySQL 8 or MariaDB
- Node.js + npm (for asset compilation)

---

## Installation

```bash
git clone https://github.com/DarulQutni-Q/inventorySalesRaravel.git
cd inventorySalesRaravel
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_sales
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## Demo Accounts

| Role  | Email               | Password |
|-------|---------------------|----------|
| Admin | admin@example.com   | password |
| Staff | staff@example.com   | password |

> Additional accounts seeded by `UserSeeder`: `admin@inventory.test` / `staff@inventory.test` (password: `password`).

---

## Business Rules

| Rule | Detail |
|------|--------|
| Server-side pricing | `selling_price` and `purchase_price` come from the database; client totals are ignored |
| Stock locking | `Product::lockForUpdate()` prevents overselling under concurrent requests |
| Atomic save | Every purchase and sale is wrapped in `DB::transaction` — partial saves are impossible |
| Cancellation | Cancelling a completed transaction restores the exact stock quantities from its items |
| Low-stock threshold | A product is flagged when `stock <= minimum_stock` |

---

## Running Tests

```bash
php artisan test
```

Test files:

| File | Coverage |
|------|----------|
| `tests/Feature/PurchaseStoreTest.php` | Purchase creation, BM- prefix, empty-item validation, index display |
| `tests/Feature/SaleStoreTest.php` | Stock decrement, insufficient-stock rejection, atomicity rollback, SL- prefix |
| `tests/Feature/AuthTest.php` | Guest redirect, wrong password, staff blocked from admin routes, admin dashboard |

---

## Known Limitations

- Single warehouse only — no multi-location stock
- No payment gateway integration
- No multi-currency support
- No barcode scanning
