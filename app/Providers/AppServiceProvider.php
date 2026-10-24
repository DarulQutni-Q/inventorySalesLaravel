<?php

namespace App\Providers;

use App\Models\CustomerOrder;
use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('vendor.pagination.plain');
        Paginator::defaultSimpleView('vendor.pagination.plain');

        // Angka pesanan menunggu di sidebar, teks polos bukan lonceng.
        View::composer('layouts.app', function ($view) {
            $view->with('pendingOrderCount', CustomerOrder::where('status', 'pending')->count());
        });

        // Jumlah barang di keranjang untuk tautan di header pelanggan.
        View::composer('layouts.customer', function ($view) {
            $view->with('cartCount', array_sum(session('cart', [])));
        });
    }
}
