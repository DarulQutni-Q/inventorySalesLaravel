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
        $today     = now()->toDateString();
        $thisMonth = now()->month;
        $thisYear  = now()->year;
        $lastMonth = now()->subMonthNoOverflow();
        $lmMonth   = $lastMonth->month;
        $lmYear    = $lastMonth->year;

        $penjualanBulanIni  = (float) Sale::where('status', 'completed')
            ->whereYear('sold_at', $thisYear)
            ->whereMonth('sold_at', $thisMonth)
            ->sum('total');

        $penjualanBulanLalu = (float) Sale::where('status', 'completed')
            ->whereYear('sold_at', $lmYear)
            ->whereMonth('sold_at', $lmMonth)
            ->sum('total');

        $totalTransaksi     = (int) Sale::where('status', 'completed')
            ->whereYear('sold_at', $thisYear)
            ->whereMonth('sold_at', $thisMonth)
            ->count();

        $totalTransaksiLalu = (int) Sale::where('status', 'completed')
            ->whereYear('sold_at', $lmYear)
            ->whereMonth('sold_at', $lmMonth)
            ->count();

        $avgPenjualan     = $totalTransaksi > 0 ? $penjualanBulanIni / $totalTransaksi : 0;
        $avgPenjualanLalu = $totalTransaksiLalu > 0 ? $penjualanBulanLalu / $totalTransaksiLalu : 0;

        $trendPenjualan  = $penjualanBulanLalu > 0
            ? round(($penjualanBulanIni - $penjualanBulanLalu) / $penjualanBulanLalu * 100, 1)
            : ($penjualanBulanIni > 0 ? 100 : 0);

        $trendTransaksi  = $totalTransaksiLalu > 0
            ? round(($totalTransaksi - $totalTransaksiLalu) / $totalTransaksiLalu * 100, 1)
            : ($totalTransaksi > 0 ? 100 : 0);

        $avgTrend = $avgPenjualanLalu > 0
            ? round(($avgPenjualan - $avgPenjualanLalu) / $avgPenjualanLalu * 100, 1)
            : ($avgPenjualan > 0 ? 100 : 0);

        $skorKinerja = min(100, (int) ($totalTransaksi * 5));

        $stats = [
            'total_produk'          => Product::count(),
            'stok_menipis'          => Product::where('stock', '<', 5)->count(),
            'nilai_persediaan'      => Product::sum(DB::raw('stock * purchase_price')),
            'penjualan_hari_ini'    => Sale::where('status', 'completed')->whereDate('sold_at', $today)->sum('total'),
            'pembelian_hari_ini'    => Purchase::where('status', 'completed')->whereDate('purchased_at', $today)->sum('total'),
            'total_user'            => User::count(),
            'penjualan_bulan_ini'   => $penjualanBulanIni,
            'penjualan_bulan_lalu'  => $penjualanBulanLalu,
            'total_transaksi'       => $totalTransaksi,
            'total_transaksi_lalu'  => $totalTransaksiLalu,
            'avg_penjualan'         => $avgPenjualan,
            'avg_penjualan_lalu'    => $avgPenjualanLalu,
            'trend_penjualan'       => $trendPenjualan,
            'trend_transaksi'       => $trendTransaksi,
            'avg_trend'             => $avgTrend,
            'skor_kinerja'          => $skorKinerja,
        ];

        // Penjualan 7 hari terakhir untuk grafik batang.
        $sales7 = Sale::where('status', 'completed')
            ->where('sold_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(sold_at) as tanggal, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $chart = [];
        $max   = max(1, (float) ($sales7->max() ?? 0));
        for ($i = 6; $i >= 0; $i--) {
            $d       = now()->subDays($i);
            $t       = (float) $sales7->get($d->toDateString(), 0);
            $chart[] = [
                'label' => $d->format('d M'),
                'total' => $t,
                'pct'   => (int) round($t / $max * 100),
            ];
        }

        // 5 produk terlaris.
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.status', 'completed')
            ->selectRaw('products.name, products.sku, SUM(sale_items.quantity) as terjual')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('terjual')
            ->limit(5)
            ->get();

        $latestSales     = Sale::with('user')->withCount('items')->latest('sold_at')->limit(5)->get();
        $latestPurchases = Purchase::with('supplier')->latest('purchased_at')->limit(5)->get();

        return view('dashboard', compact('stats', 'chart', 'topProducts', 'latestSales', 'latestPurchases'));
    }
}
