<?php

namespace App\Services;

use App\Models\CustomerOrder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerOrderService
{
    public function __construct(private SaleService $saleService)
    {
    }

    /**
     * Buat pesanan dari keranjang session. Stok TIDAK berubah di sini;
     * stok hanya berkurang saat staff mengkonfirmasi (method confirm).
     */
    public function create(array $cart, int $customerId): CustomerOrder
    {
        return DB::transaction(function () use ($cart, $customerId) {
            if (empty($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang kosong, tidak bisa membuat pesanan.',
                ]);
            }

            $products = Product::whereIn('id', array_keys($cart))->get();
            if ($products->count() !== count($cart)) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada produk di keranjang yang sudah tidak tersedia. Hapus dari keranjang lalu coba lagi.',
                ]);
            }

            $order = CustomerOrder::create([
                'customer_id' => $customerId,
                'order_number' => $this->generateNumber(),
                'total' => 0,
                'status' => 'pending',
                'ordered_at' => now(),
            ]);

            $total = 0;
            foreach ($products as $product) {
                $qty = (int) $cart[$product->id];

                // unit_price di pesanan hanya snapshot untuk tampilan;
                // total final dihitung ulang saat staff mengkonfirmasi.
                $subtotal = $product->selling_price * $qty;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            session()->forget('cart');

            return $order;
        });
    }

    /**
     * Konfirmasi pesanan: satu-satunya jalan pesanan menjadi penjualan.
     * Stok dikunci, dicek, dan dikurangi di sini dalam satu transaction.
     */
    public function confirm(CustomerOrder $order, int $userId): Sale
    {
        return DB::transaction(function () use ($order, $userId) {
            if ($order->status !== 'pending') {
                throw ValidationException::withMessages([
                    'order' => 'Pesanan ini sudah diproses sebelumnya.',
                ]);
            }

            $order->load('items');

            // Fase 1: periksa stok SEMUA item dulu dengan lockForUpdate,
            // mengikuti pola SaleService. Satu item kurang, seluruh
            // konfirmasi gagal dan pesanan tetap menunggu.
            $checked = [];
            $errors = [];
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                $qty = $item->quantity;

                if ($product->stock < $qty) {
                    $errors[] = "Stok {$product->name} tidak cukup (tersedia {$product->stock}, diminta {$qty}).";
                }

                $checked[] = [$product, $qty];
            }

            if ($errors) {
                throw ValidationException::withMessages(['order' => $errors]);
            }

            // Fase 2: buat sale. Total dihitung dari selling_price di
            // database saat konfirmasi, bukan dari snapshot pesanan.
            $sale = Sale::create([
                'user_id' => $userId,
                'customer_id' => $order->customer_id,
                'customer_order_id' => $order->id,
                'sale_number' => $this->saleService->generateNumber(),
                'total' => 0,
                'status' => 'completed',
                'sold_at' => now(),
            ]);

            $total = 0;
            foreach ($checked as [$product, $qty]) {
                $subtotal = $product->selling_price * $qty;

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $qty);
                $total += $subtotal;
            }

            $sale->update(['total' => $total]);

            $order->update([
                'status' => 'confirmed',
                'confirmed_by' => $userId,
                'confirmed_at' => now(),
            ]);

            return $sale;
        });
    }

    /**
     * Tolak pesanan: tidak ada sale yang dibuat, stok tidak berubah.
     */
    public function reject(CustomerOrder $order): void
    {
        if ($order->status !== 'pending') {
            throw ValidationException::withMessages([
                'order' => 'Pesanan ini sudah diproses sebelumnya.',
            ]);
        }

        $order->update(['status' => 'rejected']);
    }

    /**
     * Nomor pesanan format PP-YYYYMMDD-XXXX, urut per hari,
     * mengikuti pola SL- (penjualan) dan BM- (pembelian).
     */
    public function generateNumber(): string
    {
        $date = now()->format('Ymd');

        $last = CustomerOrder::where('order_number', 'like', "PP-{$date}-%")
            ->orderByDesc('order_number')
            ->value('order_number');

        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return sprintf('PP-%s-%04d', $date, $seq);
    }
}
