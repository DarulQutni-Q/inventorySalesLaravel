<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Data untuk dropdown notifikasi di topbar.
        View::composer('layouts.app', function ($view) {
            $recentSales = Sale::latest('sold_at')->limit(4)->get();
            $lowStock    = Product::where('stock', '<', 5)->orderBy('stock')->limit(3)->get();

            $notifications = $recentSales->map(fn ($s) => [
                'type'  => 'sale',
                'title' => 'Penjualan ' . $s->sale_number,
                'desc'  => 'Total Rp ' . number_format($s->total, 0, ',', '.'),
                'time'  => $s->sold_at->diffForHumans(),
                'url'   => route('sales.show', $s),
            ])->concat($lowStock->map(fn ($p) => [
                'type'  => 'low-stock',
                'title' => 'Stok menipis: ' . $p->name,
                'desc'  => 'Tersisa ' . $p->stock . ' pcs',
                'time'  => 'perlu restock',
                'url'   => route('products.show', $p),
            ]))->values();

            $view->with('notifications', $notifications);
        });
    }
}
