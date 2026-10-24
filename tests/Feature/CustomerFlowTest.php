<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerFlowTest extends TestCase
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

    private function makeProduct(int $stock = 10, int $minimumStock = 2, string $sku = 'PRD-001'): Product
    {
        $category = Category::firstOrCreate(['name' => 'Kategori Test']);

        return Product::create([
            'category_id'    => $category->id,
            'sku'            => $sku,
            'name'           => 'Produk Test ' . $sku,
            'purchase_price' => 5000,
            'selling_price'  => 8000,
            'stock'          => $stock,
            'minimum_stock'  => $minimumStock,
        ]);
    }

    private function actAsCustomer(Customer $customer): static
    {
        return $this->withSession([
            'customer_id'   => $customer->id,
            'customer_name' => $customer->name,
        ]);
    }

    private function makeOrder(Customer $customer, Product $product, int $qty): CustomerOrder
    {
        $order = CustomerOrder::create([
            'customer_id' => $customer->id,
            'order_number' => 'PP-' . now()->format('Ymd') . '-0001',
            'total' => $product->selling_price * $qty,
            'status' => 'pending',
            'ordered_at' => now(),
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $qty,
            'unit_price' => $product->selling_price,
            'subtotal' => $product->selling_price * $qty,
        ]);

        return $order;
    }

    /** Masuk pelanggan dengan telepon baru membuat customer, telepon lama dipakai ulang. */
    public function test_customer_login_reuses_existing_phone(): void
    {
        $this->post('/pelanggan/masuk', [
            'name' => 'Toko Bu Ani',
            'phone' => '0812-9999-0001',
        ]);

        $this->assertDatabaseCount('customers', 1);

        // Nama berbeda, telepon sama: tidak membuat duplikat.
        $this->post('/pelanggan/masuk', [
            'name' => 'Nama Lain',
            'phone' => '0812-9999-0001',
        ]);

        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseHas('customers', ['name' => 'Toko Bu Ani']);
    }

    /** Tanpa session pelanggan, katalog dan keranjang dialihkan ke halaman masuk pelanggan. */
    public function test_guest_customer_redirected_from_catalog(): void
    {
        $this->get('/katalog')->assertRedirect('/pelanggan/masuk');
        $this->get('/keranjang')->assertRedirect('/pelanggan/masuk');
    }

    /** Session pelanggan tidak bisa membuka route admin atau staff lewat URL. */
    public function test_customer_cannot_access_admin_routes(): void
    {
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0002']);

        $this->actAsCustomer($customer)
            ->get('/products')
            ->assertRedirect('/login');

        $this->actAsCustomer($customer)
            ->get('/dashboard')
            ->assertRedirect('/login');

        $this->actAsCustomer($customer)
            ->get('/users')
            ->assertRedirect('/login');
    }

    /** Membuat pesanan dari keranjang tidak mengubah stok sama sekali. */
    public function test_placing_order_does_not_change_stock(): void
    {
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0003']);
        $product = $this->makeProduct(stock: 10);

        $this->actAsCustomer($customer)->post('/katalog/tambah', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->actAsCustomer($customer)->post('/keranjang/pesan');

        $this->assertDatabaseCount('customer_orders', 1);
        $order = CustomerOrder::first();
        $this->assertStringStartsWith('PP-', $order->order_number);
        $this->assertEquals(3 * 8000, (float) $order->total);

        // Stok tetap, keranjang dikosongkan.
        $this->assertSame(10, $product->fresh()->stock);
        $this->assertEmpty(session('cart'));
    }

    /** Keranjang kosong ditolak, tidak membuat pesanan. */
    public function test_checkout_rejects_empty_cart(): void
    {
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0004']);

        $this->actAsCustomer($customer)
            ->post('/keranjang/pesan')
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('customer_orders', 0);
    }

    /** Staff mengkonfirmasi pesanan stok cukup: sale baru, stok berkurang, pesanan confirmed. */
    public function test_confirm_order_with_enough_stock_creates_sale(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0005']);
        $product = $this->makeProduct(stock: 10);
        $order = $this->makeOrder($customer, $product, 3);

        $response = $this->actingAs($staff)
            ->post(route('customer-orders.confirm', $order));

        // Setelah konfirmasi berhasil, staff diarahkan ke detail sale barunya.
        $response->assertRedirect(route('sales.show', \App\Models\Sale::first()));

        $order->refresh();
        $this->assertSame('confirmed', $order->status);
        $this->assertSame($staff->id, $order->confirmed_by);

        // Sale baru dengan total dari harga database, stok berkurang.
        $this->assertDatabaseCount('sales', 1);
        $sale = \App\Models\Sale::first();
        $this->assertEquals(3 * 8000, (float) $sale->total);
        $this->assertSame($order->id, $sale->customer_order_id);
        $this->assertSame($customer->id, $sale->customer_id);
        $this->assertSame(7, $product->fresh()->stock);
    }

    /** Konfirmasi pesanan dengan stok kurang gagal total: tanpa sale, stok dan pesanan tetap. */
    public function test_confirm_order_with_insufficient_stock_fails_completely(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0006']);
        $product = $this->makeProduct(stock: 2);
        $order = $this->makeOrder($customer, $product, 5);

        $this->actingAs($staff)
            ->post(route('customer-orders.confirm', $order))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('sales', 0);
        $this->assertSame(2, $product->fresh()->stock);
        $this->assertSame('pending', $order->fresh()->status);
    }

    /** Menolak pesanan tidak mengubah stok dan tidak membuat sale. */
    public function test_reject_order_keeps_stock_unchanged(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0007']);
        $product = $this->makeProduct(stock: 10);
        $order = $this->makeOrder($customer, $product, 3);

        $this->actingAs($staff)
            ->post(route('customer-orders.reject', $order))
            ->assertSessionHas('success');

        $this->assertSame('rejected', $order->fresh()->status);
        $this->assertDatabaseCount('sales', 0);
        $this->assertSame(10, $product->fresh()->stock);
    }

    /** Pesanan sudah diproses tidak bisa dikonfirmasi lagi. */
    public function test_confirmed_order_cannot_be_confirmed_again(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0008']);
        $product = $this->makeProduct(stock: 10);
        $order = $this->makeOrder($customer, $product, 1);

        $this->actingAs($staff)->post(route('customer-orders.confirm', $order));
        $this->actingAs($staff)
            ->post(route('customer-orders.confirm', $order))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('sales', 1);
        $this->assertSame(9, $product->fresh()->stock);
    }

    /** Konfirmasi pesanan menangani exception tak terduga tanpa melempar ke system error page. */
    public function test_confirm_order_handles_unexpected_exception(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Toko Bu Ani', 'phone' => '0812-9999-0011']);
        $product = $this->makeProduct(stock: 10);
        $order = $this->makeOrder($customer, $product, 1);

        $this->mock(\App\Services\CustomerOrderService::class, function ($mock) {
            $mock->shouldReceive('confirm')->andThrow(new \Exception('Koneksi database terputus.'));
        });

        $this->actingAs($staff)
            ->post(route('customer-orders.confirm', $order))
            ->assertRedirect(route('customer-orders.show', $order))
            ->assertSessionHas('error', 'Terjadi kesalahan saat memproses pesanan: Koneksi database terputus.');
    }

    /** Pelanggan hanya bisa melihat pesanan miliknya sendiri. */
    public function test_customer_cannot_view_others_order(): void
    {
        $owner = Customer::create(['name' => 'Pemilik Pesanan', 'phone' => '0812-9999-0009']);
        $other = Customer::create(['name' => 'Pelanggan Lain', 'phone' => '0812-9999-0010']);
        $product = $this->makeProduct();
        $order = $this->makeOrder($owner, $product, 1);

        $this->actAsCustomer($other)
            ->get(route('customer.orders.show', $order))
            ->assertForbidden();
    }

    /** Admin bisa membuat pelanggan; validasi nama wajib. */
    public function test_admin_can_create_customer_and_name_is_required(): void
    {
        $admin = $this->adminUser();

        $this->actingAs($admin)->post('/customers', [
            'name' => 'Warung Pak Budi',
            'phone' => '0813-8888-0001',
        ])->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', ['name' => 'Warung Pak Budi']);

        $this->actingAs($admin)->post('/customers', [
            'name' => '',
            'phone' => '0813-8888-0002',
        ])->assertSessionHasErrors('name');
    }

    /** Staff tidak bisa membuat atau menghapus pelanggan. */
    public function test_staff_cannot_create_or_delete_customer(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Warung Pak Budi', 'phone' => '0813-8888-0003']);

        $this->actingAs($staff)->post('/customers', [
            'name' => 'Pelanggan Baru',
            'phone' => '0813-8888-0004',
        ])->assertStatus(403);

        $this->actingAs($staff)->delete('/customers/' . $customer->id)->assertStatus(403);
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    /** Penjualan bisa dibuat dengan pelanggan dan tanpa pelanggan. */
    public function test_sale_with_and_without_customer(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Warung Pak Budi', 'phone' => '0813-8888-0005']);
        $product = $this->makeProduct(stock: 10);

        $this->actingAs($staff)->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);
        $this->assertDatabaseHas('sales', ['customer_id' => $customer->id]);

        $this->actingAs($staff)->post(route('sales.store'), [
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);
        $this->assertDatabaseHas('sales', ['customer_id' => null]);
    }

    /** Detail penjualan menampilkan nama pelanggan. */
    public function test_sale_detail_shows_customer_name(): void
    {
        $staff = $this->staffUser();
        $customer = Customer::create(['name' => 'Warung Pak Budi', 'phone' => '0813-8888-0006']);
        $product = $this->makeProduct(stock: 10);

        $this->actingAs($staff)->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $sale = \App\Models\Sale::first();

        $this->actingAs($staff)
            ->get(route('sales.show', $sale))
            ->assertSee('Warung Pak Budi');
    }

    /** Pelanggan yang sudah dipakai penjualan atau pesanan tidak bisa dihapus. */
    public function test_customer_with_history_cannot_be_deleted(): void
    {
        $admin = $this->adminUser();
        $product = $this->makeProduct(stock: 10);

        $customerWithSale = Customer::create(['name' => 'Pakai Sale', 'phone' => '0813-8888-0007']);
        $this->actingAs($admin)->post(route('sales.store'), [
            'customer_id' => $customerWithSale->id,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);
        $this->actingAs($admin)
            ->delete('/customers/' . $customerWithSale->id)
            ->assertSessionHas('error');
        $this->assertDatabaseHas('customers', ['id' => $customerWithSale->id]);

        $customerWithOrder = Customer::create(['name' => 'Pakai Order', 'phone' => '0813-8888-0008']);
        $this->makeOrder($customerWithOrder, $product, 1);
        $this->actingAs($admin)
            ->delete('/customers/' . $customerWithOrder->id)
            ->assertSessionHas('error');
        $this->assertDatabaseHas('customers', ['id' => $customerWithOrder->id]);

        // Pelanggan tanpa riwayat boleh dihapus.
        $plain = Customer::create(['name' => 'Tanpa Riwayat', 'phone' => '0813-8888-0009']);
        $this->actingAs($admin)
            ->delete('/customers/' . $plain->id)
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('customers', ['id' => $plain->id]);
    }

    /** Halaman stok rendah memakai aturan stock <= minimum_stock, bukan angka 5. */
    public function test_low_stock_uses_minimum_stock_rule(): void
    {
        $admin = $this->adminUser();

        // Stok 5 dengan minimum 5: rendah (menyentuh batas).
        $this->makeProduct(stock: 5, minimumStock: 5, sku: 'LOW-001');

        // Stok 6 dengan minimum 5: masih aman, tidak muncul meski angka 6 di atas 5.
        $this->makeProduct(stock: 6, minimumStock: 5, sku: 'OK-001');

        // Stok 4 dengan minimum 10: rendah.
        $this->makeProduct(stock: 4, minimumStock: 10, sku: 'LOW-002');

        $response = $this->actingAs($admin)->get(route('reports.low-stock'));

        $response->assertStatus(200);
        $response->assertSee('Produk Test LOW-001');
        $response->assertSee('Produk Test LOW-002');
        $response->assertDontSee('Produk Test OK-001');
    }
}
