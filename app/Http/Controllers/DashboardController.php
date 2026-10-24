<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Stok rendah memakai satu aturan yang sama di seluruh aplikasi:
        // stock sudah menyentuh minimum_stock (lihat Product::isLowStock).
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        $stats = [
            'total_produk'        => Product::count(),
            'penjualan_hari_ini'  => Sale::where('status', 'completed')->whereDate('sold_at', $today)->sum('total'),
            'penjualan_bulan_ini' => Sale::where('status', 'completed')
                ->whereYear('sold_at', now()->year)
                ->whereMonth('sold_at', now()->month)
                ->sum('total'),
            'produk_stok_rendah'  => $lowStockCount,
            'pesanan_menunggu'    => CustomerOrder::where('status', 'pending')->count(),
        ];

        $latestSales = Sale::with(['user', 'customer'])
            ->latest('sold_at')
            ->limit(5)
            ->get();

        $latestPurchases = Purchase::with('supplier')
            ->latest('purchased_at')
            ->limit(5)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'latestSales', 'latestPurchases', 'lowStockProducts'));
    }
}
