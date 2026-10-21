<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductTest extends TestCase
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

    private function staffUser(): User
    {
        return User::create([
            'name'     => 'Staff Test',
            'email'    => 'staff@test.com',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);
    }

    private function makeCategory(string $name = 'Elektronik'): Category
    {
        return Category::firstOrCreate(['name' => $name]);
    }

    private function makeProduct(array $attributes = []): Product
    {
        $category = $this->makeCategory();

        return Product::create(array_merge([
            'category_id'    => $category->id,
            'sku'            => 'PRD-001',
            'name'           => 'Produk Contoh',
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'stock'          => 10,
            'minimum_stock'  => 2,
        ], $attributes));
    }

    /**
     * Admin mencoba menyimpan produk dengan SKU yang sudah ada -> validasi sku gagal.
     */
    public function test_duplicate_sku_is_rejected(): void
    {
        $admin = $this->adminUser();
        $category = $this->makeCategory();

        $this->makeProduct([
            'category_id' => $category->id,
            'sku'         => 'SKU-001',
            'name'        => 'Produk Pertama',
        ]);

        $response = $this->actingAs($admin)->post('/products', [
            'sku'            => 'SKU-001',
            'name'           => 'Produk Duplikat',
            'category_id'    => $category->id,
            'purchase_price' => 20000,
            'selling_price'  => 30000,
            'minimum_stock'  => 5,
        ]);

        $response->assertSessionHasErrors(['sku']);
        $this->assertDatabaseCount('products', 1);
    }

    /**
     * Admin mencoba menyimpan produk dengan harga negatif -> validasi purchase_price / selling_price gagal.
     */
    public function test_negative_price_is_rejected(): void
    {
        $admin = $this->adminUser();
        $category = $this->makeCategory();

        // Harga beli negatif
        $responsePurchasePrice = $this->actingAs($admin)->post('/products', [
            'sku'            => 'PRD-NEG-1',
            'name'           => 'Produk Harga Beli Negatif',
            'category_id'    => $category->id,
            'purchase_price' => -5000,
            'selling_price'  => 10000,
            'minimum_stock'  => 2,
        ]);
        $responsePurchasePrice->assertSessionHasErrors(['purchase_price']);

        // Harga jual negatif
        $responseSellingPrice = $this->actingAs($admin)->post('/products', [
            'sku'            => 'PRD-NEG-2',
            'name'           => 'Produk Harga Jual Negatif',
            'category_id'    => $category->id,
            'purchase_price' => 5000,
            'selling_price'  => -10000,
            'minimum_stock'  => 2,
        ]);
        $responseSellingPrice->assertSessionHasErrors(['selling_price']);

        $this->assertDatabaseCount('products', 0);
    }

    /**
     * Pencarian SKU/nama dan filter category_id pada /products.
     */
    public function test_product_search_and_category_filter(): void
    {
        $admin = $this->adminUser();

        $categoryFood = $this->makeCategory('Makanan');
        $categoryDrink = $this->makeCategory('Minuman');

        $this->makeProduct([
            'category_id' => $categoryFood->id,
            'sku'         => 'MK-001',
            'name'        => 'Biskuit Cokelat',
        ]);

        $this->makeProduct([
            'category_id' => $categoryDrink->id,
            'sku'         => 'MN-001',
            'name'        => 'Kopi Susu',
        ]);

        $this->makeProduct([
            'category_id' => $categoryDrink->id,
            'sku'         => 'MN-002',
            'name'        => 'Teh Manis',
        ]);

        // 1. Cari berdasarkan SKU
        $responseSku = $this->actingAs($admin)->get('/products?search=MK-001');
        $responseSku->assertStatus(200);
        $responseSku->assertSee('Biskuit Cokelat');
        $responseSku->assertDontSee('Kopi Susu');
        $responseSku->assertDontSee('Teh Manis');

        // 2. Cari berdasarkan Nama
        $responseName = $this->actingAs($admin)->get('/products?search=Kopi');
        $responseName->assertStatus(200);
        $responseName->assertSee('Kopi Susu');
        $responseName->assertDontSee('Biskuit Cokelat');
        $responseName->assertDontSee('Teh Manis');

        // 3. Filter berdasarkan category_id
        $responseCategory = $this->actingAs($admin)->get('/products?category_id=' . $categoryDrink->id);
        $responseCategory->assertStatus(200);
        $responseCategory->assertSee('Kopi Susu');
        $responseCategory->assertSee('Teh Manis');
        $responseCategory->assertDontSee('Biskuit Cokelat');

        // 4. Kombinasi pencarian nama dan filter kategori
        $responseCombined = $this->actingAs($admin)->get('/products?search=Teh&category_id=' . $categoryDrink->id);
        $responseCombined->assertStatus(200);
        $responseCombined->assertSee('Teh Manis');
        $responseCombined->assertDontSee('Kopi Susu');
        $responseCombined->assertDontSee('Biskuit Cokelat');
    }

    /**
     * Produk yang sudah dipakai dalam transaksi (penjualan atau pembelian) tidak boleh dihapus.
     */
    public function test_product_used_in_transaction_cannot_be_deleted(): void
    {
        $admin = $this->adminUser();
        $supplier = Supplier::create([
            'name'    => 'Supplier Utama',
            'phone'   => '08123456789',
            'email'   => 'supplier@test.com',
            'address' => 'Jl. Uji No. 1',
        ]);

        // 1. Produk dipakai di penjualan
        $productSale = $this->makeProduct([
            'sku'  => 'PRD-SALE',
            'name' => 'Produk Terjual',
        ]);

        $sale = Sale::create([
            'user_id'     => $admin->id,
            'sale_number' => 'SL-20261022-0001',
            'total'       => 15000,
            'status'      => 'completed',
            'sold_at'     => now(),
        ]);

        SaleItem::create([
            'sale_id'    => $sale->id,
            'product_id' => $productSale->id,
            'quantity'   => 1,
            'unit_price' => 15000,
            'subtotal'   => 15000,
        ]);

        $responseSale = $this->actingAs($admin)->delete('/products/' . $productSale->id);
        $responseSale->assertRedirect(route('products.index'));
        $responseSale->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $productSale->id]);

        // 2. Produk dipakai di pembelian
        $productPurchase = $this->makeProduct([
            'sku'  => 'PRD-PURCHASE',
            'name' => 'Produk Dibeli',
        ]);

        $purchase = Purchase::create([
            'supplier_id'     => $supplier->id,
            'user_id'         => $admin->id,
            'purchase_number' => 'BM-20261022-0001',
            'total'           => 10000,
            'status'          => 'completed',
            'purchased_at'    => now(),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id'  => $productPurchase->id,
            'quantity'    => 1,
            'unit_cost'   => 10000,
            'subtotal'    => 10000,
        ]);

        $responsePurchase = $this->actingAs($admin)->delete('/products/' . $productPurchase->id);
        $responsePurchase->assertRedirect(route('products.index'));
        $responsePurchase->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $productPurchase->id]);
    }

    /**
     * Staf tidak boleh membuat produk (POST /products) atau menghapus produk (DELETE /products/{id}).
     */
    public function test_staff_cannot_create_or_delete_product(): void
    {
        $staff = $this->staffUser();
        $category = $this->makeCategory();
        $product = $this->makeProduct();

        // Staf mencoba create produk
        $responseCreate = $this->actingAs($staff)->post('/products', [
            'sku'            => 'STAFF-001',
            'name'           => 'Produk dari Staf',
            'category_id'    => $category->id,
            'purchase_price' => 10000,
            'selling_price'  => 15000,
            'minimum_stock'  => 2,
        ]);
        $this->assertContains($responseCreate->getStatusCode(), [302, 403]);
        $this->assertDatabaseMissing('products', ['sku' => 'STAFF-001']);

        // Staf mencoba delete produk
        $responseDelete = $this->actingAs($staff)->delete('/products/' . $product->id);
        $this->assertContains($responseDelete->getStatusCode(), [302, 403]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }
}
