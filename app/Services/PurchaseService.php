<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    /**
     * Simpan pembelian beserta item-itemnya dalam satu database transaction.
     * Total dihitung di server dari unit_cost x quantity per item.
     */
    public function create(array $data, int $userId): Purchase
    {
        return DB::transaction(function () use ($data, $userId) {
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $userId,
                'purchase_number' => $this->generateNumber(),
                'total' => 0,
                'status' => 'completed',
                'purchased_at' => $data['purchased_at'] ?? now(),
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_cost'];

                $purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'subtotal' => $subtotal,
                ]);

                // Stok bertambah otomatis setiap pembelian tersimpan.
                // Baris ini di dalam transaction yang sama: kalau ada item
                // yang gagal, penambahan stok ikut dibatalkan (rollback).
                Product::where('id', $item['product_id'])
                    ->increment('stock', $item['quantity']);

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            return $purchase;
        });
    }

    /**
     * Batalkan pembelian: stok yang dulu bertambah dikurangi kembali.
     * Hanya untuk transaksi yang masih berstatus completed.
     */
    public function cancel(Purchase $purchase): void
    {
        if ($purchase->status !== 'completed') {
            throw ValidationException::withMessages([
                'transaksi' => 'Transaksi ini sudah dibatalkan sebelumnya.',
            ]);
        }

        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                Product::where('id', $item->product_id)
                    ->decrement('stock', $item->quantity);
            }

            $purchase->update(['status' => 'cancelled']);
        });
    }

    /**
     * Nomor transaksi format PR-YYYYMMDD-XXXX, urut per hari.
     * Unik karena kolom purchase_number punya unique constraint.
     */
    private function generateNumber(): string
    {
        $date = now()->format('Ymd');

        $last = Purchase::where('purchase_number', 'like', "PR-{$date}-%")
            ->orderByDesc('purchase_number')
            ->value('purchase_number');

        $seq = $last ? (int) substr($last, -4) + 1 : 1;

        return sprintf('PR-%s-%04d', $date, $seq);
    }
}
