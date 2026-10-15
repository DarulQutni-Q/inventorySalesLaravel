<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user')
            ->latest('sold_at')
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        // Hanya tampilkan produk yang stoknya masih ada.
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $productsJson = $products->map(fn ($p) => [
            'id' => $p->id,
            'sku' => $p->sku,
            'name' => $p->name,
            'price' => $p->selling_price,
            'stock' => $p->stock,
        ])->values()->toArray();

        return view('sales.create', compact('products', 'productsJson'));
    }

    public function store(SaleRequest $request, SaleService $service)
    {
        $sale = $service->create($request->validated(), auth()->id());

        return redirect()->route('sales.index')
            ->with('success', 'Penjualan ' . $sale->sale_number . ' berhasil disimpan.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', compact('sale'));
    }
}
