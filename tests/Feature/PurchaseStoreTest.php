<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PurchaseStoreTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }

    private function supplier(): Supplier
    {
        return Supplier::create(['name' => 'Supplier Test', 'phone' => '08123', 'email' => 's@test.com', 'address' => '-']);
    }

    private function product(): Product
    {
        $cat = Category::create(['name' => 'Kategori Test']);
        return Product::create([
            'category_id'    => $cat->id,
            'sku'            => 'PRD-001',
            'name'           => 'Produk Test',
            'purchase_price' => 5000,
            'selling_price'  => 8000,
            'stock'          => 0,
            'minimum_stock'  => 2,
        ]);
    }

    /** Items disimpan dan total dihitung benar di server. */
    public function test_store_saves_items_and_calculates_total(): void
    {
        $user     = $this->user();
        $supplier = $this->supplier();
        $product  = $this->product();

        $response = $this->actingAs($user)->post(route('purchases.store'), [
            'supplier_id'  => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i'),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3, 'unit_cost' => 5000],
            ],
        ]);

        $response->assertRedirect(route('purchases.index'));

        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $supplier->id,
            'total'       => 15000,
        ]);

        $this->assertDatabaseHas('purchase_items', [
            'product_id' => $product->id,
            'quantity'   => 3,
            'unit_cost'  => 5000,
            'subtotal'   => 15000,
        ]);

        // Stok harus bertambah sesuai quantity.
        $this->assertEquals(3, $product->fresh()->stock);
    }

    /** Nomor faktur harus berformat BM-YYYYMMDD-XXXX. */
    public function test_purchase_number_uses_bm_prefix(): void
    {
        $user     = $this->user();
        $supplier = $this->supplier();
        $product  = $this->product();

        $this->actingAs($user)->post(route('purchases.store'), [
            'supplier_id'  => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i'),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1, 'unit_cost' => 1000],
            ],
        ]);

        $purchase = Purchase::first();
        $this->assertStringStartsWith('BM-', $purchase->purchase_number);
    }

    /** Validasi: items kosong tidak boleh lolos. */
    public function test_store_rejects_empty_items(): void
    {
        $user     = $this->user();
        $supplier = $this->supplier();

        $response = $this->actingAs($user)->post(route('purchases.store'), [
            'supplier_id'  => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i'),
            'items'        => [],
        ]);

        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('purchases', 0);
    }

    /** Index menampilkan TOTAL ITEM (items_count) dan TOTAL HARGA yang benar. */
    public function test_index_displays_items_count_and_total_harga(): void
    {
        $user     = $this->user();
        $supplier = $this->supplier();
        $product  = $this->product();

        $this->actingAs($user)->post(route('purchases.store'), [
            'supplier_id'  => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i'),
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2, 'unit_cost' => 7500],
            ],
        ]);

        $response = $this->actingAs($user)->get(route('purchases.index'));
        $response->assertStatus(200);

        // items_count = 1 (satu baris item)
        $response->assertSee('1');
        // total = 15000 → formatted as 15.000
        $response->assertSee('15.000');
    }
}
