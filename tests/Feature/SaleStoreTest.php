<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SaleStoreTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }

    private function makeProduct(int $stock = 10, string $sku = 'PRD-001'): Product
    {
        $cat = Category::firstOrCreate(['name' => 'Kategori Test']);
        return Product::create([
            'category_id'    => $cat->id,
            'sku'            => $sku,
            'name'           => 'Produk Test ' . $sku,
            'purchase_price' => 5000,
            'selling_price'  => 8000,
            'stock'          => $stock,
            'minimum_stock'  => 2,
        ]);
    }

    /** Stok berkurang sesuai qty yang dijual, total dihitung dari harga DB. */
    public function test_sale_decrements_stock_correctly(): void
    {
        $user    = $this->adminUser();
        $product = $this->makeProduct(stock: 10);

        $response = $this->actingAs($user)->post(route('sales.store'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $response->assertRedirect(route('sales.index'));

        // Stok harus berkurang 3.
        $this->assertSame(7, $product->fresh()->stock);

        // Total = qty * selling_price dari DB.
        $this->assertDatabaseHas('sales', [
            'total' => 3 * 8000,
        ]);

        // Tepat satu baris item.
        $sale = Sale::first();
        $this->assertNotNull($sale);
        $this->assertCount(1, $sale->items);
    }

    /** Stok tidak cukup → session error 'items', tidak ada sale yang tersimpan. */
    public function test_sale_rejected_when_stock_insufficient(): void
    {
        $user    = $this->adminUser();
        $product = $this->makeProduct(stock: 2);

        $response = $this->actingAs($user)->post(route('sales.store'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('sales', 0);

        // Stok tetap 2 — tidak berubah.
        $this->assertSame(2, $product->fresh()->stock);
    }

    /**
     * Jika item ke-2 stoknya tidak cukup, seluruh transaksi di-rollback.
     * Stok item ke-1 harus tetap sama.
     */
    public function test_sale_atomicity_rollback(): void
    {
        $user     = $this->adminUser();
        $product1 = $this->makeProduct(stock: 10, sku: 'PRD-001');
        $product2 = $this->makeProduct(stock: 1,  sku: 'PRD-002');

        $this->actingAs($user)->post(route('sales.store'), [
            'items' => [
                ['product_id' => $product1->id, 'quantity' => 3],
                ['product_id' => $product2->id, 'quantity' => 5], // stok tidak cukup
            ],
        ]);

        // Tidak ada sale yang tersimpan.
        $this->assertDatabaseCount('sales', 0);

        // Stok produk pertama harus tetap 10 (rollback).
        $this->assertSame(10, $product1->fresh()->stock);

        // Stok produk kedua harus tetap 1.
        $this->assertSame(1, $product2->fresh()->stock);
    }

    /** Nomor faktur penjualan harus dimulai dengan 'SL-'. */
    public function test_sale_number_uses_sl_prefix(): void
    {
        $user    = $this->adminUser();
        $product = $this->makeProduct(stock: 5);

        $this->actingAs($user)->post(route('sales.store'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $sale = Sale::first();
        $this->assertNotNull($sale);
        $this->assertStringStartsWith('SL-', $sale->sale_number);
    }
}
