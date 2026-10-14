<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // [kategori, sku, nama, harga beli, harga jual, stok, stok minimum]
        $items = [
            ['Sembako', 'BR-001', 'Beras Premium 5kg', 68500, 75000, 120, 20],
            ['Sembako', 'MG-002', 'Minyak Goreng 2L', 42000, 47000, 85, 15],
            ['Sembako', 'GP-003', 'Gula Pasir 1kg', 17500, 19500, 200, 30],
            ['Sembako', 'TP-004', 'Tepung Terigu 1kg', 13000, 15000, 90, 15],
            ['Minuman', 'TB-005', 'Teh Botol 450ml', 3500, 4500, 300, 50],
            ['Minuman', 'KP-006', 'Kopi Sachet 10s', 15000, 18000, 110, 20],
            ['Minuman', 'AM-007', 'Air Mineral 600ml', 2500, 3500, 400, 60],
            ['Makanan Ringan', 'BS-008', 'Biskuit Kaleng 500g', 35000, 42000, 45, 10],
            ['Makanan Ringan', 'MI-009', 'Mie Instan Goreng', 3000, 3800, 500, 80],
            ['Bumbu Dapur', 'KM-010', 'Kecap Manis 600ml', 24000, 28000, 70, 12],
            ['Bumbu Dapur', 'GR-011', 'Garam 500g', 5000, 7000, 5, 10],
            ['Kebersihan', 'SC-012', 'Sabun Cuci Piring 800ml', 18000, 22000, 60, 10],
            ['Kebersihan', 'DT-013', 'Deterjen Bubuk 800g', 22000, 26000, 55, 10],
        ];

        foreach ($items as [$category, $sku, $name, $buy, $sell, $stock, $min]) {
            Product::create([
                'category_id' => Category::where('name', $category)->firstOrFail()->id,
                'sku' => $sku,
                'name' => $name,
                'purchase_price' => $buy,
                'selling_price' => $sell,
                'stock' => $stock,
                'minimum_stock' => $min,
            ]);
        }
    }
}
