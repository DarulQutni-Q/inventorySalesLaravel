<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stats = [
            'total_produk' => Product::count(),
            'stok_menipis' => Product::where('stock', '<', 5)->count(),
            'nilai_persediaan' => Product::sum(DB::raw('stock * purchase_price')),
            'penjualan_hari_ini' => Sale::where('status', 'completed')->whereDate('sold_at', $today)->sum('total'),
            'pembelian_hari_ini' => Purchase::where('status', 'completed')->whereDate('purchased_at', $today)->sum('total'),
            'total_user' => User::count(),
        ];

        // Penjualan 7 hari terakhir untuk grafik batang sederhana.
        $sales7 = Sale::where('status', 'completed')
            ->where('sold_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(sold_at) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $chart = [];
        $max = max(1, (float) ($sales7->max() ?? 0));
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $t = (float) $sales7->get($d->toDateString(), 0);
            $chart[] = [
                'label' => $d->format('d M'),
                'total' => $t,
                'pct' => (int) round($t / $max * 100),
            ];
        }

        // 5 produk terlaris berdasarkan quantity terjual.
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', 'completed')
            ->selectRaw('products.name, products.sku, SUM(sale_items.quantity) as terjual')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('terjual')
            ->limit(5)
            ->get();

        $latestSales = Sale::with('user')->latest('sold_at')->limit(5)->get();
        $latestPurchases = Purchase::with('supplier')->latest('purchased_at')->limit(5)->get();

        return view('dashboard', compact('stats', 'chart', 'topProducts', 'latestSales', 'latestPurchases'));
    }
}
