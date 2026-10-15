<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest('purchased_at')
            ->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        // Dipetakan di controller supaya @json di Blade menerima array polos.
        $productsJson = $products->map(fn ($p) => [
            'id' => $p->id,
            'sku' => $p->sku,
            'name' => $p->name,
            'price' => $p->purchase_price,
        ])->values()->toArray();

        return view('purchases.create', compact('suppliers', 'products', 'productsJson'));
    }

    public function store(PurchaseRequest $request, PurchaseService $service)
    {
        $purchase = $service->create($request->validated(), auth()->id());

        return redirect()->route('purchases.index')
            ->with('success', 'Pembelian ' . $purchase->purchase_number . ' berhasil disimpan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }
}
