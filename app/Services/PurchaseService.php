<?php

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

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
                'purchase_number' => 'PR-' . now()->format('YmdHis'),
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

                $total += $subtotal;
            }

            $purchase->update(['total' => $total]);

            return $purchase;
        });
    }
}
