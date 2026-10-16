<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    /**
     * Simpan penjualan dalam satu database transaction.
     * Harga diambil dari database (bukan dari input browser), dan stok
     * dikurangi hanya kalau seluruh transaksi berhasil.
     */
    public function create(array $data, int $userId): Sale
    {
        return DB::transaction(function () use ($data, $userId) {
            // Fase 1: periksa stok SEMUA item dulu. Kalau ada satu saja yang
            // kurang, seluruh transaksi ditolak sebelum ada data yang berubah.
            $checked = [];
            $errors = [];
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                if ($qty < 1 || $product->stock < $qty) {
                    $errors[] = "Stok {$product->name} tidak cukup (tersedia {$product->stock}, diminta {$qty}).";
                }

                $checked[] = [$product, $qty];
            }

            if ($errors) {
                throw ValidationException::withMessages(['items' => $errors]);
            }

            // Fase 2: semua item valid, simpan header, item, dan kurangi stok.
            $sale = Sale::create([
                'user_id' => $userId,
                'sale_number' => $this->generateNumber(),
                'total' => 0,
                'status' => 'completed',
                'sold_at' => $data['sold_at'] ?? now(),
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

            return $sale;
        });
    }

    /**
     * Nomor transaksi format SL-YYYYMMDD-XXXX, urut per hari.
     */
    private function generateNumber(): string
    {
        $date = now()->format('Ymd');

        $last = Sale::where('sale_number', 'like', "SL-{$date}-%")
            ->orderByDesc('sale_number')
            ->value('sale_number');

        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return sprintf('SL-%s-%04d', $date, $seq);
    }
}
