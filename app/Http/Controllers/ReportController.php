<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Daftar produk dengan stok di bawah batas (default 5).
     */
    public function lowStock(Request $request)
    {
        $threshold = max(1, (int) $request->query('batas', 5));

        $products = Product::with('category')
            ->where('stock', '<', $threshold)
            ->orderBy('stock')
            ->paginate(15)
            ->withQueryString();

        return view('reports.low-stock', compact('products', 'threshold'));
    }

    /**
     * Laporan transaksi pembelian dan penjualan dengan filter tanggal.
     */
    public function transactions(Request $request)
    {
        $validated = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
        ]);

        $dari = $validated['dari'] ?? now()->startOfMonth()->toDateString();
        $sampai = $validated['sampai'] ?? now()->toDateString();

        // Nama parameter page dibedakan supaya paginasi dua tabel tidak bentrok.
        $purchases = Purchase::with(['supplier', 'user'])
            ->whereDate('purchased_at', '>=', $dari)
            ->whereDate('purchased_at', '<=', $sampai)
            ->latest('purchased_at')
            ->paginate(10, ['*'], 'purchase_page')
            ->withQueryString();

        $sales = Sale::with('user')
            ->whereDate('sold_at', '>=', $dari)
            ->whereDate('sold_at', '<=', $sampai)
            ->latest('sold_at')
            ->paginate(10, ['*'], 'sale_page')
            ->withQueryString();

        $totalPurchase = Purchase::where('status', 'completed')
            ->whereDate('purchased_at', '>=', $dari)
            ->whereDate('purchased_at', '<=', $sampai)
            ->sum('total');

        $totalSale = Sale::where('status', 'completed')
            ->whereDate('sold_at', '>=', $dari)
            ->whereDate('sold_at', '<=', $sampai)
            ->sum('total');

        return view('reports.transactions', compact(
            'purchases', 'sales', 'dari', 'sampai', 'totalPurchase', 'totalSale'
        ));
    }
}
